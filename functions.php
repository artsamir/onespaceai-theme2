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
define('ONESPACE_THEME_VERSION', '1.0.0');
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
}
add_action('customize_preview_init', 'onespace_customize_preview_scripts');

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

    // Footer menu letter spacing (em) and padding (rem)
    $footer_menu_letter_spacing = get_theme_mod('footer_menu_letter_spacing', 0);
    $css_vars['--footer-menu-letter-spacing'] = floatval($footer_menu_letter_spacing) . 'em';
    $footer_menu_padding_x = get_theme_mod('footer_menu_padding_x', 0.75);
    $footer_menu_padding_y = get_theme_mod('footer_menu_padding_y', 0.5);
    $css_vars['--footer-menu-padding-x'] = floatval($footer_menu_padding_x) . 'rem';
    $css_vars['--footer-menu-padding-y'] = floatval($footer_menu_padding_y) . 'rem';
    
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