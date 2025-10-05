<?php
/**
 * Header Customizer Settings
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register header customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object
 */
function onespace_header_customizer($wp_customize) {
    
    // Add main header section (onespace_header)
    $wp_customize->add_section('onespace_header', array(
        'title'    => __('Header Settings', 'onespace-theme2'),
        'priority' => 30,
    ));

    // Top-level category sections
    $wp_customize->add_section('onespace_header_menu', array(
        'title'    => __('Header Menu Settings', 'onespace-theme2'),
        'priority' => 31,
    ));

    $wp_customize->add_section('onespace_header_search', array(
        'title'    => __('Header Search Settings', 'onespace-theme2'),
        'priority' => 32,
    ));

    $wp_customize->add_section('onespace_header_theme', array(
        'title'    => __('Dark/ Light Theme Settings', 'onespace-theme2'),
        'priority' => 33,
    ));

    $wp_customize->add_section('onespace_header_toggle', array(
        'title'    => __('Header Toggle settings (Mobile View)', 'onespace-theme2'),
        'priority' => 34,
    ));
    
    // Add Header Settings (Mobile View) section
    $wp_customize->add_section('onespace_header_mobile', array(
        'title'    => __('Header Settings (Mobile View)', 'onespace-theme2'),
        'priority' => 35,
    ));
    
    // Register all header settings and controls
    onespace_register_base_header_settings($wp_customize);
    onespace_register_site_settings($wp_customize);
    onespace_register_menu_settings($wp_customize);
    onespace_register_search_settings($wp_customize);
    onespace_register_dark_light_settings($wp_customize);
    onespace_register_search_result_settings($wp_customize);
}
add_action('customize_register', 'onespace_header_customizer');

/**
 * Tweak core sections: remove Colors, move Header Image controls under Header Settings
 */
function onespace_customize_core_sections($wp_customize) {
    // Remove the default Colors section from the sidebar
    if ($wp_customize->get_section('colors')) {
        $wp_customize->remove_section('colors');
    }

    // Move Header Image related controls into our 'Header Settings' section
    $target_section = 'onespace_header';
    $to_move = array('header_image', 'header_video', 'external_header_video');

    $priority = 15; // place after Header Background Color (priority 10)
    foreach ($to_move as $control_id) {
        $control = $wp_customize->get_control($control_id);
        if ($control) {
            $control->section = $target_section;
            $control->priority = $priority;
            $priority += 1;
        }
    }

    // Move header text color if present
    $header_text = $wp_customize->get_control('header_textcolor');
    if ($header_text) {
        $header_text->section = $target_section;
        $header_text->priority = $priority;
        $priority += 1;
    }

    // Remove the now-empty Header Image section
    if ($wp_customize->get_section('header_image')) {
        $wp_customize->remove_section('header_image');
    }
}
add_action('customize_register', 'onespace_customize_core_sections', 200);

/**
 * Register base header settings (kept in onespace_header section)
 */
function onespace_register_base_header_settings($wp_customize) {
    
    // Header Background Color
    $wp_customize->add_setting('header_background_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_background_color', array(
        'label'    => __('Header Background Color', 'onespace-theme2'),
        'section'  => 'onespace_header',
        'priority' => 10,
    )));
    
    // Header Height
    $wp_customize->add_setting('header_height', array(
        'default'           => 56,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 30, 200); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_height', array(
        'label'       => __('Header Height (px)', 'onespace-theme2'),
        'section'     => 'onespace_header',
        'type'        => 'number',
        'priority'    => 20,
        'input_attrs' => array(
            'min'  => 30,
            'max'  => 200,
            'step' => 1,
        ),
        'description' => __('Set the height of the header in pixels.', 'onespace-theme2'),
    ));
}

/**
 * Register site settings (tagline and logo controls)
 */
function onespace_register_site_settings($wp_customize) {
    
    // Tagline Position
    $wp_customize->add_setting('header_tagline_position', array(
        'default'           => 'below-title',
        'sanitize_callback' => 'onespace_sanitize_tagline_position',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_tagline_position', array(
        'label'    => __('Tagline Position', 'onespace-theme2'),
        'section'  => 'title_tagline',
        'type'     => 'select',
        'priority' => 25,
        'choices'  => array(
            'above-title' => __('Above Title', 'onespace-theme2'),
            'below-title' => __('Below Title', 'onespace-theme2'),
            'left-title'  => __('Left of Title', 'onespace-theme2'),
            'right-title' => __('Right of Title', 'onespace-theme2'),
            'hidden'      => __('Hidden', 'onespace-theme2'),
        ),
    ));
    
    // Tagline Gap
    $wp_customize->add_setting('header_tagline_gap', array(
        'default'           => 1,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 10); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_tagline_gap', array(
        'label'       => __('Tagline Gap (rem)', 'onespace-theme2'),
        'section'     => 'title_tagline',
        'type'        => 'number',
        'priority'    => 30,
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 10,
            'step' => 0.1,
        ),
    ));
    
    // Tagline Offset X
    $wp_customize->add_setting('header_tagline_offset_x', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, -50, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_tagline_offset_x', array(
        'label'       => __('Tagline Offset X (rem)', 'onespace-theme2'),
        'section'     => 'title_tagline',
        'type'        => 'number',
        'priority'    => 35,
        'input_attrs' => array(
            'min'  => -50,
            'max'  => 50,
            'step' => 0.1,
        ),
    ));
    
    // Tagline Offset Y
    $wp_customize->add_setting('header_tagline_offset_y', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, -50, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_tagline_offset_y', array(
        'label'       => __('Tagline Offset Y (rem)', 'onespace-theme2'),
        'section'     => 'title_tagline',
        'type'        => 'number',
        'priority'    => 40,
        'input_attrs' => array(
            'min'  => -50,
            'max'  => 50,
            'step' => 0.1,
        ),
    ));
    
    // Logo Width Value
    $wp_customize->add_setting('header_logo_width_value', array(
        'default'           => 200,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 1, 1000); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_logo_width_value', array(
        'label'       => __('Logo Width Value', 'onespace-theme2'),
        'section'     => 'title_tagline',
        'type'        => 'number',
        'priority'    => 45,
        'input_attrs' => array(
            'min' => 1,
            'max' => 1000,
        ),
    ));
    
    // Logo Width Unit
    $wp_customize->add_setting('header_logo_width_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'onespace_sanitize_dimension_unit',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_logo_width_unit', array(
        'label'    => __('Logo Width Unit', 'onespace-theme2'),
        'section'  => 'title_tagline',
        'type'     => 'select',
        'priority' => 50,
        'choices'  => array(
            'px'  => 'px',
            'em'  => 'em',
            'rem' => 'rem',
            '%'   => '%',
            'vh'  => 'vh',
            'vw'  => 'vw',
        ),
    ));
    
    // Logo Height Value
    $wp_customize->add_setting('header_logo_height_value', array(
        'default'           => 80,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 1, 500); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_logo_height_value', array(
        'label'       => __('Logo Height Value', 'onespace-theme2'),
        'section'     => 'title_tagline',
        'type'        => 'number',
        'priority'    => 55,
        'input_attrs' => array(
            'min' => 1,
            'max' => 500,
        ),
    ));
    
    // Logo Height Unit
    $wp_customize->add_setting('header_logo_height_unit', array(
        'default'           => 'px',
        'sanitize_callback' => 'onespace_sanitize_dimension_unit',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_logo_height_unit', array(
        'label'    => __('Logo Height Unit', 'onespace-theme2'),
        'section'  => 'title_tagline',
        'type'     => 'select',
        'priority' => 56,
        'choices'  => array(
            'px'  => 'px',
            'em'  => 'em',
            'rem' => 'rem',
            '%'   => '%',
            'vh'  => 'vh',
            'vw'  => 'vw',
        ),
    ));
    
    // (Removed Mobile Logo and related mobile controls from Site Identity per request)
}

/**
 * Register menu settings
 */
function onespace_register_menu_settings($wp_customize) {
    
    // Menu Text Color
    $wp_customize->add_setting('header_menu_text_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_menu_text_color', array(
        'label'    => __('Menu Text Color', 'onespace-theme2'),
    'section'  => 'onespace_header_menu',
        'priority' => 10,
    )));
    
    // Menu Background Hover
    $wp_customize->add_setting('header_menu_bg_hover', array(
        'default'           => 'rgba(0, 0, 0, 0.1)',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_menu_bg_hover', array(
        'label'    => __('Menu Background Hover', 'onespace-theme2'),
    'section'  => 'onespace_header_menu',
        'priority' => 20,
    )));
    
    // Menu Padding X
    $wp_customize->add_setting('header_menu_padding_x', array(
        'default'           => 1,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 5); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_padding_x', array(
        'label'       => __('Menu Padding X (rem)', 'onespace-theme2'),
    'section'     => 'onespace_header_menu',
        'type'        => 'number',
        'priority'    => 30,
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 5,
            'step' => 0.1,
        ),
    ));
    
    // Menu Padding Y
    $wp_customize->add_setting('header_menu_padding_y', array(
        'default'           => 0.5,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 5); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_padding_y', array(
        'label'       => __('Menu Padding Y (rem)', 'onespace-theme2'),
    'section'     => 'onespace_header_menu',
        'type'        => 'number',
        'priority'    => 40,
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 5,
            'step' => 0.1,
        ),
    ));
    
    // Menu Radius
    $wp_customize->add_setting('header_menu_radius', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_radius', array(
        'label'       => __('Menu Border Radius (px)', 'onespace-theme2'),
    'section'     => 'onespace_header_menu',
        'type'        => 'number',
        'priority'    => 50,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
        ),
    ));
    
    // Menu Font Size
    $wp_customize->add_setting('header_menu_font_size', array(
        'default'           => 1,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0.5, 3); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_font_size', array(
        'label'       => __('Menu Font Size (rem)', 'onespace-theme2'),
    'section'     => 'onespace_header_menu',
        'type'        => 'number',
        'priority'    => 60,
        'input_attrs' => array(
            'min'  => 0.5,
            'max'  => 3,
            'step' => 0.1,
        ),
    ));
    
    // Menu Font Weight
    $wp_customize->add_setting('header_menu_font_weight', array(
        'default'           => '400',
        'sanitize_callback' => 'onespace_sanitize_font_weight',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_font_weight', array(
        'label'    => __('Menu Font Weight', 'onespace-theme2'),
    'section'  => 'onespace_header_menu',
        'type'     => 'select',
        'priority' => 70,
        'choices'  => array(
            '100' => '100',
            '200' => '200',
            '300' => '300',
            '400' => '400 (Normal)',
            '500' => '500',
            '600' => '600',
            '700' => '700 (Bold)',
            '800' => '800',
            '900' => '900',
        ),
    ));
    
    // Menu Font Family
    $wp_customize->add_setting('header_menu_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_font_family',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_font_family', array(
        'label'       => __('Menu Font Family', 'onespace-theme2'),
    'section'     => 'onespace_header_menu',
        'type'        => 'text',
        'priority'    => 80,
        'description' => __('Enter font family name or leave empty for theme default', 'onespace-theme2'),
    ));
    
    // Menu Font Style
    $wp_customize->add_setting('header_menu_font_style', array(
        'default'           => 'normal',
        'sanitize_callback' => 'onespace_sanitize_font_style',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_font_style', array(
        'label'    => __('Menu Font Style', 'onespace-theme2'),
    'section'  => 'onespace_header_menu',
        'type'     => 'select',
        'priority' => 90,
        'choices'  => array(
            'normal'  => __('Normal', 'onespace-theme2'),
            'italic'  => __('Italic', 'onespace-theme2'),
            'oblique' => __('Oblique', 'onespace-theme2'),
        ),
    ));
    
    // Menu Letter Spacing
    $wp_customize->add_setting('header_menu_letter_spacing', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, -2, 2); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_letter_spacing', array(
        'label'       => __('Menu Letter Spacing (em)', 'onespace-theme2'),
    'section'     => 'onespace_header_menu',
        'type'        => 'number',
        'priority'    => 100,
        'input_attrs' => array(
            'min'  => -2,
            'max'  => 2,
            'step' => 0.01,
        ),
    ));
    
    // Menu Line Height
    $wp_customize->add_setting('header_menu_line_height', array(
        'default'           => 1.5,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 1, 3); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_line_height', array(
        'label'       => __('Menu Line Height', 'onespace-theme2'),
    'section'     => 'onespace_header_menu',
        'type'        => 'number',
        'priority'    => 110,
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 3,
            'step' => 0.1,
        ),
    ));
    
    // Menu Offset Left
    $wp_customize->add_setting('header_menu_offset_left', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, -50, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_offset_left', array(
        'label'       => __('Menu Offset Left (rem)', 'onespace-theme2'),
    'section'     => 'onespace_header_menu',
        'type'        => 'number',
        'priority'    => 120,
        'input_attrs' => array(
            'min'  => -50,
            'max'  => 50,
            'step' => 0.1,
        ),
    ));
    
    // Menu Offset Right
    $wp_customize->add_setting('header_menu_offset_right', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, -50, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_menu_offset_right', array(
        'label'       => __('Menu Offset Right (rem)', 'onespace-theme2'),
    'section'     => 'onespace_header_menu',
        'type'        => 'number',
        'priority'    => 130,
        'input_attrs' => array(
            'min'  => -50,
            'max'  => 50,
            'step' => 0.1,
        ),
    ));
    
    // Submenu Background
    $wp_customize->add_setting('header_submenu_bg', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_submenu_bg', array(
        'label'    => __('Submenu Background', 'onespace-theme2'),
    'section'  => 'onespace_header_menu',
        'priority' => 140,
    )));
    
    // Submenu Hover Background
    $wp_customize->add_setting('header_submenu_hover_bg', array(
        'default'           => 'rgba(0, 0, 0, 0.05)',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_submenu_hover_bg', array(
        'label'    => __('Submenu Hover Background', 'onespace-theme2'),
    'section'  => 'onespace_header_menu',
        'priority' => 150,
    )));
    
    // Submenu Text Color
    $wp_customize->add_setting('header_submenu_text_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_submenu_text_color', array(
        'label'    => __('Submenu Text Color', 'onespace-theme2'),
    'section'  => 'onespace_header_menu',
        'priority' => 160,
    )));
}

/**
 * Register search settings
 */
function onespace_register_search_settings($wp_customize) {
    
    // Search Width
    $wp_customize->add_setting('header_search_width', array(
        'default'           => 200,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 100, 500); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_width', array(
        'label'       => __('Search Width (px)', 'onespace-theme2'),
    'section'     => 'onespace_header_search',
        'type'        => 'number',
        'priority'    => 200,
        'input_attrs' => array(
            'min' => 100,
            'max' => 500,
        ),
    ));
    
    // Search Height
    $wp_customize->add_setting('header_search_height', array(
        'default'           => 40,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 20, 80); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_height', array(
        'label'       => __('Search Height (px)', 'onespace-theme2'),
    'section'     => 'onespace_header_search',
        'type'        => 'number',
        'priority'    => 210,
        'input_attrs' => array(
            'min' => 20,
            'max' => 80,
        ),
    ));
    
    // Search Font Size
    $wp_customize->add_setting('header_search_font_size', array(
        'default'           => 1,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0.5, 2); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_font_size', array(
        'label'       => __('Search Font Size (rem)', 'onespace-theme2'),
    'section'     => 'onespace_header_search',
        'type'        => 'number',
        'priority'    => 220,
        'input_attrs' => array(
            'min'  => 0.5,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));
    
    // Search Font Family
    $wp_customize->add_setting('header_search_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_font_family',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_font_family', array(
        'label'       => __('Search Font Family', 'onespace-theme2'),
        'section'     => 'onespace_header_search',
        'type'        => 'text',
        'priority'    => 230,
        'description' => __('Enter font family name or leave empty for theme default', 'onespace-theme2'),
    ));
    
    // Search Placeholder
    $wp_customize->add_setting('header_search_placeholder', array(
        'default'           => __('Search...', 'onespace-theme2'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_placeholder', array(
        'label'    => __('Search Placeholder Text', 'onespace-theme2'),
    'section'  => 'onespace_header_search',
        'type'     => 'text',
        'priority' => 240,
    ));
    
    // Search Placeholder Color
    $wp_customize->add_setting('header_search_placeholder_color', array(
        'default'           => '#666666',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_search_placeholder_color', array(
        'label'    => __('Search Placeholder Color', 'onespace-theme2'),
    'section'  => 'onespace_header_search',
        'priority' => 250,
    )));
    
    // Search Background
    $wp_customize->add_setting('header_search_bg', array(
        'default'           => '#f8f9fa',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_search_bg', array(
        'label'    => __('Search Background', 'onespace-theme2'),
    'section'  => 'onespace_header_search',
        'priority' => 260,
    )));
    
    // Search Text Color
    $wp_customize->add_setting('header_search_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_search_color', array(
        'label'    => __('Search Text Color', 'onespace-theme2'),
    'section'  => 'onespace_header_search',
        'priority' => 270,
    )));
    
    // Search Border Radius
    $wp_customize->add_setting('header_search_radius', array(
        'default'           => 4,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_radius', array(
        'label'       => __('Search Border Radius (px)', 'onespace-theme2'),
    'section'     => 'onespace_header_search',
        'type'        => 'number',
        'priority'    => 280,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
        ),
    ));
    
    // Search Border Width
    $wp_customize->add_setting('header_search_border_width', array(
        'default'           => 1,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 10); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_border_width', array(
        'label'       => __('Search Border Width (px)', 'onespace-theme2'),
    'section'     => 'onespace_header_search',
        'type'        => 'number',
        'priority'    => 290,
        'input_attrs' => array(
            'min' => 0,
            'max' => 10,
        ),
    ));
    
    // Search Border Color
    $wp_customize->add_setting('header_search_border_color', array(
        'default'           => '#dddddd',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_search_border_color', array(
        'label'    => __('Search Border Color', 'onespace-theme2'),
    'section'  => 'onespace_header_search',
        'priority' => 300,
    )));
}

/**
 * Register dark/light settings
 */
function onespace_register_dark_light_settings($wp_customize) {
    
    // Mobile icon controls moved to Header Toggle Settings section
    
    // Mobile Logo Settings
    $wp_customize->add_setting('mobile_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'mobile_logo', array(
        'label'       => __('Mobile Logo', 'onespace-theme2'),
        'description' => __('Upload a logo specifically for mobile devices. If not set, desktop logo will be used.', 'onespace-theme2'),
        'section'     => 'onespace_header_mobile',
        'mime_type'   => 'image',
        'priority'    => 1,
    )));
    
    // Mobile Logo Width Value
    $wp_customize->add_setting('mobile_logo_width_value', array(
        'default'           => 120,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 20, 500); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_logo_width_value', array(
        'label'       => __('Mobile Logo Width Value', 'onespace-theme2'),
        'section'     => 'onespace_header_mobile',
        'type'        => 'number',
        'priority'    => 2,
        'input_attrs' => array('min' => 20, 'max' => 500, 'step' => 1),
    ));
    
    // Mobile Logo Width Unit
    $wp_customize->add_setting('mobile_logo_width_unit', array(
        'default'           => 'px',
        'sanitize_callback' => function($value) {
            $allowed = array('px', '%', 'em', 'rem', 'vw');
            return in_array($value, $allowed) ? $value : 'px';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_logo_width_unit', array(
        'label'   => __('Mobile Logo Width Unit', 'onespace-theme2'),
        'section' => 'onespace_header_mobile',
        'type'    => 'select',
        'priority' => 3,
        'choices' => array(
            'px'  => __('Pixels (px)', 'onespace-theme2'),
            '%'   => __('Percentage (%)', 'onespace-theme2'),
            'em'  => __('Em (em)', 'onespace-theme2'),
            'rem' => __('Rem (rem)', 'onespace-theme2'),
            'vw'  => __('Viewport Width (vw)', 'onespace-theme2'),
        ),
    ));
    
    // Mobile Logo Height Value
    $wp_customize->add_setting('mobile_logo_height_value', array(
        'default'           => 40,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 20, 200); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_logo_height_value', array(
        'label'       => __('Mobile Logo Height Value', 'onespace-theme2'),
        'section'     => 'onespace_header_mobile',
        'type'        => 'number',
        'priority'    => 4,
        'input_attrs' => array('min' => 20, 'max' => 200, 'step' => 1),
    ));
    
    // Mobile Logo Height Unit
    $wp_customize->add_setting('mobile_logo_height_unit', array(
        'default'           => 'px',
        'sanitize_callback' => function($value) {
            $allowed = array('px', '%', 'em', 'rem', 'vh', 'auto');
            return in_array($value, $allowed) ? $value : 'px';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_logo_height_unit', array(
        'label'   => __('Mobile Logo Height Unit', 'onespace-theme2'),
        'section' => 'onespace_header_mobile',
        'type'    => 'select',
        'priority' => 5,
        'choices' => array(
            'px'   => __('Pixels (px)', 'onespace-theme2'),
            '%'    => __('Percentage (%)', 'onespace-theme2'),
            'em'   => __('Em (em)', 'onespace-theme2'),
            'rem'  => __('Rem (rem)', 'onespace-theme2'),
            'vh'   => __('Viewport Height (vh)', 'onespace-theme2'),
            'auto' => __('Auto', 'onespace-theme2'),
        ),
    ));
    
    // Show Logo on Mobile
    $wp_customize->add_setting('show_mobile_logo', array(
        'default'           => true,
        'sanitize_callback' => function($value) { return (bool) $value; },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('show_mobile_logo', array(
        'label'   => __('Show Logo on Mobile', 'onespace-theme2'),
        'section' => 'onespace_header_mobile',
        'type'    => 'checkbox',
        'priority' => 6,
    ));
    
    // Show Tagline on Mobile
    $wp_customize->add_setting('show_mobile_tagline', array(
        'default'           => false,
        'sanitize_callback' => function($value) { return (bool) $value; },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('show_mobile_tagline', array(
        'label'   => __('Show Tagline on Mobile', 'onespace-theme2'),
        'section' => 'onespace_header_mobile',
        'type'    => 'checkbox',
        'priority' => 7,
    ));
    
    // Mobile Logo Alignment
    $wp_customize->add_setting('mobile_logo_alignment', array(
        'default'           => 'left',
        'sanitize_callback' => function($value) {
            $allowed = array('left', 'center', 'right');
            return in_array($value, $allowed) ? $value : 'left';
        },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_logo_alignment', array(
        'label'   => __('Mobile Logo Alignment', 'onespace-theme2'),
        'section' => 'onespace_header_mobile',
        'type'    => 'select',
        'priority' => 8,
        'choices' => array(
            'left'   => __('Left', 'onespace-theme2'),
            'center' => __('Center', 'onespace-theme2'),
            'right'  => __('Right', 'onespace-theme2'),
        ),
    ));
    
    // Mobile Header Spacing Controls
    $wp_customize->add_setting('mobile_header_padding_top', array(
        'default'           => 8,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_header_padding_top', array(
        'label'       => __('Mobile Header Padding Top (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_mobile',
        'type'        => 'number',
        'priority'    => 10,
        'input_attrs' => array('min' => 0, 'max' => 50, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_header_padding_bottom', array(
        'default'           => 8,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_header_padding_bottom', array(
        'label'       => __('Mobile Header Padding Bottom (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_mobile',
        'type'        => 'number',
        'priority'    => 11,
        'input_attrs' => array('min' => 0, 'max' => 50, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_header_padding_left', array(
        'default'           => 16,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_header_padding_left', array(
        'label'       => __('Mobile Header Padding Left (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_mobile',
        'type'        => 'number',
        'priority'    => 12,
        'input_attrs' => array('min' => 0, 'max' => 50, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_header_padding_right', array(
        'default'           => 16,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_header_padding_right', array(
        'label'       => __('Mobile Header Padding Right (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_mobile',
        'type'        => 'number',
        'priority'    => 13,
        'input_attrs' => array('min' => 0, 'max' => 50, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_header_margin_top', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 30); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_header_margin_top', array(
        'label'       => __('Mobile Header Margin Top (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_mobile',
        'type'        => 'number',
        'priority'    => 14,
        'input_attrs' => array('min' => 0, 'max' => 30, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_header_margin_bottom', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 30); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_header_margin_bottom', array(
        'label'       => __('Mobile Header Margin Bottom (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_mobile',
        'type'        => 'number',
        'priority'    => 14,
        'input_attrs' => array('min' => 0, 'max' => 30, 'step' => 1),
    ));
    
    // Toggle Icon Mode
    $wp_customize->add_setting('header_toggle_icon_mode', array(
        'default'           => 'text',
        'sanitize_callback' => 'onespace_sanitize_toggle_icon_mode',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_toggle_icon_mode', array(
        'label'    => __('Toggle Icon Mode', 'onespace-theme2'),
    'section'  => 'onespace_header_theme',
        'type'     => 'select',
        'priority' => 400,
        'choices'  => array(
            'text'  => __('Text', 'onespace-theme2'),
            'image' => __('Image', 'onespace-theme2'),
        ),
    ));
    
    // Toggle Icon Light (Text)
    $wp_customize->add_setting('header_toggle_icon_light', array(
        'default'           => '☀️',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_toggle_icon_light', array(
        'label'    => __('Light Mode Icon (Text)', 'onespace-theme2'),
    'section'  => 'onespace_header_theme',
        'type'     => 'text',
        'priority' => 410,
    ));
    
    // Toggle Icon Dark (Text)
    $wp_customize->add_setting('header_toggle_icon_dark', array(
        'default'           => '🌙',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_toggle_icon_dark', array(
        'label'    => __('Dark Mode Icon (Text)', 'onespace-theme2'),
    'section'  => 'onespace_header_theme',
        'type'     => 'text',
        'priority' => 420,
    ));
    
    // Toggle Icon Light Image
    $wp_customize->add_setting('header_toggle_icon_light_image', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_image',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_toggle_icon_light_image', array(
        'label'    => __('Light Mode Icon (Image)', 'onespace-theme2'),
    'section'  => 'onespace_header_theme',
        'priority' => 430,
    )));
    
    // Toggle Icon Dark Image
    $wp_customize->add_setting('header_toggle_icon_dark_image', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_image',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_toggle_icon_dark_image', array(
        'label'    => __('Dark Mode Icon (Image)', 'onespace-theme2'),
    'section'  => 'onespace_header_theme',
        'priority' => 440,
    )));
    
    // Toggle Icon Color
    $wp_customize->add_setting('header_toggle_icon_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_toggle_icon_color', array(
        'label'    => __('Toggle Icon Color', 'onespace-theme2'),
        'section'  => 'onespace_header_toggle',
        'priority' => 450,
    )));
    
    // Mobile Icon Styling Controls
    $wp_customize->add_setting('mobile_dark_light_icon_size', array(
        'default'           => 16,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 8, 48); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_dark_light_icon_size', array(
        'label'       => __('Dark/Light Icon Size (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_toggle',
        'type'        => 'number',
        'priority'    => 451,
        'input_attrs' => array('min' => 8, 'max' => 48, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_dark_light_padding', array(
        'default'           => 8,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 30); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_dark_light_padding', array(
        'label'       => __('Dark/Light Padding (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_toggle',
        'type'        => 'number',
        'priority'    => 452,
        'input_attrs' => array('min' => 0, 'max' => 30, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_dark_light_margin', array(
        'default'           => 4,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 20); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_dark_light_margin', array(
        'label'       => __('Dark/Light Margin (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_toggle',
        'type'        => 'number',
        'priority'    => 453,
        'input_attrs' => array('min' => 0, 'max' => 20, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_search_icon_size', array(
        'default'           => 16,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 8, 48); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_search_icon_size', array(
        'label'       => __('Search Icon Size (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_toggle',
        'type'        => 'number',
        'priority'    => 454,
        'input_attrs' => array('min' => 8, 'max' => 48, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_search_padding', array(
        'default'           => 8,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 30); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_search_padding', array(
        'label'       => __('Search Padding (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_toggle',
        'type'        => 'number',
        'priority'    => 455,
        'input_attrs' => array('min' => 0, 'max' => 30, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_search_margin', array(
        'default'           => 4,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 20); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_search_margin', array(
        'label'       => __('Search Margin (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_toggle',
        'type'        => 'number',
        'priority'    => 456,
        'input_attrs' => array('min' => 0, 'max' => 20, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_menu_icon_size', array(
        'default'           => 18,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 8, 48); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_menu_icon_size', array(
        'label'       => __('Menu Toggle Icon Size (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_toggle',
        'type'        => 'number',
        'priority'    => 457,
        'input_attrs' => array('min' => 8, 'max' => 48, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_menu_padding', array(
        'default'           => 8,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 30); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_menu_padding', array(
        'label'       => __('Menu Toggle Padding (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_toggle',
        'type'        => 'number',
        'priority'    => 458,
        'input_attrs' => array('min' => 0, 'max' => 30, 'step' => 1),
    ));
    
    $wp_customize->add_setting('mobile_menu_margin', array(
        'default'           => 4,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 0, 20); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('mobile_menu_margin', array(
        'label'       => __('Menu Toggle Margin (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_toggle',
        'type'        => 'number',
        'priority'    => 459,
        'input_attrs' => array('min' => 0, 'max' => 20, 'step' => 1),
    ));
    
    // Toggle Background Color (moved to Dark/ Light Theme Settings)
    $wp_customize->add_setting('header_toggle_bg_color', array(
        'default'           => 'transparent',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_toggle_bg_color', array(
        'label'    => __('Toggle Background Color', 'onespace-theme2'),
        'section'  => 'onespace_header_theme',
        'priority' => 465,
    )));
    
    // Toggle Icon Width
    $wp_customize->add_setting('header_toggle_icon_width', array(
        'default'           => 24,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 16, 64); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_toggle_icon_width', array(
        'label'       => __('Toggle Icon Width (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_theme',
        'type'        => 'number',
        'priority'    => 470,
        'input_attrs' => array(
            'min' => 16,
            'max' => 64,
        ),
    ));
    
    // Toggle Icon Height
    $wp_customize->add_setting('header_toggle_icon_height', array(
        'default'           => 24,
        'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, 16, 64); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_toggle_icon_height', array(
        'label'       => __('Toggle Icon Height (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_theme',
        'type'        => 'number',
        'priority'    => 480,
        'input_attrs' => array(
            'min' => 16,
            'max' => 64,
        ),
    ));
    
    // Toggle Icon Padding
    $wp_customize->add_setting('header_toggle_icon_padding', array(
        'default'           => 0.5,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 2); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_toggle_icon_padding', array(
        'label'       => __('Toggle Icon Padding (rem)', 'onespace-theme2'),
        'section'     => 'onespace_header_theme',
        'type'        => 'number',
        'priority'    => 490,
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));
    
    // Toggle Icon Radius
    $wp_customize->add_setting('header_toggle_icon_radius', array(
        'default'           => 4,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_toggle_icon_radius', array(
        'label'       => __('Toggle Icon Border Radius (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_theme',
        'type'        => 'number',
        'priority'    => 500,
        'input_attrs' => array(
            'min' => 0,
            'max' => 50,
        ),
    ));

    // Toggle Border Width
    $wp_customize->add_setting('header_toggle_border_width', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 10); },
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('header_toggle_border_width', array(
        'label'       => __('Toggle Border Width (px)', 'onespace-theme2'),
        'section'     => 'onespace_header_theme',
        'type'        => 'number',
        'priority'    => 501,
        'input_attrs' => array(
            'min' => 0,
            'max' => 10,
            'step' => 0.5,
        ),
    ));

    // Toggle Border Color
    $wp_customize->add_setting('header_toggle_border_color', array(
        'default'           => 'rgba(0,0,0,0.15)',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_toggle_border_color', array(
        'label'    => __('Toggle Border Color', 'onespace-theme2'),
        'section'  => 'onespace_header_theme',
        'priority' => 502,
    )));

    // Toggle Offset X
    $wp_customize->add_setting('header_toggle_offset_x', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, -50, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_toggle_offset_x', array(
        'label'       => __('Toggle Offset X (rem)', 'onespace-theme2'),
        'section'     => 'onespace_header_theme',
        'type'        => 'number',
        'priority'    => 505,
        'input_attrs' => array(
            'min'  => -50,
            'max'  => 50,
            'step' => 0.1,
        ),
    ));

    // Toggle Offset Y
    $wp_customize->add_setting('header_toggle_offset_y', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, -50, 50); },
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_toggle_offset_y', array(
        'label'       => __('Toggle Offset Y (rem)', 'onespace-theme2'),
        'section'     => 'onespace_header_theme',
        'type'        => 'number',
        'priority'    => 506,
        'input_attrs' => array(
            'min'  => -50,
            'max'  => 50,
            'step' => 0.1,
        ),
    ));
    
    // Toggle Margins
    $margins = array(
        'header_toggle_margin_left'   => __('Toggle Margin Left (rem)', 'onespace-theme2'),
        'header_toggle_margin_right'  => __('Toggle Margin Right (rem)', 'onespace-theme2'),
        'header_toggle_margin_top'    => __('Toggle Margin Top (rem)', 'onespace-theme2'),
        'header_toggle_margin_bottom' => __('Toggle Margin Bottom (rem)', 'onespace-theme2'),
    );
    
    $priority = 510;
    foreach ($margins as $setting => $label) {
        $wp_customize->add_setting($setting, array(
            'default'           => 0,
            'sanitize_callback' => function($value) { return onespace_sanitize_float($value, -5, 5); },
            'transport'         => 'postMessage',
        ));
        
        $wp_customize->add_control($setting, array(
            'label'       => $label,
            'section'     => 'onespace_header_toggle',
            'type'        => 'number',
            'priority'    => $priority,
            'input_attrs' => array(
                'min'  => -5,
                'max'  => 5,
                'step' => 0.1,
            ),
        ));
        
        $priority += 10;
    }
}

// Duplicate mobile settings function removed - settings are now in onespace_register_dark_light_settings

/**
 * Register search result control settings
 */
function onespace_register_search_result_settings($wp_customize) {
    
    // Search Scope
    $wp_customize->add_setting('header_search_scope', array(
        'default'           => 'site',
        'sanitize_callback' => 'onespace_sanitize_search_scope',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_scope', array(
        'label'    => __('Search Scope', 'onespace-theme2'),
    'section'  => 'onespace_header_search',
        'type'     => 'select',
        'priority' => 600,
        'choices'  => array(
            'site'   => __('Site Search', 'onespace-theme2'),
            'custom' => __('Custom URL', 'onespace-theme2'),
            'manual' => __('Manual Post IDs', 'onespace-theme2'),
        ),
    ));
    
    // Search Custom URL
    $wp_customize->add_setting('header_search_custom_url', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_url',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_custom_url', array(
        'label'       => __('Custom Search URL', 'onespace-theme2'),
    'section'     => 'onespace_header_search',
        'type'        => 'url',
        'priority'    => 610,
        'description' => __('Enter the custom URL for search results', 'onespace-theme2'),
    ));
    
    // Search Manual IDs
    $wp_customize->add_setting('header_search_manual_ids', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_comma_separated_ids',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('header_search_manual_ids', array(
        'label'       => __('Manual Post IDs', 'onespace-theme2'),
    'section'     => 'onespace_header_search',
        'type'        => 'text',
        'priority'    => 620,
        'description' => __('Enter comma-separated post IDs to search within (e.g., 1,2,3)', 'onespace-theme2'),
    ));
}