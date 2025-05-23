    <!-- Site Footer -->
    <footer id="colophon" class="site-footer bg-neutral-900 text-white" role="contentinfo">
        
        <!-- Main Footer Content -->
        <div class="footer-main py-16">
            <div class="container">
                
                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8 lg:gap-12">
                    
                    <!-- Company Info -->
                    <div class="footer-section company-info">
                        
                        <div class="footer-logo mb-6">
                            <?php if (has_custom_logo()): ?>
                                <div class="custom-logo-footer">
                                    <?php the_custom_logo(); ?>
                                </div>
                            <?php else: ?>
                                <h2 class="text-2xl font-bold text-white mb-2">
                                    <?php bloginfo('name'); ?>
                                </h2>
                            <?php endif; ?>
                            
                            <?php if (get_bloginfo('description')): ?>
                                <p class="text-neutral-300 text-sm">
                                    <?php bloginfo('description'); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="company-description mb-6">
                            <p class="text-neutral-300 leading-relaxed">
                                <?php esc_html_e('Professional healthcare advocacy services helping patients navigate insurance appeals, coordinate care, and make informed medical decisions with confidence.', 'rachel-advocacy'); ?>
                            </p>
                        </div>

                        <!-- Credentials & Certifications -->
                        <div class="credentials">
                            <p class="text-xs text-neutral-400 mb-2">
                                <?php esc_html_e('Licensed Healthcare Advocate', 'rachel-advocacy'); ?>
                            </p>
                            <p class="text-xs text-neutral-400">
                                <?php esc_html_e('HIPAA Compliant • Bonded & Insured', 'rachel-advocacy'); ?>
                            </p>
                        </div>

                    </div>

                    <!-- Quick Links -->
                    <div class="footer-section quick-links">
                        
                        <h3 class="text-lg font-semibold text-white mb-6">
                            <?php esc_html_e('Quick Links', 'rachel-advocacy'); ?>
                        </h3>

                        <nav aria-label="<?php esc_attr_e('Footer Navigation', 'rachel-advocacy'); ?>">
                            <?php
                            if (has_nav_menu('footer')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'footer',
                                    'menu_class'     => 'footer-nav space-y-3',
                                    'container'      => false,
                                    'fallback_cb'    => false,
                                    'walker'         => new Rachel_Advocacy_Walker_Nav_Menu('footer')
                                ));
                            } else {
                                // Fallback footer menu
                                echo '<ul class="footer-nav space-y-3">';
                                echo '<li><a href="' . home_url('/') . '" class="footer-link">Home</a></li>';
                                echo '<li><a href="' . home_url('/about/') . '" class="footer-link">About Rachel</a></li>';
                                echo '<li><a href="' . home_url('/services/') . '" class="footer-link">Services</a></li>';
                                echo '<li><a href="' . home_url('/faq/') . '" class="footer-link">FAQ</a></li>';
                                echo '<li><a href="' . home_url('/contact/') . '" class="footer-link">Contact</a></li>';
                                echo '<li><a href="' . get_privacy_policy_url() . '" class="footer-link">Privacy Policy</a></li>';
                                echo '</ul>';
                            }
                            ?>
                        </nav>

                    </div>

                    <!-- Services -->
                    <div class="footer-section services-list">
                        
                        <h3 class="text-lg font-semibold text-white mb-6">
                            <?php esc_html_e('Services', 'rachel-advocacy'); ?>
                        </h3>

                        <ul class="services-nav space-y-3">
                            <li>
                                <a href="<?php echo home_url('/services/#insurance-appeals'); ?>" class="footer-link">
                                    <?php esc_html_e('Insurance Appeals', 'rachel-advocacy'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo home_url('/services/#care-coordination'); ?>" class="footer-link">
                                    <?php esc_html_e('Care Coordination', 'rachel-advocacy'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo home_url('/services/#appointment-support'); ?>" class="footer-link">
                                    <?php esc_html_e('Appointment Support', 'rachel-advocacy'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo home_url('/services/#medical-records'); ?>" class="footer-link">
                                    <?php esc_html_e('Medical Record Review', 'rachel-advocacy'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo home_url('/services/#treatment-decisions'); ?>" class="footer-link">
                                    <?php esc_html_e('Treatment Decision Support', 'rachel-advocacy'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo home_url('/services/#crisis-support'); ?>" class="footer-link">
                                    <?php esc_html_e('Crisis Support', 'rachel-advocacy'); ?>
                                </a>
                            </li>
                        </ul>

                    </div>

                    <!-- Contact Info -->
                    <div class="footer-section contact-info">
                        
                        <h3 class="text-lg font-semibold text-white mb-6">
                            <?php esc_html_e('Get in Touch', 'rachel-advocacy'); ?>
                        </h3>

                        <div class="contact-details space-y-4">
                            
                            <!-- Phone -->
                            <div class="contact-item">
                                <a href="tel:+15551234567" class="flex items-start text-neutral-300 hover:text-white transition-colors">
                                    <span class="text-lg mr-3 mt-0.5">📞</span>
                                    <div>
                                        <div class="font-medium"><?php esc_html_e('Phone & Text', 'rachel-advocacy'); ?></div>
                                        <div class="text-sm">(555) 123-4567</div>
                                        <div class="text-xs text-neutral-400"><?php esc_html_e('Mon-Fri 9am-6pm PST', 'rachel-advocacy'); ?></div>
                                    </div>
                                </a>
                            </div>

                            <!-- Email -->
                            <div class="contact-item">
                                <a href="mailto:rachel@racheladvocacy.com" class="flex items-start text-neutral-300 hover:text-white transition-colors">
                                    <span class="text-lg mr-3 mt-0.5">📧</span>
                                    <div>
                                        <div class="font-medium"><?php esc_html_e('Email', 'rachel-advocacy'); ?></div>
                                        <div class="text-sm">rachel@racheladvocacy.com</div>
                                        <div class="text-xs text-neutral-400"><?php esc_html_e('Response within 24 hours', 'rachel-advocacy'); ?></div>
                                    </div>
                                </a>
                            </div>

                            <!-- Emergency -->
                            <div class="emergency-contact mt-6 p-4 bg-error-900 border border-error-700 rounded-lg">
                                <div class="text-sm font-medium text-error-200 mb-1">
                                    <?php esc_html_e('Emergency Support', 'rachel-advocacy'); ?>
                                </div>
                                <div class="text-xs text-error-300">
                                    <?php esc_html_e('24/7 availability for existing clients with urgent healthcare advocacy needs', 'rachel-advocacy'); ?>
                                </div>
                            </div>

                        </div>

                        <!-- CTA Button -->
                        <div class="footer-cta mt-8">
                            <?php
                            rachel_advocacy_get_component('button', array(
                                'text' => __('Free Consultation', 'rachel-advocacy'),
                                'url' => get_permalink(get_page_by_path('contact')),
                                'variant' => 'outline',
                                'size' => 'sm',
                                'attributes' => array('class' => 'w-full justify-center border-white text-white hover:bg-white hover:text-neutral-900'),
                                'aria_label' => __('Schedule your free consultation', 'rachel-advocacy')
                            ));
                            ?>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        <!-- Trust Indicators -->
        <div class="trust-indicators bg-neutral-800 py-8 border-t border-neutral-700">
            <div class="container">
                
                <div class="grid md:grid-cols-3 gap-6 text-center">
                    
                    <div class="trust-item">
                        <div class="trust-icon text-2xl mb-2">🔒</div>
                        <div class="trust-title text-sm font-medium text-white mb-1">
                            <?php esc_html_e('HIPAA Compliant', 'rachel-advocacy'); ?>
                        </div>
                        <div class="trust-description text-xs text-neutral-400">
                            <?php esc_html_e('Your health information is always secure and confidential', 'rachel-advocacy'); ?>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon text-2xl mb-2">⚡</div>
                        <div class="trust-title text-sm font-medium text-white mb-1">
                            <?php esc_html_e('Rapid Response', 'rachel-advocacy'); ?>
                        </div>
                        <div class="trust-description text-xs text-neutral-400">
                            <?php esc_html_e('Quick action on time-sensitive healthcare situations', 'rachel-advocacy'); ?>
                        </div>
                    </div>

                    <div class="trust-item">
                        <div class="trust-icon text-2xl mb-2">💰</div>
                        <div class="trust-title text-sm font-medium text-white mb-1">
                            <?php esc_html_e('Transparent Pricing', 'rachel-advocacy'); ?>
                        </div>
                        <div class="trust-description text-xs text-neutral-400">
                            <?php esc_html_e('Clear fees with no hidden costs or surprise charges', 'rachel-advocacy'); ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom bg-neutral-950 py-6 border-t border-neutral-800">
            <div class="container">
                
                <div class="md:flex md:justify-between md:items-center">
                    
                    <!-- Copyright -->
                    <div class="copyright text-neutral-400 text-sm mb-4 md:mb-0">
                        <p>
                            <?php
                            printf(
                                esc_html__('© %1$s %2$s. All rights reserved.', 'rachel-advocacy'),
                                date('Y'),
                                get_bloginfo('name')
                            );
                            ?>
                        </p>
                    </div>

                    <!-- Legal Links -->
                    <div class="legal-links">
                        <ul class="flex flex-wrap items-center space-x-6 text-sm">
                            
                            <?php if (get_privacy_policy_url()): ?>
                                <li>
                                    <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" class="text-neutral-400 hover:text-white transition-colors">
                                        <?php esc_html_e('Privacy Policy', 'rachel-advocacy'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <li>
                                <a href="<?php echo home_url('/terms-of-service/'); ?>" class="text-neutral-400 hover:text-white transition-colors">
                                    <?php esc_html_e('Terms of Service', 'rachel-advocacy'); ?>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo home_url('/accessibility/'); ?>" class="text-neutral-400 hover:text-white transition-colors">
                                    <?php esc_html_e('Accessibility', 'rachel-advocacy'); ?>
                                </a>
                            </li>

                        </ul>
                    </div>

                </div>

                <!-- Professional Disclaimer -->
                <div class="disclaimer mt-6 pt-6 border-t border-neutral-800">
                    <p class="text-xs text-neutral-500 leading-relaxed">
                        <?php esc_html_e('Professional healthcare advocacy services. Not a substitute for medical advice, diagnosis, or treatment. Always consult with qualified healthcare providers regarding medical decisions. Rachel Lee Patient Advocacy works within all applicable laws and professional standards.', 'rachel-advocacy'); ?>
                    </p>
                </div>

            </div>
        </div>

    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

<!-- Accessibility Enhancement Script -->
<script>
document.documentElement.className = document.documentElement.className.replace('no-js', 'js');

// Enhanced keyboard navigation
document.addEventListener('keydown', function(e) {
    // Add visible focus indicators for keyboard users
    if (e.key === 'Tab') {
        document.body.classList.add('user-is-tabbing');
    }
});

document.addEventListener('mousedown', function() {
    document.body.classList.remove('user-is-tabbing');
});

// Announce page changes to screen readers
if (window.history && window.history.pushState) {
    window.addEventListener('popstate', function() {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = 'Page content changed';
        document.body.appendChild(announcement);
        
        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
    });
}
</script>

</body>
</html> 