            </main><!-- #main -->
        </div><!-- #primary -->

        <?php get_sidebar(); ?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer" role="contentinfo">
        
        <?php if (is_active_sidebar('footer-1')) : ?>
            <div class="footer-widgets">
                <div class="footer-widget-area">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <div class="footer-content">
                
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

                <?php if (has_nav_menu('footer')) : ?>
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
                <?php endif; ?>

                <div class="footer-credits">
                    <span class="copyright">
                        <?php
                        printf(
                            /* translators: 1: copyright symbol, 2: current year, 3: site name */
                            esc_html__('%1$s %2$s %3$s. All rights reserved.', 'onespace-theme2'),
                            '&copy;',
                            date_i18n('Y'),
                            get_bloginfo('name')
                        );
                        ?>
                    </span>
                    
                    <span class="powered-by">
                        <?php
                        printf(
                            /* translators: 1: Theme name, 2: WordPress link */
                            esc_html__('Powered by %1$s and %2$s', 'onespace-theme2'),
                            '<a href="https://wordpress.org/" rel="nofollow">WordPress</a>',
                            '<strong>OnespaceTheme2</strong>'
                        );
                        ?>
                    </span>
                </div>

            </div><!-- .footer-content -->
        </div><!-- .site-info -->

    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>