<?php
/**
 * Template Name: Home Page
 * 
 * Custom home page template with flexible content blocks
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('home-page'); ?>>
            
            <?php if (have_rows('page_content')): ?>
                
                <div class="flexible-content">
                    <?php
                    while (have_rows('page_content')) : the_row();
                        $layout = get_row_layout();
                        
                        // Map layout names to template files
                        $template_map = array(
                            'hero_block' => 'hero',
                            'image_text_split' => 'image-text', 
                            'testimonial_block' => 'testimonial',
                            'faq_block' => 'faq',
                            'cta_banner' => 'cta-banner',
                            'form_block' => 'form'
                        );
                        
                        $template_name = $template_map[$layout] ?? $layout;
                        $template_path = get_template_directory() . '/template-parts/flexible-content/' . $template_name . '.php';
                        
                        if (file_exists($template_path)) {
                            include $template_path;
                        } else {
                            // Fallback for missing templates
                            echo '<!-- Missing template: ' . esc_html($template_name) . '.php -->';
                        }
                        
                    endwhile;
                    ?>
                </div>
                
            <?php else: ?>
                
                <!-- Fallback content if no flexible content is set -->
                <section class="hero-section bg-gradient-to-br from-primary-600 to-primary-800 text-white section-large">
                    <div class="container text-center">
                        
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                            <?php esc_html_e('Your Healthcare Advocate', 'rachel-advocacy'); ?>
                        </h1>
                        
                        <div class="text-lg md:text-xl lg:text-2xl mb-8 opacity-90 max-w-3xl mx-auto">
                            <p><?php esc_html_e('Empowering you to navigate the complex healthcare system with confidence and get the care you deserve.', 'rachel-advocacy'); ?></p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <?php
                            rachel_advocacy_get_component('button', array(
                                'text' => __('Get Started', 'rachel-advocacy'),
                                'url' => get_permalink(get_page_by_path('contact')),
                                'variant' => 'outline',
                                'size' => 'lg'
                            ));
                            
                            rachel_advocacy_get_component('button', array(
                                'text' => __('Learn More', 'rachel-advocacy'),
                                'url' => get_permalink(get_page_by_path('about')),
                                'variant' => 'ghost',
                                'size' => 'lg'
                            ));
                            ?>
                        </div>
                        
                    </div>
                </section>

                <!-- Services Overview -->
                <section class="services-overview bg-white section">
                    <div class="container">
                        
                        <header class="text-center mb-12">
                            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                                <?php esc_html_e('How I Can Help You', 'rachel-advocacy'); ?>
                            </h2>
                            <p class="text-lg md:text-xl text-neutral-600 max-w-3xl mx-auto">
                                <?php esc_html_e('Professional healthcare advocacy services designed to help you navigate medical challenges with confidence.', 'rachel-advocacy'); ?>
                            </p>
                        </header>

                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                            
                            <?php
                            $services = array(
                                array(
                                    'title' => __('Insurance Appeals', 'rachel-advocacy'),
                                    'content' => __('Expert assistance with denied claims, prior authorizations, and insurance disputes to ensure you get covered care.', 'rachel-advocacy'),
                                    'icon' => '📋'
                                ),
                                array(
                                    'title' => __('Care Coordination', 'rachel-advocacy'),
                                    'content' => __('Seamless coordination between your healthcare providers to prevent gaps in care and improve outcomes.', 'rachel-advocacy'),
                                    'icon' => '🤝'
                                ),
                                array(
                                    'title' => __('Medical Record Review', 'rachel-advocacy'),
                                    'content' => __('Thorough analysis of your medical records to identify inconsistencies and ensure accurate documentation.', 'rachel-advocacy'),
                                    'icon' => '📄'
                                ),
                                array(
                                    'title' => __('Appointment Support', 'rachel-advocacy'),
                                    'content' => __('Accompany you to important medical appointments to ensure your concerns are heard and understood.', 'rachel-advocacy'),
                                    'icon' => '🏥'
                                ),
                                array(
                                    'title' => __('Treatment Planning', 'rachel-advocacy'),
                                    'content' => __('Help you understand treatment options and make informed decisions about your healthcare journey.', 'rachel-advocacy'),
                                    'icon' => '🗺️'
                                ),
                                array(
                                    'title' => __('Crisis Support', 'rachel-advocacy'),
                                    'content' => __('Immediate advocacy support during medical emergencies and critical healthcare decisions.', 'rachel-advocacy'),
                                    'icon' => '🚨'
                                )
                            );

                            foreach ($services as $service):
                            ?>
                                <div class="service-card">
                                    <?php
                                    rachel_advocacy_get_component('card', array(
                                        'title' => $service['icon'] . ' ' . $service['title'],
                                        'content' => $service['content'],
                                        'variant' => 'default'
                                    ));
                                    ?>
                                </div>
                            <?php endforeach; ?>

                        </div>

                        <div class="text-center mt-12">
                            <?php
                            rachel_advocacy_get_component('button', array(
                                'text' => __('View All Services', 'rachel-advocacy'),
                                'url' => get_permalink(get_page_by_path('services')),
                                'variant' => 'primary',
                                'size' => 'lg'
                            ));
                            ?>
                        </div>

                    </div>
                </section>

                <!-- About Rachel -->
                <section class="about-rachel bg-neutral-50 section">
                    <div class="container">
                        
                        <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center">
                            
                            <div class="about-content">
                                <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-6">
                                    <?php esc_html_e('Meet Rachel Lee', 'rachel-advocacy'); ?>
                                </h2>
                                
                                <div class="prose prose-lg max-w-none text-neutral-700 mb-6">
                                    <p><?php esc_html_e('With over a decade of experience in healthcare advocacy, I understand the challenges you face when navigating our complex medical system. My mission is to empower you with the knowledge, support, and advocacy you need to receive the best possible care.', 'rachel-advocacy'); ?></p>
                                    
                                    <p><?php esc_html_e('Whether you\'re dealing with insurance denials, coordinating care between multiple providers, or simply need someone to help you understand your options, I\'m here to be your trusted advocate every step of the way.', 'rachel-advocacy'); ?></p>
                                </div>
                                
                                <?php
                                rachel_advocacy_get_component('button', array(
                                    'text' => __('Learn More About Rachel', 'rachel-advocacy'),
                                    'url' => get_permalink(get_page_by_path('about')),
                                    'variant' => 'primary'
                                ));
                                ?>
                            </div>

                            <div class="about-image mt-8 lg:mt-0">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/rachel-lee-placeholder.jpg'); ?>"
                                     alt="<?php esc_attr_e('Rachel Lee, Patient Advocate', 'rachel-advocacy'); ?>"
                                     class="w-full h-auto rounded-lg shadow-lg">
                            </div>

                        </div>

                    </div>
                </section>

                <!-- Contact CTA -->
                <section class="contact-cta bg-gradient-to-r from-primary-600 to-primary-700 text-white section-large">
                    <div class="container text-center">
                        
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
                            <?php esc_html_e('Ready to Take Control of Your Healthcare?', 'rachel-advocacy'); ?>
                        </h2>
                        
                        <div class="text-lg md:text-xl mb-8 opacity-90 max-w-3xl mx-auto">
                            <p><?php esc_html_e('Don\'t navigate the healthcare system alone. Let me be your advocate and guide you toward better health outcomes.', 'rachel-advocacy'); ?></p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <?php
                            rachel_advocacy_get_component('button', array(
                                'text' => __('Schedule Consultation', 'rachel-advocacy'),
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

<?php
get_footer(); 