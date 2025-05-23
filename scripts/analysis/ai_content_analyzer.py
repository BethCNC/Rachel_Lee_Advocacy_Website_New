import requests
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from urllib.parse import urljoin, urlparse
from bs4 import BeautifulSoup
import json
import time
import re
from datetime import datetime
from collections import defaultdict, Counter
import nltk
from textblob import TextBlob

# Download required NLTK data
try:
    nltk.download('punkt', quiet=True)
    nltk.download('stopwords', quiet=True)
    nltk.download('vader_lexicon', quiet=True)
except:
    pass

class AIContentAnalyzer:
    def __init__(self, base_url, use_selenium=True):
        self.base_url = base_url
        self.domain = urlparse(base_url).netloc
        self.visited_urls = set()
        self.all_content = {}
        self.use_selenium = use_selenium
        
        # Content categorization keywords
        self.movement_keywords = [
            'movement', 'mobility', 'exercise', 'fitness', 'therapy', 'physical',
            'body', 'posture', 'alignment', 'training', 'rehabilitation', 'wellness',
            'bodywork', 'somatic', 'pilates', 'yoga', 'dance', 'kinesthetic',
            'biomechanics', 'coordination', 'balance', 'flexibility', 'strength',
            'movement intelligence', 'umi movement', 'embodiment', 'proprioception'
        ]
        
        self.advocacy_keywords = [
            'advocate', 'advocacy', 'patient', 'rights', 'support', 'navigate',
            'healthcare', 'medical', 'insurance', 'hospital', 'doctor', 'treatment',
            'care', 'health system', 'consultation', 'guidance', 'assistance',
            'empowerment', 'education', 'resources', 'coordination', 'liaison',
            'communication', 'planning', 'decision', 'informed consent'
        ]
        
        if use_selenium:
            self.setup_selenium()
    
    def setup_selenium(self):
        """Setup Selenium WebDriver"""
        chrome_options = Options()
        chrome_options.add_argument('--headless')
        chrome_options.add_argument('--no-sandbox')
        chrome_options.add_argument('--disable-dev-shm-usage')
        chrome_options.add_argument('--disable-gpu')
        chrome_options.add_argument('--window-size=1920,1080')
        
        try:
            self.driver = webdriver.Chrome(options=chrome_options)
            self.wait = WebDriverWait(self.driver, 10)
        except Exception as e:
            print(f"Selenium setup error: {e}")
            self.use_selenium = False
    
    def extract_comprehensive_content(self, url):
        """Extract detailed content from a page"""
        try:
            if self.use_selenium:
                self.driver.get(url)
                time.sleep(3)
                
                # Get page source for BeautifulSoup parsing
                page_source = self.driver.page_source
                soup = BeautifulSoup(page_source, 'html.parser')
                
                # Get dynamic content that might be loaded by JavaScript
                try:
                    dynamic_content = self.driver.find_elements(By.CSS_SELECTOR, '[data-content], .content, .text-content')
                    dynamic_text = ' '.join([elem.text for elem in dynamic_content])
                except:
                    dynamic_text = ''
            else:
                headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
                response = requests.get(url, headers=headers, timeout=10)
                response.raise_for_status()
                soup = BeautifulSoup(response.content, 'html.parser')
                dynamic_text = ''
            
            # Remove script and style elements
            for script in soup(["script", "style", "nav", "footer"]):
                script.decompose()
            
            # Extract comprehensive content
            content_data = {
                'url': url,
                'title': soup.find('title').get_text().strip() if soup.find('title') else '',
                'meta_description': '',
                'headings': {},
                'body_text': '',
                'links': [],
                'images': [],
                'forms': [],
                'navigation_items': [],
                'call_to_actions': [],
                'contact_info': [],
                'services_mentioned': [],
                'testimonials': [],
                'dynamic_content': dynamic_text
            }
            
            # Meta description
            meta_desc = soup.find('meta', attrs={'name': 'description'})
            if meta_desc:
                content_data['meta_description'] = meta_desc.get('content', '')
            
            # Extract headings with hierarchy
            for i in range(1, 7):
                h_elements = soup.find_all(f'h{i}')
                content_data['headings'][f'h{i}'] = [h.get_text().strip() for h in h_elements if h.get_text().strip()]
            
            # Extract main body text
            main_content = soup.find('main') or soup.find('article') or soup.find('div', class_=re.compile(r'content|main'))
            if main_content:
                content_data['body_text'] = main_content.get_text().strip()
            else:
                # Fallback: get all paragraph text
                paragraphs = soup.find_all(['p', 'div'], string=True)
                content_data['body_text'] = ' '.join([p.get_text().strip() for p in paragraphs])
            
            # Extract links with context
            for link in soup.find_all('a', href=True):
                link_text = link.get_text().strip()
                href = link['href']
                absolute_url = urljoin(url, href)
                
                content_data['links'].append({
                    'url': absolute_url,
                    'text': link_text,
                    'is_internal': urlparse(absolute_url).netloc == self.domain,
                    'context': self.get_link_context(link)
                })
            
            # Extract images with alt text and context
            for img in soup.find_all('img'):
                src = img.get('src', '')
                if src:
                    content_data['images'].append({
                        'src': urljoin(url, src),
                        'alt': img.get('alt', ''),
                        'title': img.get('title', ''),
                        'context': self.get_element_context(img)
                    })
            
            # Extract forms and their purposes
            for form in soup.find_all('form'):
                form_data = {
                    'action': form.get('action', ''),
                    'method': form.get('method', 'get'),
                    'fields': [],
                    'purpose': self.identify_form_purpose(form)
                }
                
                for input_elem in form.find_all(['input', 'textarea', 'select']):
                    form_data['fields'].append({
                        'type': input_elem.get('type', 'text'),
                        'name': input_elem.get('name', ''),
                        'placeholder': input_elem.get('placeholder', ''),
                        'required': input_elem.has_attr('required')
                    })
                
                content_data['forms'].append(form_data)
            
            # Extract navigation items
            nav_elements = soup.find_all(['nav', 'ul', 'ol'], class_=re.compile(r'nav|menu'))
            for nav in nav_elements:
                nav_links = nav.find_all('a')
                for link in nav_links:
                    content_data['navigation_items'].append({
                        'text': link.get_text().strip(),
                        'url': urljoin(url, link.get('href', ''))
                    })
            
            # Identify call-to-action elements
            cta_selectors = ['button', 'a[class*="btn"]', 'a[class*="cta"]', 'input[type="submit"]']
            for selector in cta_selectors:
                for elem in soup.select(selector):
                    text = elem.get_text().strip()
                    if text and len(text) < 100:  # Reasonable CTA length
                        content_data['call_to_actions'].append({
                            'text': text,
                            'type': elem.name,
                            'url': urljoin(url, elem.get('href', '')) if elem.get('href') else '',
                            'context': self.get_element_context(elem)
                        })
            
            # Extract contact information
            contact_patterns = [
                r'\b\d{3}[-.]?\d{3}[-.]?\d{4}\b',  # Phone numbers
                r'\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b',  # Email
                r'\b\d+\s+[A-Za-z\s,]+\b(?:Street|St|Avenue|Ave|Road|Rd|Drive|Dr|Lane|Ln|Boulevard|Blvd)'  # Addresses
            ]
            
            full_text = content_data['body_text'] + ' ' + dynamic_text
            for pattern in contact_patterns:
                matches = re.findall(pattern, full_text, re.IGNORECASE)
                content_data['contact_info'].extend(matches)
            
            # Extract testimonials or quotes
            testimonial_selectors = [
                '.testimonial', '.review', '.quote', 
                '[class*="testimonial"]', '[class*="review"]'
            ]
            for selector in testimonial_selectors:
                for elem in soup.select(selector):
                    text = elem.get_text().strip()
                    if text and len(text) > 20:  # Meaningful testimonial length
                        content_data['testimonials'].append(text)
            
            return content_data
            
        except Exception as e:
            print(f"Error extracting content from {url}: {e}")
            return None
    
    def get_link_context(self, link_element):
        """Get context around a link"""
        parent = link_element.parent
        if parent:
            return parent.get_text().strip()[:200]
        return ''
    
    def get_element_context(self, element):
        """Get context around an element"""
        # Look for nearby text
        for sibling in element.find_next_siblings():
            text = sibling.get_text().strip()
            if text:
                return text[:200]
        
        parent = element.parent
        if parent:
            return parent.get_text().strip()[:200]
        return ''
    
    def identify_form_purpose(self, form):
        """Identify the purpose of a form"""
        form_text = form.get_text().lower()
        
        if any(word in form_text for word in ['contact', 'email', 'message']):
            return 'contact'
        elif any(word in form_text for word in ['subscribe', 'newsletter', 'updates']):
            return 'newsletter'
        elif any(word in form_text for word in ['appointment', 'booking', 'schedule']):
            return 'appointment'
        elif any(word in form_text for word in ['search']):
            return 'search'
        else:
            return 'unknown'
    
    def analyze_content_categories(self, content_data):
        """Analyze content to categorize between movement work and advocacy"""
        full_text = (
            content_data.get('title', '') + ' ' +
            content_data.get('meta_description', '') + ' ' +
            content_data.get('body_text', '') + ' ' +
            ' '.join([h for headings in content_data.get('headings', {}).values() for h in headings])
        ).lower()
        
        movement_score = sum(1 for keyword in self.movement_keywords if keyword in full_text)
        advocacy_score = sum(1 for keyword in self.advocacy_keywords if keyword in full_text)
        
        # Weight by keyword frequency
        movement_weight = sum(full_text.count(keyword) for keyword in self.movement_keywords)
        advocacy_weight = sum(full_text.count(keyword) for keyword in self.advocacy_keywords)
        
        total_words = len(full_text.split())
        
        analysis = {
            'movement_score': movement_score,
            'advocacy_score': advocacy_score,
            'movement_weight': movement_weight,
            'advocacy_weight': advocacy_weight,
            'movement_density': movement_weight / max(total_words, 1),
            'advocacy_density': advocacy_weight / max(total_words, 1),
            'primary_category': 'movement' if movement_weight > advocacy_weight else 'advocacy',
            'confidence': abs(movement_weight - advocacy_weight) / max(movement_weight + advocacy_weight, 1),
            'is_mixed_content': min(movement_weight, advocacy_weight) / max(movement_weight + advocacy_weight, 1) > 0.3
        }
        
        return analysis
    
    def perform_sentiment_analysis(self, text):
        """Perform sentiment analysis on text content"""
        try:
            blob = TextBlob(text)
            return {
                'polarity': blob.sentiment.polarity,  # -1 to 1
                'subjectivity': blob.sentiment.subjectivity,  # 0 to 1
                'tone': 'positive' if blob.sentiment.polarity > 0.1 else 'negative' if blob.sentiment.polarity < -0.1 else 'neutral'
            }
        except:
            return {'polarity': 0, 'subjectivity': 0, 'tone': 'neutral'}
    
    def crawl_and_analyze(self, max_pages=25):
        """Crawl website and perform comprehensive analysis"""
        print(f"Starting AI-powered analysis of {self.base_url}")
        
        to_visit = [self.base_url]
        pages_analyzed = 0
        
        while to_visit and pages_analyzed < max_pages:
            url = to_visit.pop(0)
            
            if url in self.visited_urls:
                continue
            
            print(f"Analyzing page {pages_analyzed + 1}: {url}")
            
            # Extract comprehensive content
            content_data = self.extract_comprehensive_content(url)
            if not content_data:
                continue
            
            # Perform AI analysis
            category_analysis = self.analyze_content_categories(content_data)
            sentiment_analysis = self.perform_sentiment_analysis(content_data['body_text'])
            
            # Store comprehensive data
            self.all_content[url] = {
                **content_data,
                'category_analysis': category_analysis,
                'sentiment_analysis': sentiment_analysis,
                'word_count': len(content_data['body_text'].split()),
                'reading_time': len(content_data['body_text'].split()) / 200,  # Average reading speed
                'analysis_timestamp': datetime.now().isoformat()
            }
            
            self.visited_urls.add(url)
            pages_analyzed += 1
            
            # Add internal links to crawl queue
            for link in content_data['links']:
                if link['is_internal'] and link['url'] not in self.visited_urls:
                    if link['url'] not in to_visit:
                        to_visit.append(link['url'])
            
            time.sleep(1)  # Be respectful
        
        if self.use_selenium:
            self.driver.quit()
        
        return self.generate_ai_insights()
    
    def generate_ai_insights(self):
        """Generate AI-powered insights and recommendations"""
        
        # Categorize content by business branch
        movement_pages = []
        advocacy_pages = []
        mixed_pages = []
        
        for url, data in self.all_content.items():
            analysis = data['category_analysis']
            if analysis['is_mixed_content']:
                mixed_pages.append((url, data))
            elif analysis['primary_category'] == 'movement':
                movement_pages.append((url, data))
            else:
                advocacy_pages.append((url, data))
        
        # Analyze site structure and user journeys
        site_insights = {
            'content_distribution': {
                'movement_focused': len(movement_pages),
                'advocacy_focused': len(advocacy_pages),
                'mixed_content': len(mixed_pages),
                'total_pages': len(self.all_content)
            },
            'movement_branch_content': self.analyze_branch_content(movement_pages, 'movement'),
            'advocacy_branch_content': self.analyze_branch_content(advocacy_pages, 'advocacy'),
            'mixed_content_analysis': self.analyze_mixed_content(mixed_pages),
            'navigation_analysis': self.analyze_navigation_patterns(),
            'content_gaps': self.identify_content_gaps(),
            'ux_recommendations': self.generate_ux_recommendations(),
            'technical_recommendations': self.generate_technical_recommendations(),
            'content_migration_plan': self.create_content_migration_plan(movement_pages, advocacy_pages, mixed_pages)
        }
        
        return site_insights
    
    def analyze_branch_content(self, pages, branch_type):
        """Analyze content for a specific branch"""
        if not pages:
            return {}
        
        all_headings = []
        all_services = []
        all_ctas = []
        total_word_count = 0
        
        for url, data in pages:
            for headings in data['headings'].values():
                all_headings.extend(headings)
            all_ctas.extend([cta['text'] for cta in data['call_to_actions']])
            total_word_count += data['word_count']
        
        return {
            'page_count': len(pages),
            'total_word_count': total_word_count,
            'average_word_count': total_word_count / len(pages),
            'common_headings': Counter(all_headings).most_common(10),
            'call_to_actions': Counter(all_ctas).most_common(5),
            'pages': [(url, data['title'], data['category_analysis']['confidence']) for url, data in pages]
        }
    
    def analyze_mixed_content(self, mixed_pages):
        """Analyze pages with mixed content"""
        recommendations = []
        
        for url, data in mixed_pages:
            analysis = data['category_analysis']
            
            recommendations.append({
                'url': url,
                'title': data['title'],
                'movement_weight': analysis['movement_weight'],
                'advocacy_weight': analysis['advocacy_weight'],
                'recommendation': self.suggest_content_split(data)
            })
        
        return recommendations
    
    def suggest_content_split(self, page_data):
        """Suggest how to split mixed content"""
        headings = []
        for h_level, h_list in page_data['headings'].items():
            headings.extend(h_list)
        
        movement_headings = [h for h in headings if any(kw in h.lower() for kw in self.movement_keywords)]
        advocacy_headings = [h for h in headings if any(kw in h.lower() for kw in self.advocacy_keywords)]
        
        if len(movement_headings) > len(advocacy_headings):
            return f"Primary focus: Movement. Consider moving advocacy content to separate page. Movement sections: {movement_headings[:3]}"
        else:
            return f"Primary focus: Advocacy. Consider moving movement content to separate page. Advocacy sections: {advocacy_headings[:3]}"
    
    def analyze_navigation_patterns(self):
        """Analyze current navigation and suggest improvements"""
        all_nav_items = []
        
        for data in self.all_content.values():
            all_nav_items.extend([item['text'] for item in data['navigation_items']])
        
        nav_counter = Counter(all_nav_items)
        
        return {
            'current_nav_items': nav_counter.most_common(15),
            'suggested_movement_nav': [
                'Movement Services', 'Somatic Therapy', 'Movement Intelligence', 
                'Training Programs', 'About Movement Work', 'Book Session'
            ],
            'suggested_advocacy_nav': [
                'Patient Advocacy', 'Healthcare Navigation', 'Insurance Support',
                'Medical Liaison', 'Advocacy Services', 'Get Support'
            ]
        }
    
    def identify_content_gaps(self):
        """Identify missing content that should be created"""
        gaps = {
            'movement_branch_gaps': [],
            'advocacy_branch_gaps': []
        }
        
        # Essential movement content
        movement_essentials = [
            'about the practitioner', 'service offerings', 'pricing',
            'booking system', 'testimonials', 'methodology'
        ]
        
        # Essential advocacy content
        advocacy_essentials = [
            'advocacy services', 'process overview', 'success stories',
            'consultation booking', 'resources', 'contact'
        ]
        
        current_content = ' '.join([data['body_text'].lower() for data in self.all_content.values()])
        
        for essential in movement_essentials:
            if essential not in current_content:
                gaps['movement_branch_gaps'].append(essential)
        
        for essential in advocacy_essentials:
            if essential not in current_content:
                gaps['advocacy_branch_gaps'].append(essential)
        
        return gaps
    
    def generate_ux_recommendations(self):
        """Generate UX recommendations based on analysis"""
        return {
            'information_architecture': [
                'Create clear separation between Movement and Advocacy services',
                'Implement distinct visual branding for each business branch',
                'Design separate user journeys for each target audience',
                'Create dedicated landing pages for each service type'
            ],
            'navigation_strategy': [
                'Use distinct primary navigation for each website',
                'Implement breadcrumbs for complex service hierarchies',
                'Create cross-referral system between the two sites',
                'Design mobile-first navigation for both branches'
            ],
            'content_strategy': [
                'Develop audience-specific content for each branch',
                'Create clear calls-to-action for different user intents',
                'Implement content personalization based on user journey',
                'Design conversion-focused landing pages'
            ],
            'user_experience': [
                'Streamline booking processes for each service type',
                'Create trust signals appropriate for each audience',
                'Implement progressive disclosure for complex information',
                'Design accessible interfaces for healthcare context'
            ]
        }
    
    def generate_technical_recommendations(self):
        """Generate technical recommendations"""
        return {
            'seo_strategy': [
                'Implement separate domain strategy or clear subdomain structure',
                'Create distinct keyword strategies for each business branch',
                'Optimize meta descriptions for target audiences',
                'Implement structured data for healthcare services'
            ],
            'performance': [
                'Optimize for Core Web Vitals on both sites',
                'Implement efficient image loading strategies',
                'Use CDN for faster global content delivery',
                'Minimize JavaScript for better mobile performance'
            ],
            'security_compliance': [
                'Implement HIPAA compliance measures for patient advocacy',
                'Use SSL certificates for both domains',
                'Implement proper data handling for contact forms',
                'Consider privacy regulations for health-related content'
            ]
        }
    
    def create_content_migration_plan(self, movement_pages, advocacy_pages, mixed_pages):
        """Create a detailed content migration plan"""
        return {
            'movement_website': {
                'direct_migration': [{'url': url, 'title': data['title']} for url, data in movement_pages],
                'content_to_extract': [
                    {
                        'source_url': url,
                        'title': data['title'],
                        'movement_sections': self.extract_movement_content(data)
                    } for url, data in mixed_pages
                ],
                'new_content_needed': [
                    'Movement philosophy page',
                    'Practitioner credentials and training',
                    'Session types and pricing',
                    'Online booking system'
                ]
            },
            'advocacy_website': {
                'direct_migration': [{'url': url, 'title': data['title']} for url, data in advocacy_pages],
                'content_to_extract': [
                    {
                        'source_url': url,
                        'title': data['title'],
                        'advocacy_sections': self.extract_advocacy_content(data)
                    } for url, data in mixed_pages
                ],
                'new_content_needed': [
                    'Advocacy process overview',
                    'Client success stories',
                    'Healthcare navigation guides',
                    'Consultation booking system'
                ]
            },
            'shared_elements': {
                'about_pages': 'Create separate about pages highlighting relevant expertise',
                'contact_info': 'Maintain consistent contact information with service-specific forms',
                'branding': 'Develop cohesive but distinct visual identities'
            }
        }
    
    def extract_movement_content(self, page_data):
        """Extract movement-related content from mixed pages"""
        headings = []
        for h_level, h_list in page_data['headings'].items():
            movement_headings = [h for h in h_list if any(kw in h.lower() for kw in self.movement_keywords)]
            headings.extend(movement_headings)
        return headings
    
    def extract_advocacy_content(self, page_data):
        """Extract advocacy-related content from mixed pages"""
        headings = []
        for h_level, h_list in page_data['headings'].items():
            advocacy_headings = [h for h in h_list if any(kw in h.lower() for kw in self.advocacy_keywords)]
            headings.extend(advocacy_headings)
        return headings
    
    def save_comprehensive_report(self, insights, filename_prefix='ai_content_analysis'):
        """Save comprehensive AI analysis report"""
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        
        # Save detailed JSON report
        json_filename = f"{filename_prefix}_{timestamp}.json"
        with open(json_filename, 'w', encoding='utf-8') as f:
            json.dump({
                'analysis_insights': insights,
                'raw_content_data': self.all_content
            }, f, indent=2, ensure_ascii=False)
        
        # Save executive summary
        summary_filename = f"{filename_prefix}_executive_summary_{timestamp}.txt"
        with open(summary_filename, 'w', encoding='utf-8') as f:
            f.write("UMI WELLNESS CENTER - AI CONTENT ANALYSIS & UX STRATEGY REPORT\n")
            f.write("=" * 70 + "\n\n")
            
            f.write("EXECUTIVE SUMMARY:\n")
            f.write(f"Total pages analyzed: {insights['content_distribution']['total_pages']}\n")
            f.write(f"Movement-focused content: {insights['content_distribution']['movement_focused']} pages\n")
            f.write(f"Advocacy-focused content: {insights['content_distribution']['advocacy_focused']} pages\n")
            f.write(f"Mixed content pages: {insights['content_distribution']['mixed_content']} pages\n\n")
            
            f.write("CONTENT MIGRATION RECOMMENDATIONS:\n")
            f.write("\nMovement Website Content:\n")
            for page in insights['content_migration_plan']['movement_website']['direct_migration'][:5]:
                f.write(f"  - {page['title']} ({page['url']})\n")
            
            f.write("\nAdvocacy Website Content:\n")
            for page in insights['content_migration_plan']['advocacy_website']['direct_migration'][:5]:
                f.write(f"  - {page['title']} ({page['url']})\n")
            
            f.write("\nUX RECOMMENDATIONS:\n")
            for category, recommendations in insights['ux_recommendations'].items():
                f.write(f"\n{category.replace('_', ' ').title()}:\n")
                for rec in recommendations[:3]:
                    f.write(f"  • {rec}\n")
        
        print(f"\nAI Analysis Reports saved:")
        print(f"  - Comprehensive data: {json_filename}")
        print(f"  - Executive summary: {summary_filename}")
        
        return json_filename, summary_filename

# Usage
if __name__ == "__main__":
    # Initialize AI-powered analyzer
    analyzer = AIContentAnalyzer("https://umiwellnesscenter.com/", use_selenium=True)
    
    print("Starting comprehensive AI analysis...")
    print("This will analyze content, categorize by business branch, and provide UX recommendations.")
    
    # Perform analysis
    insights = analyzer.crawl_and_analyze(max_pages=20)
    
    # Save reports
    analyzer.save_comprehensive_report(insights, "umi_wellness_ai_analysis")
    
    # Print key insights
    print("\n" + "="*70)
    print("AI ANALYSIS COMPLETE - KEY INSIGHTS")
    print("="*70)
    
    dist = insights['content_distribution']
    print(f"Content Distribution:")
    print(f"  Movement-focused: {dist['movement_focused']} pages")
    print(f"  Advocacy-focused: {dist['advocacy_focused']} pages") 
    print(f"  Mixed content: {dist['mixed_content']} pages")
    
    print(f"\nTop UX Recommendations:")
    for rec in insights['ux_recommendations']['information_architecture'][:3]:
        print(f"  • {rec}")
    
    print("\nReady for website redesign planning!")
