<?php
/**
 * Single Post Template
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

get_header();
?>

<div class="home-layout-wrapper single-post-layout">
    <main class="home-main single-post-main" role="main">
        <?php while (have_posts()) : the_post(); ?>
            <?php
            // Pre-calc values used in the layout
            $author_id = get_the_author_meta('ID');
            $author_name = get_the_author_meta('display_name');
            $author_avatar = get_avatar_url($author_id, array('size' => 48));
            $word_count = str_word_count( wp_strip_all_tags( get_the_content() ) );
            $reading_time = max(1, ceil($word_count / 200));
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article'); ?>>
                
                <!-- 1. Post Title -->
                <header class="single-post-header">
                    <h1 class="single-post-title"><?php the_title(); ?></h1>
                </header>

                <!-- 2. Main Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="single-post-featured-image">
                        <?php the_post_thumbnail('large', ['alt' => the_title_attribute(['echo' => false])]); ?>
                    </div>
                <?php endif; ?>

                <!-- 3. Meta Box -->
                <div class="single-post-meta-box">
                    <div class="post-author-info">
                        <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="author-avatar" />
                        <span class="author-name"><?php esc_html_e('By', 'onespace-theme2'); ?> <?php echo esc_html($author_name); ?></span>
                    </div>
                    <div class="post-dates">
                        <span class="post-published"><i class="icon-calendar"></i><?php esc_html_e('Published:', 'onespace-theme2'); ?> <?php echo esc_html(get_the_date('M j, Y')); ?></span>
                        <span class="post-updated"><i class="icon-clock"></i><?php esc_html_e('Updated:', 'onespace-theme2'); ?> <?php echo esc_html(get_the_modified_date('M j, Y')); ?></span>
                    </div>
                    <div class="post-stats">
                        <span class="post-views"><i class="icon-eye"></i><?php echo number_format_i18n( (int) ( get_post_meta(get_the_ID(), 'post_views_count', true) ?: 0 ) ); ?> <?php esc_html_e('views', 'onespace-theme2'); ?></span>
                        <span class="reading-time"><i class="icon-time"></i><?php echo esc_html($reading_time); ?> <?php esc_html_e('min read', 'onespace-theme2'); ?></span>
                        <span class="current-reading-time" id="current-reading-time"><i class="icon-stopwatch"></i><?php esc_html_e('Reading:', 'onespace-theme2'); ?> <span id="reading-timer">0m</span></span>
                    </div>
                </div>

                <!-- 4. Content -->
                <div class="single-post-content">
                    <?php the_content(); ?>
                    <?php wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'onespace-theme2'),
                        'after'  => '</div>',
                    )); ?>
                </div>

                <!-- 5. Tags -->
                <?php if ( has_tag() ) : ?>
                    <div class="single-post-tags-section">
                        <h3 class="tags-heading"><?php esc_html_e('Tags', 'onespace-theme2'); ?></h3>
                        <div class="tags-container"><?php the_tags('', ''); ?></div>
                    </div>
                <?php endif; ?>

                <!-- 6. About Author -->
                <div class="about-author-section">
                    <h3 class="about-heading"><?php esc_html_e('About Suman', 'onespace-theme2'); ?></h3>
                    <div class="about-content">
                        <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="about-author-avatar" />
                        <div class="about-text">
                            <p><?php echo esc_html__("Hi I'm Suman from West Bengal. Driven by my passion for AI and new technologies, I started this blog. Here, you'll find practical tips to earn money from home, honest tool reviews, and easy-to-follow tutorials.", 'onespace-theme2'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- 7. Comment Form (customizable via Customizer) -->
                <?php if ( ! onespace_render_custom_comment_form(get_the_ID()) ) : ?>
                    <?php comment_form(); ?>
                <?php endif; ?>

                <!-- 8. Prev/Next Navigation with thumbnails -->
                <div class="post-navigation-section">
                    <div class="nav-posts">
                        <?php $prev_post = get_previous_post(); if ($prev_post) : ?>
                        <div class="nav-post prev-post">
                            <a class="nav-link ajax-blog-link" data-post-id="<?php echo esc_attr($prev_post->ID); ?>" href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
                                <div class="nav-post-image">
                                    <?php echo has_post_thumbnail($prev_post->ID) ? get_the_post_thumbnail($prev_post->ID, 'thumbnail') : '<div class="no-image">No Image</div>'; ?>
                                </div>
                                <div class="nav-post-content">
                                    <span class="nav-label">&larr; <?php esc_html_e('Previous Post', 'onespace-theme2'); ?></span>
                                    <span class="nav-title"><?php echo esc_html(get_the_title($prev_post)); ?></span>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>

                        <?php $next_post = get_next_post(); if ($next_post) : ?>
                        <div class="nav-post next-post">
                            <a class="nav-link ajax-blog-link" data-post-id="<?php echo esc_attr($next_post->ID); ?>" href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
                                <div class="nav-post-content">
                                    <span class="nav-label"><?php esc_html_e('Next Post', 'onespace-theme2'); ?> &rarr;</span>
                                    <span class="nav-title"><?php echo esc_html(get_the_title($next_post)); ?></span>
                                </div>
                                <div class="nav-post-image">
                                    <?php echo has_post_thumbnail($next_post->ID) ? get_the_post_thumbnail($next_post->ID, 'thumbnail') : '<div class="no-image">No Image</div>'; ?>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="single-post-navigation">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="back-to-home">&larr; <?php esc_html_e('Back to Home', 'onespace-theme2'); ?></a>
                </div>
            </article>
        <?php endwhile; ?>
    </main>

    <?php
    // Use the same sidebar logic as the home page
    $sidebar_mode      = get_theme_mod('home_primary_sidebar_mode', 'custom');
    $posts_source      = get_theme_mod('home_recent_posts_source', 'latest');
    $posts_count       = get_theme_mod('home_recent_posts_count', 12);
    $posts_category    = get_theme_mod('home_recent_posts_category', '');
    $posts_manual_ids  = get_theme_mod('home_recent_posts_manual_ids', '');
    $show_comments     = (bool) get_theme_mod('home_recent_comments_enable', true);
    $comments_count    = get_theme_mod('home_recent_comments_count', 5);

    if ($sidebar_mode === 'custom') : ?>
        <aside class="home-sidebar single-post-sidebar" aria-label="Sidebar">
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
                            <a href="<?php echo esc_url($permalink); ?>" class="recent-post-link ajax-blog-link" data-post-id="<?php echo $post_obj->ID; ?>">
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
                                <a href="<?php echo esc_url(get_comment_link($c)); ?>" class="recent-comment-link ajax-blog-link">
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
        // Widget mode: use standard sidebar template
        get_sidebar();
    endif; ?>
</div><!-- .home-layout-wrapper -->

<?php get_footer(); ?>