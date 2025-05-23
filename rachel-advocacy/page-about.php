<?php
/**
 * Template Name: About Page
 * 
 * About page template with flexible content blocks
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('about-page'); ?>>
            
            <?php if (have_rows('page_content')): ?>
                
                <div class="flexible-content">
                    <?php rachel_advocacy_render_flexible_content(); ?>
                </div>
                
            <?php else: ?>
                
                <!-- Fallback content if no flexible content is set -->
                <section class="page-hero bg-gradient-to-br from-secondary-500 to-secondary-700 text-white section-large">
                    <div class="container text-center">
                        
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                            <?php the_title(); ?>
                        </h1>
                        
                        <?php if (get_the_excerpt()): ?>
                            <div class="text-lg md:text-xl lg:text-2xl opacity-90 max-w-3xl mx-auto">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </section>

                <!-- Rachel's Story -->
                <section class="rachel-story bg-white section">
                    <div class="container">
                        
                        <div class="lg:grid lg:grid-cols-2 lg:gap-16 lg:items-center">
                            
                            <div class="story-image mb-8 lg:mb-0">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/rachel-about-placeholder.jpg'); ?>"
                                     alt="<?php esc_attr_e('Rachel Lee, Certified Patient Advocate', 'rachel-advocacy'); ?>"
                                     class="w-full h-auto rounded-lg shadow-lg">
                            </div>

                            <div class="story-content">
                                <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-6">
                                    <?php esc_html_e('My Journey to Healthcare Advocacy', 'rachel-advocacy'); ?>
                                </h2>
                                
                                <div class="prose prose-lg max-w-none text-neutral-700">
                                    <p><?php esc_html_e('My path to healthcare advocacy began with my own challenging medical journey. After experiencing firsthand the frustrations of insurance denials, communication breakdowns between providers, and feeling lost in a complex system, I realized how many people need a knowledgeable advocate by their side.', 'rachel-advocacy'); ?></p>
                                    
                                    <p><?php esc_html_e('I became a Certified Patient Advocate through the National Association of Healthcare Advocacy and have since helped hundreds of individuals and families navigate their healthcare challenges. My approach combines professional expertise with genuine empathy, ensuring you never feel alone in your healthcare journey.', 'rachel-advocacy'); ?></p>
                                </div>
                            </div>

                        </div>

                    </div>
                </section>

                <!-- Mission & Values -->
                <section class="mission-values bg-neutral-50 section">
                    <div class="container">
                        
                        <header class="text-center mb-16">
                            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                                <?php esc_html_e('Mission & Values', 'rachel-advocacy'); ?>
                            </h2>
                        </header>

                        <div class="grid lg:grid-cols-3 gap-8">
                            
                            <!-- Mission -->
                            <div class="mission-card">
                                <?php
                                rachel_advocacy_get_component('card', array(
                                    'title' => '🎯 ' . __('Mission', 'rachel-advocacy'),
                                    'content' => __('To empower individuals with the knowledge, support, and advocacy they need to navigate the healthcare system confidently and receive the best possible care.', 'rachel-advocacy'),
                                    'variant' => 'featured'
                                ));
                                ?>
                            </div>

                            <!-- Compassion -->
                            <div class="value-card">
                                <?php
                                rachel_advocacy_get_component('card', array(
                                    'title' => '💙 ' . __('Compassion', 'rachel-advocacy'),
                                    'content' => __('Every client deserves to be heard, understood, and treated with dignity. I bring empathy and understanding to every interaction.', 'rachel-advocacy'),
                                    'variant' => 'default'
                                ));
                                ?>
                            </div>

                            <!-- Integrity -->
                            <div class="value-card">
                                <?php
                                rachel_advocacy_get_component('card', array(
                                    'title' => '⚖️ ' . __('Integrity', 'rachel-advocacy'),
                                    'content' => __('I operate with complete transparency, honesty, and ethical standards in all my advocacy work and client relationships.', 'rachel-advocacy'),
                                    'variant' => 'default'
                                ));
                                ?>
                            </div>

                        </div>

                    </div>
                </section>

                <!-- Credentials -->
                <section class="credentials bg-white section">
                    <div class="container">
                        
                        <header class="text-center mb-12">
                            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                                <?php esc_html_e('Credentials & Experience', 'rachel-advocacy'); ?>
                            </h2>
                        </header>

                        <div class="grid md:grid-cols-2 gap-12">
                            
                            <div class="credentials-list">
                                <h3 class="text-xl font-semibold text-neutral-900 mb-6">
                                    <?php esc_html_e('Certifications', 'rachel-advocacy'); ?>
                                </h3>
                                <ul class="space-y-4">
                                    <li class="flex items-start">
                                        <span class="text-primary-600 mr-3">✓</span>
                                        <span><?php esc_html_e('Certified Patient Advocate (NAHAC)', 'rachel-advocacy'); ?></span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-primary-600 mr-3">✓</span>
                                        <span><?php esc_html_e('Healthcare Insurance Specialist', 'rachel-advocacy'); ?></span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-primary-600 mr-3">✓</span>
                                        <span><?php esc_html_e('Medical Record Analysis Certificate', 'rachel-advocacy'); ?></span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-primary-600 mr-3">✓</span>
                                        <span><?php esc_html_e('HIPAA Privacy & Security Certification', 'rachel-advocacy'); ?></span>
                                    </li>
                                </ul>
                            </div>

                            <div class="experience-highlights">
                                <h3 class="text-xl font-semibold text-neutral-900 mb-6">
                                    <?php esc_html_e('Experience Highlights', 'rachel-advocacy'); ?>
                                </h3>
                                <ul class="space-y-4">
                                    <li class="flex items-start">
                                        <span class="text-secondary-600 mr-3">📊</span>
                                        <span><?php esc_html_e('10+ years in healthcare advocacy', 'rachel-advocacy'); ?></span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-secondary-600 mr-3">💰</span>
                                        <span><?php esc_html_e('$2M+ in insurance claims recovered', 'rachel-advocacy'); ?></span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-secondary-600 mr-3">👥</span>
                                        <span><?php esc_html_e('500+ clients successfully advocated for', 'rachel-advocacy'); ?></span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-secondary-600 mr-3">🎯</span>
                                        <span><?php esc_html_e('95% client satisfaction rate', 'rachel-advocacy'); ?></span>
                                    </li>
                                </ul>
                            </div>

                        </div>

                    </div>
                </section>

                <!-- Contact CTA -->
                <section class="contact-cta bg-gradient-to-r from-primary-600 to-secondary-600 text-white section-large">
                    <div class="container text-center">
                        
                        <h2 class="text-3xl md:text-4xl font-bold mb-6">
                            <?php esc_html_e('Ready to Work Together?', 'rachel-advocacy'); ?>
                        </h2>
                        
                        <div class="text-lg md:text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                            <p><?php esc_html_e('Let\'s discuss how I can help you navigate your healthcare challenges and achieve better outcomes.', 'rachel-advocacy'); ?></p>
                        </div>
                        
                        <?php
                        rachel_advocacy_get_component('button', array(
                            'text' => __('Schedule a Consultation', 'rachel-advocacy'),
                            'url' => get_permalink(get_page_by_path('contact')),
                            'variant' => 'outline',
                            'size' => 'lg'
                        ));
                        ?>
                        
                    </div>
                </section>
                
            <?php endif; ?>
            
        </article>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?> 