<?php
/**
 * Template Name: FAQ Page
 * 
 * FAQ page template with flexible content blocks
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('faq-page'); ?>>
            
            <?php if (have_rows('page_content')): ?>
                
                <div class="flexible-content">
                    <?php rachel_advocacy_render_flexible_content(); ?>
                </div>
                
            <?php else: ?>
                
                <!-- Fallback content if no flexible content is set -->
                <section class="page-hero bg-gradient-to-br from-neutral-600 to-neutral-800 text-white section-large">
                    <div class="container text-center">
                        
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                            <?php the_title(); ?>
                        </h1>
                        
                        <div class="text-lg md:text-xl lg:text-2xl opacity-90 max-w-3xl mx-auto">
                            <p><?php esc_html_e('Find answers to common questions about healthcare advocacy services, insurance appeals, and how I can help you navigate the healthcare system.', 'rachel-advocacy'); ?></p>
                        </div>
                        
                    </div>
                </section>

                <!-- FAQ Search -->
                <section class="faq-search bg-white section">
                    <div class="container">
                        
                        <div class="max-w-2xl mx-auto text-center">
                            
                            <h2 class="text-2xl md:text-3xl font-bold text-neutral-900 mb-6">
                                <?php esc_html_e('Search for Answers', 'rachel-advocacy'); ?>
                            </h2>
                            
                            <div class="search-form">
                                <label for="faq-search" class="sr-only">
                                    <?php esc_html_e('Search FAQ', 'rachel-advocacy'); ?>
                                </label>
                                <div class="relative">
                                    <input type="search" 
                                           id="faq-search" 
                                           class="w-full px-6 py-4 text-lg border-2 border-neutral-200 rounded-lg focus:border-primary-500 focus:ring-2 focus:ring-primary-200 focus:outline-none"
                                           placeholder="<?php esc_attr_e('Type keywords to search FAQ...', 'rachel-advocacy'); ?>"
                                           aria-describedby="faq-search-help">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-6">
                                        <span class="text-neutral-400">🔍</span>
                                    </div>
                                </div>
                                <div id="faq-search-help" class="text-sm text-neutral-600 mt-2">
                                    <?php esc_html_e('Try searching for terms like "insurance", "appeal", "cost", or "appointment"', 'rachel-advocacy'); ?>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                </section>

                <!-- FAQ Categories -->
                <section class="faq-categories bg-neutral-50 section">
                    <div class="container">
                        
                        <?php
                        $faq_categories = array(
                            'getting-started' => array(
                                'title' => __('Getting Started', 'rachel-advocacy'),
                                'icon' => '🚀',
                                'faqs' => array(
                                    array(
                                        'question' => __('What is a healthcare advocate?', 'rachel-advocacy'),
                                        'answer' => __('A healthcare advocate is a professional who helps patients navigate the complex healthcare system. I work as your personal representative to communicate with insurance companies, coordinate care between providers, assist with medical decision-making, and ensure you receive the best possible care. I serve as your voice when you feel overwhelmed or don\'t know where to turn.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('How do I know if I need a healthcare advocate?', 'rachel-advocacy'),
                                        'answer' => __('You might benefit from healthcare advocacy if you\'re facing insurance denials, dealing with multiple specialists who aren\'t communicating, feeling overwhelmed by medical decisions, experiencing billing issues, or simply want someone knowledgeable by your side during this challenging time. If you\'re spending more time fighting the system than focusing on your health, it\'s time to get help.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('What\'s included in the free consultation?', 'rachel-advocacy'),
                                        'answer' => __('During our 15-minute consultation, I\'ll listen to your situation, ask clarifying questions, and provide initial guidance on next steps. I\'ll explain how advocacy services could help your specific case and discuss service options and pricing. There\'s no obligation and no pressure - this is simply an opportunity for us to see if we\'re a good fit to work together.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('How quickly can you help with urgent situations?', 'rachel-advocacy'),
                                        'answer' => __('For urgent situations like hospital stays, insurance authorization deadlines, or time-sensitive medical decisions, I offer expedited services. I can often begin advocacy work within 24 hours of our initial contact. For existing clients, I provide 24/7 emergency support for crisis situations.', 'rachel-advocacy')
                                    )
                                )
                            ),
                            'insurance-appeals' => array(
                                'title' => __('Insurance & Appeals', 'rachel-advocacy'),
                                'icon' => '📋',
                                'faqs' => array(
                                    array(
                                        'question' => __('My insurance denied my claim. Can you help?', 'rachel-advocacy'),
                                        'answer' => __('Yes! Insurance appeals are one of my primary services. I\'ll review your denial letter, gather supporting documentation from your healthcare providers, and prepare a comprehensive appeal that addresses the specific reasons for denial. My appeals have a high success rate because I understand insurance terminology and requirements.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('How much does insurance appeal help cost?', 'rachel-advocacy'),
                                        'answer' => __('Insurance appeal services are typically charged hourly at $150/hour, but I also offer success-based pricing for certain cases where no upfront payment is required. During our consultation, I\'ll provide a clear estimate based on the complexity of your case. Many clients find the cost is quickly offset by successful appeals.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('What if my appeal is denied again?', 'rachel-advocacy'),
                                        'answer' => __('If an internal appeal is denied, we have additional options including external review through your state\'s independent review process, filing complaints with regulatory agencies, or exploring alternative coverage options. I\'ll continue advocating until we\'ve exhausted all reasonable avenues for approval.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('Can you help with prior authorizations?', 'rachel-advocacy'),
                                        'answer' => __('Absolutely. Prior authorizations can be time-consuming and confusing. I handle the entire process including gathering required documentation, completing forms correctly, following up with both your provider and insurance company, and escalating when necessary to meet your treatment timeline.', 'rachel-advocacy')
                                    )
                                )
                            ),
                            'services-pricing' => array(
                                'title' => __('Services & Pricing', 'rachel-advocacy'),
                                'icon' => '💰',
                                'faqs' => array(
                                    array(
                                        'question' => __('What services do you provide?', 'rachel-advocacy'),
                                        'answer' => __('I provide comprehensive healthcare advocacy including insurance appeals, care coordination, appointment accompaniment, medical record review, treatment decision support, and crisis advocacy. I can work on specific projects or provide ongoing support throughout your healthcare journey.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('How much do your services cost?', 'rachel-advocacy'),
                                        'answer' => __('Services are typically charged at $150/hour with transparent pricing and no hidden fees. I also offer package deals for comprehensive advocacy and success-based pricing for insurance appeals. Payment plans are available, and many HSA/FSA accounts can be used for healthcare advocacy services.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('Does insurance cover healthcare advocacy?', 'rachel-advocacy'),
                                        'answer' => __('While most insurance plans don\'t directly cover advocacy services, many HSA and FSA accounts can be used since advocacy is considered a qualified medical expense. Additionally, the money I help you save through successful appeals and improved care coordination often far exceeds the advocacy fees.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('Do you offer payment plans?', 'rachel-advocacy'),
                                        'answer' => __('Yes, I understand that healthcare costs can be overwhelming. I offer flexible payment plans and work with clients to find arrangements that fit their budget. For insurance appeals, I often offer success-based pricing so you don\'t pay unless we achieve a positive outcome.', 'rachel-advocacy')
                                    )
                                )
                            ),
                            'working-together' => array(
                                'title' => __('Working Together', 'rachel-advocacy'),
                                'icon' => '🤝',
                                'faqs' => array(
                                    array(
                                        'question' => __('What area do you serve?', 'rachel-advocacy'),
                                        'answer' => __('I provide virtual advocacy services nationwide including insurance appeals, care coordination, and consultation. For in-person services like appointment accompaniment, I serve the San Francisco Bay Area. Many advocacy services can be effectively provided remotely via phone, video, and secure messaging.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('How do we communicate during the advocacy process?', 'rachel-advocacy'),
                                        'answer' => __('I communicate through whatever method works best for you - phone, email, text, or secure messaging. I provide regular updates on progress and am available during business hours for questions. For urgent situations, I offer extended availability to existing clients.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('What information do you need to get started?', 'rachel-advocacy'),
                                        'answer' => __('I\'ll need basic information about your situation, copies of relevant medical records and insurance communications, and any documentation related to your challenge. Don\'t worry about organizing everything perfectly - I can help you gather and organize the necessary materials.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('How long does the advocacy process typically take?', 'rachel-advocacy'),
                                        'answer' => __('The timeline varies depending on your specific needs. Simple insurance appeals might be resolved in 2-4 weeks, while complex care coordination can be ongoing. I\'ll provide realistic timeframes during our consultation and keep you updated on progress throughout the process.', 'rachel-advocacy')
                                    )
                                )
                            ),
                            'confidentiality' => array(
                                'title' => __('Privacy & Confidentiality', 'rachel-advocacy'),
                                'icon' => '🔒',
                                'faqs' => array(
                                    array(
                                        'question' => __('Is our communication confidential?', 'rachel-advocacy'),
                                        'answer' => __('Absolutely. All communications and health information are strictly confidential and HIPAA-compliant. I maintain professional standards of privacy and will never share your information without your explicit written consent. Your trust is fundamental to effective advocacy.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('What permissions do you need to advocate for me?', 'rachel-advocacy'),
                                        'answer' => __('I\'ll need you to sign HIPAA authorization forms that allow me to communicate with your healthcare providers and insurance company on your behalf. These forms specify exactly what information I can access and what actions I can take. You maintain full control and can revoke authorization at any time.', 'rachel-advocacy')
                                    ),
                                    array(
                                        'question' => __('How do you protect my personal health information?', 'rachel-advocacy'),
                                        'answer' => __('I use secure, encrypted communication systems for all health information exchange. Physical documents are stored in locked filing systems, and digital files are protected with enterprise-level security. I follow all HIPAA requirements and industry best practices for protecting sensitive health information.', 'rachel-advocacy')
                                    )
                                )
                            )
                        );

                        foreach ($faq_categories as $category_id => $category):
                        ?>
                            
                            <div class="faq-category mb-16" data-category="<?php echo esc_attr($category_id); ?>">
                                
                                <header class="category-header text-center mb-12">
                                    <div class="category-icon text-5xl mb-4">
                                        <?php echo $category['icon']; ?>
                                    </div>
                                    <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">
                                        <?php echo esc_html($category['title']); ?>
                                    </h2>
                                </header>

                                <div class="faq-list max-w-4xl mx-auto space-y-4">
                                    
                                    <?php foreach ($category['faqs'] as $index => $faq): ?>
                                        
                                        <?php
                                        $faq_id = $category_id . '-' . $index;
                                        rachel_advocacy_get_component('accordion', array(
                                            'title' => $faq['question'],
                                            'content' => '<div class="prose prose-lg max-w-none text-neutral-700">' . wpautop(esc_html($faq['answer'])) . '</div>',
                                            'id' => $faq_id,
                                            'variant' => 'bordered'
                                        ));
                                        ?>
                                        
                                    <?php endforeach; ?>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>
                </section>

                <!-- Still Have Questions -->
                <section class="more-questions bg-white section">
                    <div class="container">
                        
                        <div class="max-w-3xl mx-auto text-center">
                            
                            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-6">
                                <?php esc_html_e('Still Have Questions?', 'rachel-advocacy'); ?>
                            </h2>
                            
                            <div class="text-lg md:text-xl text-neutral-600 mb-8">
                                <p><?php esc_html_e('Every healthcare situation is unique. If you don\'t see your specific question answered above, I\'d be happy to discuss your situation personally.', 'rachel-advocacy'); ?></p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-8 mb-12">
                                
                                <div class="contact-option">
                                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                        <?php esc_html_e('Schedule a Free Consultation', 'rachel-advocacy'); ?>
                                    </h3>
                                    <p class="text-neutral-600 mb-4">
                                        <?php esc_html_e('15-minute call to discuss your specific situation and how I can help.', 'rachel-advocacy'); ?>
                                    </p>
                                    <?php
                                    rachel_advocacy_get_component('button', array(
                                        'text' => __('Schedule Call', 'rachel-advocacy'),
                                        'url' => get_permalink(get_page_by_path('contact')),
                                        'variant' => 'primary',
                                        'size' => 'lg'
                                    ));
                                    ?>
                                </div>

                                <div class="contact-option">
                                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                        <?php esc_html_e('Send a Quick Message', 'rachel-advocacy'); ?>
                                    </h3>
                                    <p class="text-neutral-600 mb-4">
                                        <?php esc_html_e('Email your question and I\'ll respond with personalized guidance.', 'rachel-advocacy'); ?>
                                    </p>
                                    <?php
                                    rachel_advocacy_get_component('button', array(
                                        'text' => __('Ask a Question', 'rachel-advocacy'),
                                        'url' => get_permalink(get_page_by_path('contact')) . '#contact-form',
                                        'variant' => 'outline',
                                        'size' => 'lg'
                                    ));
                                    ?>
                                </div>

                            </div>

                            <div class="emergency-note p-6 bg-error-50 border border-error-200 rounded-lg">
                                <h3 class="text-lg font-semibold text-error-900 mb-2">
                                    <?php esc_html_e('For Urgent Healthcare Advocacy Needs', 'rachel-advocacy'); ?>
                                </h3>
                                <p class="text-error-700 mb-3">
                                    <?php esc_html_e('If you\'re facing a healthcare crisis, insurance deadline, or urgent medical decision:', 'rachel-advocacy'); ?>
                                </p>
                                <a href="tel:+15551234567" class="text-lg font-medium text-error-800 hover:text-error-900">
                                    📞 (555) 123-4567
                                </a>
                            </div>
                            
                        </div>
                        
                    </div>
                </section>
                
            <?php endif; ?>
            
        </article>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?> 