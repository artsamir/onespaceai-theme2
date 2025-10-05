<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'onespace-theme2'); ?></a>

    <header id="masthead" class="site-header">
        
        <!-- Site Branding Section -->
        <div class="site-branding">
            <?php
            $tagline_position = get_theme_mod('header_tagline_position', 'below-title');
            $tagline_offset_x = get_theme_mod('header_tagline_offset_x', 0);
            $tagline_offset_y = get_theme_mod('header_tagline_offset_y', 0);
            
            // Apply tagline offsets if set
            $tagline_style = '';
            if ($tagline_offset_x != 0 || $tagline_offset_y != 0) {
                $tagline_style = sprintf(
                    'style="transform: translate(%srem, %srem);"', 
                    esc_attr($tagline_offset_x), 
                    esc_attr($tagline_offset_y)
                );
            }
            
            // Mobile logo logic
            $mobile_logo_id = get_theme_mod('mobile_logo');
            $has_mobile_logo = !empty($mobile_logo_id);
            $show_mobile_logo = get_theme_mod('show_mobile_logo', true);
            $show_mobile_tagline = get_theme_mod('show_mobile_tagline', false);
            $mobile_logo_alignment = get_theme_mod('mobile_logo_alignment', 'left');
            
            // Build logo classes
            $logo_classes = array('site-logo');
            if ($has_mobile_logo) $logo_classes[] = 'has-mobile-logo';
            if (!$show_mobile_logo) $logo_classes[] = 'hide-mobile-logo';
            if (!$show_mobile_tagline) $logo_classes[] = 'hide-mobile-tagline';
            $logo_classes[] = 'mobile-logo-' . $mobile_logo_alignment;
            $logo_class = implode(' ', $logo_classes);
            
            if (has_custom_logo()) : ?>
                <div class="<?php echo esc_attr($logo_class); ?>">
                    <!-- Desktop Logo -->
                    <div class="desktop-logo">
                        <?php the_custom_logo(); ?>
                    </div>
                    <!-- Mobile Logo -->
                    <?php if ($has_mobile_logo) :
                        $mobile_logo = wp_get_attachment_image($mobile_logo_id, 'full', false, array('class' => 'mobile-custom-logo'));
                        if ($mobile_logo) : ?>
                            <div class="mobile-logo">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-custom-logo-link" rel="home">
                                    <?php echo $mobile_logo; ?>
                                </a>
                            </div>
                        <?php endif;
                    endif; ?>
                </div>
            <?php endif; ?>

            <?php if (display_header_text()) : ?>
                <?php if ($tagline_position === 'above-title' && get_bloginfo('description')) : ?>
                    <p class="site-tagline mobile-tagline" <?php echo $tagline_style; ?>><?php bloginfo('description'); ?></p>
                <?php endif; ?>

                <?php if (is_front_page() && is_home()) : ?>
                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                <?php else : ?>
                    <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                <?php endif; ?>

                <?php if (in_array($tagline_position, ['below-title', 'left-title', 'right-title']) && get_bloginfo('description')) : ?>
                    <p class="site-tagline mobile-tagline" <?php echo $tagline_style; ?>><?php bloginfo('description'); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div><!-- .site-branding -->

        <!-- Main Navigation Section -->
        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'onespace-theme2'); ?>">
            <?php
            $menu_offset_left = get_theme_mod('header_menu_offset_left', 0);
            $menu_offset_right = get_theme_mod('header_menu_offset_right', 0);
            
            $nav_style = '';
            if ($menu_offset_left != 0 || $menu_offset_right != 0) {
                $nav_style = sprintf(
                    'style="margin-left: %srem; margin-right: %srem;"', 
                    esc_attr($menu_offset_left), 
                    esc_attr($menu_offset_right)
                );
            }
            ?>
            
            <div class="nav-container" <?php echo $nav_style; ?>>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => 'onespace_default_menu',
                ));
                ?>
            </div>
        </nav><!-- #site-navigation -->

    <!-- Header Utilities Section -->
    <div class="header-utilities">
            
            <!-- Search Form -->
            <?php
            $search_scope = get_theme_mod('header_search_scope', 'site');
            $search_placeholder = get_theme_mod('header_search_placeholder', __('Search...', 'onespace-theme2'));
            $custom_url = get_theme_mod('header_search_custom_url', '');
            $manual_ids = get_theme_mod('header_search_manual_ids', '');
            
            $action_url = home_url('/');
            if ($search_scope === 'custom' && !empty($custom_url)) {
                $action_url = esc_url($custom_url);
            }
            
            // Add hidden fields for manual post ID search
            $hidden_fields = '';
            if ($search_scope === 'manual' && !empty($manual_ids)) {
                $post_ids = explode(',', $manual_ids);
                $post_ids = array_map('trim', $post_ids);
                $post_ids = array_filter($post_ids, 'is_numeric');
                
                if (!empty($post_ids)) {
                    $hidden_fields = '<input type="hidden" name="post__in" value="' . esc_attr(implode(',', $post_ids)) . '">';
                }
            }
            ?>
            
            <!-- Mobile Search Toggle -->
            <button class="mobile-search-toggle" type="button" aria-label="<?php esc_attr_e('Open search', 'onespace-theme2'); ?>" aria-expanded="false">
                <span class="toggle-icon" aria-hidden="true">🔍</span>
            </button>

            <form role="search" method="get" class="search-form header-search" id="header-search-form" action="<?php echo esc_url($action_url); ?>">
                <label>
                    <span class="screen-reader-text"><?php echo _x('Search for:', 'label', 'onespace-theme2'); ?></span>
                    <input type="search" 
                           class="search-field" 
                           placeholder="<?php echo esc_attr($search_placeholder); ?>" 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" 
                           autocomplete="off" />
                </label>
                <?php echo $hidden_fields; ?>
                <input type="submit" class="search-submit screen-reader-text" value="<?php echo esc_attr_x('Search', 'submit button', 'onespace-theme2'); ?>" />
            </form>

            <!-- Dark/Light Mode Toggle -->
            <?php
            $toggle_mode = get_theme_mod('header_toggle_icon_mode', 'text');
            $light_text = get_theme_mod('header_toggle_icon_light', '☀️');
            $dark_text = get_theme_mod('header_toggle_icon_dark', '🌙');
            $light_image = get_theme_mod('header_toggle_icon_light_image', '');
            $dark_image = get_theme_mod('header_toggle_icon_dark_image', '');
            
            // Only show toggle if at least one icon/text is set
            $show_toggle = false;
            if ($toggle_mode === 'text' && ($light_text || $dark_text)) {
                $show_toggle = true;
            } elseif ($toggle_mode === 'image' && ($light_image || $dark_image)) {
                $show_toggle = true;
            }
            
            if ($show_toggle) :
            ?>
        <button class="dark-light-toggle" 
                        type="button" 
                        aria-label="<?php esc_attr_e('Toggle dark/light mode', 'onespace-theme2'); ?>"
                        title="<?php esc_attr_e('Toggle dark/light mode', 'onespace-theme2'); ?>">
                    
                    <span class="light-icon" style="display: block;">
                        <?php if ($toggle_mode === 'text') : ?>
                            <span class="toggle-icon"><?php echo esc_html($light_text); ?></span>
                        <?php elseif ($toggle_mode === 'image' && $light_image) : ?>
                            <span class="toggle-icon">
                                <img src="<?php echo esc_url($light_image); ?>" 
                                     alt="<?php esc_attr_e('Light mode', 'onespace-theme2'); ?>" />
                            </span>
                        <?php endif; ?>
                    </span>
                    
                    <span class="dark-icon" style="display: none;">
                        <?php if ($toggle_mode === 'text') : ?>
                            <span class="toggle-icon"><?php echo esc_html($dark_text); ?></span>
                        <?php elseif ($toggle_mode === 'image' && $dark_image) : ?>
                            <span class="toggle-icon">
                                <img src="<?php echo esc_url($dark_image); ?>" 
                                     alt="<?php esc_attr_e('Dark mode', 'onespace-theme2'); ?>" />
                            </span>
                        <?php endif; ?>
                    </span>
                </button>
            <?php endif; ?>

            <!-- Mobile Menu Toggle -->
            <button class="menu-toggle" type="button" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'onespace-theme2'); ?>">
                <span class="hamburger" aria-hidden="true">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </span>
            </button>

        </div><!-- .header-utilities -->

    </header><!-- #masthead -->

    <div id="content" class="site-content">
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">

<?php
/**
 * Default fallback menu when no menu is assigned
 */
function onespace_default_menu() {
    echo '<ul id="primary-menu" class="menu">';
    
    // Home link
    echo '<li class="menu-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'onespace-theme2') . '</a></li>';
    
    // Recent posts
    $recent_posts = wp_get_recent_posts(array(
        'numberposts' => 3,
        'post_status' => 'publish'
    ));
    
    foreach ($recent_posts as $post) {
        echo '<li class="menu-item"><a href="' . esc_url(get_permalink($post['ID'])) . '">' . esc_html($post['post_title']) . '</a></li>';
    }
    
    // Sample page link
    $sample_page = get_page_by_title('Sample Page');
    if ($sample_page) {
        echo '<li class="menu-item"><a href="' . esc_url(get_permalink($sample_page->ID)) . '">' . esc_html($sample_page->post_title) . '</a></li>';
    }
    
    echo '</ul>';
}
?>