import json
import csv
from datetime import datetime
from collections import defaultdict, Counter
import re

class SEOAnalysisEnhancer:
    def __init__(self, ai_analysis_file=None):
        self.ai_data = None
        if ai_analysis_file:
            self.load_ai_analysis(ai_analysis_file)
    
    def load_ai_analysis(self, json_file):
        """Load the AI analysis data"""
        try:
            with open(json_file, 'r', encoding='utf-8') as f:
                data = json.load(f)
                self.ai_data = data.get('raw_content_data', data)
            print(f"✅ Loaded AI analysis data from {json_file}")
            return True
        except Exception as e:
            print(f"❌ Error loading AI analysis: {e}")
            return False
    
    def analyze_content_seo_opportunities(self):
        """Analyze content for SEO opportunities based on AI data"""
        if not self.ai_data:
            return None
        
        seo_opportunities = {
            'keyword_opportunities': {},
            'content_gaps': [],
            'duplicate_content_risks': [],
            'internal_linking_opportunities': [],
            'title_optimization': [],
            'meta_description_optimization': [],
            'heading_structure_issues': [],
            'image_seo_issues': []
        }
        
        # Analyze each page
        for url, page_data in self.ai_data.items():
            if not page_data:
                continue
                
            # Title optimization opportunities
            title = page_data.get('title', '')
            if title:
                if len(title) < 30:
                    seo_opportunities['title_optimization'].append({
                        'url': url,
                        'current_title': title,
                        'issue': 'Title too short',
                        'recommendation': 'Expand title to include more descriptive keywords'
                    })
                elif len(title) > 60:
                    seo_opportunities['title_optimization'].append({
                        'url': url,
                        'current_title': title,
                        'issue': 'Title too long',
                        'recommendation': 'Shorten title for better search display'
                    })
            
            # Meta description opportunities
            meta_desc = page_data.get('meta_description', '')
            if not meta_desc:
                seo_opportunities['meta_description_optimization'].append({
                    'url': url,
                    'issue': 'Missing meta description',
                    'recommendation': self.suggest_meta_description(page_data)
                })
            elif len(meta_desc) < 120:
                seo_opportunities['meta_description_optimization'].append({
                    'url': url,
                    'current_meta': meta_desc,
                    'issue': 'Meta description too short',
                    'recommendation': 'Expand to 120-160 characters with compelling CTA'
                })
            
            # Heading structure analysis
            headings = page_data.get('headings', {})
            if not headings.get('h1'):
                seo_opportunities['heading_structure_issues'].append({
                    'url': url,
                    'issue': 'Missing H1 tag',
                    'recommendation': 'Add descriptive H1 tag that includes target keywords'
                })
            
            # Image SEO analysis
            images = page_data.get('images', [])
            images_without_alt = sum(1 for img in images if not img.get('alt'))
            if images_without_alt > 0:
                seo_opportunities['image_seo_issues'].append({
                    'url': url,
                    'images_without_alt': images_without_alt,
                    'total_images': len(images),
                    'recommendation': 'Add descriptive alt text to all images'
                })
            
            # Keyword opportunities based on content categorization
            category_analysis = page_data.get('category_analysis', {})
            if category_analysis:
                primary_category = category_analysis.get('primary_category', '')
                confidence = category_analysis.get('confidence', 0)
                
                if confidence < 0.7:  # Low confidence indicates opportunity for better keyword focus
                    seo_opportunities['keyword_opportunities'][url] = {
                        'category': primary_category,
                        'confidence': confidence,
                        'recommendation': f'Strengthen {primary_category} keyword focus'
                    }
        
        # Identify internal linking opportunities
        seo_opportunities['internal_linking_opportunities'] = self.find_internal_linking_opportunities()
        
        # Check for duplicate content risks
        seo_opportunities['duplicate_content_risks'] = self.identify_duplicate_content_risks()
        
        return seo_opportunities
    
    def suggest_meta_description(self, page_data):
        """Suggest meta description based on page content"""
        title = page_data.get('title', '')
        body_text = page_data.get('body_text', '')
        category = page_data.get('category_analysis', {}).get('primary_category', '')
        
        if 'movement' in category.lower():
            return f"Discover {title.lower()} at Umi Wellness Center. Professional movement therapy and somatic practices to improve your wellbeing. Book your session today."
        elif 'advocacy' in category.lower():
            return f"Expert patient advocacy services at Umi Wellness Center. Get professional support navigating healthcare systems. Contact us for consultation."
        else:
            # Extract key phrases from content
            sentences = body_text.split('.')[:2] if body_text else []
            if sentences:
                return f"{sentences[0].strip()}. Learn more at Umi Wellness Center."
            else:
                return "Professional wellness and advocacy services at Umi Wellness Center. Contact us to learn how we can help you."
    
    def find_internal_linking_opportunities(self):
        """Find internal linking opportunities between related pages"""
        if not self.ai_data:
            return []
        
        opportunities = []
        pages_by_category = defaultdict(list)
        
        # Group pages by category
        for url, page_data in self.ai_data.items():
            category = page_data.get('category_analysis', {}).get('primary_category', 'unknown')
            pages_by_category[category].append((url, page_data))
        
        # Find linking opportunities within categories
        for category, pages in pages_by_category.items():
            if len(pages) > 1:
                for i, (url1, data1) in enumerate(pages):
                    for url2, data2 in pages[i+1:]:
                        # Check if pages could benefit from linking to each other
                        common_keywords = self.find_common_keywords(data1, data2)
                        if common_keywords:
                            opportunities.append({
                                'source_page': url1,
                                'target_page': url2,
                                'category': category,
                                'common_keywords': common_keywords,
                                'recommendation': f'Add internal link from {url1} to {url2} using anchor text related to: {", ".join(common_keywords[:3])}'
                            })
        
        return opportunities[:10]  # Return top 10 opportunities
    
    def find_common_keywords(self, page1_data, page2_data):
        """Find common keywords between two pages"""
        # Extract text from both pages
        text1 = (page1_data.get('body_text', '') + ' ' + 
                ' '.join([h for headings in page1_data.get('headings', {}).values() for h in headings])).lower()
        text2 = (page2_data.get('body_text', '') + ' ' + 
                ' '.join([h for headings in page2_data.get('headings', {}).values() for h in headings])).lower()
        
        # Simple keyword extraction (could be enhanced with NLP)
        words1 = set(re.findall(r'\b\w{4,}\b', text1))
        words2 = set(re.findall(r'\b\w{4,}\b', text2))
        
        # Find common meaningful words
        common = words1.intersection(words2)
        
        # Filter out common stop words
        stop_words = {'that', 'with', 'have', 'this', 'will', 'your', 'from', 'they', 'know', 'want', 'been', 'good', 'much', 'some', 'time', 'very', 'when', 'come', 'here', 'there', 'could', 'would', 'should', 'their', 'what', 'said', 'each', 'which', 'about', 'were', 'being', 'where', 'after', 'back', 'other'}
        
        meaningful_common = [word for word in common if word not in stop_words and len(word) > 4]
        
        return meaningful_common[:5]
    
    def identify_duplicate_content_risks(self):
        """Identify potential duplicate content issues"""
        if not self.ai_data:
            return []
        
        risks = []
        titles_seen = defaultdict(list)
        
        # Check for duplicate titles
        for url, page_data in self.ai_data.items():
            title = page_data.get('title', '').strip().lower()
            if title:
                titles_seen[title].append(url)
        
        # Find duplicates
        for title, urls in titles_seen.items():
            if len(urls) > 1:
                risks.append({
                    'issue': 'Duplicate page titles',
                    'title': title,
                    'pages': urls,
                    'recommendation': 'Create unique, descriptive titles for each page'
                })
        
        # Check for very similar content (simplified check)
        pages_list = list(self.ai_data.items())
        for i, (url1, data1) in enumerate(pages_list):
            for url2, data2 in pages_list[i+1:]:
                if self.content_similarity_check(data1, data2):
                    risks.append({
                        'issue': 'Potentially similar content',
                        'page1': url1,
                        'page2': url2,
                        'recommendation': 'Review content to ensure unique value proposition for each page'
                    })
        
        return risks
    
    def content_similarity_check(self, data1, data2):
        """Simple content similarity check"""
        text1 = data1.get('body_text', '')
        text2 = data2.get('body_text', '')
        
        if len(text1) < 100 or len(text2) < 100:
            return False
        
        # Simple word overlap check
        words1 = set(text1.lower().split())
        words2 = set(text2.lower().split())
        
        overlap = len(words1.intersection(words2))
        total_unique = len(words1.union(words2))
        
        similarity_ratio = overlap / total_unique if total_unique > 0 else 0
        
        return similarity_ratio > 0.7  # 70% similarity threshold
    
    def generate_seo_action_plan(self, seo_opportunities):
        """Generate prioritized SEO action plan"""
        action_plan = {
            'high_priority': [],
            'medium_priority': [],
            'low_priority': [],
            'quick_wins': []
        }
        
        # High priority: Missing essential elements
        if seo_opportunities['meta_description_optimization']:
            for item in seo_opportunities['meta_description_optimization']:
                if 'Missing meta description' in item['issue']:
                    action_plan['high_priority'].append({
                        'task': f"Add meta description to {item['url']}",
                        'impact': 'High',
                        'effort': 'Low',
                        'recommendation': item['recommendation']
                    })
        
        # Quick wins: Easy fixes with good impact
        if seo_opportunities['image_seo_issues']:
            action_plan['quick_wins'].extend([
                {
                    'task': f"Add alt text to {item['images_without_alt']} images on {item['url']}",
                    'impact': 'Medium',
                    'effort': 'Low',
                    'recommendation': item['recommendation']
                } for item in seo_opportunities['image_seo_issues']
            ])
        
        # Medium priority: Optimization opportunities
        if seo_opportunities['title_optimization']:
            action_plan['medium_priority'].extend([
                {
                    'task': f"Optimize title on {item['url']}",
                    'impact': 'Medium', 
                    'effort': 'Medium',
                    'current': item.get('current_title', ''),
                    'issue': item['issue'],
                    'recommendation': item['recommendation']
                } for item in seo_opportunities['title_optimization']
            ])
        
        # Low priority: Advanced optimizations
        if seo_opportunities['internal_linking_opportunities']:
            action_plan['low_priority'].extend([
                {
                    'task': f"Add internal link from {item['source_page']} to {item['target_page']}",
                    'impact': 'Low',
                    'effort': 'Low',
                    'recommendation': item['recommendation']
                } for item in seo_opportunities['internal_linking_opportunities'][:5]
            ])
        
        return action_plan
    
    def save_seo_enhancement_reports(self, seo_opportunities, filename_prefix='seo_enhancement'):
        """Save SEO enhancement reports"""
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        
        # Generate action plan
        action_plan = self.generate_seo_action_plan(seo_opportunities)
        
        # 1. Save SEO opportunities CSV
        csv_filename = f"{filename_prefix}_opportunities_{timestamp}.csv"
        with open(csv_filename, 'w', newline='', encoding='utf-8') as f:
            writer = csv.writer(f)
            writer.writerow(['Page URL', 'Issue Type', 'Current Value', 'Issue Description', 'Recommendation', 'Priority'])
            
            # Title issues
            for item in seo_opportunities['title_optimization']:
                writer.writerow([
                    item['url'], 'Title Optimization', item.get('current_title', ''), 
                    item['issue'], item['recommendation'], 'Medium'
                ])
            
            # Meta description issues
            for item in seo_opportunities['meta_description_optimization']:
                writer.writerow([
                    item['url'], 'Meta Description', item.get('current_meta', 'Missing'), 
                    item['issue'], item['recommendation'], 'High'
                ])
            
            # Image SEO issues
            for item in seo_opportunities['image_seo_issues']:
                writer.writerow([
                    item['url'], 'Image SEO', f"{item['images_without_alt']}/{item['total_images']} missing alt", 
                    'Images without alt text', item['recommendation'], 'Medium'
                ])
        
        # 2. Save action plan CSV
        action_csv_filename = f"{filename_prefix}_action_plan_{timestamp}.csv"
        with open(action_csv_filename, 'w', newline='', encoding='utf-8') as f:
            writer = csv.writer(f)
            writer.writerow(['Priority Level', 'Task', 'Impact', 'Effort', 'Recommendation'])
            
            for priority, tasks in action_plan.items():
                for task in tasks:
                    writer.writerow([
                        priority.replace('_', ' ').title(),
                        task['task'],
                        task['impact'],
                        task['effort'],
                        task['recommendation']
                    ])
        
        # 3. Save comprehensive JSON
        json_filename = f"{filename_prefix}_comprehensive_{timestamp}.json"
        with open(json_filename, 'w', encoding='utf-8') as f:
            json.dump({
                'seo_opportunities': seo_opportunities,
                'action_plan': action_plan,
                'analysis_date': datetime.now().isoformat()
            }, f, indent=2, ensure_ascii=False)
        
        # 4. Save executive summary
        summary_filename = f"{filename_prefix}_executive_summary_{timestamp}.txt"
        with open(summary_filename, 'w', encoding='utf-8') as f:
            f.write("SEO ENHANCEMENT ANALYSIS - EXECUTIVE SUMMARY\n")
            f.write("=" * 60 + "\n\n")
            
            f.write("OVERVIEW:\n")
            f.write(f"Pages analyzed: {len(self.ai_data) if self.ai_data else 0}\n")
            f.write(f"Title optimization opportunities: {len(seo_opportunities['title_optimization'])}\n")
            f.write(f"Meta description issues: {len(seo_opportunities['meta_description_optimization'])}\n")
            f.write(f"Image SEO issues: {len(seo_opportunities['image_seo_issues'])}\n")
            f.write(f"Internal linking opportunities: {len(seo_opportunities['internal_linking_opportunities'])}\n\n")
            
            f.write("PRIORITY ACTIONS:\n\n")
            
            f.write("HIGH PRIORITY (Do First):\n")
            for task in action_plan['high_priority'][:5]:
                f.write(f"  • {task['task']}\n")
            
            f.write(f"\nQUICK WINS (Easy Implementation):\n")
            for task in action_plan['quick_wins'][:5]:
                f.write(f"  • {task['task']}\n")
            
            f.write(f"\nMEDIUM PRIORITY:\n")
            for task in action_plan['medium_priority'][:5]:
                f.write(f"  • {task['task']}\n")
            
            f.write(f"\nRECOMMENDED NEXT STEPS:\n")
            f.write("1. Implement high priority fixes immediately\n")
            f.write("2. Tackle quick wins for immediate SEO boost\n")
            f.write("3. Plan medium priority optimizations\n")
            f.write("4. Monitor impact and adjust strategy\n")
        
        print(f"\nSEO Enhancement Reports Generated:")
        print(f"  📋 SEO Opportunities: {csv_filename}")
        print(f"  📋 Action Plan: {action_csv_filename}")
        print(f"  📄 Comprehensive Data: {json_filename}")
        print(f"  📝 Executive Summary: {summary_filename}")
        
        return {
            'opportunities_csv': csv_filename,
            'action_plan_csv': action_csv_filename,
            'comprehensive_json': json_filename,
            'executive_summary': summary_filename
        }

# Usage
if __name__ == "__main__":
    # Initialize enhancer
    enhancer = SEOAnalysisEnhancer()
    
    # Try to load the most recent AI analysis file
    import glob
    ai_files = glob.glob("umi_wellness_ai_analysis_*.json")
    if ai_files:
        latest_file = max(ai_files)
        print(f"Loading latest AI analysis: {latest_file}")
        
        if enhancer.load_ai_analysis(latest_file):
            print("Analyzing SEO opportunities based on AI content analysis...")
            
            # Analyze SEO opportunities
            seo_opportunities = enhancer.analyze_content_seo_opportunities()
            
            # Save reports
            enhancer.save_seo_enhancement_reports(seo_opportunities)
            
            # Print summary
            print(f"\n" + "="*60)
            print("SEO ENHANCEMENT ANALYSIS COMPLETE")
            print("="*60)
            print(f"Title optimization opportunities: {len(seo_opportunities['title_optimization'])}")
            print(f"Meta description issues: {len(seo_opportunities['meta_description_optimization'])}")
            print(f"Image SEO issues: {len(seo_opportunities['image_seo_issues'])}")
            print(f"Internal linking opportunities: {len(seo_opportunities['internal_linking_opportunities'])}")
            
            print("\n🎯 Ready to implement SEO improvements!")
        else:
            print("❌ Could not load AI analysis data")
    else:
        print("❌ No AI analysis files found. Please run the AI content analyzer first.")