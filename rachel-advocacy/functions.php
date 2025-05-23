<?php
/**
 * Rachel Lee Patient Advocacy Theme Functions
 * 
 * @package Rachel_Advocacy
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function rachel_advocacy_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'rachel-advocacy'),
        'footer' => esc_html__('Footer Menu', 'rachel-advocacy'),
    ));

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'rachel_advocacy_setup');

/**
 * Enqueue scripts and styles
 */
function rachel_advocacy_scripts() {
    // Main stylesheet (compiled Tailwind CSS)
    wp_enqueue_style(
        'rachel-advocacy-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );

    // Main JavaScript file
    wp_enqueue_script(
        'rachel-advocacy-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    // Add ARIA support for navigation
    if (has_nav_menu('primary')) {
        wp_enqueue_script(
            'rachel-advocacy-navigation',
            get_template_directory_uri() . '/assets/js/navigation.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
    }

    // Localize script for AJAX and accessibility
    wp_localize_script('rachel-advocacy-script', 'rachelAdvocacy', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('rachel_advocacy_nonce'),
        'skipLinks' => array(
            'skipToContent' => esc_html__('Skip to content', 'rachel-advocacy'),
            'skipToNavigation' => esc_html__('Skip to navigation', 'rachel-advocacy'),
        )
    ));

    // Conditional script loading for contact forms
    if (is_page_template('page-contact.php')) {
        wp_enqueue_script(
            'rachel-advocacy-forms',
            get_template_directory_uri() . '/assets/js/forms.js',
            array('rachel-advocacy-script'),
            wp_get_theme()->get('Version'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'rachel_advocacy_scripts');

/**
 * Add ACF options pages
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'Theme Settings',
        'menu_title' => 'Theme Options',
        'menu_slug' => 'theme-options',
        'capability' => 'edit_posts',
        'icon_url' => 'dashicons-admin-customizer',
        'position' => 30,
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Contact Information',
        'menu_title' => 'Contact Info',
        'parent_slug' => 'theme-options',
    ));

    acf_add_options_sub_page(array(
        'page_title' => 'Social Media',
        'menu_title' => 'Social Media',
        'parent_slug' => 'theme-options',
    ));
}

/**
 * Security enhancements
 */
// Remove WordPress version from head
remove_action('wp_head', 'wp_generator');

// Hide login errors
function rachel_advocacy_login_errors() {
    return 'Something is wrong!';
}
add_filter('login_errors', 'rachel_advocacy_login_errors');

/**
 * Accessibility improvements
 */
function rachel_advocacy_add_skip_links() {
    echo '<a class="screen-reader-text skip-link" href="#main">' . esc_html__('Skip to content', 'rachel-advocacy') . '</a>';
    echo '<a class="screen-reader-text skip-link" href="#primary-navigation">' . esc_html__('Skip to navigation', 'rachel-advocacy') . '</a>';
}
add_action('wp_body_open', 'rachel_advocacy_add_skip_links');

/**
 * Custom image sizes for responsive design
 */
function rachel_advocacy_custom_image_sizes() {
    add_image_size('hero-large', 1920, 800, true);
    add_image_size('hero-medium', 1200, 600, true);
    add_image_size('card-thumbnail', 400, 300, true);
    add_image_size('testimonial-avatar', 100, 100, true);
}
add_action('after_setup_theme', 'rachel_advocacy_custom_image_sizes');

/**
 * Widget areas
 */
function rachel_advocacy_widgets_init() {
    register_sidebar(array(
        'name' => esc_html__('Footer Widget Area', 'rachel-advocacy'),
        'id' => 'footer-widgets',
        'description' => esc_html__('Add widgets here to appear in the footer.', 'rachel-advocacy'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'rachel_advocacy_widgets_init');

/**
 * Custom excerpt length and more text
 */
function rachel_advocacy_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'rachel_advocacy_excerpt_length');

function rachel_advocacy_excerpt_more($more) {
    return '&hellip; <a href="' . get_permalink() . '" class="read-more" aria-label="' . sprintf(esc_attr__('Continue reading %s', 'rachel-advocacy'), get_the_title()) . '">' . esc_html__('Read more', 'rachel-advocacy') . '</a>';
}
add_filter('excerpt_more', 'rachel_advocacy_excerpt_more');

/**
 * Add body classes for accessibility and styling
 */
function rachel_advocacy_body_classes($classes) {
    // Add class for JavaScript detection
    $classes[] = 'no-js';
    
    // Add page template class
    if (is_page_template()) {
        $template_name = get_page_template_slug();
        $template_name = str_replace('.php', '', $template_name);
        $classes[] = 'template-' . str_replace('_', '-', $template_name);
    }
    
    return $classes;
}
add_filter('body_class', 'rachel_advocacy_body_classes');

/**
 * Include component files
 */
function rachel_advocacy_get_component($component_name, $args = array()) {
    $component_path = get_template_directory() . '/components/' . $component_name . '.php';
    
    if (file_exists($component_path)) {
        // Extract variables for use in component
        if (!empty($args)) {
            extract($args, EXTR_SKIP);
        }
        include $component_path;
    } else {
        // Log error for debugging
        error_log('Component not found: ' . $component_path);
    }
}

/**
 * Flexible content helper function
 */
function rachel_advocacy_flexible_content($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (have_rows('flexible_content', $post_id)) {
        while (have_rows('flexible_content', $post_id)) {
            the_row();
            $layout = get_row_layout();
            
            $template_path = get_template_directory() . '/template-parts/flexible-content/' . $layout . '.php';
            
            if (file_exists($template_path)) {
                include $template_path;
            } else {
                // Fallback or error handling
                echo '<!-- Flexible content template not found: ' . esc_html($layout) . ' -->';
            }
        }
    }
}

/**
 * Clean up WordPress head
 */
function rachel_advocacy_cleanup_head() {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
}
add_action('init', 'rachel_advocacy_cleanup_head');

/**
 * Disable XML-RPC for security
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Add theme support for Gutenberg
 */
function rachel_advocacy_gutenberg_support() {
    // Add support for wide and full alignments
    add_theme_support('align-wide');
    
    // Add support for custom color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name' => esc_html__('Primary Blue', 'rachel-advocacy'),
            'slug' => 'primary-blue',
            'color' => '#2563eb',
        ),
        array(
            'name' => esc_html__('Secondary Green', 'rachel-advocacy'),
            'slug' => 'secondary-green',
            'color' => '#059669',
        ),
        array(
            'name' => esc_html__('Neutral Gray', 'rachel-advocacy'),
            'slug' => 'neutral-gray',
            'color' => '#6b7280',
        ),
    ));
}
add_action('after_setup_theme', 'rachel_advocacy_gutenberg_support'); 