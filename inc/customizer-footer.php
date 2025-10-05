<?php
/**
 * Footer Customizer Settings
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register footer customizer settings
 */
function onespace_footer_customizer($wp_customize) {
    // Section
    $wp_customize->add_section('onespace_footer', array(
        'title'    => __('Footer Settings', 'onespace-theme2'),
        'priority' => 80,
    ));

    // Copyright text (supports placeholders)
    $wp_customize->add_setting('footer_copyright_text', array(
        'default'           => '© {year} {site}. All rights reserved.',
        'sanitize_callback' => 'onespace_sanitize_text_html',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('footer_copyright_text', array(
        'label'       => __('Copyright Text', 'onespace-theme2'),
        'section'     => 'onespace_footer',
        'type'        => 'textarea',
        'priority'    => 10,
        'description' => __('Placeholders: {year}, {site}', 'onespace-theme2'),
    ));

    // Font family
    $wp_customize->add_setting('footer_credits_font_family', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_font_family',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('footer_credits_font_family', array(
        'label'       => __('Font Family (Copyright)', 'onespace-theme2'),
        'section'     => 'onespace_footer',
        'type'        => 'text',
        'priority'    => 20,
        'description' => __('Leave empty to inherit theme default', 'onespace-theme2'),
    ));

    // Font size (rem)
    $wp_customize->add_setting('footer_credits_font_size', array(
        'default'           => 0.85,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0.6, 2.0); },
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('footer_credits_font_size', array(
        'label'       => __('Font Size (rem)', 'onespace-theme2'),
        'section'     => 'onespace_footer',
        'type'        => 'number',
        'priority'    => 30,
        'input_attrs' => array('min' => 0.6, 'max' => 2.0, 'step' => 0.05),
    ));

    // Text color
    $wp_customize->add_setting('footer_credits_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_credits_color', array(
        'label'    => __('Text Color (Copyright)', 'onespace-theme2'),
        'section'  => 'onespace_footer',
        'priority' => 40,
    )));

    // Footer background color
    $wp_customize->add_setting('footer_background_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_background_color', array(
        'label'    => __('Footer Background Color', 'onespace-theme2'),
        'section'  => 'onespace_footer',
        'priority' => 50,
    )));

    // Copyright position
    $wp_customize->add_setting('footer_copyright_position', array(
        'default'           => 'center',
        'sanitize_callback' => function($value) { return onespace_sanitize_select($value, array('left','center','right'), 'left'); },
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('footer_copyright_position', array(
        'label'    => __('Copyright Position', 'onespace-theme2'),
        'section'  => 'onespace_footer',
        'type'     => 'select',
        'priority' => 60,
        'choices'  => array(
            'left'   => __('Left', 'onespace-theme2'),
            'center' => __('Center', 'onespace-theme2'),
            'right'  => __('Right', 'onespace-theme2'),
        ),
    ));

    // Footer menu alignment
    $wp_customize->add_setting('footer_menu_position', array(
        'default'           => 'center',
        'sanitize_callback' => function($value){ return onespace_sanitize_select($value, array('left','center','right'), 'center'); },
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('footer_menu_position', array(
        'label'    => __('Footer Menu Position', 'onespace-theme2'),
        'section'  => 'onespace_footer',
        'type'     => 'select',
        'priority' => 65,
        'choices'  => array(
            'left'   => __('Left', 'onespace-theme2'),
            'center' => __('Center', 'onespace-theme2'),
            'right'  => __('Right', 'onespace-theme2'),
        ),
    ));

    // Footer menu background color (for debug/styling)
    $wp_customize->add_setting('footer_menu_background_color', array(
        'default'           => 'transparent',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_menu_background_color', array(
        'label'    => __('Footer Menu Background Color', 'onespace-theme2'),
        'section'  => 'onespace_footer',
        'priority' => 67,
        'description' => __('Background color for the footer menu container. Use transparent to remove background.', 'onespace-theme2'),
    )));

    // Footer menu positioning (offsets in px)
    $offsets = array(
        'footer_menu_offset_top'    => __('Footer Menu Offset Top (px)', 'onespace-theme2'),
        'footer_menu_offset_right'  => __('Footer Menu Offset Right (px)', 'onespace-theme2'),
        'footer_menu_offset_bottom' => __('Footer Menu Offset Bottom (px)', 'onespace-theme2'),
        'footer_menu_offset_left'   => __('Footer Menu Offset Left (px)', 'onespace-theme2'),
    );
    $priority = 70;
    foreach ($offsets as $id => $label) {
        $wp_customize->add_setting($id, array(
            'default'           => 0,
            'sanitize_callback' => function($value) { return onespace_sanitize_integer($value, -200, 200); },
            'transport'         => 'postMessage',
        ));
        $wp_customize->add_control($id, array(
            'label'       => $label,
            'section'     => 'onespace_footer',
            'type'        => 'number',
            'priority'    => $priority,
            'input_attrs' => array('min' => -200, 'max' => 200),
        ));
        $priority += 5;
    }

    // Footer menu colors
    $wp_customize->add_setting('footer_menu_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_menu_text_color', array(
        'label'    => __('Footer Menu Text Color', 'onespace-theme2'),
        'section'  => 'onespace_footer',
        'priority' => 90,
    )));

    $wp_customize->add_setting('footer_menu_hover_text_color', array(
        'default'           => '#e9ecef',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_menu_hover_text_color', array(
        'label'    => __('Footer Menu Hover Text Color', 'onespace-theme2'),
        'section'  => 'onespace_footer',
        'priority' => 95,
    )));

    $wp_customize->add_setting('footer_menu_hover_bg_color', array(
        'default'           => 'rgba(255,255,255,0.08)',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_menu_hover_bg_color', array(
        'label'    => __('Footer Menu Hover Background', 'onespace-theme2'),
        'section'  => 'onespace_footer',
        'priority' => 100,
    )));

    // Footer menu letter spacing (em)
    $wp_customize->add_setting('footer_menu_letter_spacing', array(
        'default'           => 0,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, -0.2, 1); },
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('footer_menu_letter_spacing', array(
        'label'       => __('Footer Menu Letter Spacing (em)', 'onespace-theme2'),
        'section'     => 'onespace_footer',
        'type'        => 'number',
        'priority'    => 105,
        'input_attrs' => array('min' => -0.2, 'max' => 1, 'step' => 0.01),
    ));

    // Footer menu padding (rem)
    $wp_customize->add_setting('footer_menu_padding_x', array(
        'default'           => 0.75,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 3); },
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('footer_menu_padding_x', array(
        'label'       => __('Footer Menu Padding X (rem)', 'onespace-theme2'),
        'section'     => 'onespace_footer',
        'type'        => 'number',
        'priority'    => 110,
        'input_attrs' => array('min' => 0, 'max' => 3, 'step' => 0.05),
    ));

    $wp_customize->add_setting('footer_menu_padding_y', array(
        'default'           => 0.5,
        'sanitize_callback' => function($value) { return onespace_sanitize_float($value, 0, 3); },
        'transport'         => 'postMessage',
    ));
    $wp_customize->add_control('footer_menu_padding_y', array(
        'label'       => __('Footer Menu Padding Y (rem)', 'onespace-theme2'),
        'section'     => 'onespace_footer',
        'type'        => 'number',
        'priority'    => 115,
        'input_attrs' => array('min' => 0, 'max' => 3, 'step' => 0.05),
    ));
}
add_action('customize_register', 'onespace_footer_customizer');
