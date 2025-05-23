<?php
/**
 * The main template file for Rachel Lee Patient Advocacy
 * 
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * 
 * @package Rachel_Advocacy
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <header class="page-header section">
                <?php if (is_home() && !is_front_page()) : ?>
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                <?php elseif (is_archive()) : ?>
                    <?php
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="archive-description text-lg text-neutral-600 mt-4">', '</div>');
                    ?>
                <?php elseif (is_search()) : ?>
                    <h1 class="page-title">
                        <?php
                        printf(
                            esc_html__('Search Results for: %s', 'rachel-advocacy'),
                            '<span class="text-primary-600">' . get_search_query() . '</span>'
                        );
                        ?>
                    </h1>
                <?php else : ?>
                    <h1 class="page-title"><?php esc_html_e('Latest Posts', 'rachel-advocacy'); ?></h1>
                <?php endif; ?>
            </header>

            <div class="posts-container space-y-8 section">
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?> itemscope itemtype="https://schema.org/Article">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Read more about %s', 'rachel-advocacy'), get_the_title())); ?>">
                                    <?php the_post_thumbnail('large', array(
                                        'class' => 'w-full h-64 object-cover',
                                        'loading' => 'lazy',
                                        'itemprop' => 'image'
                                    )); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <header class="entry-header mb-4">
                                <h2 class="entry-title text-2xl font-semibold mb-2" itemprop="headline">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark" class="text-neutral-900 hover:text-primary-600 no-underline">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <div class="entry-meta text-sm text-neutral-600 flex flex-wrap gap-4">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                        <span class="sr-only"><?php esc_html_e('Published on', 'rachel-advocacy'); ?></span>
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                    
                                    <span class="author vcard" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                        <span class="sr-only"><?php esc_html_e('By', 'rachel-advocacy'); ?></span>
                                        <span itemprop="name"><?php the_author(); ?></span>
                                    </span>

                                    <?php if (has_category()) : ?>
                                        <span class="categories">
                                            <span class="sr-only"><?php esc_html_e('Categories:', 'rachel-advocacy'); ?></span>
                                            <?php the_category(', '); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </header>

                            <div class="entry-content prose max-w-none" itemprop="articleBody">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer mt-4">
                                <a href="<?php the_permalink(); ?>" 
                                   class="btn btn-outline btn-small"
                                   aria-label="<?php echo esc_attr(sprintf(__('Read the full article: %s', 'rachel-advocacy'), get_the_title())); ?>">
                                    <?php esc_html_e('Read More', 'rachel-advocacy'); ?>
                                    <span class="sr-only"><?php printf(esc_html__('about %s', 'rachel-advocacy'), get_the_title()); ?></span>
                                </a>

                                <?php if (has_tag()) : ?>
                                    <div class="tags mt-3">
                                        <span class="sr-only"><?php esc_html_e('Tags:', 'rachel-advocacy'); ?></span>
                                        <?php the_tags('<div class="flex flex-wrap gap-2">', '', '</div>'); ?>
                                    </div>
                                <?php endif; ?>
                            </footer>
                        </div>

                    </article>

                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            $pagination = paginate_links(array(
                'type' => 'array',
                'prev_text' => __('&laquo; Previous', 'rachel-advocacy'),
                'next_text' => __('Next &raquo;', 'rachel-advocacy'),
                'before_page_number' => '<span class="sr-only">' . __('Page', 'rachel-advocacy') . ' </span>',
            ));

            if ($pagination) :
            ?>
                <nav class="pagination-nav section" role="navigation" aria-label="<?php esc_attr_e('Posts pagination', 'rachel-advocacy'); ?>">
                    <ul class="pagination flex flex-wrap justify-center gap-2">
                        <?php foreach ($pagination as $page) : ?>
                            <li class="page-item">
                                <?php echo str_replace('page-numbers', 'page-numbers btn btn-outline btn-small', $page); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php else : ?>

            <section class="no-results not-found section">
                <header class="page-header text-center">
                    <h1 class="page-title"><?php esc_html_e('Nothing here', 'rachel-advocacy'); ?></h1>
                </header>

                <div class="page-content prose mx-auto text-center">
                    <?php if (is_home() && current_user_can('publish_posts')) : ?>
                        <p><?php
                            printf(
                                wp_kses(
                                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'rachel-advocacy'),
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                        ),
                                    )
                                ),
                                esc_url(admin_url('post-new.php'))
                            );
                        ?></p>
                    <?php elseif (is_search()) : ?>
                        <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'rachel-advocacy'); ?></p>
                        <?php get_search_form(); ?>
                    <?php else : ?>
                        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'rachel-advocacy'); ?></p>
                        <?php get_search_form(); ?>
                    <?php endif; ?>
                </div>
            </section>

        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?> 