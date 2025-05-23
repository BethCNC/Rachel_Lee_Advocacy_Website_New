<?php
/**
 * Template Name: Contact Page
 * 
 * Contact page template with flexible content blocks
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('contact-page'); ?>>
            
            <?php if (have_rows('page_content')): ?>
                
                <div class="flexible-content">
                    <?php rachel_advocacy_render_flexible_content(); ?>
                </div>
                
            <?php else: ?>
                
                <!-- Fallback content if no flexible content is set -->
                <section class="page-hero bg-gradient-to-br from-primary-600 to-secondary-600 text-white section-large">
                    <div class="container text-center">
                        
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                            <?php the_title(); ?>
                        </h1>
                        
                        <div class="text-lg md:text-xl lg:text-2xl opacity-90 max-w-3xl mx-auto">
                            <p><?php esc_html_e('Ready to take control of your healthcare journey? Let\'s discuss how I can help you navigate challenges and achieve better outcomes.', 'rachel-advocacy'); ?></p>
                        </div>
                        
                    </div>
                </section>

                <!-- Contact Methods -->
                <section class="contact-methods bg-white section">
                    <div class="container">
                        
                        <header class="text-center mb-12">
                            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                                <?php esc_html_e('Get in Touch', 'rachel-advocacy'); ?>
                            </h2>
                            <p class="text-lg md:text-xl text-neutral-600 max-w-3xl mx-auto">
                                <?php esc_html_e('Choose the method that works best for you. All initial consultations are completely free and confidential.', 'rachel-advocacy'); ?>
                            </p>
                        </header>

                        <div class="grid md:grid-cols-3 gap-8 mb-16">
                            
                            <!-- Phone -->
                            <div class="contact-method text-center">
                                <div class="contact-icon text-4xl text-primary-600 mb-4">📞</div>
                                <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                    <?php esc_html_e('Call or Text', 'rachel-advocacy'); ?>
                                </h3>
                                <p class="text-neutral-600 mb-4">
                                    <?php esc_html_e('Immediate response during business hours', 'rachel-advocacy'); ?>
                                </p>
                                <div class="contact-info">
                                    <a href="tel:+15551234567" class="text-lg font-medium text-primary-600 hover:text-primary-700">
                                        (555) 123-4567
                                    </a>
                                </div>
                                <div class="text-sm text-neutral-500 mt-2">
                                    <?php esc_html_e('Mon-Fri 9am-6pm PST', 'rachel-advocacy'); ?>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="contact-method text-center">
                                <div class="contact-icon text-4xl text-secondary-600 mb-4">📧</div>
                                <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                    <?php esc_html_e('Email', 'rachel-advocacy'); ?>
                                </h3>
                                <p class="text-neutral-600 mb-4">
                                    <?php esc_html_e('Detailed inquiries and documentation', 'rachel-advocacy'); ?>
                                </p>
                                <div class="contact-info">
                                    <a href="mailto:rachel@racheladvocacy.com" class="text-lg font-medium text-secondary-600 hover:text-secondary-700">
                                        rachel@racheladvocacy.com
                                    </a>
                                </div>
                                <div class="text-sm text-neutral-500 mt-2">
                                    <?php esc_html_e('Response within 24 hours', 'rachel-advocacy'); ?>
                                </div>
                            </div>

                            <!-- Video Call -->
                            <div class="contact-method text-center">
                                <div class="contact-icon text-4xl text-neutral-600 mb-4">💻</div>
                                <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                    <?php esc_html_e('Video Consultation', 'rachel-advocacy'); ?>
                                </h3>
                                <p class="text-neutral-600 mb-4">
                                    <?php esc_html_e('Face-to-face consultations via Zoom', 'rachel-advocacy'); ?>
                                </p>
                                <div class="contact-info">
                                    <?php
                                    rachel_advocacy_get_component('button', array(
                                        'text' => __('Schedule Call', 'rachel-advocacy'),
                                        'url' => '#contact-form',
                                        'variant' => 'outline',
                                        'size' => 'sm'
                                    ));
                                    ?>
                                </div>
                                <div class="text-sm text-neutral-500 mt-2">
                                    <?php esc_html_e('Flexible scheduling available', 'rachel-advocacy'); ?>
                                </div>
                            </div>

                        </div>

                    </div>
                </section>

                <!-- Contact Form -->
                <section id="contact-form" class="contact-form bg-neutral-50 section">
                    <div class="container">
                        
                        <div class="max-w-4xl mx-auto">
                            
                            <header class="text-center mb-12">
                                <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                                    <?php esc_html_e('Send a Message', 'rachel-advocacy'); ?>
                                </h2>
                                <p class="text-lg text-neutral-600">
                                    <?php esc_html_e('Tell me about your situation and I\'ll respond with next steps and availability.', 'rachel-advocacy'); ?>
                                </p>
                            </header>

                            <div class="lg:grid lg:grid-cols-3 lg:gap-12">
                                
                                <!-- Form -->
                                <div class="form-container lg:col-span-2 mb-12 lg:mb-0">
                                    
                                    <form id="contact-form" class="contact-form bg-white rounded-lg shadow-lg p-8" action="#" method="post" novalidate>
                                        
                                        <?php wp_nonce_field('contact_form_nonce', 'contact_nonce'); ?>
                                        
                                        <div class="form-row grid md:grid-cols-2 gap-6 mb-6">
                                            
                                            <div class="form-group">
                                                <label for="first_name" class="form-label">
                                                    <?php esc_html_e('First Name', 'rachel-advocacy'); ?>
                                                    <span class="required" aria-label="<?php esc_attr_e('Required', 'rachel-advocacy'); ?>">*</span>
                                                </label>
                                                <input type="text" 
                                                       id="first_name" 
                                                       name="first_name" 
                                                       class="form-input" 
                                                       required 
                                                       aria-describedby="first_name_error"
                                                       autocomplete="given-name">
                                                <div id="first_name_error" class="form-error" role="alert" aria-live="polite"></div>
                                            </div>

                                            <div class="form-group">
                                                <label for="last_name" class="form-label">
                                                    <?php esc_html_e('Last Name', 'rachel-advocacy'); ?>
                                                    <span class="required" aria-label="<?php esc_attr_e('Required', 'rachel-advocacy'); ?>">*</span>
                                                </label>
                                                <input type="text" 
                                                       id="last_name" 
                                                       name="last_name" 
                                                       class="form-input" 
                                                       required 
                                                       aria-describedby="last_name_error"
                                                       autocomplete="family-name">
                                                <div id="last_name_error" class="form-error" role="alert" aria-live="polite"></div>
                                            </div>

                                        </div>

                                        <div class="form-row grid md:grid-cols-2 gap-6 mb-6">
                                            
                                            <div class="form-group">
                                                <label for="email" class="form-label">
                                                    <?php esc_html_e('Email Address', 'rachel-advocacy'); ?>
                                                    <span class="required" aria-label="<?php esc_attr_e('Required', 'rachel-advocacy'); ?>">*</span>
                                                </label>
                                                <input type="email" 
                                                       id="email" 
                                                       name="email" 
                                                       class="form-input" 
                                                       required 
                                                       aria-describedby="email_error"
                                                       autocomplete="email">
                                                <div id="email_error" class="form-error" role="alert" aria-live="polite"></div>
                                            </div>

                                            <div class="form-group">
                                                <label for="phone" class="form-label">
                                                    <?php esc_html_e('Phone Number', 'rachel-advocacy'); ?>
                                                </label>
                                                <input type="tel" 
                                                       id="phone" 
                                                       name="phone" 
                                                       class="form-input" 
                                                       aria-describedby="phone_help"
                                                       autocomplete="tel">
                                                <div id="phone_help" class="form-help">
                                                    <?php esc_html_e('Optional - for faster response', 'rachel-advocacy'); ?>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="form-group mb-6">
                                            <label for="inquiry_type" class="form-label">
                                                <?php esc_html_e('Type of Inquiry', 'rachel-advocacy'); ?>
                                                <span class="required" aria-label="<?php esc_attr_e('Required', 'rachel-advocacy'); ?>">*</span>
                                            </label>
                                            <select id="inquiry_type" 
                                                    name="inquiry_type" 
                                                    class="form-select" 
                                                    required 
                                                    aria-describedby="inquiry_type_error">
                                                <option value=""><?php esc_html_e('Please select...', 'rachel-advocacy'); ?></option>
                                                <option value="insurance_appeal"><?php esc_html_e('Insurance Appeal/Denial', 'rachel-advocacy'); ?></option>
                                                <option value="care_coordination"><?php esc_html_e('Care Coordination', 'rachel-advocacy'); ?></option>
                                                <option value="appointment_support"><?php esc_html_e('Appointment Support', 'rachel-advocacy'); ?></option>
                                                <option value="medical_records"><?php esc_html_e('Medical Record Review', 'rachel-advocacy'); ?></option>
                                                <option value="treatment_decisions"><?php esc_html_e('Treatment Decision Support', 'rachel-advocacy'); ?></option>
                                                <option value="crisis_support"><?php esc_html_e('Crisis/Emergency Support', 'rachel-advocacy'); ?></option>
                                                <option value="general_consultation"><?php esc_html_e('General Consultation', 'rachel-advocacy'); ?></option>
                                                <option value="other"><?php esc_html_e('Other', 'rachel-advocacy'); ?></option>
                                            </select>
                                            <div id="inquiry_type_error" class="form-error" role="alert" aria-live="polite"></div>
                                        </div>

                                        <div class="form-group mb-6">
                                            <label for="message" class="form-label">
                                                <?php esc_html_e('Tell me about your situation', 'rachel-advocacy'); ?>
                                                <span class="required" aria-label="<?php esc_attr_e('Required', 'rachel-advocacy'); ?>">*</span>
                                            </label>
                                            <textarea id="message" 
                                                      name="message" 
                                                      class="form-textarea" 
                                                      rows="6" 
                                                      required 
                                                      aria-describedby="message_help message_error"
                                                      placeholder="<?php esc_attr_e('Please describe your healthcare challenge, timeline, and how I can best help you...', 'rachel-advocacy'); ?>"></textarea>
                                            <div id="message_help" class="form-help">
                                                <?php esc_html_e('Include any relevant details about your situation, urgency, and preferred contact method.', 'rachel-advocacy'); ?>
                                            </div>
                                            <div id="message_error" class="form-error" role="alert" aria-live="polite"></div>
                                        </div>

                                        <div class="form-group mb-6">
                                            <div class="form-checkbox">
                                                <input type="checkbox" 
                                                       id="privacy_consent" 
                                                       name="privacy_consent" 
                                                       class="form-check-input" 
                                                       required 
                                                       aria-describedby="privacy_consent_error">
                                                <label for="privacy_consent" class="form-check-label">
                                                    <?php
                                                    printf(
                                                        wp_kses(
                                                            __('I understand that this initial consultation is confidential and HIPAA-compliant. I consent to Rachel Lee contacting me to discuss my healthcare advocacy needs. <a href="%s" target="_blank">Privacy Policy</a>', 'rachel-advocacy'),
                                                            array('a' => array('href' => array(), 'target' => array()))
                                                        ),
                                                        esc_url(get_privacy_policy_url())
                                                    );
                                                    ?>
                                                    <span class="required" aria-label="<?php esc_attr_e('Required', 'rachel-advocacy'); ?>">*</span>
                                                </label>
                                                <div id="privacy_consent_error" class="form-error" role="alert" aria-live="polite"></div>
                                            </div>
                                        </div>

                                        <div class="form-submit">
                                            <?php
                                            rachel_advocacy_get_component('button', array(
                                                'text' => __('Send Message', 'rachel-advocacy'),
                                                'variant' => 'primary',
                                                'size' => 'lg',
                                                'attributes' => array(
                                                    'type' => 'submit',
                                                    'id' => 'submit-btn'
                                                )
                                            ));
                                            ?>
                                            
                                            <div id="form-loading" class="form-loading hidden" aria-live="polite">
                                                <span class="loading-spinner"></span>
                                                <?php esc_html_e('Sending your message...', 'rachel-advocacy'); ?>
                                            </div>
                                        </div>

                                        <div id="form-messages" class="form-messages" role="alert" aria-live="assertive"></div>

                                    </form>

                                </div>

                                <!-- Sidebar Info -->
                                <div class="contact-sidebar lg:col-span-1">
                                    
                                    <div class="sidebar-card bg-white rounded-lg shadow-lg p-8 mb-8">
                                        <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                            <?php esc_html_e('What to Expect', 'rachel-advocacy'); ?>
                                        </h3>
                                        <ul class="space-y-3">
                                            <li class="flex items-start">
                                                <span class="text-primary-600 mr-3 mt-1">📞</span>
                                                <span class="text-sm text-neutral-700"><?php esc_html_e('Free 15-minute consultation within 24 hours', 'rachel-advocacy'); ?></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary-600 mr-3 mt-1">🔒</span>
                                                <span class="text-sm text-neutral-700"><?php esc_html_e('All communications are confidential and HIPAA-compliant', 'rachel-advocacy'); ?></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary-600 mr-3 mt-1">📋</span>
                                                <span class="text-sm text-neutral-700"><?php esc_html_e('Clear next steps and service recommendations', 'rachel-advocacy'); ?></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-primary-600 mr-3 mt-1">💰</span>
                                                <span class="text-sm text-neutral-700"><?php esc_html_e('Transparent pricing with no hidden fees', 'rachel-advocacy'); ?></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="sidebar-card bg-primary-50 rounded-lg p-8">
                                        <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                                            <?php esc_html_e('Emergency Support', 'rachel-advocacy'); ?>
                                        </h3>
                                        <p class="text-sm text-neutral-700 mb-4">
                                            <?php esc_html_e('For urgent healthcare advocacy needs (hospital stays, insurance authorization deadlines, emergency decisions):', 'rachel-advocacy'); ?>
                                        </p>
                                        <div class="emergency-contact">
                                            <a href="tel:+15551234567" class="text-lg font-medium text-primary-600 hover:text-primary-700">
                                                (555) 123-4567
                                            </a>
                                        </div>
                                        <p class="text-xs text-neutral-500 mt-2">
                                            <?php esc_html_e('Available 24/7 for existing clients', 'rachel-advocacy'); ?>
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                </section>

                <!-- Location & Office Hours -->
                <section class="location-hours bg-white section">
                    <div class="container">
                        
                        <div class="lg:grid lg:grid-cols-2 lg:gap-16">
                            
                            <div class="office-hours mb-12 lg:mb-0">
                                <h2 class="text-2xl md:text-3xl font-bold text-neutral-900 mb-6">
                                    <?php esc_html_e('Office Hours', 'rachel-advocacy'); ?>
                                </h2>
                                
                                <div class="hours-schedule space-y-4">
                                    <div class="day-hours flex justify-between py-2 border-b border-neutral-200">
                                        <span class="font-medium"><?php esc_html_e('Monday - Friday', 'rachel-advocacy'); ?></span>
                                        <span><?php esc_html_e('9:00 AM - 6:00 PM PST', 'rachel-advocacy'); ?></span>
                                    </div>
                                    <div class="day-hours flex justify-between py-2 border-b border-neutral-200">
                                        <span class="font-medium"><?php esc_html_e('Saturday', 'rachel-advocacy'); ?></span>
                                        <span><?php esc_html_e('10:00 AM - 2:00 PM PST', 'rachel-advocacy'); ?></span>
                                    </div>
                                    <div class="day-hours flex justify-between py-2 border-b border-neutral-200">
                                        <span class="font-medium"><?php esc_html_e('Sunday', 'rachel-advocacy'); ?></span>
                                        <span><?php esc_html_e('Emergency calls only', 'rachel-advocacy'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="hours-note mt-6 p-4 bg-neutral-50 rounded-lg">
                                    <p class="text-sm text-neutral-600">
                                        <?php esc_html_e('I understand healthcare issues don\'t follow business hours. Emergency support is available 24/7 for existing clients with urgent needs.', 'rachel-advocacy'); ?>
                                    </p>
                                </div>
                            </div>

                            <div class="service-area">
                                <h2 class="text-2xl md:text-3xl font-bold text-neutral-900 mb-6">
                                    <?php esc_html_e('Service Area', 'rachel-advocacy'); ?>
                                </h2>
                                
                                <div class="service-details space-y-4">
                                    <div class="service-item">
                                        <h3 class="font-semibold text-neutral-900 mb-2">
                                            <?php esc_html_e('Virtual Services (Nationwide)', 'rachel-advocacy'); ?>
                                        </h3>
                                        <p class="text-neutral-700">
                                            <?php esc_html_e('Insurance appeals, medical record review, care coordination, and consultation services available to clients nationwide via phone, video, and secure messaging.', 'rachel-advocacy'); ?>
                                        </p>
                                    </div>
                                    
                                    <div class="service-item">
                                        <h3 class="font-semibold text-neutral-900 mb-2">
                                            <?php esc_html_e('In-Person Services (Bay Area)', 'rachel-advocacy'); ?>
                                        </h3>
                                        <p class="text-neutral-700">
                                            <?php esc_html_e('Appointment accompaniment and in-person advocacy available throughout the San Francisco Bay Area, including San Francisco, Oakland, San Jose, and surrounding communities.', 'rachel-advocacy'); ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="contact-cta mt-8">
                                    <p class="text-neutral-600 mb-4">
                                        <?php esc_html_e('Not sure if I can help with your specific situation or location?', 'rachel-advocacy'); ?>
                                    </p>
                                    <?php
                                    rachel_advocacy_get_component('button', array(
                                        'text' => __('Ask Me Directly', 'rachel-advocacy'),
                                        'url' => '#contact-form',
                                        'variant' => 'outline'
                                    ));
                                    ?>
                                </div>
                            </div>

                        </div>

                    </div>
                </section>
                
            <?php endif; ?>
            
        </article>
        
    <?php endwhile; ?>
    
</main>

<?php get_footer(); ?> 