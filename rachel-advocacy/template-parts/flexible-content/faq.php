<?php
/**
 * FAQ Block Template
 * 
 * Flexible content template for FAQ sections using accordions
 * 
 * ACF Fields Expected:
 * - faq_title (Text)
 * - faq_subtitle (Textarea)
 * - faq_items (Repeater)
 *   - question (Text)
 *   - answer (Wysiwyg)
 */

// Get ACF fields
$title = get_sub_field('faq_title');
$subtitle = get_sub_field('faq_subtitle');
$faq_items = get_sub_field('faq_items');

if (!$faq_items || empty($faq_items)) {
    return; // Don't render if no FAQ items
}
?>

<section class="faq-section bg-white section" aria-labelledby="faq-heading">
    <div class="container">
        
        <?php if ($title || $subtitle): ?>
            <header class="faq-header text-center mb-12">
                
                <?php if ($title): ?>
                    <h2 id="faq-heading" class="faq-title text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($subtitle): ?>
                    <div class="faq-subtitle text-lg md:text-xl text-neutral-600 max-w-3xl mx-auto">
                        <?php echo wp_kses_post(wpautop($subtitle)); ?>
                    </div>
                <?php endif; ?>

            </header>
        <?php endif; ?>

        <div class="faq-container max-w-4xl mx-auto">
            
            <div class="faq-list" role="list">
                
                <?php foreach ($faq_items as $index => $item): ?>
                    <?php
                    $question = $item['question'] ?? '';
                    $answer = $item['answer'] ?? '';
                    
                    if (!$question || !$answer) {
                        continue;
                    }

                    $faq_id = 'faq-item-' . ($index + 1);
                    ?>
                    
                    <div class="faq-item" role="listitem">
                        <?php
                        rachel_advocacy_get_component('accordion', array(
                            'title' => $question,
                            'content' => $answer,
                            'id' => $faq_id,
                            'expanded' => $index === 0, // First item expanded by default
                            'variant' => 'default',
                            'attributes' => array(
                                'itemscope' => '',
                                'itemtype' => 'https://schema.org/Question'
                            )
                        ));
                        ?>
                    </div>

                <?php endforeach; ?>

            </div>

        </div>

        <!-- Optional contact CTA for unanswered questions -->
        <div class="faq-cta text-center mt-12 p-8 bg-neutral-50 rounded-lg">
            <h3 class="text-xl font-semibold text-neutral-900 mb-4">
                <?php esc_html_e("Don't see your question answered?", 'rachel-advocacy'); ?>
            </h3>
            <p class="text-neutral-600 mb-6">
                <?php esc_html_e("I'm here to help! Contact me directly for personalized support with your healthcare advocacy needs.", 'rachel-advocacy'); ?>
            </p>
            
            <?php
            rachel_advocacy_get_component('button', array(
                'text' => __('Contact Rachel', 'rachel-advocacy'),
                'url' => get_permalink(get_page_by_path('contact')),
                'variant' => 'primary',
                'aria_label' => __('Contact Rachel Lee for personalized advocacy support', 'rachel-advocacy')
            ));
            ?>
        </div>

    </div>
</section>

<?php
// Add structured data for FAQ
if ($faq_items):
?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        <?php foreach ($faq_items as $index => $item): ?>
            <?php if (!empty($item['question']) && !empty($item['answer'])): ?>
            {
                "@type": "Question",
                "name": "<?php echo esc_js($item['question']); ?>",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "<?php echo esc_js(wp_strip_all_tags($item['answer'])); ?>"
                }
            }<?php echo ($index < count($faq_items) - 1) ? ',' : ''; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    ]
}
</script>
<?php endif; ?> 