<?php
/**
 * Hero Block Template
 * 
 * Flexible content template for hero sections
 * 
 * ACF Fields Expected:
 * - hero_title (Text)
 * - hero_subtitle (Textarea)
 * - hero_image (Image)
 * - hero_cta_text (Text)
 * - hero_cta_url (URL)
 * - hero_layout (Select: full, split)
 * - hero_background_color (Select)
 */

// Get ACF fields
$title = get_sub_field('hero_title');
$subtitle = get_sub_field('hero_subtitle');
$image = get_sub_field('hero_image');
$cta_text = get_sub_field('hero_cta_text');
$cta_url = get_sub_field('hero_cta_url');
$layout = get_sub_field('hero_layout') ?: 'full';
$bg_color = get_sub_field('hero_background_color') ?: 'primary';

// Background color classes
$bg_classes = array(
    'primary' => 'bg-gradient-to-br from-primary-600 to-primary-800 text-white',
    'secondary' => 'bg-gradient-to-br from-secondary-500 to-secondary-700 text-white',
    'neutral' => 'bg-neutral-50 text-neutral-900',
    'white' => 'bg-white text-neutral-900'
);

$background_class = $bg_classes[$bg_color] ?? $bg_classes['primary'];

// Layout classes
$layout_classes = array(
    'full' => 'text-center',
    'split' => 'lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center'
);

$layout_class = $layout_classes[$layout] ?? $layout_classes['full'];

if (!$title && !$subtitle && !$image) {
    return; // Don't render if no content
}
?>

<section class="hero-section <?php echo esc_attr($background_class); ?> section-large" role="banner">
    <div class="container">
        <div class="<?php echo esc_attr($layout_class); ?>">
            
            <div class="hero-content <?php echo $layout === 'split' ? 'lg:order-1' : ''; ?>">
                
                <?php if ($title): ?>
                    <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        <?php echo esc_html($title); ?>
                    </h1>
                <?php endif; ?>

                <?php if ($subtitle): ?>
                    <div class="hero-subtitle text-lg md:text-xl lg:text-2xl mb-8 opacity-90 max-w-3xl <?php echo $layout === 'full' ? 'mx-auto' : ''; ?>">
                        <?php echo wp_kses_post(wpautop($subtitle)); ?>
                    </div>
                <?php endif; ?>

                <?php if ($cta_text && $cta_url): ?>
                    <div class="hero-cta">
                        <?php
                        rachel_advocacy_get_component('button', array(
                            'text' => $cta_text,
                            'url' => $cta_url,
                            'variant' => $bg_color === 'white' || $bg_color === 'neutral' ? 'primary' : 'outline',
                            'size' => 'lg',
                            'attributes' => array(
                                'class' => 'hero-cta-button'
                            )
                        ));
                        ?>
                    </div>
                <?php endif; ?>

            </div>

            <?php if ($image && $layout === 'split'): ?>
                <div class="hero-image lg:order-2">
                    <img src="<?php echo esc_url($image['sizes']['large'] ?? $image['url']); ?>"
                         alt="<?php echo esc_attr($image['alt'] ?: $title); ?>"
                         class="w-full h-auto rounded-lg shadow-2xl"
                         loading="eager"
                         itemscope itemtype="https://schema.org/ImageObject">
                </div>
            <?php elseif ($image && $layout === 'full'): ?>
                <div class="hero-image mt-12">
                    <img src="<?php echo esc_url($image['sizes']['hero-large'] ?? $image['url']); ?>"
                         alt="<?php echo esc_attr($image['alt'] ?: $title); ?>"
                         class="w-full max-w-4xl mx-auto h-auto rounded-lg shadow-2xl"
                         loading="eager"
                         itemscope itemtype="https://schema.org/ImageObject">
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if ($bg_color === 'primary' || $bg_color === 'secondary'): ?>
        <!-- Decorative wave for visual interest -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden" style="height: 60px;">
            <svg class="absolute bottom-0 w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" 
                      fill="<?php echo $bg_color === 'primary' ? '#ffffff' : '#f9fafb'; ?>" opacity="0.1"></path>
            </svg>
        </div>
    <?php endif; ?>

</section> 