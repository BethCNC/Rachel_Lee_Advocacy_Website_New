<?php
/**
 * Template Name: Services Page
 * 
 * Services page template with flexible content blocks
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('services-page'); ?>>
            
            <?php if (have_rows('page_content')): ?>
                
                <div class="flexible-content">
                    <?php rachel_advocacy_render_flexible_content(); ?>
                </div>
                
            <?php else: ?>
                
                <!-- Fallback content if no flexible content is set -->
                <section class="page-hero bg-gradient-to-br from-primary-600 to-primary-800 text-white section-large">
                    <div class="container text-center">
                        
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                            <?php the_title(); ?>
                        </h1>
                        
                        <div class="text-lg md:text-xl lg:text-2xl opacity-90 max-w-3xl mx-auto">
                            <p><?php esc_html_e('Comprehensive healthcare advocacy services designed to empower you and improve your health outcomes.', 'rachel-advocacy'); ?></p>
                        </div>
                        
                    </div>
                </section>

                <!-- Services Overview -->
                <section class="services-overview bg-white section">
                    <div class="container">
                        
                        <header class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                                <?php esc_html_e('How I Support Your Healthcare Journey', 'rachel-advocacy'); ?>
                            </h2>
                            <p class="text-lg md:text-xl text-neutral-600 max-w-3xl mx-auto">
                                <?php esc_html_e('From insurance appeals to care coordination, I provide the expertise and support you need to navigate healthcare challenges successfully.', 'rachel-advocacy'); ?>
                            </p>
                        </header>

                        <div class="services-grid grid gap-12">
                            
                            <?php
                            $services = array(
                                array(
                                    'title' => __('Insurance Appeals & Authorization', 'rachel-advocacy'),
                                    'description' => __('Expert assistance with denied claims and prior authorizations', 'rachel-advocacy'),
                                    'content' => __('When insurance companies deny coverage for necessary treatments, I step in to advocate for you. I review denial letters, gather supporting documentation from your healthcare providers, and prepare compelling appeals that significantly increase your chances of approval.', 'rachel-advocacy'),
                                    'features' => array(
                                        __('Claim denial review and analysis', 'rachel-advocacy'),
                                        __('Prior authorization assistance', 'rachel-advocacy'),
                                        __('Appeals letter writing and submission', 'rachel-advocacy'),
                                        __('Follow-up with insurance companies', 'rachel-advocacy'),
                                        __('External review coordination', 'rachel-advocacy')
                                    ),
                                    'icon' => '📋',
                                    'bg_color' => 'bg-primary-50'
                                ),
                                array(
                                    'title' => __('Care Coordination & Communication', 'rachel-advocacy'),
                                    'description' => __('Seamless coordination between all your healthcare providers', 'rachel-advocacy'),
                                    'content' => __('Healthcare often involves multiple specialists, and communication gaps can lead to delayed or duplicated care. I serve as your central advocate, ensuring all providers have the information they need and that your care plan is coordinated effectively.', 'rachel-advocacy'),
                                    'features' => array(
                                        __('Provider communication facilitation', 'rachel-advocacy'),
                                        __('Appointment scheduling and coordination', 'rachel-advocacy'),
                                        __('Medical record organization', 'rachel-advocacy'),
                                        __('Treatment plan review and clarification', 'rachel-advocacy'),
                                        __('Discharge planning support', 'rachel-advocacy')
                                    ),
                                    'icon' => '🤝',
                                    'bg_color' => 'bg-secondary-50'
                                ),
                                array(
                                    'title' => __('Medical Record Review & Analysis', 'rachel-advocacy'),
                                    'description' => __('Thorough analysis to ensure accuracy and completeness', 'rachel-advocacy'),
                                    'content' => __('Medical records form the foundation of your healthcare decisions. I review your records for accuracy, completeness, and consistency, identifying any discrepancies or missing information that could impact your care or insurance coverage.', 'rachel-advocacy'),
                                    'features' => array(
                                        __('Comprehensive record review', 'rachel-advocacy'),
                                        __('Error identification and correction', 'rachel-advocacy'),
                                        __('Timeline creation and organization', 'rachel-advocacy'),
                                        __('Provider communication for clarifications', 'rachel-advocacy'),
                                        __('Summary reports for your reference', 'rachel-advocacy')
                                    ),
                                    'icon' => '📄',
                                    'bg_color' => 'bg-neutral-50'
                                ),
                                array(
                                    'title' => __('Appointment & Treatment Support', 'rachel-advocacy'),
                                    'description' => __('Professional support during important medical appointments', 'rachel-advocacy'),
                                    'content' => __('Having an advocate present during critical appointments ensures your concerns are heard and understood. I help you prepare questions, take detailed notes, and ensure you fully understand treatment recommendations and next steps.', 'rachel-advocacy'),
                                    'features' => array(
                                        __('Pre-appointment preparation', 'rachel-advocacy'),
                                        __('In-person or virtual appointment support', 'rachel-advocacy'),
                                        __('Question development and prioritization', 'rachel-advocacy'),
                                        __('Detailed note-taking and summaries', 'rachel-advocacy'),
                                        __('Follow-up action item coordination', 'rachel-advocacy')
                                    ),
                                    'icon' => '🏥',
                                    'bg_color' => 'bg-primary-50'
                                ),
                                array(
                                    'title' => __('Treatment Decision Support', 'rachel-advocacy'),
                                    'description' => __('Guidance for informed healthcare decisions', 'rachel-advocacy'),
                                    'content' => __('Complex medical decisions require careful consideration of all options. I help you understand treatment alternatives, research providers and facilities, and ensure you have all the information needed to make confident decisions about your healthcare.', 'rachel-advocacy'),
                                    'features' => array(
                                        __('Treatment option research and analysis', 'rachel-advocacy'),
                                        __('Provider and facility evaluation', 'rachel-advocacy'),
                                        __('Second opinion coordination', 'rachel-advocacy'),
                                        __('Risk-benefit analysis discussion', 'rachel-advocacy'),
                                        __('Decision documentation and communication', 'rachel-advocacy')
                                    ),
                                    'icon' => '🗺️',
                                    'bg_color' => 'bg-secondary-50'
                                ),
                                array(
                                    'title' => __('Crisis & Emergency Support', 'rachel-advocacy'),
                                    'description' => __('Immediate advocacy during healthcare emergencies', 'rachel-advocacy'),
                                    'content' => __('Healthcare crises require immediate, expert advocacy. I provide urgent support during hospital stays, emergency situations, and time-sensitive healthcare decisions to ensure you receive appropriate care and your rights are protected.', 'rachel-advocacy'),
                                    'features' => array(
                                        __('Emergency consultation and support', 'rachel-advocacy'),
                                        __('Hospital advocacy and communication', 'rachel-advocacy'),
                                        __('Urgent insurance authorization assistance', 'rachel-advocacy'),
                                        __('Crisis decision-making support', 'rachel-advocacy'),
                                        __('Family communication and coordination', 'rachel-advocacy')
                                    ),
                                    'icon' => '🚨',
                                    'bg_color' => 'bg-error-50'
                                )
                            );

                            foreach ($services as $index => $service):
                            ?>
                                
                                <div class="service-detail <?php echo esc_attr($service['bg_color']); ?> rounded-lg p-8 md:p-12">
                                    
                                    <div class="lg:grid lg:grid-cols-3 lg:gap-12 lg:items-start">
                                        
                                        <div class="service-header lg:col-span-1 mb-8 lg:mb-0">
                                            <div class="service-icon text-6xl mb-4">
                                                <?php echo $service['icon']; ?>
                                            </div>
                                            
                                            <h3 class="service-title text-2xl md:text-3xl font-bold text-neutral-900 mb-4">
                                                <?php echo esc_html($service['title']); ?>
                                            </h3>
                                            
                                            <p class="service-description text-lg text-neutral-600 mb-6">
                                                <?php echo esc_html($service['description']); ?>
                                            </p>

                                            <?php
                                            rachel_advocacy_get_component('button', array(
                                                'text' => __('Get Started', 'rachel-advocacy'),
                                                'url' => get_permalink(get_page_by_path('contact')),
                                                'variant' => 'primary'
                                            ));
                                            ?>
                                        </div>

                                        <div class="service-content lg:col-span-2">
                                            
                                            <div class="service-overview mb-8">
                                                <p class="text-lg text-neutral-700 leading-relaxed">
                                                    <?php echo esc_html($service['content']); ?>
                                                </p>
                                            </div>

                                            <div class="service-features">
                                                <h4 class="text-xl font-semibold text-neutral-900 mb-4">
                                                    <?php esc_html_e('What\'s Included:', 'rachel-advocacy'); ?>
                                                </h4>
                                                
                                                <ul class="grid md:grid-cols-2 gap-3">
                                                    <?php foreach ($service['features'] as $feature): ?>
                                                        <li class="flex items-start">
                                                            <span class="text-primary-600 mr-3 mt-1">✓</span>
                                                            <span class="text-neutral-700"><?php echo esc_html($feature); ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    </div>
                </section>

                <!-- Pricing Information -->
                <section class="pricing-info bg-neutral-50 section">
                    <div class="container">
                        
                        <div class="max-w-4xl mx-auto text-center">
                            
                            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-6">
                                <?php esc_html_e('Investment in Your Health', 'rachel-advocacy'); ?>
                            </h2>
                            
                            <div class="prose prose-lg max-w-none text-neutral-700 mb-8">
                                <p><?php esc_html_e('Healthcare advocacy is an investment in your health outcomes and peace of mind. I offer flexible service options to meet your needs and budget, with transparent pricing and no hidden fees.', 'rachel-advocacy'); ?></p>
                            </div>

                            <div class="grid md:grid-cols-3 gap-8 mb-12">
                                
                                <!-- Consultation -->
                                <div class="pricing-card bg-white rounded-lg shadow-lg p-8">
                                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                        <?php esc_html_e('Free Consultation', 'rachel-advocacy'); ?>
                                    </h3>
                                    <div class="text-3xl font-bold text-primary-600 mb-4">
                                        <?php esc_html_e('$0', 'rachel-advocacy'); ?>
                                    </div>
                                    <p class="text-neutral-600 mb-6">
                                        <?php esc_html_e('15-minute call to discuss your needs and how I can help', 'rachel-advocacy'); ?>
                                    </p>
                                    <?php
                                    rachel_advocacy_get_component('button', array(
                                        'text' => __('Schedule Now', 'rachel-advocacy'),
                                        'url' => get_permalink(get_page_by_path('contact')),
                                        'variant' => 'outline'
                                    ));
                                    ?>
                                </div>

                                <!-- Hourly -->
                                <div class="pricing-card bg-white rounded-lg shadow-lg p-8 border-2 border-primary-200">
                                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                        <?php esc_html_e('Hourly Services', 'rachel-advocacy'); ?>
                                    </h3>
                                    <div class="text-3xl font-bold text-primary-600 mb-4">
                                        <?php esc_html_e('$150/hr', 'rachel-advocacy'); ?>
                                    </div>
                                    <p class="text-neutral-600 mb-6">
                                        <?php esc_html_e('Flexible hourly support for specific advocacy needs', 'rachel-advocacy'); ?>
                                    </p>
                                    <?php
                                    rachel_advocacy_get_component('button', array(
                                        'text' => __('Learn More', 'rachel-advocacy'),
                                        'url' => get_permalink(get_page_by_path('contact')),
                                        'variant' => 'primary'
                                    ));
                                    ?>
                                </div>

                                <!-- Package -->
                                <div class="pricing-card bg-white rounded-lg shadow-lg p-8">
                                    <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                        <?php esc_html_e('Service Packages', 'rachel-advocacy'); ?>
                                    </h3>
                                    <div class="text-3xl font-bold text-primary-600 mb-4">
                                        <?php esc_html_e('Custom', 'rachel-advocacy'); ?>
                                    </div>
                                    <p class="text-neutral-600 mb-6">
                                        <?php esc_html_e('Comprehensive packages tailored to your specific situation', 'rachel-advocacy'); ?>
                                    </p>
                                    <?php
                                    rachel_advocacy_get_component('button', array(
                                        'text' => __('Get Quote', 'rachel-advocacy'),
                                        'url' => get_permalink(get_page_by_path('contact')),
                                        'variant' => 'outline'
                                    ));
                                    ?>
                                </div>

                            </div>

                            <div class="text-center">
                                <p class="text-neutral-600 mb-4">
                                    <?php esc_html_e('Most insurance plans and HSA/FSA accounts can be used for healthcare advocacy services.', 'rachel-advocacy'); ?>
                                </p>
                                <p class="text-sm text-neutral-500">
                                    <?php esc_html_e('Payment plans available • No upfront fees for insurance appeals', 'rachel-advocacy'); ?>
                                </p>
                            </div>

                        </div>

                    </div>
                </section>

                <!-- Contact CTA -->
                <section class="contact-cta bg-gradient-to-r from-primary-600 to-primary-700 text-white section-large">
                    <div class="container text-center">
                        
                        <h2 class="text-3xl md:text-4xl font-bold mb-6">
                            <?php esc_html_e('Ready to Get the Healthcare You Deserve?', 'rachel-advocacy'); ?>
                        </h2>
                        
                        <div class="text-lg md:text-xl mb-8 opacity-90 max-w-3xl mx-auto">
                            <p><?php esc_html_e('Don\'t let insurance denials, provider miscommunication, or system complexity prevent you from getting the care you need.', 'rachel-advocacy'); ?></p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <?php
                            rachel_advocacy_get_component('button', array(
                                'text' => __('Schedule Free Consultation', 'rachel-advocacy'),
                                'url' => get_permalink(get_page_by_path('contact')),
                                'variant' => 'outline',
                                'size' => 'lg'
                            ));
                            
                            rachel_advocacy_get_component('button', array(
                                'text' => __('Call (555) 123-4567', 'rachel-advocacy'),
                                'url' => 'tel:+15551234567',
                                'variant' => 'ghost',
                                'size' => 'lg'
                            ));
                            ?>
                        </div>
                        
                    </div>
                </section>
                
            <?php endif; ?>
            
        </article>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?> 