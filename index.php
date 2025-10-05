<?php
/**
 * The main template file
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

get_header();
?>

<div class="home-layout-wrapper">
    <main class="home-main" role="main">
        <?php 
        // Get blog content settings
        $blog_source = get_theme_mod('home_blog_post_source', 'latest');
        $blog_count = get_theme_mod('home_blog_cards_count', 12);
        $blog_manual_posts = get_theme_mod('home_blog_manual_posts', '');
        $blog_date_start = get_theme_mod('home_blog_date_start', '');
        $blog_date_end = get_theme_mod('home_blog_date_end', '');
        $enable_pagination = get_theme_mod('home_blog_enable_pagination', true);
        $pagination_style = get_theme_mod('home_blog_pagination_style', 'modern');
        
        // Build custom query based on settings
        $custom_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => intval($blog_count),
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1
        );
        
        // Apply source-specific query modifications
        if ($blog_source === 'oldest') {
            $custom_args['orderby'] = 'date';
            $custom_args['order'] = 'ASC';
        } elseif ($blog_source === 'date_range' && $blog_date_start && $blog_date_end) {
            $custom_args['date_query'] = array(
                array(
                    'after' => $blog_date_start,
                    'before' => $blog_date_end,
                    'inclusive' => true,
                ),
            );
        } elseif ($blog_source === 'manual' && $blog_manual_posts) {
            $post_ids = array_filter(array_map('intval', explode(',', $blog_manual_posts)));
            if (!empty($post_ids)) {
                $custom_args['post__in'] = $post_ids;
                $custom_args['orderby'] = 'post__in';
                $custom_args['posts_per_page'] = -1; // Show all manual posts
            }
        }
        
        // Use custom query if not default
        if ($blog_source !== 'latest' || $blog_count != 12) {
            $custom_query = new WP_Query($custom_args);
            $posts_query = $custom_query;
        } else {
            $posts_query = $wp_query;
        }
        ?>
        
        <?php if ($posts_query->have_posts()) : ?>
            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header">
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>

            <div class="home-post-grid" aria-label="Posts">
                <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?> tabindex="0" aria-describedby="post-<?php the_ID(); ?>-title">
                        <a href="<?php the_permalink(); ?>" class="card-link-overlay" aria-label="<?php echo esc_attr(get_the_title()); ?>"></a>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-thumb">
                                <?php the_post_thumbnail('medium_large', ['alt' => the_title_attribute(['echo' => false])]); ?>
                            </div>
                        <?php endif; ?>
                        <div class="card-content">
                            <h2 class="card-title" id="post-<?php the_ID(); ?>-title"><?php the_title(); ?></h2>
                            <div class="card-meta">
                                <span class="card-date"><?php echo esc_html(get_the_date()); ?></span>
                                <span class="card-author"><?php echo esc_html(get_the_author()); ?></span>
                            </div>
                            <p class="card-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 28, '…')); ?></p>
                            <div class="card-actions">
                                <a href="<?php the_permalink(); ?>" class="read-more-btn" aria-label="<?php printf(esc_attr__('Read more about %s', 'onespace-theme2'), get_the_title()); ?>">
                                    <?php esc_html_e('Read More', 'onespace-theme2'); ?> →
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div><!-- .home-post-grid -->

            <?php if ($enable_pagination && $posts_query->max_num_pages > 1) : ?>
                <nav class="posts-pagination posts-pagination-<?php echo esc_attr($pagination_style); ?>" aria-label="Pagination">
                    <?php 
                    if ($pagination_style === 'modern') {
                        echo paginate_links(array(
                            'total' => $posts_query->max_num_pages,
                            'current' => max(1, get_query_var('paged')),
                            'format' => '?paged=%#%',
                            'prev_text' => '← ' . __('Previous', 'onespace-theme2'),
                            'next_text' => __('Next', 'onespace-theme2') . ' →',
                            'type' => 'list',
                            'end_size' => 2,
                            'mid_size' => 1,
                        ));
                    } elseif ($pagination_style === 'minimal') {
                        previous_posts_link(__('← Newer Posts', 'onespace-theme2'));
                        next_posts_link(__('Older Posts →', 'onespace-theme2'), $posts_query->max_num_pages);
                    } else {
                        the_posts_pagination(array(
                            'total' => $posts_query->max_num_pages,
                            'current' => max(1, get_query_var('paged')),
                        ));
                    }
                    ?>
                </nav>
            <?php endif; ?>
        <?php else : ?>
            <section class="no-results not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Nothing here', 'onespace-theme2'); ?></h1>
                </header>
                <div class="page-content">
                    <?php get_search_form(); ?>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <?php
    $sidebar_mode      = get_theme_mod('home_primary_sidebar_mode', 'custom');
    $posts_source      = get_theme_mod('home_recent_posts_source', 'latest');
    $posts_count       = get_theme_mod('home_recent_posts_count', 12);
    $posts_category    = get_theme_mod('home_recent_posts_category', '');
    $posts_manual_ids  = get_theme_mod('home_recent_posts_manual_ids', '');
    $show_comments     = (bool) get_theme_mod('home_recent_comments_enable', true);
    $comments_count    = get_theme_mod('home_recent_comments_count', 5);

    if ($sidebar_mode === 'custom') : ?>
        <aside class="home-sidebar" aria-label="Sidebar">
            <?php
            // Build recent posts query based on source
            $recent_args = array(
                'post_status' => 'publish',
                'numberposts' => intval($posts_count),
            );
            if ($posts_source === 'category' && $posts_category) {
                $recent_args['category_name'] = sanitize_title($posts_category);
            } elseif ($posts_source === 'manual' && $posts_manual_ids) {
                $ids = array_filter(array_map('intval', explode(',', $posts_manual_ids)));
                if (!empty($ids)) {
                    $recent_args['include'] = $ids;
                    $recent_args['orderby'] = 'post__in';
                }
            }
            $recent_posts = get_posts($recent_args);
            ?>
            <section class="sidebar-section recent-posts" aria-labelledby="recent-posts-heading">
                <h2 id="recent-posts-heading" class="sidebar-heading"><?php esc_html_e('Recent Posts', 'onespace-theme2'); ?></h2>
                <ul class="recent-posts-list">
                    <?php foreach ($recent_posts as $post_obj) :
                        $permalink = get_permalink($post_obj->ID);
                        $title = get_the_title($post_obj->ID);
                        ?>
                        <li class="recent-post-item">
                            <a href="<?php echo esc_url($permalink); ?>" class="recent-post-link">
                                <?php if (has_post_thumbnail($post_obj->ID)) : ?>
                                    <span class="recent-thumb"><?php echo get_the_post_thumbnail($post_obj->ID, 'thumbnail', ['alt' => esc_attr($title)]); ?></span>
                                <?php endif; ?>
                                <span class="recent-info">
                                    <span class="recent-title"><?php echo esc_html(wp_trim_words($title, 10, '…')); ?></span>
                                    <time class="recent-date" datetime="<?php echo esc_attr(get_the_date('c', $post_obj->ID)); ?>"><?php echo esc_html(get_the_date('', $post_obj->ID)); ?></time>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; wp_reset_postdata(); ?>
                </ul>
            </section>

            <?php if ($show_comments) :
                $recent_comments = get_comments(array(
                    'number'      => intval($comments_count),
                    'status'      => 'approve',
                    'post_status' => 'publish'
                ));
                ?>
                <section class="sidebar-section recent-comments" aria-labelledby="recent-comments-heading">
                    <h2 id="recent-comments-heading" class="sidebar-heading"><?php esc_html_e('Recent Comments', 'onespace-theme2'); ?></h2>
                    <ul class="recent-comments-list">
                        <?php foreach ($recent_comments as $c) :
                            $comment_post_title = get_the_title($c->comment_post_ID);
                            ?>
                            <li class="recent-comment-item">
                                <a href="<?php echo esc_url(get_comment_link($c)); ?>" class="recent-comment-link">
                                    <span class="comment-author"><?php echo esc_html(get_comment_author($c)); ?></span>
                                    <span class="comment-on"><?php echo esc_html(wp_trim_words($comment_post_title, 8, '…')); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>
        </aside>
    <?php elseif ( is_active_sidebar('sidebar-1') ) :
        // Widget mode: use standard sidebar template (prevents duplicate curated sidebar)
        get_sidebar();
    endif; ?>
</div><!-- .home-layout-wrapper -->

<?php get_footer(); ?>