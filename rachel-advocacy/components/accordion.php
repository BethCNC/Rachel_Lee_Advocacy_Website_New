<?php
/**
 * Accordion Component
 * 
 * Reusable accordion component with accessibility features
 * 
 * @param array $args {
 *     @type string $title        Accordion title
 *     @type string $content      Accordion content
 *     @type string $id           Unique ID for the accordion
 *     @type bool   $expanded     Whether accordion starts expanded
 *     @type string $variant      Accordion variant (default, bordered)
 *     @type array  $attributes   Additional HTML attributes
 * }
 */

// Set defaults
$defaults = array(
    'title' => '',
    'content' => '',
    'id' => 'accordion-' . uniqid(),
    'expanded' => false,
    'variant' => 'default',
    'attributes' => array()
);

$args = wp_parse_args($args, $defaults);

// Build CSS classes based on variant
$base_classes = 'accordion-item border-b border-neutral-200';

$variant_classes = array(
    'default' => '',
    'bordered' => 'border border-neutral-300 rounded-lg mb-4 overflow-hidden'
);

$accordion_classes = $base_classes . ' ' . ($variant_classes[$args['variant']] ?? '');

// Generate unique IDs
$button_id = $args['id'] . '-button';
$panel_id = $args['id'] . '-panel';

// Build attributes
$attributes = '';
foreach ($args['attributes'] as $key => $value) {
    $attributes .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
}
?>

<div class="<?php echo esc_attr($accordion_classes); ?>" <?php echo $attributes; ?>>
    
    <h3 class="accordion-header">
        <button type="button"
                id="<?php echo esc_attr($button_id); ?>"
                class="accordion-toggle w-full text-left px-4 py-4 bg-white hover:bg-neutral-50 focus:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset transition-colors duration-200 flex items-center justify-between"
                aria-expanded="<?php echo $args['expanded'] ? 'true' : 'false'; ?>"
                aria-controls="<?php echo esc_attr($panel_id); ?>"
                data-accordion-toggle="<?php echo esc_attr($panel_id); ?>">
            
            <span class="accordion-title text-lg font-medium text-neutral-900">
                <?php echo esc_html($args['title']); ?>
            </span>
            
            <span class="accordion-icon ml-4 flex-shrink-0 transition-transform duration-200 <?php echo $args['expanded'] ? 'rotate-180' : ''; ?>" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </span>
            
        </button>
    </h3>

    <div id="<?php echo esc_attr($panel_id); ?>"
         class="accordion-panel <?php echo $args['expanded'] ? '' : 'hidden'; ?>"
         aria-hidden="<?php echo $args['expanded'] ? 'false' : 'true'; ?>"
         aria-labelledby="<?php echo esc_attr($button_id); ?>"
         role="region">
        
        <div class="accordion-content px-4 py-4 bg-neutral-50 prose max-w-none">
            <?php echo wp_kses_post($args['content']); ?>
        </div>
        
    </div>

</div> 