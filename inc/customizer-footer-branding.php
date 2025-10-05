<?php
/**
 * Footer Branding Customizer Settings
 *
 * @package OnespaceTheme2
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function onespace_footer_branding_customize( $wp_customize ) {
    $wp_customize->add_section( 'footer_branding_section', array(
        'title'       => __( 'Footer Branding', 'onespace-theme2' ),
        'priority'    => 170,
        'description' => __( 'Show / hide footer logo & tagline; adjust position, margin (%) and offsets (%).', 'onespace-theme2' ),
    ) );

    // Enable branding block
    $wp_customize->add_setting( 'footer_branding_enable', array(
        'default'           => true,
        'sanitize_callback' => 'onespace_fb_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_enable', array(
        'label'   => __( 'Show Footer Branding', 'onespace-theme2' ),
        'section' => 'footer_branding_section',
        'type'    => 'checkbox',
    ) );

    // Enable tagline
    $wp_customize->add_setting( 'footer_branding_tagline_enable', array(
        'default'           => true,
        'sanitize_callback' => 'onespace_fb_sanitize_checkbox',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_tagline_enable', array(
        'label'   => __( 'Show Tagline', 'onespace-theme2' ),
        'section' => 'footer_branding_section',
        'type'    => 'checkbox',
    ) );

    // Tagline text
    $wp_customize->add_setting( 'footer_branding_tagline', array(
        'default'           => __( 'Bridging Creativity and Technology', 'onespace-theme2' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_tagline', array(
        'label'       => __( 'Tagline Text', 'onespace-theme2' ),
        'section'     => 'footer_branding_section',
        'type'        => 'text',
    ) );

    // Optional branding image override
    $wp_customize->add_setting( 'footer_branding_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( new WP_Customize_Media_Control(
        $wp_customize,
        'footer_branding_image',
        array(
            'label'     => __( 'Branding Image', 'onespace-theme2' ),
            'section'   => 'footer_branding_section',
            'mime_type' => 'image',
        )
    ) );

    // Horizontal position
    $wp_customize->add_setting( 'footer_branding_position', array(
        'default'           => 'left',
        'sanitize_callback' => 'onespace_fb_sanitize_position',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_position', array(
        'label'   => __( 'Horizontal Position', 'onespace-theme2' ),
        'section' => 'footer_branding_section',
        'type'    => 'select',
        'choices' => array(
            'left'   => __( 'Left', 'onespace-theme2' ),
            'center' => __( 'Center', 'onespace-theme2' ),
            'right'  => __( 'Right', 'onespace-theme2' ),
        ),
    ) );

    // Margin (%) uniform
    $wp_customize->add_setting( 'footer_branding_margin', array(
        'default'           => 0,
        'sanitize_callback' => 'onespace_fb_sanitize_int',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_margin', array(
        'label'       => __( 'Outer Margin (%)', 'onespace-theme2' ),
        'section'     => 'footer_branding_section',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 0, 'max' => 25, 'step' => 1 ),
    ) );

    // Offset X (%)
    $wp_customize->add_setting( 'footer_branding_offset_x', array(
        'default'           => 0,
        'sanitize_callback' => 'onespace_fb_sanitize_int',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_offset_x', array(
        'label'       => __( 'Offset X (%)', 'onespace-theme2' ),
        'section'     => 'footer_branding_section',
        'type'        => 'number',
        'input_attrs' => array( 'min' => -50, 'max' => 50, 'step' => 1 ),
    ) );

    // Offset Y (%)
    $wp_customize->add_setting( 'footer_branding_offset_y', array(
        'default'           => 0,
        'sanitize_callback' => 'onespace_fb_sanitize_int',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_offset_y', array(
        'label'       => __( 'Offset Y (%)', 'onespace-theme2' ),
        'section'     => 'footer_branding_section',
        'type'        => 'number',
        'input_attrs' => array( 'min' => -50, 'max' => 50, 'step' => 1 ),
    ) );

    // Logo width (px)
    $wp_customize->add_setting( 'footer_branding_logo_width', array(
        'default'           => 0, // 0 = auto
        'sanitize_callback' => 'onespace_fb_sanitize_int',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_logo_width', array(
        'label'       => __( 'Logo Width (px)', 'onespace-theme2' ),
        'section'     => 'footer_branding_section',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 0, 'max' => 600, 'step' => 10 ),
        'description' => __( '0 = natural image width', 'onespace-theme2' ),
    ) );

    // Logo height (px)
    $wp_customize->add_setting( 'footer_branding_logo_height', array(
        'default'           => 0, // 0 = auto
        'sanitize_callback' => 'onespace_fb_sanitize_int',
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_logo_height', array(
        'label'       => __( 'Logo Height (px)', 'onespace-theme2' ),
        'section'     => 'footer_branding_section',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 0, 'max' => 400, 'step' => 10 ),
        'description' => __( '0 = natural image height', 'onespace-theme2' ),
    ) );

    // Logo layout (relationship between logo and tagline)
    $wp_customize->add_setting( 'footer_branding_logo_layout', array(
        'default'           => 'logo-top',
        'sanitize_callback' => function( $val ) {
            $allowed = array( 'logo-top','logo-bottom','logo-left','logo-right' );
            return in_array( $val, $allowed, true ) ? $val : 'logo-top';
        },
        'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'footer_branding_logo_layout', array(
        'label'   => __( 'Logo / Tagline Layout', 'onespace-theme2' ),
        'section' => 'footer_branding_section',
        'type'    => 'select',
        'choices' => array(
            'logo-top'    => __( 'Logo Above Tagline', 'onespace-theme2' ),
            'logo-bottom' => __( 'Logo Below Tagline', 'onespace-theme2' ),
            'logo-left'   => __( 'Logo Left, Tagline Right', 'onespace-theme2' ),
            'logo-right'  => __( 'Logo Right, Tagline Left', 'onespace-theme2' ),
        ),
        'description' => __( 'Controls vertical or horizontal arrangement.', 'onespace-theme2' ),
    ) );
}
add_action( 'customize_register', 'onespace_footer_branding_customize' );

// Sanitizers
function onespace_fb_sanitize_checkbox( $v ) { return ( isset( $v ) && (bool) $v ); }
function onespace_fb_sanitize_position( $v ) { $a = array( 'left','center','right' ); return in_array( $v, $a, true ) ? $v : 'left'; }
function onespace_fb_sanitize_int( $v ) { return intval( $v ); }
