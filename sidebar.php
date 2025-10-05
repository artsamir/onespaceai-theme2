<?php
/**
 * The sidebar containing the main widget area
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

// If home page is using custom curated sidebar (handled inside index.php), skip rendering this file.
if ( is_home() && get_theme_mod('home_primary_sidebar_mode', 'custom') === 'custom' ) {
    return;
}

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar" role="complementary" aria-label="<?php esc_attr_e('Sidebar', 'onespace-theme2'); ?>">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside><!-- #secondary -->