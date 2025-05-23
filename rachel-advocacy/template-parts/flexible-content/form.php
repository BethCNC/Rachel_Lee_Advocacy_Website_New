<?php
/**
 * Form Block Template
 * 
 * Flexible content template for contact and intake forms
 * 
 * ACF Fields Expected:
 * - form_title (Text)
 * - form_subtitle (Textarea)
 * - form_type (Select: contact, intake, consultation)
 * - form_recipient_email (Email)
 * - form_success_message (Textarea)
 * - form_background (Select: white, neutral, primary)
 */

// Get ACF fields
$title = get_sub_field('form_title');
$subtitle = get_sub_field('form_subtitle');
$form_type = get_sub_field('form_type') ?: 'contact';
$recipient_email = get_sub_field('form_recipient_email') ?: get_option('admin_email');
$success_message = get_sub_field('form_success_message');
$background = get_sub_field('form_background') ?: 'white';

// Background classes
$bg_classes = array(
    'white' => 'bg-white',
    'neutral' => 'bg-neutral-50',
    'primary' => 'bg-primary-50'
);

$background_class = $bg_classes[$background] ?? $bg_classes['white'];

// Form configurations based on type
$form_configs = array(
    'contact' => array(
        'default_title' => __('Contact Rachel', 'rachel-advocacy'),
        'default_subtitle' => __('Ready to take control of your healthcare journey? Send me a message and I\'ll get back to you within 24 hours.', 'rachel-advocacy'),
        'fields' => array('name', 'email', 'phone', 'subject', 'message')
    ),
    'intake' => array(
        'default_title' => __('Client Intake Form', 'rachel-advocacy'),
        'default_subtitle' => __('Please provide some initial information about your healthcare advocacy needs so I can better assist you.', 'rachel-advocacy'),
        'fields' => array('name', 'email', 'phone', 'dob', 'insurance', 'medical_condition', 'advocacy_needs', 'urgency', 'previous_advocacy')
    ),
    'consultation' => array(
        'default_title' => __('Schedule a Consultation', 'rachel-advocacy'),
        'default_subtitle' => __('Book a free 15-minute consultation to discuss your healthcare advocacy needs.', 'rachel-advocacy'),
        'fields' => array('name', 'email', 'phone', 'preferred_date', 'preferred_time', 'consultation_type', 'brief_description')
    )
);

$config = $form_configs[$form_type] ?? $form_configs['contact'];
$form_title = $title ?: $config['default_title'];
$form_subtitle = $subtitle ?: $config['default_subtitle'];

$form_id = 'form-' . $form_type . '-' . uniqid();
?>

<section class="form-section <?php echo esc_attr($background_class); ?> section" aria-labelledby="form-heading">
    <div class="container">
        
        <div class="max-w-4xl mx-auto">
            
            <?php if ($form_title || $form_subtitle): ?>
                <header class="form-header text-center mb-12">
                    
                    <?php if ($form_title): ?>
                        <h2 id="form-heading" class="form-title text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                            <?php echo esc_html($form_title); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if ($form_subtitle): ?>
                        <div class="form-subtitle text-lg md:text-xl text-neutral-600 max-w-3xl mx-auto">
                            <?php echo wp_kses_post(wpautop($form_subtitle)); ?>
                        </div>
                    <?php endif; ?>

                </header>
            <?php endif; ?>

            <div class="form-container bg-white rounded-lg shadow-lg p-8 md:p-12">
                
                <!-- Success Message (Hidden by default) -->
                <div id="<?php echo esc_attr($form_id); ?>-success" 
                     class="form-success hidden mb-8 p-6 bg-success-50 border border-success-200 rounded-lg"
                     role="alert"
                     aria-live="polite">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-success-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div class="text-success-800">
                            <?php echo $success_message ? wp_kses_post($success_message) : __('Thank you! Your message has been sent successfully. I\'ll get back to you soon.', 'rachel-advocacy'); ?>
                        </div>
                    </div>
                </div>

                <!-- Error Message (Hidden by default) -->
                <div id="<?php echo esc_attr($form_id); ?>-error" 
                     class="form-error hidden mb-8 p-6 bg-error-50 border border-error-200 rounded-lg"
                     role="alert"
                     aria-live="assertive">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-error-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-error-800" id="<?php echo esc_attr($form_id); ?>-error-message">
                            <?php esc_html_e('There was an error sending your message. Please try again.', 'rachel-advocacy'); ?>
                        </div>
                    </div>
                </div>

                <form id="<?php echo esc_attr($form_id); ?>" 
                      class="rachel-advocacy-form" 
                      method="post" 
                      action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
                      novalidate>
                    
                    <input type="hidden" name="action" value="rachel_advocacy_form_submit">
                    <input type="hidden" name="form_type" value="<?php echo esc_attr($form_type); ?>">
                    <input type="hidden" name="recipient_email" value="<?php echo esc_attr($recipient_email); ?>">
                    <?php wp_nonce_field('rachel_advocacy_form_' . $form_type, 'form_nonce'); ?>

                    <div class="form-fields grid gap-6">
                        
                        <?php if (in_array('name', $config['fields'])): ?>
                            <div class="form-group">
                                <label for="<?php echo esc_attr($form_id); ?>-name" class="form-label required">
                                    <?php esc_html_e('Full Name', 'rachel-advocacy'); ?>
                                </label>
                                <input type="text" 
                                       id="<?php echo esc_attr($form_id); ?>-name" 
                                       name="name" 
                                       class="form-control" 
                                       required
                                       aria-describedby="<?php echo esc_attr($form_id); ?>-name-error">
                                <div id="<?php echo esc_attr($form_id); ?>-name-error" class="form-error-message" role="alert"></div>
                            </div>
                        <?php endif; ?>

                        <div class="grid md:grid-cols-2 gap-6">
                            
                            <?php if (in_array('email', $config['fields'])): ?>
                                <div class="form-group">
                                    <label for="<?php echo esc_attr($form_id); ?>-email" class="form-label required">
                                        <?php esc_html_e('Email Address', 'rachel-advocacy'); ?>
                                    </label>
                                    <input type="email" 
                                           id="<?php echo esc_attr($form_id); ?>-email" 
                                           name="email" 
                                           class="form-control" 
                                           required
                                           aria-describedby="<?php echo esc_attr($form_id); ?>-email-error">
                                    <div id="<?php echo esc_attr($form_id); ?>-email-error" class="form-error-message" role="alert"></div>
                                </div>
                            <?php endif; ?>

                            <?php if (in_array('phone', $config['fields'])): ?>
                                <div class="form-group">
                                    <label for="<?php echo esc_attr($form_id); ?>-phone" class="form-label">
                                        <?php esc_html_e('Phone Number', 'rachel-advocacy'); ?>
                                    </label>
                                    <input type="tel" 
                                           id="<?php echo esc_attr($form_id); ?>-phone" 
                                           name="phone" 
                                           class="form-control">
                                </div>
                            <?php endif; ?>

                        </div>

                        <?php if (in_array('subject', $config['fields'])): ?>
                            <div class="form-group">
                                <label for="<?php echo esc_attr($form_id); ?>-subject" class="form-label">
                                    <?php esc_html_e('Subject', 'rachel-advocacy'); ?>
                                </label>
                                <input type="text" 
                                       id="<?php echo esc_attr($form_id); ?>-subject" 
                                       name="subject" 
                                       class="form-control">
                            </div>
                        <?php endif; ?>

                        <?php if (in_array('message', $config['fields'])): ?>
                            <div class="form-group">
                                <label for="<?php echo esc_attr($form_id); ?>-message" class="form-label required">
                                    <?php esc_html_e('Message', 'rachel-advocacy'); ?>
                                </label>
                                <textarea id="<?php echo esc_attr($form_id); ?>-message" 
                                          name="message" 
                                          rows="6" 
                                          class="form-control" 
                                          required
                                          aria-describedby="<?php echo esc_attr($form_id); ?>-message-error"
                                          placeholder="<?php esc_attr_e('Please describe how I can help you with your healthcare advocacy needs...', 'rachel-advocacy'); ?>"></textarea>
                                <div id="<?php echo esc_attr($form_id); ?>-message-error" class="form-error-message" role="alert"></div>
                            </div>
                        <?php endif; ?>

                        <!-- Additional fields for specific form types -->
                        <?php if ($form_type === 'intake'): ?>
                            
                            <?php if (in_array('medical_condition', $config['fields'])): ?>
                                <div class="form-group">
                                    <label for="<?php echo esc_attr($form_id); ?>-medical-condition" class="form-label">
                                        <?php esc_html_e('Primary Medical Condition/Concern', 'rachel-advocacy'); ?>
                                    </label>
                                    <textarea id="<?php echo esc_attr($form_id); ?>-medical-condition" 
                                              name="medical_condition" 
                                              rows="3" 
                                              class="form-control"></textarea>
                                </div>
                            <?php endif; ?>

                            <?php if (in_array('advocacy_needs', $config['fields'])): ?>
                                <div class="form-group">
                                    <fieldset>
                                        <legend class="form-label">
                                            <?php esc_html_e('What type of advocacy support do you need?', 'rachel-advocacy'); ?>
                                        </legend>
                                        <div class="checkbox-group">
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="advocacy_needs[]" value="insurance_appeals" class="checkbox">
                                                <?php esc_html_e('Insurance Appeals', 'rachel-advocacy'); ?>
                                            </label>
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="advocacy_needs[]" value="care_coordination" class="checkbox">
                                                <?php esc_html_e('Care Coordination', 'rachel-advocacy'); ?>
                                            </label>
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="advocacy_needs[]" value="medical_record_review" class="checkbox">
                                                <?php esc_html_e('Medical Record Review', 'rachel-advocacy'); ?>
                                            </label>
                                            <label class="checkbox-label">
                                                <input type="checkbox" name="advocacy_needs[]" value="appointment_support" class="checkbox">
                                                <?php esc_html_e('Appointment Support', 'rachel-advocacy'); ?>
                                            </label>
                                        </div>
                                    </fieldset>
                                </div>
                            <?php endif; ?>

                        <?php endif; ?>

                    </div>

                    <div class="form-footer mt-8 text-center">
                        <?php
                        rachel_advocacy_get_component('button', array(
                            'text' => $form_type === 'consultation' ? __('Schedule Consultation', 'rachel-advocacy') : __('Send Message', 'rachel-advocacy'),
                            'variant' => 'primary',
                            'size' => 'lg',
                            'attributes' => array(
                                'type' => 'submit',
                                'class' => 'form-submit-button',
                                'data-form-id' => $form_id
                            )
                        ));
                        ?>
                        
                        <div class="form-loading hidden mt-4" role="status" aria-live="polite">
                            <span class="sr-only"><?php esc_html_e('Sending message...', 'rachel-advocacy'); ?></span>
                            <div class="inline-flex items-center">
                                <svg class="animate-spin h-5 w-5 text-primary-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <?php esc_html_e('Sending...', 'rachel-advocacy'); ?>
                            </div>
                        </div>
                    </div>

                </form>

            </div>

        </div>

    </div>
</section> 