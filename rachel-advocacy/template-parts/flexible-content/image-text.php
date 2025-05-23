<?php
/**
 * Image Text Split Block Template
 * 
 * Flexible content template for image and text side-by-side sections
 * 
 * ACF Fields Expected:
 * - image_text_title (Text)
 * - image_text_content (Wysiwyg)
 * - image_text_image (Image)
 * - image_text_layout (Select: image-left, image-right)
 * - image_text_cta_text (Text)
 * - image_text_cta_url (URL)
 * - image_text_background (Select: white, neutral, primary)
 */

// Get ACF fields
$title = get_sub_field('image_text_title');
$content = get_sub_field('image_text_content');
$image = get_sub_field('image_text_image');
$layout = get_sub_field('image_text_layout') ?: 'image-left';
$cta_text = get_sub_field('image_text_cta_text');
$cta_url = get_sub_field('image_text_cta_url');
$background = get_sub_field('image_text_background') ?: 'white';

if (!$title && !$content && !$image) {
    return; // Don't render if no content
}

// Background classes
$bg_classes = array(
    'white' => 'bg-white',
    'neutral' => 'bg-neutral-50',
    'primary' => 'bg-primary-50'
);

$background_class = $bg_classes[$background] ?? $bg_classes['white'];

// Layout classes
$is_image_right = $layout === 'image-right';
?>

<section class="image-text-section <?php echo esc_attr($background_class); ?> section">
    <div class="container">
        
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            
            <!-- Text Content -->
            <div class="image-text-content <?php echo $is_image_right ? 'lg:order-2' : 'lg:order-1'; ?>">
                
                <?php if ($title): ?>
                    <h2 class="image-text-title text-3xl md:text-4xl font-bold text-neutral-900 mb-6">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($content): ?>
                    <div class="image-text-body prose prose-lg max-w-none text-neutral-700 mb-6">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                <?php endif; ?>

                <?php if ($cta_text && $cta_url): ?>
                    <div class="image-text-cta">
                        <?php
                        rachel_advocacy_get_component('button', array(
                            'text' => $cta_text,
                            'url' => $cta_url,
                            'variant' => 'primary',
                            'size' => 'md'
                        ));
                        ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Image -->
            <?php if ($image): ?>
                <div class="image-text-image <?php echo $is_image_right ? 'lg:order-1' : 'lg:order-2'; ?>">
                    <figure class="relative">
                        <img src="<?php echo esc_url($image['sizes']['large'] ?? $image['url']); ?>"
                             alt="<?php echo esc_attr($image['alt'] ?: $title); ?>"
                             class="w-full h-auto rounded-lg shadow-lg"
                             loading="lazy"
                             itemscope itemtype="https://schema.org/ImageObject">
                        
                        <?php if (!empty($image['caption'])): ?>
                            <figcaption class="mt-3 text-sm text-neutral-600 italic">
                                <?php echo esc_html($image['caption']); ?>
                            </figcaption>
                        <?php endif; ?>
                    </figure>
                </div>
            <?php endif; ?>

        </div>

    </div>
</section> 