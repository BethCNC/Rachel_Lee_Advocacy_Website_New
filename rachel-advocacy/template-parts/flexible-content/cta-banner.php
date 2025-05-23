<?php
/**
 * CTA Banner Block Template
 * 
 * Flexible content template for call-to-action banner sections
 * 
 * ACF Fields Expected:
 * - cta_title (Text)
 * - cta_content (Textarea)
 * - cta_primary_text (Text)
 * - cta_primary_url (URL)
 * - cta_secondary_text (Text)
 * - cta_secondary_url (URL)
 * - cta_background_style (Select: primary, secondary, gradient, image)
 * - cta_background_image (Image)
 * - cta_layout (Select: centered, split)
 */

// Get ACF fields
$title = get_sub_field('cta_title');
$content = get_sub_field('cta_content');
$primary_text = get_sub_field('cta_primary_text');
$primary_url = get_sub_field('cta_primary_url');
$secondary_text = get_sub_field('cta_secondary_text');
$secondary_url = get_sub_field('cta_secondary_url');
$bg_style = get_sub_field('cta_background_style') ?: 'primary';
$bg_image = get_sub_field('cta_background_image');
$layout = get_sub_field('cta_layout') ?: 'centered';

if (!$title && !$content) {
    return; // Don't render if no content
}

// Background style classes
$bg_classes = array(
    'primary' => 'bg-gradient-to-r from-primary-600 to-primary-700 text-white',
    'secondary' => 'bg-gradient-to-r from-secondary-500 to-secondary-600 text-white',
    'gradient' => 'bg-gradient-to-br from-primary-600 via-secondary-500 to-primary-800 text-white',
    'image' => 'bg-neutral-900 text-white relative overflow-hidden'
);

$background_class = $bg_classes[$bg_style] ?? $bg_classes['primary'];

// Layout classes
$layout_classes = array(
    'centered' => 'text-center',
    'split' => 'lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center lg:text-left'
);

$layout_class = $layout_classes[$layout] ?? $layout_classes['centered'];
?>

<section class="cta-banner <?php echo esc_attr($background_class); ?> section-large relative">
    
    <?php if ($bg_style === 'image' && $bg_image): ?>
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="<?php echo esc_url($bg_image['sizes']['hero-large'] ?? $bg_image['url']); ?>"
                 alt="<?php echo esc_attr($bg_image['alt'] ?: ''); ?>"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
        </div>
    <?php endif; ?>

    <div class="container relative z-10">
        <div class="<?php echo esc_attr($layout_class); ?>">
            
            <div class="cta-content <?php echo $layout === 'split' ? 'lg:pr-8' : 'max-w-4xl mx-auto'; ?>">
                
                <?php if ($title): ?>
                    <h2 class="cta-title text-3xl md:text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($content): ?>
                    <div class="cta-body text-lg md:text-xl mb-8 opacity-90 leading-relaxed">
                        <?php echo wp_kses_post(wpautop($content)); ?>
                    </div>
                <?php endif; ?>

            </div>

            <?php if ($primary_text || $secondary_text): ?>
                <div class="cta-actions <?php echo $layout === 'split' ? 'lg:pl-8 lg:flex lg:flex-col lg:justify-center' : ''; ?>">
                    
                    <div class="flex flex-col sm:flex-row gap-4 <?php echo $layout === 'centered' ? 'justify-center' : ''; ?>">
                        
                        <?php if ($primary_text && $primary_url): ?>
                            <?php
                            rachel_advocacy_get_component('button', array(
                                'text' => $primary_text,
                                'url' => $primary_url,
                                'variant' => ($bg_style === 'primary' || $bg_style === 'secondary' || $bg_style === 'gradient' || $bg_style === 'image') ? 'outline' : 'primary',
                                'size' => 'lg',
                                'attributes' => array(
                                    'class' => 'cta-primary-button'
                                )
                            ));
                            ?>
                        <?php endif; ?>

                        <?php if ($secondary_text && $secondary_url): ?>
                            <?php
                            rachel_advocacy_get_component('button', array(
                                'text' => $secondary_text,
                                'url' => $secondary_url,
                                'variant' => 'ghost',
                                'size' => 'lg',
                                'attributes' => array(
                                    'class' => 'cta-secondary-button'
                                )
                            ));
                            ?>
                        <?php endif; ?>

                    </div>

                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if ($bg_style === 'primary' || $bg_style === 'secondary'): ?>
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-64 h-64 opacity-10">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,79.6,-45.8C87.4,-32.6,90,-16.3,89.2,-0.4C88.4,15.4,84.1,30.8,75.8,42.8C67.4,54.8,55,63.4,41.6,69.8C28.1,76.2,14.1,80.4,-0.5,81.2C-15.1,82,-30.2,79.4,-42.1,72.2C-54,65,-62.7,53.2,-68.6,40.1C-74.5,27,-77.6,12.6,-76.9,-2.2C-76.2,-17,-71.7,-34,-62.4,-46.2C-53.1,-58.4,-39,-65.8,-24.2,-72.8C-9.4,-79.8,6.1,-86.4,22.4,-87.2C38.7,-88,55.8,-83,44.7,-76.4Z" transform="translate(100 100)" />
            </svg>
        </div>
        
        <div class="absolute bottom-0 left-0 w-48 h-48 opacity-10">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="currentColor" d="M37.1,-63.7C48.8,-56.2,59.1,-46.6,64.8,-34.4C70.5,-22.2,71.6,-7.4,69.8,6.3C68,20,63.3,32.6,55.8,42.8C48.3,53,38,60.8,26.8,64.9C15.6,69,3.5,69.4,-8.8,67.6C-21.1,65.8,-33.6,61.8,-43.2,54.4C-52.8,47,-59.5,36.2,-62.8,24.2C-66.1,12.2,-66,0,-62.5,-10.4C-59,-20.8,-52.1,-29.4,-43.8,-36.9C-35.5,-44.4,-25.8,-50.8,-15.2,-56.7C-4.6,-62.6,6.9,-68,18.6,-68.8C30.3,-69.6,42.2,-65.8,37.1,-63.7Z" transform="translate(100 100)" />
            </svg>
        </div>
    <?php endif; ?>

</section> 