import requests
from urllib.parse import urljoin, urlparse
from bs4 import BeautifulSoup
import csv
import json
import time
import re
from datetime import datetime
from collections import defaultdict, Counter
import ssl
import socket
from urllib.robotparser import RobotFileParser

class ComprehensiveSEOAuditor:
    def __init__(self, base_url):
        self.base_url = base_url
        self.domain = urlparse(base_url).netloc
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        })
        
        # SEO tracking
        self.broken_links = []
        self.redirect_chains = []
        self.slow_pages = []
        self.seo_issues = defaultdict(list)
        self.all_pages = {}
        self.all_links = set()
        self.external_links = set()
        self.internal_links = set()
        
        # SEO issue counters
        self.page_errors = defaultdict(int)
        
    def check_robots_txt(self):
        """Check robots.txt file"""
        try:
            robots_url = urljoin(self.base_url, '/robots.txt')
            response = self.session.get(robots_url, timeout=10)
            
            if response.status_code == 200:
                return {
                    'exists': True,
                    'content': response.text,
                    'issues': self.analyze_robots_txt(response.text)
                }
            else:
                return {
                    'exists': False,
                    'issues': ['robots.txt file not found']
                }
        except Exception as e:
            return {
                'exists': False,
                'error': str(e),
                'issues': ['Failed to access robots.txt']
            }
    
    def analyze_robots_txt(self, content):
        """Analyze robots.txt content for issues"""
        issues = []
        lines = content.strip().split('\n')
        
        has_user_agent = False
        has_sitemap = False
        
        for line in lines:
            line = line.strip()
            if line.lower().startswith('user-agent:'):
                has_user_agent = True
            elif line.lower().startswith('sitemap:'):
                has_sitemap = True
        
        if not has_user_agent:
            issues.append('No User-agent directive found')
        if not has_sitemap:
            issues.append('No Sitemap directive found')
            
        return issues
    
    def check_sitemap(self):
        """Check sitemap.xml file"""
        try:
            sitemap_url = urljoin(self.base_url, '/sitemap.xml')
            response = self.session.get(sitemap_url, timeout=10)
            
            if response.status_code == 200:
                return {
                    'exists': True,
                    'url': sitemap_url,
                    'content_type': response.headers.get('content-type', ''),
                    'size': len(response.content),
                    'issues': self.analyze_sitemap(response.text)
                }
            else:
                return {
                    'exists': False,
                    'status_code': response.status_code,
                    'issues': ['sitemap.xml not found or inaccessible']
                }
        except Exception as e:
            return {
                'exists': False,
                'error': str(e),
                'issues': ['Failed to access sitemap.xml']
            }
    
    def analyze_sitemap(self, content):
        """Analyze sitemap content"""
        issues = []
        try:
            soup = BeautifulSoup(content, 'xml')
            urls = soup.find_all('url')
            
            if len(urls) == 0:
                issues.append('No URLs found in sitemap')
            else:
                # Check for common sitemap issues
                for url in urls[:10]:  # Check first 10 URLs
                    loc = url.find('loc')
                    if loc and loc.text:
                        # Verify URL is accessible
                        try:
                            test_response = self.session.head(loc.text, timeout=5)
                            if test_response.status_code >= 400:
                                issues.append(f'Sitemap contains inaccessible URL: {loc.text}')
                        except:
                            issues.append(f'Sitemap contains unreachable URL: {loc.text}')
                            
        except Exception as e:
            issues.append(f'Invalid XML format: {str(e)}')
            
        return issues
    
    def check_ssl_certificate(self):
        """Check SSL certificate status"""
        try:
            parsed_url = urlparse(self.base_url)
            if parsed_url.scheme == 'https':
                context = ssl.create_default_context()
                with socket.create_connection((parsed_url.netloc, 443), timeout=10) as sock:
                    with context.wrap_socket(sock, server_hostname=parsed_url.netloc) as ssock:
                        cert = ssock.getpeercert()
                        
                return {
                    'valid': True,
                    'issuer': dict(x[0] for x in cert['issuer']),
                    'expires': cert['notAfter'],
                    'subject': dict(x[0] for x in cert['subject'])
                }
            else:
                return {
                    'valid': False,
                    'issue': 'Site not using HTTPS'
                }
        except Exception as e:
            return {
                'valid': False,
                'error': str(e)
            }
    
    def analyze_page_seo(self, url):
        """Comprehensive SEO analysis for a single page"""
        try:
            start_time = time.time()
            response = self.session.get(url, timeout=15)
            load_time = time.time() - start_time
            
            if response.status_code >= 400:
                return {
                    'url': url,
                    'status_code': response.status_code,
                    'error': f'HTTP {response.status_code}',
                    'accessible': False
                }
            
            soup = BeautifulSoup(response.content, 'html.parser')
            
            # Basic page info
            page_analysis = {
                'url': url,
                'status_code': response.status_code,
                'load_time': round(load_time, 2),
                'accessible': True,
                'content_size': len(response.content),
                'content_type': response.headers.get('content-type', ''),
                'seo_issues': []
            }
            
            # Title analysis
            title_tag = soup.find('title')
            if title_tag:
                title = title_tag.get_text().strip()
                page_analysis['title'] = title
                page_analysis['title_length'] = len(title)
                
                if len(title) == 0:
                    page_analysis['seo_issues'].append('Empty title tag')
                elif len(title) < 30:
                    page_analysis['seo_issues'].append('Title too short (< 30 characters)')
                elif len(title) > 60:
                    page_analysis['seo_issues'].append('Title too long (> 60 characters)')
            else:
                page_analysis['title'] = ''
                page_analysis['title_length'] = 0
                page_analysis['seo_issues'].append('Missing title tag')
            
            # Meta description analysis
            meta_desc = soup.find('meta', attrs={'name': 'description'})
            if meta_desc and meta_desc.get('content'):
                desc = meta_desc.get('content').strip()
                page_analysis['meta_description'] = desc
                page_analysis['meta_description_length'] = len(desc)
                
                if len(desc) < 120:
                    page_analysis['seo_issues'].append('Meta description too short (< 120 characters)')
                elif len(desc) > 160:
                    page_analysis['seo_issues'].append('Meta description too long (> 160 characters)')
            else:
                page_analysis['meta_description'] = ''
                page_analysis['meta_description_length'] = 0
                page_analysis['seo_issues'].append('Missing meta description')
            
            # Heading analysis
            headings = {}
            heading_issues = []
            
            for i in range(1, 7):
                h_tags = soup.find_all(f'h{i}')
                headings[f'h{i}'] = len(h_tags)
                if i == 1 and len(h_tags) == 0:
                    heading_issues.append('Missing H1 tag')
                elif i == 1 and len(h_tags) > 1:
                    heading_issues.append(f'Multiple H1 tags found ({len(h_tags)})')
            
            page_analysis['headings'] = headings
            page_analysis['seo_issues'].extend(heading_issues)
            
            # Image analysis
            images = soup.find_all('img')
            image_analysis = {
                'total_images': len(images),
                'images_without_alt': 0,
                'images_with_empty_alt': 0,
                'large_images': 0
            }
            
            for img in images:
                alt = img.get('alt', '')
                if not img.has_attr('alt'):
                    image_analysis['images_without_alt'] += 1
                elif not alt.strip():
                    image_analysis['images_with_empty_alt'] += 1
                    
                # Check image size if src is available
                src = img.get('src', '')
                if src and any(ext in src.lower() for ext in ['.jpg', '.jpeg', '.png', '.gif', '.webp']):
                    try:
                        img_url = urljoin(url, src)
                        img_response = self.session.head(img_url, timeout=5)
                        content_length = img_response.headers.get('content-length')
                        if content_length and int(content_length) > 500000:  # 500KB
                            image_analysis['large_images'] += 1
                    except:
                        pass
            
            page_analysis['images'] = image_analysis
            
            if image_analysis['images_without_alt'] > 0:
                page_analysis['seo_issues'].append(f'{image_analysis["images_without_alt"]} images missing alt text')
            if image_analysis['images_with_empty_alt'] > 0:
                page_analysis['seo_issues'].append(f'{image_analysis["images_with_empty_alt"]} images with empty alt text')
            if image_analysis['large_images'] > 0:
                page_analysis['seo_issues'].append(f'{image_analysis["large_images"]} large images (>500KB)')
            
            # Link analysis
            links = soup.find_all('a', href=True)
            internal_links = []
            external_links = []
            broken_links_on_page = []
            
            for link in links:
                href = link['href']
                absolute_url = urljoin(url, href)
                link_text = link.get_text().strip()
                
                # Skip javascript and mailto links
                if href.startswith(('javascript:', 'mailto:', 'tel:')):
                    continue
                
                # Categorize links
                if urlparse(absolute_url).netloc == self.domain:
                    internal_links.append(absolute_url)
                    self.internal_links.add(absolute_url)
                else:
                    external_links.append(absolute_url)
                    self.external_links.add(absolute_url)
                
                self.all_links.add(absolute_url)
                
                # Check for empty link text
                if not link_text and not link.find('img'):
                    page_analysis['seo_issues'].append(f'Link with empty text: {href}')
            
            page_analysis['links'] = {
                'internal_links': len(internal_links),
                'external_links': len(external_links),
                'total_links': len(links)
            }
            
            # Check for duplicate content indicators
            content_text = soup.get_text().strip()
            word_count = len(content_text.split())
            page_analysis['word_count'] = word_count
            
            if word_count < 300:
                page_analysis['seo_issues'].append('Low content (< 300 words)')
            
            # Check for canonical tag
            canonical = soup.find('link', attrs={'rel': 'canonical'})
            if canonical:
                page_analysis['canonical'] = canonical.get('href', '')
            else:
                page_analysis['seo_issues'].append('Missing canonical tag')
            
            # Check for meta robots
            meta_robots = soup.find('meta', attrs={'name': 'robots'})
            if meta_robots:
                robots_content = meta_robots.get('content', '')
                page_analysis['meta_robots'] = robots_content
                if 'noindex' in robots_content.lower():
                    page_analysis['seo_issues'].append('Page set to noindex')
            
            # Performance issues
            if load_time > 3:
                page_analysis['seo_issues'].append(f'Slow load time ({load_time:.2f}s)')
                self.slow_pages.append({
                    'url': url,
                    'load_time': load_time
                })
            
            # Check for schema markup
            schema_scripts = soup.find_all('script', type='application/ld+json')
            page_analysis['schema_markup'] = len(schema_scripts) > 0
            if len(schema_scripts) == 0:
                page_analysis['seo_issues'].append('No structured data/schema markup found')
            
            return page_analysis
            
        except Exception as e:
            return {
                'url': url,
                'error': str(e),
                'accessible': False,
                'seo_issues': [f'Failed to analyze: {str(e)}']
            }
    
    def check_link_status(self, url, source_page=''):
        """Check individual link status with detailed info"""
        try:
            start_time = time.time()
            response = self.session.head(url, timeout=10, allow_redirects=True)
            response_time = time.time() - start_time
            
            # If head request fails, try GET
            if response.status_code >= 400:
                response = self.session.get(url, timeout=10)
            
            link_info = {
                'url': url,
                'source_page': source_page,
                'status_code': response.status_code,
                'response_time': round(response_time, 2),
                'final_url': response.url,
                'content_type': response.headers.get('content-type', ''),
                'is_redirect': response.url != url,
                'redirect_count': len(response.history),
                'is_broken': response.status_code >= 400
            }
            
            # Check for redirect chains
            if len(response.history) > 3:
                self.redirect_chains.append({
                    'original_url': url,
                    'final_url': response.url,
                    'redirect_count': len(response.history),
                    'source_page': source_page
                })
                link_info['issue'] = f'Long redirect chain ({len(response.history)} redirects)'
            
            # Check for broken links
            if response.status_code >= 400:
                self.broken_links.append({
                    'url': url,
                    'status_code': response.status_code,
                    'source_page': source_page,
                    'error_type': self.categorize_http_error(response.status_code)
                })
            
            return link_info
            
        except requests.exceptions.Timeout:
            error_info = {
                'url': url,
                'source_page': source_page,
                'status_code': 'TIMEOUT',
                'is_broken': True,
                'error_type': 'Timeout',
                'issue': 'Request timed out'
            }
            self.broken_links.append(error_info)
            return error_info
            
        except requests.exceptions.ConnectionError:
            error_info = {
                'url': url,
                'source_page': source_page,
                'status_code': 'CONNECTION_ERROR',
                'is_broken': True,
                'error_type': 'Connection Error',
                'issue': 'Cannot connect to server'
            }
            self.broken_links.append(error_info)
            return error_info
            
        except Exception as e:
            error_info = {
                'url': url,
                'source_page': source_page,
                'status_code': 'ERROR',
                'is_broken': True,
                'error_type': 'Unknown Error',
                'issue': str(e)
            }
            self.broken_links.append(error_info)
            return error_info
    
    def categorize_http_error(self, status_code):
        """Categorize HTTP error codes"""
        if status_code == 404:
            return 'Page Not Found'
        elif status_code == 403:
            return 'Forbidden'
        elif status_code == 500:
            return 'Server Error'
        elif status_code == 502:
            return 'Bad Gateway'
        elif status_code == 503:
            return 'Service Unavailable'
        elif 400 <= status_code < 500:
            return 'Client Error'
        elif 500 <= status_code < 600:
            return 'Server Error'
        else:
            return f'HTTP {status_code}'
    
    def crawl_website(self, max_pages=30):
        """Crawl website and perform comprehensive SEO analysis"""
        print(f"Starting comprehensive SEO audit of {self.base_url}")
        
        # Check technical SEO basics
        print("Checking technical SEO fundamentals...")
        robots_analysis = self.check_robots_txt()
        sitemap_analysis = self.check_sitemap()
        ssl_analysis = self.check_ssl_certificate()
        
        # Start crawling pages
        to_visit = [self.base_url]
        visited = set()
        pages_analyzed = 0
        
        while to_visit and pages_analyzed < max_pages:
            url = to_visit.pop(0)
            
            if url in visited or not url.startswith(self.base_url):
                continue
            
            print(f"Analyzing page {pages_analyzed + 1}: {url}")
            
            # Analyze page SEO
            page_analysis = self.analyze_page_seo(url)
            self.all_pages[url] = page_analysis
            visited.add(url)
            pages_analyzed += 1
            
            # Extract internal links for further crawling
            if page_analysis.get('accessible', False):
                try:
                    response = self.session.get(url, timeout=10)
                    soup = BeautifulSoup(response.content, 'html.parser')
                    
                    for link in soup.find_all('a', href=True):
                        href = link['href']
                        absolute_url = urljoin(url, href)
                        
                        # Add internal links to crawl queue
                        if (urlparse(absolute_url).netloc == self.domain and 
                            absolute_url not in visited and 
                            absolute_url not in to_visit):
                            to_visit.append(absolute_url)
                
                except Exception as e:
                    print(f"Error extracting links from {url}: {e}")
            
            time.sleep(0.5)  # Be respectful
        
        # Check all collected links
        print(f"\nChecking {len(self.all_links)} links for broken status...")
        self.check_all_links()
        
        # Compile comprehensive results
        return {
            'technical_seo': {
                'robots_txt': robots_analysis,
                'sitemap': sitemap_analysis,
                'ssl_certificate': ssl_analysis
            },
            'pages_analyzed': len(self.all_pages),
            'total_links_found': len(self.all_links),
            'broken_links_count': len(self.broken_links),
            'redirect_chains_count': len(self.redirect_chains),
            'slow_pages_count': len(self.slow_pages),
            'pages_data': self.all_pages
        }
    
    def check_all_links(self):
        """Check all collected links for issues"""
        checked_links = set()
        
        for i, link_url in enumerate(self.all_links):
            if link_url in checked_links:
                continue
                
            print(f"Checking link {i+1}/{len(self.all_links)}: {link_url[:80]}...")
            
            # Find source page for this link
            source_page = ''
            for page_url, page_data in self.all_pages.items():
                if page_data.get('accessible', False):
                    # This is a simplified check - in practice, you'd want to track this during crawling
                    source_page = page_url
                    break
            
            self.check_link_status(link_url, source_page)
            checked_links.add(link_url)
            time.sleep(0.3)  # Be respectful to external servers
    
    def generate_seo_summary(self):
        """Generate comprehensive SEO summary"""
        # Count issues by type
        issue_summary = defaultdict(int)
        pages_with_issues = 0
        
        for page_data in self.all_pages.values():
            if page_data.get('seo_issues'):
                pages_with_issues += 1
                for issue in page_data['seo_issues']:
                    # Categorize issues
                    if 'title' in issue.lower():
                        issue_summary['Title Issues'] += 1
                    elif 'meta description' in issue.lower():
                        issue_summary['Meta Description Issues'] += 1
                    elif 'h1' in issue.lower():
                        issue_summary['Heading Issues'] += 1
                    elif 'alt' in issue.lower():
                        issue_summary['Image Issues'] += 1
                    elif 'load time' in issue.lower():
                        issue_summary['Performance Issues'] += 1
                    elif 'canonical' in issue.lower():
                        issue_summary['Technical Issues'] += 1
                    else:
                        issue_summary['Other Issues'] += 1
        
        return {
            'total_pages_analyzed': len(self.all_pages),
            'pages_with_seo_issues': pages_with_issues,
            'total_seo_issues': sum(issue_summary.values()),
            'issue_breakdown': dict(issue_summary),
            'broken_links_count': len(self.broken_links),
            'redirect_chains_count': len(self.redirect_chains),
            'slow_pages_count': len(self.slow_pages),
            'average_load_time': self.calculate_average_load_time(),
            'pages_missing_meta_description': self.count_missing_meta_descriptions(),
            'pages_missing_title': self.count_missing_titles()
        }
    
    def calculate_average_load_time(self):
        """Calculate average page load time"""
        load_times = [page.get('load_time', 0) for page in self.all_pages.values() 
                     if page.get('load_time')]
        return round(sum(load_times) / len(load_times), 2) if load_times else 0
    
    def count_missing_meta_descriptions(self):
        """Count pages missing meta descriptions"""
        return sum(1 for page in self.all_pages.values() 
                  if page.get('meta_description_length', 0) == 0)
    
    def count_missing_titles(self):
        """Count pages missing titles"""
        return sum(1 for page in self.all_pages.values() 
                  if page.get('title_length', 0) == 0)
    
    def save_reports(self, filename_prefix='seo_audit'):
        """Save comprehensive SEO reports"""
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        
        # 1. Save broken links CSV
        csv_filename = f"{filename_prefix}_broken_links_{timestamp}.csv"
        with open(csv_filename, 'w', newline='', encoding='utf-8') as f:
            writer = csv.writer(f)
            writer.writerow([
                'Broken URL', 'Status Code', 'Error Type', 'Source Page', 
                'Issue Description', 'Response Time'
            ])
            
            for link in self.broken_links:
                writer.writerow([
                    link.get('url', ''),
                    link.get('status_code', ''),
                    link.get('error_type', ''),
                    link.get('source_page', ''),
                    link.get('issue', ''),
                    link.get('response_time', '')
                ])
        
        # 2. Save SEO issues CSV
        seo_csv_filename = f"{filename_prefix}_seo_issues_{timestamp}.csv"
        with open(seo_csv_filename, 'w', newline='', encoding='utf-8') as f:
            writer = csv.writer(f)
            writer.writerow([
                'Page URL', 'Page Title', 'Title Length', 'Meta Description Length',
                'Word Count', 'Load Time', 'H1 Count', 'Images Without Alt',
                'SEO Issues Count', 'SEO Issues List'
            ])
            
            for url, page_data in self.all_pages.items():
                if page_data.get('accessible', False):
                    writer.writerow([
                        url,
                        page_data.get('title', ''),
                        page_data.get('title_length', 0),
                        page_data.get('meta_description_length', 0),
                        page_data.get('word_count', 0),
                        page_data.get('load_time', 0),
                        page_data.get('headings', {}).get('h1', 0),
                        page_data.get('images', {}).get('images_without_alt', 0),
                        len(page_data.get('seo_issues', [])),
                        '; '.join(page_data.get('seo_issues', []))
                    ])
        
        # 3. Save comprehensive JSON report
        json_filename = f"{filename_prefix}_comprehensive_{timestamp}.json"
        comprehensive_data = {
            'audit_summary': self.generate_seo_summary(),
            'broken_links': self.broken_links,
            'redirect_chains': self.redirect_chains,
            'slow_pages': self.slow_pages,
            'page_analyses': self.all_pages,
            'technical_seo': {
                'robots_txt': self.check_robots_txt(),
                'sitemap': self.check_sitemap(),
                'ssl_certificate': self.check_ssl_certificate()
            },
            'audit_date': datetime.now().isoformat(),
            'website': self.base_url
        }
        
        with open(json_filename, 'w', encoding='utf-8') as f:
            json.dump(comprehensive_data, f, indent=2, ensure_ascii=False)
        
        # 4. Save executive summary report
        summary_filename = f"{filename_prefix}_executive_summary_{timestamp}.txt"
        with open(summary_filename, 'w', encoding='utf-8') as f:
            summary = self.generate_seo_summary()
            
            f.write("SEO AUDIT EXECUTIVE SUMMARY\n")
            f.write(f"Website: {self.base_url}\n")
            f.write(f"Audit Date: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
            f.write("=" * 60 + "\n\n")
            
            f.write("OVERVIEW:\n")
            f.write(f"Pages Analyzed: {summary['total_pages_analyzed']}\n")
            f.write(f"Pages with SEO Issues: {summary['pages_with_seo_issues']}\n")
            f.write(f"Total SEO Issues Found: {summary['total_seo_issues']}\n")
            f.write(f"Broken Links: {summary['broken_links_count']}\n")
            f.write(f"Redirect Chains: {summary['redirect_chains_count']}\n")
            f.write(f"Slow Loading Pages: {summary['slow_pages_count']}\n")
            f.write(f"Average Load Time: {summary['average_load_time']}s\n\n")
            
            f.write("CRITICAL ISSUES:\n")
            f.write(f"• {summary['pages_missing_title']} pages missing titles\n")
            f.write(f"• {summary['pages_missing_meta_description']} pages missing meta descriptions\n")
            f.write(f"• {summary['broken_links_count']} broken links found\n")
            f.write(f"• {summary['slow_pages_count']} slow-loading pages (>3s)\n\n")
            
            f.write("ISSUE BREAKDOWN:\n")
            for issue_type, count in summary['issue_breakdown'].items():
                f.write(f"• {issue_type}: {count}\n")
            
            f.write("\nRECOMMENDATIONS:\n")
            f.write("1. Fix all broken links immediately\n")
            f.write("2. Add missing meta descriptions\n")
            f.write("3. Optimize page load times\n")
            f.write("4. Fix missing title tags\n")
            f.write("5. Add alt text to images\n")
            f.write("6. Implement structured data\n")
            f.write("7. Fix redirect chains\n")
        
        print(f"\nSEO Audit Reports Generated:")
        print(f"  📋 Broken Links CSV: {csv_filename}")
        print(f"  📊 SEO Issues CSV: {seo_csv_filename}")
        print(f"  📄 Comprehensive Data: {json_filename}")
        print(f"  📝 Executive Summary: {summary_filename}")
        
        return {
            'broken_links_csv': csv_filename,
            'seo_issues_csv': seo_csv_filename,
            'comprehensive_json': json_filename,
            'executive_summary': summary_filename
        }

# Usage
if __name__ == "__main__":
    # Initialize SEO auditor
    auditor = ComprehensiveSEOAuditor("https://umiwellnesscenter.com/")
    
    print("Starting comprehensive SEO audit...")
    print("This will check for broken links, SEO issues, and technical problems.")
    
    # Perform comprehensive audit
    audit_results = auditor.crawl_website(max_pages=25)
    
    # Generate and save all reports
    report_files = auditor.save_reports("umi_wellness_seo_audit")
    
    # Print summary
    summary = auditor.generate_seo_summary()
    print("\n" + "="*70)
    print("SEO AUDIT COMPLETE - KEY FINDINGS")
    print("="*70)
    print(f"Pages Analyzed: {summary['total_pages_analyzed']}")
    print(f"SEO Issues Found: {summary['total_seo_issues']}")
    print(f"Broken Links: {summary['broken_links_count']}")
    print(f"Slow Pages: {summary['slow_pages_count']}")
    print(f"Average Load Time: {summary['average_load_time']}s")
    
    print(f"\nCritical Issues:")
    print(f"  • {summary['pages_missing_title']} pages missing titles")
    print(f"  • {summary['pages_missing_meta_description']} pages missing meta descriptions")
    print(f"  • {summary['broken_links_count']} broken links")
    
    print(f"\nTop Issue Categories:")
    for issue_type, count in list(summary['issue_breakdown'].items())[:5]:
        print(f"  • {issue_type}: {count}")
    
    print("\n📊 Reports saved - ready for client review!")
    print("\nNext steps:")
    print("1. Review broken links CSV for immediate fixes")
    print("2. Check SEO issues CSV for optimization opportunities") 
    print("3. Share executive summary with client")
    print("4. Prioritize fixes based on impact and effort")