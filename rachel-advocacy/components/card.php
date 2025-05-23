<?php
/**
 * Card Component
 * 
 * Reusable card component with accessibility features
 * 
 * @param array $args {
 *     @type string $title        Card title
 *     @type string $content      Card content/description
 *     @type string $image        Image URL
 *     @type string $image_alt    Image alt text
 *     @type string $link_url     Card link URL
 *     @type string $link_text    Link text
 *     @type string $variant      Card variant (default, featured, testimonial)
 *     @type string $tag          HTML tag for title (h2, h3, h4)
 *     @type array  $attributes   Additional HTML attributes
 * }
 */

// Set defaults
$defaults = array(
    'title' => '',
    'content' => '',
    'image' => '',
    'image_alt' => '',
    'link_url' => '',
    'link_text' => 'Learn More',
    'variant' => 'default',
    'tag' => 'h3',
    'attributes' => array()
);

$args = wp_parse_args($args, $defaults);

// Build CSS classes based on variant
$base_classes = 'card transition-all duration-200 hover:shadow-lg';

$variant_classes = array(
    'default' => 'bg-white border-neutral-200',
    'featured' => 'bg-gradient-to-br from-primary-50 to-secondary-50 border-primary-200 ring-1 ring-primary-200',
    'testimonial' => 'bg-neutral-50 border-neutral-300'
);

$card_classes = $base_classes . ' ' . ($variant_classes[$args['variant']] ?? $variant_classes['default']);

// Build attributes
$attributes = '';
foreach ($args['attributes'] as $key => $value) {
    $attributes .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
}

$card_id = !empty($args['attributes']['id']) ? $args['attributes']['id'] : 'card-' . uniqid();
?>

<article class="<?php echo esc_attr($card_classes); ?>" <?php echo $attributes; ?> itemscope itemtype="https://schema.org/Article">
    
    <?php if (!empty($args['image'])): ?>
        <div class="card-image">
            <img src="<?php echo esc_url($args['image']); ?>" 
                 alt="<?php echo esc_attr($args['image_alt']); ?>"
                 class="w-full h-48 object-cover rounded-t-lg"
                 loading="lazy"
                 itemprop="image">
        </div>
    <?php endif; ?>

    <div class="card-body">
        <?php if (!empty($args['title'])): ?>
            <header class="card-header mb-4">
                <<?php echo esc_attr($args['tag']); ?> class="card-title text-xl font-semibold text-neutral-900 mb-2" itemprop="headline">
                    <?php if (!empty($args['link_url'])): ?>
                        <a href="<?php echo esc_url($args['link_url']); ?>" 
                           class="text-neutral-900 hover:text-primary-600 no-underline"
                           aria-describedby="<?php echo esc_attr($card_id . '-content'); ?>">
                            <?php echo esc_html($args['title']); ?>
                        </a>
                    <?php else: ?>
                        <?php echo esc_html($args['title']); ?>
                    <?php endif; ?>
                </<?php echo esc_attr($args['tag']); ?>>
            </header>
        <?php endif; ?>

        <?php if (!empty($args['content'])): ?>
            <div class="card-content text-neutral-600 mb-4" 
                 id="<?php echo esc_attr($card_id . '-content'); ?>"
                 itemprop="description">
                <?php echo wp_kses_post($args['content']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($args['link_url'])): ?>
            <footer class="card-footer">
                <?php
                rachel_advocacy_get_component('button', array(
                    'text' => $args['link_text'],
                    'url' => $args['link_url'],
                    'variant' => 'outline',
                    'size' => 'sm',
                    'aria_label' => sprintf(__('Learn more about %s', 'rachel-advocacy'), $args['title'])
                ));
                ?>
            </footer>
        <?php endif; ?>
    </div>

</article> 