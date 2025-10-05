            </main><!-- #main -->
        </div><!-- #primary -->

        <?php get_sidebar(); ?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer" role="contentinfo">

        <!-- Upper Footer: brand/logo, widgets -->
        <div class="footer-top">
            <div class="footer-content">

                <!-- Brand image/title area -->
                <div class="footer-branding">
                    <?php if (has_custom_logo()) : ?>
                        <div class="footer-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php else : ?>
                        <div class="footer-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if (get_bloginfo('description')) : ?>
                        <div class="footer-description">
                            <?php bloginfo('description'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Footer widgets: up to three columns -->
                <div class="footer-widgets">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <div class="footer-widget-area footer-col-1">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <div class="footer-widget-area footer-col-2">
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <div class="footer-widget-area footer-col-3">
                            <?php dynamic_sidebar('footer-3'); ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div><!-- .footer-content -->
        </div><!-- .footer-top -->

        <?php if (has_nav_menu('footer')) : ?>
        <div class="footer-middle">
            <div class="footer-content">
                <nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e('Footer Navigation', 'onespace-theme2'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'container'      => false,
                        'depth'          => 1,
                    ));
                    ?>
                </nav>
            </div>
        </div>
        <?php endif; ?>
        <!-- Lower Footer: copyright strip -->
        <div class="site-info">
            <div class="footer-bottom">
                <div class="footer-credits">
                    <span class="copyright">
                        <?php
                        $copyright_tpl = get_theme_mod('footer_copyright_text');
                        $year = date_i18n('Y');
                        $site = get_bloginfo('name');
                        if ($copyright_tpl) {
                            $rendered = str_replace(array('{year}', '{site}'), array($year, $site), $copyright_tpl);
                            echo wp_kses_post($rendered);
                        } else {
                            printf(
                                /* translators: 1: copyright symbol, 2: current year, 3: site name */
                                esc_html__('%1$s %2$s %3$s. All rights reserved.', 'onespace-theme2'),
                                '&copy;',
                                $year,
                                $site
                            );
                        }
                        ?>
                    </span>
                    
                </div>
            </div>
        </div><!-- .site-info -->

    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>