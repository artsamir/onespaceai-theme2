<?php
/**
 * OnespaceTheme2 functions and definitions
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme constants
define('ONESPACE_THEME_VERSION', '1.0.1');
define('ONESPACE_THEME_DIR', get_template_directory());
define('ONESPACE_THEME_URI', get_template_directory_uri());

/**
 * Theme setup
 */
function onespace_theme_setup() {
    // Make theme available for translation
    load_theme_textdomain('onespace-theme2', ONESPACE_THEME_DIR . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Add support for custom logos
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for custom header
    add_theme_support('custom-header', array(
        'default-color' => 'ffffff',
        'height'        => 200,
        'flex-height'   => true,
        'uploads'       => true,
    ));

    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'onespace-theme2'),
        'footer'  => __('Footer Menu', 'onespace-theme2'),
    ));

    // Add support for wide and full alignment in Gutenberg
    add_theme_support('align-wide');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for dark editor style
    add_theme_support('dark-editor-style');
}
add_action('after_setup_theme', 'onespace_theme_setup');

/**
 * Set the content width in pixels
 */
function onespace_content_width() {
    $GLOBALS['content_width'] = apply_filters('onespace_content_width', 1200);
}
add_action('after_setup_theme', 'onespace_content_width', 0);

/**
 * Enqueue scripts and styles
 */
function onespace_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style('onespace-style', get_stylesheet_uri(), array(), ONESPACE_THEME_VERSION);

    // Enqueue theme JavaScript
    wp_enqueue_script('onespace-theme-js', ONESPACE_THEME_URI . '/js/theme.js', array('jquery'), ONESPACE_THEME_VERSION, true);
    
    // Enqueue footer alignment fix script
    wp_enqueue_script('onespace-footer-alignment', ONESPACE_THEME_URI . '/js/footer-alignment.js', array('jquery'), ONESPACE_THEME_VERSION, true);

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Localize script for AJAX and theme data
    wp_localize_script('onespace-theme-js', 'onespace_theme_data', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('onespace_theme_nonce'),
        'rtl'      => is_rtl(),
    ));
}
add_action('wp_enqueue_scripts', 'onespace_scripts');

/**
 * Enqueue customizer scripts and styles
 */
function onespace_customize_scripts() {
    // Enqueue customizer controls script
    wp_enqueue_script(
        'onespace-customizer-controls',
        ONESPACE_THEME_URI . '/js/customizer-controls.js',
        array('jquery', 'customize-controls'),
        ONESPACE_THEME_VERSION,
        true
    );

    // Enqueue customizer controls stylesheet
    wp_enqueue_style(
        'onespace-customizer-controls',
        ONESPACE_THEME_URI . '/css/customizer-controls.css',
        array(),
        ONESPACE_THEME_VERSION
    );

    // Localize customizer controls script
    wp_localize_script('onespace-customizer-controls', 'onespace_customizer_data', array(
        'debug' => defined('WP_DEBUG') && WP_DEBUG,
    ));
}
add_action('customize_controls_enqueue_scripts', 'onespace_customize_scripts');

/**
 * Enqueue customizer preview scripts
 */
function onespace_customize_preview_scripts() {
    wp_enqueue_script(
        'onespace-customizer-preview',
        ONESPACE_THEME_URI . '/js/customizer-preview.js',
        array('jquery', 'customize-preview'),
        ONESPACE_THEME_VERSION,
        true
    );
    // Footer branding live preview
    wp_enqueue_script(
        'onespace-footer-branding-preview',
        ONESPACE_THEME_URI . '/js/footer-branding-customizer.js',
        array('jquery','customize-preview'),
        ONESPACE_THEME_VERSION,
        true
    );
}
add_action('customize_preview_init', 'onespace_customize_preview_scripts');

// Mobile view toggle JavaScript no longer needed - using separate section

/**
 * Load customizer files
 */
function onespace_load_customizer() {
    // Load sanitization functions
    require_once ONESPACE_THEME_DIR . '/inc/customizer-sanitize.php';
    
    // Load header customizer settings
    require_once ONESPACE_THEME_DIR . '/inc/customizer-header.php';

    // Load footer customizer settings
    require_once ONESPACE_THEME_DIR . '/inc/customizer-footer.php';
    
    // Load footer branding customizer settings (new)
    if ( file_exists( ONESPACE_THEME_DIR . '/inc/customizer-footer-branding.php' ) ) {
        require_once ONESPACE_THEME_DIR . '/inc/customizer-footer-branding.php';
    }
    // Load home sidebar customizer settings (new)
    if ( file_exists( ONESPACE_THEME_DIR . '/inc/customizer-home-sidebar.php' ) ) {
        require_once ONESPACE_THEME_DIR . '/inc/customizer-home-sidebar.php';
    }
    // Load comment form customizer
    if ( file_exists( ONESPACE_THEME_DIR . '/inc/customizer-comment-form.php' ) ) {
        require_once ONESPACE_THEME_DIR . '/inc/customizer-comment-form.php';
    }
}
add_action('customize_register', 'onespace_load_customizer', 1);

/**
 * Widget areas
 */
function onespace_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'onespace-theme2'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'onespace-theme2'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer', 'onespace-theme2'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'onespace-theme2'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    // Additional footer widget areas for multi-column layout
    register_sidebar(array(
        'name'          => __('Footer 2', 'onespace-theme2'),
        'id'            => 'footer-2',
        'description'   => __('Second footer widget area.', 'onespace-theme2'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer 3', 'onespace-theme2'),
        'id'            => 'footer-3',
        'description'   => __('Third footer widget area.', 'onespace-theme2'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'onespace_widgets_init');

/**
 * Custom search form
 */
function onespace_search_form($form) {
    $search_placeholder = get_theme_mod('header_search_placeholder', __('Search...', 'onespace-theme2'));
    $search_scope = get_theme_mod('header_search_scope', 'site');
    $custom_url = get_theme_mod('header_search_custom_url', '');
    
    $action_url = home_url('/');
    if ($search_scope === 'custom' && !empty($custom_url)) {
        $action_url = esc_url($custom_url);
    }
    
    $form = '<form role="search" method="get" class="search-form header-search" action="' . $action_url . '">
                <label>
                    <span class="screen-reader-text">' . _x('Search for:', 'label', 'onespace-theme2') . '</span>
                    <input type="search" class="search-field" placeholder="' . esc_attr($search_placeholder) . '" value="' . get_search_query() . '" name="s" />
                </label>
                <input type="submit" class="search-submit" value="' . esc_attr_x('Search', 'submit button', 'onespace-theme2') . '" />
            </form>';
    
    return $form;
}
add_filter('get_search_form', 'onespace_search_form');

/**
 * Generate CSS custom properties from theme mods
 */
function onespace_generate_css_vars() {
    $css_vars = array();
    
    // Header background
    $header_bg = get_theme_mod('header_background_color', '#ffffff');
    if ($header_bg) {
        $css_vars['--header-bg-color'] = sanitize_hex_color($header_bg);
    }
    
    // Logo dimensions
    $logo_width = get_theme_mod('header_logo_width_value', 200);
    $logo_width_unit = get_theme_mod('header_logo_width_unit', 'px');
    $logo_height = get_theme_mod('header_logo_height_value', 80);
    $logo_height_unit = get_theme_mod('header_logo_height_unit', 'px');
    
    $css_vars['--logo-width'] = intval($logo_width) . $logo_width_unit;
    $css_vars['--logo-height'] = intval($logo_height) . $logo_height_unit;
    
    // Mobile logo dimensions (now in Header Toggle Settings)
    $mobile_logo_width = get_theme_mod('mobile_logo_width_value', 120);
    $mobile_logo_width_unit = get_theme_mod('mobile_logo_width_unit', 'px');
    $mobile_logo_height = get_theme_mod('mobile_logo_height_value', 40);
    $mobile_logo_height_unit = get_theme_mod('mobile_logo_height_unit', 'px');
    
    $css_vars['--mobile-logo-width'] = intval($mobile_logo_width) . $mobile_logo_width_unit;
    $css_vars['--mobile-logo-height'] = ($mobile_logo_height_unit === 'auto') ? 'auto' : (intval($mobile_logo_height) . $mobile_logo_height_unit);
    
    // Tagline gap
    $tagline_gap = get_theme_mod('header_tagline_gap', 1);
    $css_vars['--tagline-gap'] = floatval($tagline_gap) . 'rem';
    
    // Menu styles
    $menu_vars = array(
        'header_menu_text_color' => '--menu-text-color',
        'header_menu_bg_hover' => '--menu-bg-hover',
        'header_menu_font_size' => '--menu-font-size',
        'header_menu_font_weight' => '--menu-font-weight',
        'header_menu_font_family' => '--menu-font-family',
        'header_menu_font_style' => '--menu-font-style',
        'header_menu_letter_spacing' => '--menu-letter-spacing',
        'header_menu_line_height' => '--menu-line-height',
        'header_submenu_bg' => '--submenu-bg',
        'header_submenu_hover_bg' => '--submenu-hover-bg',
        'header_submenu_text_color' => '--submenu-text-color',
    );
    
    foreach ($menu_vars as $mod => $var) {
        $value = get_theme_mod($mod);
        if ($value) {
            $css_vars[$var] = $value;
        }
    }
    
    // Menu padding and radius
    $menu_padding_x = get_theme_mod('header_menu_padding_x', 1);
    $menu_padding_y = get_theme_mod('header_menu_padding_y', 0.5);
    $menu_radius = get_theme_mod('header_menu_radius', 0);
    
    $css_vars['--menu-padding-x'] = floatval($menu_padding_x) . 'rem';
    $css_vars['--menu-padding-y'] = floatval($menu_padding_y) . 'rem';
    $css_vars['--menu-radius'] = floatval($menu_radius) . 'px';
    
    // Search styles
    $search_vars = array(
        'header_search_width' => '--search-width',
        'header_search_height' => '--search-height',
        'header_search_font_size' => '--search-font-size',
        'header_search_font_family' => '--search-font-family',
        'header_search_placeholder_color' => '--search-placeholder-color',
        'header_search_bg' => '--search-bg',
        'header_search_color' => '--search-color',
        'header_search_border_color' => '--search-border-color',
    );
    
    foreach ($search_vars as $mod => $var) {
        $value = get_theme_mod($mod);
        if ($value) {
            if (strpos($var, 'width') !== false || strpos($var, 'height') !== false) {
                $css_vars[$var] = intval($value) . 'px';
            } elseif (strpos($var, 'font-size') !== false) {
                $css_vars[$var] = floatval($value) . 'rem';
            } else {
                $css_vars[$var] = $value;
            }
        }
    }
    
    // Search border and radius
    $search_radius = get_theme_mod('header_search_radius', 4);
    $search_border_width = get_theme_mod('header_search_border_width', 1);
    
    $css_vars['--search-radius'] = floatval($search_radius) . 'px';
    $css_vars['--search-border-width'] = floatval($search_border_width) . 'px';
    
    // Toggle styles
    $toggle_vars = array(
        'header_toggle_icon_color' => '--toggle-icon-color',
        'header_toggle_bg_color' => '--toggle-bg-color',
    );
    
    foreach ($toggle_vars as $mod => $var) {
        $value = get_theme_mod($mod);
        if ($value) {
            $css_vars[$var] = $value;
        }
    }
    
    // Toggle dimensions and spacing
    $toggle_width = get_theme_mod('header_toggle_icon_width', 24);
    $toggle_height = get_theme_mod('header_toggle_icon_height', 24);
    $toggle_padding = get_theme_mod('header_toggle_icon_padding', 0.5);
    $toggle_radius = get_theme_mod('header_toggle_icon_radius', 4);
    
    $css_vars['--toggle-icon-width'] = intval($toggle_width) . 'px';
    $css_vars['--toggle-icon-height'] = intval($toggle_height) . 'px';
    $css_vars['--toggle-icon-padding'] = floatval($toggle_padding) . 'rem';
    $css_vars['--toggle-icon-radius'] = floatval($toggle_radius) . 'px';

    // Toggle border
    $toggle_border_width = get_theme_mod('header_toggle_border_width', 0);
    $toggle_border_color = get_theme_mod('header_toggle_border_color', 'rgba(0,0,0,0.15)');
    $css_vars['--toggle-border-width'] = floatval($toggle_border_width) . 'px';
    if ($toggle_border_color) {
        $css_vars['--toggle-border-color'] = $toggle_border_color;
    }
    
    // Toggle margins
    $toggle_margins = array(
        'header_toggle_margin_top' => '--toggle-margin-top',
        'header_toggle_margin_right' => '--toggle-margin-right',
        'header_toggle_margin_bottom' => '--toggle-margin-bottom',
        'header_toggle_margin_left' => '--toggle-margin-left',
    );
    
    foreach ($toggle_margins as $mod => $var) {
        $value = get_theme_mod($mod, 0);
        $css_vars[$var] = floatval($value) . 'rem';
    }

    // Toggle offsets
    $toggle_offset_x = get_theme_mod('header_toggle_offset_x', 0);
    $toggle_offset_y = get_theme_mod('header_toggle_offset_y', 0);
    $css_vars['--toggle-offset-x'] = floatval($toggle_offset_x) . 'rem';
    $css_vars['--toggle-offset-y'] = floatval($toggle_offset_y) . 'rem';
    
    // Mobile icon styling
    $mobile_dark_light_size = get_theme_mod('mobile_dark_light_icon_size', 16);
    $mobile_dark_light_padding = get_theme_mod('mobile_dark_light_padding', 8);
    $mobile_dark_light_margin = get_theme_mod('mobile_dark_light_margin', 4);
    $mobile_search_size = get_theme_mod('mobile_search_icon_size', 16);
    $mobile_search_padding = get_theme_mod('mobile_search_padding', 8);
    $mobile_search_margin = get_theme_mod('mobile_search_margin', 4);
    $mobile_menu_size = get_theme_mod('mobile_menu_icon_size', 18);
    $mobile_menu_padding = get_theme_mod('mobile_menu_padding', 8);
    $mobile_menu_margin = get_theme_mod('mobile_menu_margin', 4);
    
    $css_vars['--mobile-dark-light-size'] = intval($mobile_dark_light_size) . 'px';
    $css_vars['--mobile-dark-light-padding'] = intval($mobile_dark_light_padding) . 'px';
    $css_vars['--mobile-dark-light-margin'] = intval($mobile_dark_light_margin) . 'px';
    $css_vars['--mobile-search-size'] = intval($mobile_search_size) . 'px';
    $css_vars['--mobile-search-padding'] = intval($mobile_search_padding) . 'px';
    $css_vars['--mobile-search-margin'] = intval($mobile_search_margin) . 'px';
    $css_vars['--mobile-menu-size'] = intval($mobile_menu_size) . 'px';
    $css_vars['--mobile-menu-padding'] = intval($mobile_menu_padding) . 'px';
    $css_vars['--mobile-menu-margin'] = intval($mobile_menu_margin) . 'px';
    
    // Mobile icon styling
    $mobile_dark_light_size = get_theme_mod('mobile_dark_light_icon_size', 16);
    $mobile_dark_light_padding = get_theme_mod('mobile_dark_light_padding', 8);
    $mobile_dark_light_margin = get_theme_mod('mobile_dark_light_margin', 4);
    $mobile_search_size = get_theme_mod('mobile_search_icon_size', 16);
    $mobile_search_padding = get_theme_mod('mobile_search_padding', 8);
    $mobile_search_margin = get_theme_mod('mobile_search_margin', 4);
    $mobile_menu_size = get_theme_mod('mobile_menu_icon_size', 18);
    $mobile_menu_padding = get_theme_mod('mobile_menu_padding', 8);
    $mobile_menu_margin = get_theme_mod('mobile_menu_margin', 4);
    
    $css_vars['--mobile-dark-light-size'] = intval($mobile_dark_light_size) . 'px';
    $css_vars['--mobile-dark-light-padding'] = intval($mobile_dark_light_padding) . 'px';
    $css_vars['--mobile-dark-light-margin'] = intval($mobile_dark_light_margin) . 'px';
    $css_vars['--mobile-search-size'] = intval($mobile_search_size) . 'px';
    $css_vars['--mobile-search-padding'] = intval($mobile_search_padding) . 'px';
    $css_vars['--mobile-search-margin'] = intval($mobile_search_margin) . 'px';
    $css_vars['--mobile-menu-size'] = intval($mobile_menu_size) . 'px';
    $css_vars['--mobile-menu-padding'] = intval($mobile_menu_padding) . 'px';
    $css_vars['--mobile-menu-margin'] = intval($mobile_menu_margin) . 'px';
    
    // Mobile header spacing
    $mobile_header_padding_top = get_theme_mod('mobile_header_padding_top', 8);
    $mobile_header_padding_bottom = get_theme_mod('mobile_header_padding_bottom', 8);
    $mobile_header_padding_left = get_theme_mod('mobile_header_padding_left', 16);
    $mobile_header_padding_right = get_theme_mod('mobile_header_padding_right', 16);
    $mobile_header_margin_top = get_theme_mod('mobile_header_margin_top', 0);
    $mobile_header_margin_bottom = get_theme_mod('mobile_header_margin_bottom', 0);
    
    $css_vars['--mobile-header-padding-top'] = intval($mobile_header_padding_top) . 'px';
    $css_vars['--mobile-header-padding-bottom'] = intval($mobile_header_padding_bottom) . 'px';
    $css_vars['--mobile-header-padding-left'] = intval($mobile_header_padding_left) . 'px';
    $css_vars['--mobile-header-padding-right'] = intval($mobile_header_padding_right) . 'px';
    $css_vars['--mobile-header-margin-top'] = intval($mobile_header_margin_top) . 'px';
    $css_vars['--mobile-header-margin-bottom'] = intval($mobile_header_margin_bottom) . 'px';

    // Footer copyright styles
    $footer_font_family = get_theme_mod('footer_credits_font_family', '');
    $footer_font_size = get_theme_mod('footer_credits_font_size', 0.85);
    $footer_color = get_theme_mod('footer_credits_color', '#ffffff');
    if ($footer_font_family) {
        $css_vars['--footer-credits-font-family'] = $footer_font_family;
    }
    $css_vars['--footer-credits-font-size'] = floatval($footer_font_size) . 'rem';
    if ($footer_color) {
        $css_vars['--footer-credits-color'] = $footer_color;
    }

    // Footer background
    $footer_bg = get_theme_mod('footer_background_color', '#333333');
    if ($footer_bg) {
        $css_vars['--footer-bg'] = $footer_bg;
    }

    // Footer menu offsets (px)
    $footer_offsets = array(
        'footer_menu_offset_top' => '--footer-menu-offset-top',
        'footer_menu_offset_right' => '--footer-menu-offset-right',
        'footer_menu_offset_bottom' => '--footer-menu-offset-bottom',
        'footer_menu_offset_left' => '--footer-menu-offset-left',
    );
    foreach ($footer_offsets as $mod => $var) {
        $value = get_theme_mod($mod, 0);
        $css_vars[$var] = intval($value) . 'px';
    }

    // Footer menu colors
    $footer_menu_text = get_theme_mod('footer_menu_text_color', '#ffffff');
    if ($footer_menu_text) {
        $css_vars['--footer-menu-text-color'] = $footer_menu_text;
    }
    $footer_menu_hover_text = get_theme_mod('footer_menu_hover_text_color', '#e9ecef');
    if ($footer_menu_hover_text) {
        $css_vars['--footer-menu-hover-text-color'] = $footer_menu_hover_text;
    }
    $footer_menu_hover_bg = get_theme_mod('footer_menu_hover_bg_color', 'rgba(255,255,255,0.08)');
    if ($footer_menu_hover_bg) {
        $css_vars['--footer-menu-hover-bg'] = $footer_menu_hover_bg;
    }

    // Footer menu background color
    $footer_menu_bg = get_theme_mod('footer_menu_background_color', 'transparent');
    if ($footer_menu_bg) {
        $css_vars['--footer-menu-background'] = $footer_menu_bg;
    }

    // Footer menu letter spacing (em) and padding (rem)
    $footer_menu_letter_spacing = get_theme_mod('footer_menu_letter_spacing', 0);
    $css_vars['--footer-menu-letter-spacing'] = floatval($footer_menu_letter_spacing) . 'em';
    $footer_menu_padding_x = get_theme_mod('footer_menu_padding_x', 0.75);
    $footer_menu_padding_y = get_theme_mod('footer_menu_padding_y', 0.5);
    $css_vars['--footer-menu-padding-x'] = floatval($footer_menu_padding_x) . 'rem';
    $css_vars['--footer-menu-padding-y'] = floatval($footer_menu_padding_y) . 'rem';
    
    // Home sidebar styles
    $home_sidebar_width = get_theme_mod('home_sidebar_width', 18); // rem
    $home_layout_gap = get_theme_mod('home_layout_gap', 2); // rem
    if ($home_sidebar_width) { $css_vars['--home-sidebar-width'] = floatval($home_sidebar_width) . 'rem'; }
    if ($home_layout_gap !== null) { $css_vars['--home-layout-gap'] = floatval($home_layout_gap) . 'rem'; }
    $recent_post_item_gap = get_theme_mod('home_recent_posts_item_gap', 0.75);
    if ($recent_post_item_gap !== null) { $css_vars['--home-recent-post-gap'] = floatval($recent_post_item_gap) . 'rem'; }
    
    // Blog card settings
    $cards_per_row = get_theme_mod('home_blog_cards_per_row', '3');
    $css_vars['--home-cards-per-row'] = intval($cards_per_row);
    $card_text_color = get_theme_mod('home_blog_card_text_color', '');
    if ($card_text_color) { $css_vars['--home-card-text-color'] = $card_text_color; }
    $card_padding = get_theme_mod('home_blog_card_padding', 1);
    if ($card_padding !== null) { $css_vars['--home-card-padding'] = floatval($card_padding) . 'rem'; }
    
    // Blog pagination settings
    $pagination_align = get_theme_mod('home_blog_pagination_align', 'center');
    $pagination_justify = 'center';
    if ($pagination_align === 'left') { $pagination_justify = 'flex-start'; }
    elseif ($pagination_align === 'right') { $pagination_justify = 'flex-end'; }
    $css_vars['--home-pagination-justify'] = $pagination_justify;
    
    return $css_vars;
}

/**
 * Output custom CSS
 */
function onespace_custom_css() {
    $css_vars = onespace_generate_css_vars();
    
    if (empty($css_vars)) {
        return;
    }
    
    echo '<style type="text/css" id="onespace-custom-css">';
    echo ':root {';
    
    foreach ($css_vars as $property => $value) {
        echo $property . ': ' . $value . ';';
    }
    
    echo '}';
    echo '</style>';
}
add_action('wp_head', 'onespace_custom_css');

/**
 * Footer branding inline CSS
 */
function onespace_footer_branding_inline_css() {
    // Always output base styles so enabling via Customizer postMessage shows instantly
    $margin   = intval( get_theme_mod('footer_branding_margin', 0) );
    $offset_x = intval( get_theme_mod('footer_branding_offset_x', 0) );
    $offset_y = intval( get_theme_mod('footer_branding_offset_y', 0) );
    $pos      = get_theme_mod('footer_branding_position', 'left');
    $logo_w   = intval( get_theme_mod('footer_branding_logo_width', 0) );
    $logo_h   = intval( get_theme_mod('footer_branding_logo_height', 0) );
    $layout   = get_theme_mod('footer_branding_logo_layout', 'logo-top');

    $justify = 'flex-start';
    if ($pos === 'center') { $justify = 'center'; }
    elseif ($pos === 'right') { $justify = 'flex-end'; }

    $size_rules = '';
    if ( $logo_w > 0 ) { $size_rules .= 'width:'.$logo_w.'px;'; }
    if ( $logo_h > 0 ) { $size_rules .= 'height:'.$logo_h.'px;'; }
    if ( $size_rules ) { $size_rules .= 'object-fit:contain;'; }

    // Determine flex-direction & ordering based on layout
    $direction = 'column';
    $tagline_margin = 'margin-top:.5rem;';
    $wrap_class_extra = '';
    if ( $layout === 'logo-bottom' ) {
        $direction = 'column-reverse';
        $tagline_margin = 'margin-bottom:.5rem;';
    } elseif ( $layout === 'logo-left' ) {
        $direction = 'row';
        $tagline_margin = 'margin-left:.75rem;';
        $wrap_class_extra = ' flex-row';
    } elseif ( $layout === 'logo-right' ) {
        $direction = 'row-reverse';
        $tagline_margin = 'margin-right:.75rem;';
        $wrap_class_extra = ' flex-row';
    }

    // True full width - escape parent container completely
    $css = ".footer-branding-wrapper{display:flex;flex-direction:$direction;align-items:$justify;width:100%;position:relative;margin-top:{$margin}%;margin-bottom:{$margin}%;transition:transform .25s ease;padding-left:1rem;padding-right:1rem;box-sizing:border-box}.footer-branding-wrapper.flex-row{align-items:center}.footer-branding-wrapper img{max-width:100%;height:auto;display:block;$size_rules}.footer-branding-wrapper .footer-tagline{" . $tagline_margin . "font-size:.9rem;opacity:.85}";
    echo '<style id="footer-branding-css">' . esc_html( $css ) . '</style>';
}
add_action('wp_head','onespace_footer_branding_inline_css', 30);

/**
 * Body classes
 */
function onespace_body_classes($classes) {
    // Add tagline position class
    $tagline_position = get_theme_mod('header_tagline_position', 'below-title');
    $classes[] = 'tagline-' . $tagline_position;
    
    // Add dark mode class if needed
    $classes[] = 'theme-onespace';

    // Footer copyright position
    $copyright_pos = get_theme_mod('footer_copyright_position', 'left');
    $classes[] = 'footer-copyright-' . (in_array($copyright_pos, array('left','center','right'), true) ? $copyright_pos : 'left');

    // Footer menu position
    $menu_pos = get_theme_mod('footer_menu_position', 'center');
    if (!in_array($menu_pos, array('left','center','right'), true)) {
        $menu_pos = 'center';
    }
    $classes[] = 'footer-menu-' . $menu_pos;
    
    return $classes;
}
add_filter('body_class', 'onespace_body_classes');

/**
 * Dark/Light toggle functionality
 */
function onespace_dark_light_toggle() {
    $toggle_mode = get_theme_mod('header_toggle_icon_mode', 'text');
    $light_text = get_theme_mod('header_toggle_icon_light', '☀️');
    $dark_text = get_theme_mod('header_toggle_icon_dark', '🌙');
    $light_image = get_theme_mod('header_toggle_icon_light_image', '');
    $dark_image = get_theme_mod('header_toggle_icon_dark_image', '');
    
    if ($toggle_mode === 'text') {
        $light_content = '<span class="toggle-icon">' . esc_html($light_text) . '</span>';
        $dark_content = '<span class="toggle-icon">' . esc_html($dark_text) . '</span>';
    } else {
        $light_content = $light_image ? '<span class="toggle-icon"><img src="' . esc_url($light_image) . '" alt="' . esc_attr__('Light mode', 'onespace-theme2') . '"></span>' : '';
        $dark_content = $dark_image ? '<span class="toggle-icon"><img src="' . esc_url($dark_image) . '" alt="' . esc_attr__('Dark mode', 'onespace-theme2') . '"></span>' : '';
    }
    
    if ($light_content || $dark_content) {
        echo '<button class="dark-light-toggle" type="button" aria-label="' . esc_attr__('Toggle dark/light mode', 'onespace-theme2') . '">';
        echo '<span class="light-icon" style="display: block;">' . $light_content . '</span>';
        echo '<span class="dark-icon" style="display: none;">' . $dark_content . '</span>';
        echo '</button>';
    }
}

/**
 * Create directory for JavaScript files
 */
function onespace_create_js_directory() {
    $js_dir = ONESPACE_THEME_DIR . '/js';
    if (!file_exists($js_dir)) {
        wp_mkdir_p($js_dir);
    }
}
add_action('after_setup_theme', 'onespace_create_js_directory');

/**
 * Create directory for CSS files
 */
function onespace_create_css_directory() {
    $css_dir = ONESPACE_THEME_DIR . '/css';
    if (!file_exists($css_dir)) {
        wp_mkdir_p($css_dir);
    }
}
add_action('after_setup_theme', 'onespace_create_css_directory');

/**
 * Create directory for includes
 */
function onespace_create_inc_directory() {
    $inc_dir = ONESPACE_THEME_DIR . '/inc';
    if (!file_exists($inc_dir)) {
        wp_mkdir_p($inc_dir);
    }
}
add_action('after_setup_theme', 'onespace_create_inc_directory');

/**
 * Render custom comment form fields based on Customizer
 */
function onespace_render_custom_comment_form($post_id = null) {
    if (!get_theme_mod('comment_form_enable_custom', true)) {
        return false; // indicate not rendered
    }

    $heading     = get_theme_mod('comment_form_heading_text', __('Leave a Comment', 'onespace-theme2'));
    $consentText = get_theme_mod('comment_form_consent_text', __('I agree to the terms and privacy policy.', 'onespace-theme2'));
    $submitText  = get_theme_mod('comment_form_submit_text', __('Post Comment', 'onespace-theme2'));

    $label_comment = get_theme_mod('comment_form_label_comment', __('Comment *', 'onespace-theme2'));
    $label_name    = get_theme_mod('comment_form_label_name', __('Name *', 'onespace-theme2'));
    $label_email   = get_theme_mod('comment_form_label_email', __('Email *', 'onespace-theme2'));
    $label_website = get_theme_mod('comment_form_label_website', __('Website', 'onespace-theme2'));

    $ph_comment = get_theme_mod('comment_form_ph_comment', __('Share your thoughts...', 'onespace-theme2'));
    $ph_name    = get_theme_mod('comment_form_ph_name', __('Your name', 'onespace-theme2'));
    $ph_email   = get_theme_mod('comment_form_ph_email', 'your@email.com');
    $ph_website = get_theme_mod('comment_form_ph_website', 'https://yourwebsite.com');

    $include_website = get_theme_mod('comment_form_include_website', true);
    $extra_json      = get_theme_mod('comment_form_extra_fields_json', '');
    $extras = array();
    if ($extra_json) {
        $decoded = json_decode($extra_json, true);
        if (is_array($decoded)) {
            $extras = $decoded;
        }
    }

    $post_id = $post_id ? intval($post_id) : get_the_ID();
    ?>
    <div class="comment-form-section">
        <h3 class="comment-form-heading"><?php echo esc_html($heading); ?></h3>
        <form class="custom-comment-form" method="post" action="<?php echo esc_url( site_url('/wp-comments-post.php') ); ?>">
            <div class="form-row">
                <label class="checkbox-container">
                    <input type="checkbox" name="privacy_policy" required />
                    <span class="checkmark"></span>
                    <?php echo esc_html($consentText); ?>
                </label>
            </div>

            <div class="form-row">
                <label for="comment"><?php echo esc_html($label_comment); ?></label>
                <textarea name="comment" id="comment" placeholder="<?php echo esc_attr($ph_comment); ?>" required></textarea>
            </div>

            <div class="form-row-group">
                <div class="form-row half">
                    <label for="author"><?php echo esc_html($label_name); ?></label>
                    <input type="text" name="author" id="author" placeholder="<?php echo esc_attr($ph_name); ?>" required />
                </div>
                <div class="form-row half">
                    <label for="email"><?php echo esc_html($label_email); ?></label>
                    <input type="email" name="email" id="email" placeholder="<?php echo esc_attr($ph_email); ?>" required />
                </div>
            </div>

            <?php if ($include_website) : ?>
            <div class="form-row">
                <label for="url"><?php echo esc_html($label_website); ?></label>
                <input type="url" name="url" id="url" placeholder="<?php echo esc_attr($ph_website); ?>" />
            </div>
            <?php endif; ?>

            <?php
            // Render extra fields
            foreach ($extras as $field) {
                if (!is_array($field) || empty($field['type'])) continue;
                $type  = sanitize_key($field['type']);
                $name  = isset($field['name']) ? sanitize_key($field['name']) : '';
                $label = isset($field['label']) ? sanitize_text_field($field['label']) : '';
                $ph    = isset($field['placeholder']) ? esc_attr($field['placeholder']) : '';
                $req   = !empty($field['required']);
                $opts  = isset($field['options']) && is_array($field['options']) ? $field['options'] : array();

                echo '<div class="form-row">';
                if ($label && $type !== 'label' && $type !== 'button') {
                    echo '<label for="extra_' . esc_attr($name) . '">' . esc_html($label) . ($req ? ' *' : '') . '</label>';
                }

                switch ($type) {
                    case 'label':
                        echo '<div class="form-static-label">' . esc_html($label) . '</div>';
                        break;
                    case 'textarea':
                        echo '<textarea name="extra_' . esc_attr($name) . '" id="extra_' . esc_attr($name) . '" placeholder="' . $ph . '" ' . ($req ? 'required' : '') . '></textarea>';
                        break;
                    case 'checkbox':
                        echo '<label class="checkbox-container"><input type="checkbox" name="extra_' . esc_attr($name) . '" ' . ($req ? 'required' : '') . ' /><span class="checkmark"></span> ' . esc_html($label) . '</label>';
                        break;
                    case 'radio':
                        foreach ($opts as $i => $opt) {
                            $id = 'extra_' . $name . '_' . $i;
                            echo '<label class="radio-inline"><input type="radio" name="extra_' . esc_attr($name) . '" id="' . esc_attr($id) . '" value="' . esc_attr($opt) . '" ' . ($req ? 'required' : '') . ' /> ' . esc_html($opt) . '</label> ';
                        }
                        break;
                    case 'select':
                        echo '<select name="extra_' . esc_attr($name) . '" id="extra_' . esc_attr($name) . '" ' . ($req ? 'required' : '') . '>';
                        foreach ($opts as $opt) {
                            echo '<option value="' . esc_attr($opt) . '">' . esc_html($opt) . '</option>';
                        }
                        echo '</select>';
                        break;
                    case 'button':
                        echo '<button type="button" class="btn extra-btn">' . esc_html($label ?: __('Button', 'onespace-theme2')) . '</button>';
                        break;
                    default:
                        // text, email, url, number etc.
                        $input_type = in_array($type, array('text','email','url','number','date','time','tel'), true) ? $type : 'text';
                        echo '<input type="' . esc_attr($input_type) . '" name="extra_' . esc_attr($name) . '" id="extra_' . esc_attr($name) . '" placeholder="' . $ph . '" ' . ($req ? 'required' : '') . ' />';
                        break;
                }

                echo '</div>';
            }
            ?>

            <div class="form-row">
                <label class="checkbox-container">
                    <input type="checkbox" name="save_info" />
                    <span class="checkmark"></span>
                    <?php esc_html_e('Save my name, email, and website in this browser for the next time I comment.', 'onespace-theme2'); ?>
                </label>
            </div>

            <input type="hidden" name="comment_post_ID" value="<?php echo esc_attr($post_id); ?>" />
            <input type="hidden" name="comment_parent" value="0" />
            <?php do_action('comment_form', $post_id); ?>
            <button type="submit" class="comment-submit-btn"><?php echo esc_html($submitText); ?></button>
        </form>
    </div>
    <?php
    return true; // rendered
}

/**
 * AJAX handler for loading blog content
 */
function onespace_load_blog_content() {
    // Check nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'onespace_ajax_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $post_id = intval($_POST['post_id']);
    
    if (!$post_id) {
        wp_send_json_error('Invalid post ID');
        return;
    }
    
    // Get the post
    $post = get_post($post_id);
    
    if (!$post || $post->post_status !== 'publish') {
        wp_send_json_error('Post not found');
        return;
    }
    
    // Set up post data - use $GLOBALS to avoid variable conflict
    $GLOBALS['post'] = $post;
    setup_postdata($post);
    
    ob_start();
    
    // Calculate reading time
    $word_count = str_word_count(strip_tags($post->post_content));
    $reading_time = ceil($word_count / 200); // Average reading speed
    
    // Get author data
    $author_id = $post->post_author;
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_avatar = get_avatar_url($author_id, array('size' => 32));
    
    // Get post view count (you might need a plugin for this, for now we'll use a placeholder)
    $view_count = get_post_meta($post->ID, 'post_views_count', true) ?: rand(50, 500);
    
    ?>
    <article id="post-<?php echo $post->ID; ?>" class="single-post-article ajax-loaded">
        
        <!-- 1. Post Title -->
        <header class="single-post-header">
            <h1 class="single-post-title"><?php echo get_the_title($post); ?></h1>
        </header>

        <!-- 2. Main Image -->
        <?php if (has_post_thumbnail($post->ID)) : ?>
            <div class="single-post-featured-image">
                <?php echo get_the_post_thumbnail($post->ID, 'large', ['alt' => get_the_title($post)]); ?>
            </div>
        <?php endif; ?>

        <!-- 3. Post Meta Information Box -->
        <div class="single-post-meta-box">
            <div class="post-author-info">
                <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="author-avatar">
                <span class="author-name">By <?php echo esc_html($author_name); ?></span>
            </div>
            <div class="post-dates">
                <span class="post-published">
                    <i class="icon-calendar"></i>
                    Published: <?php echo esc_html(get_the_date('M j, Y', $post)); ?>
                </span>
                <span class="post-updated">
                    <i class="icon-clock"></i>
                    Updated: <?php echo esc_html(get_the_modified_date('M j, Y', $post)); ?>
                </span>
            </div>
            <div class="post-stats">
                <span class="post-views">
                    <i class="icon-eye"></i>
                    <?php echo number_format($view_count); ?> views
                </span>
                <span class="reading-time">
                    <i class="icon-time"></i>
                    <?php echo $reading_time; ?> min read
                </span>
                <span class="current-reading-time" id="current-reading-time">
                    <i class="icon-stopwatch"></i>
                    Reading: <span id="reading-timer">0m</span>
                </span>
            </div>
        </div>

        <!-- 4. Post Content -->
        <div class="single-post-content">
            <?php 
            $content = get_the_content(null, false, $post);
            $content = apply_filters('the_content', $content);
            echo $content;
            ?>
        </div>

        <!-- 5. Tags Section -->
        <?php 
        $tags = get_the_tags($post->ID);
        if ($tags) : ?>
            <div class="single-post-tags-section">
                <h3 class="tags-heading">Tags</h3>
                <div class="tags-container">
                    <?php foreach($tags as $tag) : ?>
                        <a href="<?php echo get_tag_link($tag->term_id); ?>" class="tag-item">
                            #<?php echo esc_html($tag->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- 6. About Author Section -->
        <div class="about-author-section">
            <h3 class="about-heading">About Suman</h3>
            <div class="about-content">
                <img src="<?php echo esc_url($author_avatar); ?>" alt="Suman" class="about-author-avatar">
                <div class="about-text">
                    <p>Hi I'm Suman from West Bengal. Driven by my passion for AI and new technologies, I started this blog. Here, you'll find practical tips to earn money from home, honest tool reviews, and easy-to-follow tutorials.</p>
                </div>
            </div>
        </div>

        <!-- 7. Comment Form (Customizer powered) -->
        <?php if ( ! onespace_render_custom_comment_form($post->ID) ) : ?>
            <?php comment_form(array('comment_field' => '<p><textarea name="comment" required></textarea></p>')); ?>
        <?php endif; ?>

        <!-- 8. Previous/Next Post Navigation -->
        <?php
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        if ($prev_post || $next_post) :
        ?>
            <div class="post-navigation-section">
                <div class="nav-posts">
                    <?php if ($prev_post) : ?>
                        <div class="nav-post prev-post">
                            <a href="<?php echo get_permalink($prev_post->ID); ?>" class="nav-link ajax-blog-link" data-post-id="<?php echo $prev_post->ID; ?>">
                                <div class="nav-post-image">
                                    <?php if (has_post_thumbnail($prev_post->ID)) : ?>
                                        <?php echo get_the_post_thumbnail($prev_post->ID, 'thumbnail'); ?>
                                    <?php else : ?>
                                        <div class="no-image">No Image</div>
                                    <?php endif; ?>
                                </div>
                                <div class="nav-post-content">
                                    <span class="nav-label">← Previous Post</span>
                                    <span class="nav-title"><?php echo get_the_title($prev_post); ?></span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($next_post) : ?>
                        <div class="nav-post next-post">
                            <a href="<?php echo get_permalink($next_post->ID); ?>" class="nav-link ajax-blog-link" data-post-id="<?php echo $next_post->ID; ?>">
                                <div class="nav-post-content">
                                    <span class="nav-label">Next Post →</span>
                                    <span class="nav-title"><?php echo get_the_title($next_post); ?></span>
                                </div>
                                <div class="nav-post-image">
                                    <?php if (has_post_thumbnail($next_post->ID)) : ?>
                                        <?php echo get_the_post_thumbnail($next_post->ID, 'thumbnail'); ?>
                                    <?php else : ?>
                                        <div class="no-image">No Image</div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Back to Posts Button -->
        <div class="single-post-navigation">
            <a href="#" class="back-to-home-grid ajax-back-to-grid">
                ← <?php esc_html_e('Back to Posts', 'onespace-theme2'); ?>
            </a>
        </div>

    </article>
    <?php
    
    $content = ob_get_clean();
    wp_reset_postdata();
    
    wp_send_json_success($content);
}
add_action('wp_ajax_load_blog_content', 'onespace_load_blog_content');
add_action('wp_ajax_nopriv_load_blog_content', 'onespace_load_blog_content');

/**
 * Enqueue AJAX script for blog functionality
 */
function onespace_enqueue_ajax_script() {
    if (is_home() || is_front_page() || is_single()) {
        wp_enqueue_script('onespace-ajax-blog', get_template_directory_uri() . '/js/ajax-blog.js', array('jquery'), ONESPACE_THEME_VERSION, true);
        wp_localize_script('onespace-ajax-blog', 'onespace_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('onespace_ajax_nonce'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'onespace_enqueue_ajax_script');