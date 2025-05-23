<?php
/**
 * Button Component
 * 
 * Reusable button component with accessibility features
 * 
 * @param array $args {
 *     @type string $text         Button text
 *     @type string $url          Button URL  
 *     @type string $target       Link target (_blank, _self)
 *     @type string $variant      Button variant (primary, secondary, outline, ghost)
 *     @type string $size         Button size (sm, md, lg, xl)
 *     @type string $icon         Icon class (optional)
 *     @type string $aria_label   ARIA label for accessibility
 *     @type array  $attributes   Additional HTML attributes
 * }
 */

// Set defaults
$defaults = array(
    'text' => 'Button',
    'url' => '#',
    'target' => '_self',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => '',
    'aria_label' => '',
    'attributes' => array()
);

$args = wp_parse_args($args, $defaults);

// Build CSS classes based on variant and size
$base_classes = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variant_classes = array(
    'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 active:bg-primary-800',
    'secondary' => 'bg-secondary-600 text-white hover:bg-secondary-700 focus:ring-secondary-500 active:bg-secondary-800',
    'outline' => 'border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white focus:ring-primary-500',
    'ghost' => 'text-primary-600 hover:bg-primary-50 focus:ring-primary-500'
);

$size_classes = array(
    'sm' => 'px-3 py-2 text-sm rounded-md',
    'md' => 'px-4 py-2 text-base rounded-lg',
    'lg' => 'px-6 py-3 text-lg rounded-lg',
    'xl' => 'px-8 py-4 text-xl rounded-xl'
);

$button_classes = $base_classes . ' ' . 
                 ($variant_classes[$args['variant']] ?? $variant_classes['primary']) . ' ' .
                 ($size_classes[$args['size']] ?? $size_classes['md']);

// Icon spacing
$icon_classes = '';
if (!empty($args['icon'])) {
    $icon_classes = $args['text'] ? 'mr-2' : '';
}

// Build attributes
$attributes = '';
foreach ($args['attributes'] as $key => $value) {
    $attributes .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
}

// ARIA label
$aria_label = !empty($args['aria_label']) ? sprintf(' aria-label="%s"', esc_attr($args['aria_label'])) : '';

// External link attributes
$external_attrs = '';
if ($args['target'] === '_blank') {
    $external_attrs = ' rel="noopener noreferrer"';
    if (empty($args['aria_label'])) {
        $aria_label = sprintf(' aria-label="%s (opens in new tab)"', esc_attr($args['text']));
    }
}
?>

<a href="<?php echo esc_url($args['url']); ?>" 
   target="<?php echo esc_attr($args['target']); ?>"
   class="<?php echo esc_attr($button_classes); ?>"
   <?php echo $aria_label; ?>
   <?php echo $external_attrs; ?>
   <?php echo $attributes; ?>>
    
    <?php if (!empty($args['icon'])): ?>
        <span class="<?php echo esc_attr($args['icon'] . ' ' . $icon_classes); ?>" aria-hidden="true"></span>
    <?php endif; ?>
    
    <?php if (!empty($args['text'])): ?>
        <span><?php echo esc_html($args['text']); ?></span>
    <?php endif; ?>
</a> 