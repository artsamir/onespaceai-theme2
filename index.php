<?php
/**
 * The main template file
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

get_header();
?>

<div class="content-wrapper">
    
    <?php if (have_posts()) : ?>

        <?php if (is_home() && !is_front_page()) : ?>
            <header class="page-header">
                <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <div class="posts-container">
            <?php
            // Start the Loop
            while (have_posts()) :
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-article'); ?>>
                    
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;

                        if ('post' === get_post_type()) :
                            ?>
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                </span>
                                <span class="byline">
                                    <?php
                                    printf(
                                        /* translators: %s: post author. */
                                        esc_html_x('by %s', 'post author', 'onespace-theme2'),
                                        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
                                    );
                                    ?>
                                </span>
                                <?php if (has_category()) : ?>
                                    <span class="cat-links">
                                        <?php
                                        printf(
                                            /* translators: %s: list of categories. */
                                            esc_html__('in %s', 'onespace-theme2'),
                                            get_the_category_list(esc_html__(', ', 'onespace-theme2'))
                                        );
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </div><!-- .entry-meta -->
                        <?php endif; ?>
                    </header><!-- .entry-header -->

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php
                            if (is_singular()) :
                                the_post_thumbnail('large', array('alt' => the_title_attribute(array('echo' => false))));
                            else :
                                ?>
                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                    <?php the_post_thumbnail('large', array('alt' => the_title_attribute(array('echo' => false)))); ?>
                                </a>
                                <?php
                            endif;
                            ?>
                        </div><!-- .post-thumbnail -->
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        if (is_singular()) {
                            the_content();
                        } else {
                            the_excerpt();
                        }

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'onespace-theme2'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div><!-- .entry-content -->

                    <?php if (!is_singular() && 'post' === get_post_type()) : ?>
                        <div class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php esc_html_e('Read More', 'onespace-theme2'); ?>
                                <span class="screen-reader-text"><?php printf(esc_html__('about %s', 'onespace-theme2'), get_the_title()); ?></span>
                            </a>
                        </div><!-- .entry-footer -->
                    <?php endif; ?>

                    <?php if (is_singular() && (has_tag() || has_category())) : ?>
                        <footer class="entry-footer">
                            <?php if (has_tag()) : ?>
                                <div class="tags-links">
                                    <?php
                                    printf(
                                        /* translators: %s: list of tags. */
                                        esc_html__('Tagged: %s', 'onespace-theme2'),
                                        get_the_tag_list('', esc_html__(', ', 'onespace-theme2'))
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>
                        </footer><!-- .entry-footer -->
                    <?php endif; ?>

                </article><!-- #post-<?php the_ID(); ?> -->

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (is_singular() && (comments_open() || get_comments_number())) :
                    comments_template();
                endif;

            endwhile;
            ?>
        </div><!-- .posts-container -->

        <?php
        // Previous/next page navigation
        the_posts_navigation(array(
            'prev_text' => esc_html__('Older posts', 'onespace-theme2'),
            'next_text' => esc_html__('Newer posts', 'onespace-theme2'),
        ));

    else :
        ?>
        
        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Nothing here', 'onespace-theme2'); ?></h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <?php if (is_home() && current_user_can('publish_posts')) : ?>
                    <p>
                        <?php
                        printf(
                            wp_kses(
                                /* translators: 1: link to WP admin new post page. */
                                __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'onespace-theme2'),
                                array(
                                    'a' => array(
                                        'href' => array(),
                                    ),
                                )
                            ),
                            esc_url(admin_url('post-new.php'))
                        );
                        ?>
                    </p>
                <?php elseif (is_search()) : ?>
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'onespace-theme2'); ?></p>
                    <?php get_search_form(); ?>
                <?php else : ?>
                    <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'onespace-theme2'); ?></p>
                    <?php get_search_form(); ?>
                <?php endif; ?>
            </div><!-- .page-content -->
        </section><!-- .no-results -->

    <?php endif; ?>

</div><!-- .content-wrapper -->

<?php
get_sidebar();
get_footer();