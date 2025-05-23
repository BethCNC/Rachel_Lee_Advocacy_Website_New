<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php
    // Preload critical resources
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/css/style.css" as="style">';
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/js/main.js" as="script">';
    ?>
    
    <!-- Accessibility & SEO Meta -->
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="theme-color" content="#1e40af">
    
    <?php wp_head(); ?>
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "HealthAndBeautyBusiness",
        "name": "Rachel Lee Patient Advocacy",
        "description": "Professional healthcare advocacy services to help patients navigate insurance appeals, care coordination, and complex medical decisions",
        "url": "<?php echo home_url(); ?>",
        "telephone": "+1-555-123-4567",
        "email": "rachel@racheladvocacy.com",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "San Francisco Bay Area",
            "addressRegion": "CA",
            "addressCountry": "US"
        },
        "serviceArea": {
            "@type": "GeoCircle",
            "geoMidpoint": {
                "@type": "GeoCoordinates",
                "latitude": 37.7749,
                "longitude": -122.4194
            }
        },
        "priceRange": "$150/hour",
        "openingHours": [
            "Mo-Fr 09:00-18:00",
            "Sa 10:00-14:00"
        ],
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Healthcare Advocacy Services",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Insurance Appeals",
                        "description": "Professional assistance with insurance claim denials and appeals"
                    }
                },
                {
                    "@type": "Offer", 
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Care Coordination",
                        "description": "Coordination between healthcare providers and specialists"
                    }
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service", 
                        "name": "Medical Decision Support",
                        "description": "Guidance and support for complex medical decisions"
                    }
                }
            ]
        }
    }
    </script>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<!-- Skip Links for Accessibility -->
<a class="skip-link screen-reader-text" href="#main">
    <?php esc_html_e('Skip to main content', 'rachel-advocacy'); ?>
</a>
<a class="skip-link screen-reader-text" href="#site-navigation">
    <?php esc_html_e('Skip to navigation', 'rachel-advocacy'); ?>
</a>

<div id="page" class="site">

    <!-- Site Header -->
    <header id="masthead" class="site-header bg-white shadow-sm border-b border-neutral-200 sticky top-0 z-40" role="banner">
        
        <!-- Top Bar (Optional contact info) -->
        <div class="header-top bg-primary-600 text-white text-sm py-2 hidden lg:block">
            <div class="container">
                <div class="flex justify-between items-center">
                    
                    <div class="contact-info flex items-center space-x-6">
                        <a href="tel:+15551234567" class="hover:text-primary-200 transition-colors">
                            <span class="sr-only"><?php esc_html_e('Call us at', 'rachel-advocacy'); ?></span>
                            📞 (555) 123-4567
                        </a>
                        <a href="mailto:rachel@racheladvocacy.com" class="hover:text-primary-200 transition-colors">
                            <span class="sr-only"><?php esc_html_e('Email us at', 'rachel-advocacy'); ?></span>
                            ✉️ rachel@racheladvocacy.com
                        </a>
                    </div>
                    
                    <div class="emergency-notice">
                        <span class="text-primary-200 font-medium">
                            <?php esc_html_e('24/7 Emergency Support Available for Existing Clients', 'rachel-advocacy'); ?>
                        </span>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="header-main py-4">
            <div class="container">
                <div class="flex justify-between items-center">
                    
                    <!-- Site Branding -->
                    <div class="site-branding">
                        <?php if (has_custom_logo()): ?>
                            <?php the_custom_logo(); ?>
                        <?php else: ?>
                            <div class="text-logo">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl md:text-3xl font-bold text-primary-600 hover:text-primary-700 transition-colors" rel="home">
                                    <span class="sr-only"><?php esc_html_e('Go to homepage', 'rachel-advocacy'); ?></span>
                                    <?php bloginfo('name'); ?>
                                </a>
                                <?php if (get_bloginfo('description')): ?>
                                    <p class="site-description text-sm text-neutral-600 mt-1">
                                        <?php bloginfo('description'); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Primary Navigation -->
                    <nav id="site-navigation" class="main-navigation hidden lg:block" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'rachel-advocacy'); ?>">
                        <?php
                        if (has_nav_menu('primary')) {
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'menu_class'     => 'nav-menu flex items-center space-x-8',
                                'container'      => false,
                                'fallback_cb'    => false,
                                'walker'         => new Rachel_Advocacy_Walker_Nav_Menu()
                            ));
                        } else {
                            // Fallback menu if no menu is assigned
                            echo '<ul class="nav-menu flex items-center space-x-8">';
                            echo '<li><a href="' . home_url('/') . '" class="nav-link">Home</a></li>';
                            echo '<li><a href="' . home_url('/about/') . '" class="nav-link">About</a></li>';
                            echo '<li><a href="' . home_url('/services/') . '" class="nav-link">Services</a></li>';
                            echo '<li><a href="' . home_url('/faq/') . '" class="nav-link">FAQ</a></li>';
                            echo '<li><a href="' . home_url('/contact/') . '" class="nav-link">Contact</a></li>';
                            echo '</ul>';
                        }
                        ?>
                    </nav>

                    <!-- CTA Button & Mobile Menu Toggle -->
                    <div class="header-actions flex items-center space-x-4">
                        
                        <!-- CTA Button (Desktop) -->
                        <div class="cta-button hidden lg:block">
                            <?php
                            rachel_advocacy_get_component('button', array(
                                'text' => __('Free Consultation', 'rachel-advocacy'),
                                'url' => get_permalink(get_page_by_path('contact')),
                                'variant' => 'primary',
                                'size' => 'sm',
                                'aria_label' => __('Schedule your free consultation', 'rachel-advocacy')
                            ));
                            ?>
                        </div>

                        <!-- Mobile Menu Toggle -->
                        <button id="mobile-menu-toggle" 
                                class="mobile-menu-toggle lg:hidden p-2 text-neutral-700 hover:text-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-md"
                                aria-controls="mobile-menu" 
                                aria-expanded="false"
                                aria-label="<?php esc_attr_e('Toggle mobile menu', 'rachel-advocacy'); ?>">
                            <span class="sr-only"><?php esc_html_e('Open main menu', 'rachel-advocacy'); ?></span>
                            <!-- Hamburger Icon -->
                            <div class="hamburger-icon w-6 h-6 flex flex-col justify-center items-center">
                                <span class="hamburger-line bg-current h-0.5 w-6 transition-all duration-300"></span>
                                <span class="hamburger-line bg-current h-0.5 w-6 mt-1 transition-all duration-300"></span>
                                <span class="hamburger-line bg-current h-0.5 w-6 mt-1 transition-all duration-300"></span>
                            </div>
                        </button>

                    </div>

                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu lg:hidden hidden bg-white border-t border-neutral-200" role="navigation" aria-label="<?php esc_attr_e('Mobile Menu', 'rachel-advocacy'); ?>">
            <div class="container py-6">
                
                <!-- Mobile Navigation Menu -->
                <nav class="mobile-nav">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'mobile-primary-menu',
                            'menu_class'     => 'mobile-nav-menu space-y-4',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'walker'         => new Rachel_Advocacy_Walker_Nav_Menu('mobile')
                        ));
                    } else {
                        // Fallback mobile menu
                        echo '<ul class="mobile-nav-menu space-y-4">';
                        echo '<li><a href="' . home_url('/') . '" class="mobile-nav-link">Home</a></li>';
                        echo '<li><a href="' . home_url('/about/') . '" class="mobile-nav-link">About</a></li>';
                        echo '<li><a href="' . home_url('/services/') . '" class="mobile-nav-link">Services</a></li>';
                        echo '<li><a href="' . home_url('/faq/') . '" class="mobile-nav-link">FAQ</a></li>';
                        echo '<li><a href="' . home_url('/contact/') . '" class="mobile-nav-link">Contact</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </nav>

                <!-- Mobile Contact Info -->
                <div class="mobile-contact mt-8 pt-6 border-t border-neutral-200">
                    <div class="grid grid-cols-1 gap-4">
                        
                        <div class="contact-item">
                            <a href="tel:+15551234567" class="flex items-center text-neutral-700 hover:text-primary-600 transition-colors">
                                <span class="text-xl mr-3">📞</span>
                                <div>
                                    <div class="font-medium"><?php esc_html_e('Call or Text', 'rachel-advocacy'); ?></div>
                                    <div class="text-sm text-neutral-500">(555) 123-4567</div>
                                </div>
                            </a>
                        </div>

                        <div class="contact-item">
                            <a href="mailto:rachel@racheladvocacy.com" class="flex items-center text-neutral-700 hover:text-secondary-600 transition-colors">
                                <span class="text-xl mr-3">📧</span>
                                <div>
                                    <div class="font-medium"><?php esc_html_e('Email', 'rachel-advocacy'); ?></div>
                                    <div class="text-sm text-neutral-500">rachel@racheladvocacy.com</div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Mobile CTA -->
                <div class="mobile-cta mt-6">
                    <?php
                    rachel_advocacy_get_component('button', array(
                        'text' => __('Free Consultation', 'rachel-advocacy'),
                        'url' => get_permalink(get_page_by_path('contact')),
                        'variant' => 'primary',
                        'size' => 'lg',
                        'attributes' => array('class' => 'w-full justify-center'),
                        'aria_label' => __('Schedule your free consultation', 'rachel-advocacy')
                    ));
                    ?>
                </div>

            </div>
        </div>

    </header>

    <!-- Page Content -->
</body>
</html> 