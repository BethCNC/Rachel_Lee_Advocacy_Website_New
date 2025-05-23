<?php
/**
 * Testimonial Block Template
 * 
 * Flexible content template for testimonial sections
 * 
 * ACF Fields Expected:
 * - testimonial_title (Text)
 * - testimonial_subtitle (Textarea)
 * - testimonial_layout (Select: grid, carousel, single)
 * - testimonial_items (Repeater)
 *   - testimonial_content (Textarea)
 *   - testimonial_name (Text)
 *   - testimonial_title (Text)
 *   - testimonial_image (Image)
 *   - testimonial_rating (Number)
 */

// Get ACF fields
$title = get_sub_field('testimonial_title');
$subtitle = get_sub_field('testimonial_subtitle');
$layout = get_sub_field('testimonial_layout') ?: 'grid';
$testimonials = get_sub_field('testimonial_items');

if (!$testimonials || empty($testimonials)) {
    return; // Don't render if no testimonials
}

// Layout classes
$layout_classes = array(
    'single' => 'max-w-4xl mx-auto',
    'grid' => 'grid md:grid-cols-2 lg:grid-cols-3 gap-8',
    'carousel' => 'testimonial-carousel'
);

$container_class = $layout_classes[$layout] ?? $layout_classes['grid'];
?>

<section class="testimonial-section bg-neutral-50 section" aria-labelledby="testimonial-heading">
    <div class="container">
        
        <?php if ($title || $subtitle): ?>
            <header class="testimonial-header text-center mb-12">
                
                <?php if ($title): ?>
                    <h2 id="testimonial-heading" class="testimonial-title text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($subtitle): ?>
                    <div class="testimonial-subtitle text-lg md:text-xl text-neutral-600 max-w-3xl mx-auto">
                        <?php echo wp_kses_post(wpautop($subtitle)); ?>
                    </div>
                <?php endif; ?>

            </header>
        <?php endif; ?>

        <div class="testimonial-container <?php echo esc_attr($container_class); ?>">
            
            <?php foreach ($testimonials as $index => $testimonial): ?>
                <?php
                $content = $testimonial['testimonial_content'] ?? '';
                $name = $testimonial['testimonial_name'] ?? '';
                $job_title = $testimonial['testimonial_title'] ?? '';
                $image = $testimonial['testimonial_image'] ?? '';
                $rating = $testimonial['testimonial_rating'] ?? 5;
                
                if (!$content) {
                    continue;
                }

                $testimonial_id = 'testimonial-' . ($index + 1);
                ?>
                
                <div class="testimonial-item mb-8 <?php echo $layout === 'grid' ? '' : 'last:mb-0'; ?>" 
                     itemscope itemtype="https://schema.org/Review">
                    
                    <?php
                    rachel_advocacy_get_component('card', array(
                        'content' => '"' . $content . '"',
                        'variant' => 'testimonial',
                        'tag' => 'div',
                        'attributes' => array(
                            'id' => $testimonial_id,
                            'itemprop' => 'reviewBody'
                        )
                    ));
                    ?>

                    <!-- Testimonial Footer -->
                    <footer class="testimonial-footer mt-6 flex items-center" itemscope itemtype="https://schema.org/Person">
                        
                        <?php if ($image): ?>
                            <div class="testimonial-avatar mr-4">
                                <img src="<?php echo esc_url($image['sizes']['thumbnail'] ?? $image['url']); ?>"
                                     alt="<?php echo esc_attr($image['alt'] ?: $name); ?>"
                                     class="w-12 h-12 rounded-full object-cover"
                                     itemprop="image">
                            </div>
                        <?php endif; ?>

                        <div class="testimonial-author">
                            <?php if ($name): ?>
                                <cite class="testimonial-name text-sm font-semibold text-neutral-900 not-italic" itemprop="name">
                                    <?php echo esc_html($name); ?>
                                </cite>
                            <?php endif; ?>
                            
                            <?php if ($job_title): ?>
                                <div class="testimonial-title text-sm text-neutral-600" itemprop="jobTitle">
                                    <?php echo esc_html($job_title); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($rating && $rating > 0): ?>
                            <div class="testimonial-rating ml-auto" 
                                 itemprop="reviewRating" 
                                 itemscope itemtype="https://schema.org/Rating">
                                <meta itemprop="ratingValue" content="<?php echo esc_attr($rating); ?>">
                                <meta itemprop="bestRating" content="5">
                                <div class="flex text-yellow-400" aria-label="<?php echo esc_attr(sprintf(__('%d out of 5 stars', 'rachel-advocacy'), $rating)); ?>">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="w-5 h-5 <?php echo $i <= $rating ? 'fill-current' : 'text-neutral-300'; ?>" 
                                             viewBox="0 0 20 20" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </footer>

                </div>

            <?php endforeach; ?>

        </div>

        <?php if ($layout === 'carousel' && count($testimonials) > 1): ?>
            <!-- Carousel Navigation -->
            <div class="testimonial-nav flex justify-center mt-8 space-x-4">
                <button type="button" 
                        class="testimonial-prev btn btn-outline"
                        aria-label="<?php esc_attr_e('Previous testimonial', 'rachel-advocacy'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button type="button" 
                        class="testimonial-next btn btn-outline"
                        aria-label="<?php esc_attr_e('Next testimonial', 'rachel-advocacy'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        <?php endif; ?>

    </div>
</section> 