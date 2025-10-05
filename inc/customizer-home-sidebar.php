<?php
/**
 * Home Sidebar Customizer Settings
 *
 * @package OnespaceTheme2
 */

if (!defined('ABSPATH')) { exit; }

function onespace_home_sidebar_customizer($wp_customize) {
    // Section
    $wp_customize->add_section('onespace_home_sidebar', array(
        'title'    => __('Home Sidebar Settings', 'onespace-theme2'),
        'priority' => 120,
    ));

    // Home Content Area Settings Section
    $wp_customize->add_section('onespace_home_content', array(
        'title'    => __('Home Content Area Settings', 'onespace-theme2'),
        'priority' => 121,
    ));

    // Primary sidebar mode (widget or custom)
    $wp_customize->add_setting('home_primary_sidebar_mode', array(
        'default'           => 'custom',
        'sanitize_callback' => function($val){ return onespace_sanitize_select($val, array('widget','custom'), 'custom'); },
    ));
    $wp_customize->add_control('home_primary_sidebar_mode', array(
        'label'   => __('Primary Sidebar Mode', 'onespace-theme2'),
        'section' => 'onespace_home_sidebar',
        'type'    => 'radio',
        'choices' => array(
            'widget' => __('Widget Area', 'onespace-theme2'),
            'custom' => __('Custom (Curated)', 'onespace-theme2'),
        ),
        'priority' => 10,
    ));

    // Secondary sidebar toggle (future use; for now simple checkbox)
    $wp_customize->add_setting('home_secondary_sidebar_enable', array(
        'default'           => false,
        'sanitize_callback' => 'onespace_sanitize_checkbox',
    ));
    $wp_customize->add_control('home_secondary_sidebar_enable', array(
        'label'       => __('Enable Secondary Sidebar (beta)', 'onespace-theme2'),
        'section'     => 'onespace_home_sidebar',
        'type'        => 'checkbox',
        'priority'    => 15,
        'description' => __('Reserved for future layout. Currently unused.', 'onespace-theme2'),
    ));

    // Sidebar width (rem)
    $wp_customize->add_setting('home_sidebar_width', array(
        'default'           => 18,
        'sanitize_callback' => function($v){ return onespace_sanitize_float($v, 10, 40); },
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_sidebar_width', array(
        'label'       => __('Sidebar Width (rem)', 'onespace-theme2'),
        'section'     => 'onespace_home_sidebar',
        'type'        => 'number',
        'priority'    => 20,
        'input_attrs' => array('min'=>10,'max'=>40,'step'=>0.5),
    ));

    // Grid gap (rem)
    $wp_customize->add_setting('home_layout_gap', array(
        'default'           => 2,
        'sanitize_callback' => function($v){ return onespace_sanitize_float($v, 0, 6); },
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_layout_gap', array(
        'label'       => __('Layout Gap (rem)', 'onespace-theme2'),
        'section'     => 'onespace_home_sidebar',
        'type'        => 'number',
        'priority'    => 25,
        'input_attrs' => array('min'=>0,'max'=>6,'step'=>0.25),
    ));

    // Recent posts count
    $wp_customize->add_setting('home_recent_posts_count', array(
        'default'           => 12,
        'sanitize_callback' => function($v){ return onespace_sanitize_integer($v, 1, 50); },
    ));
    $wp_customize->add_control('home_recent_posts_count', array(
        'label'       => __('Recent Posts Count', 'onespace-theme2'),
        'section'     => 'onespace_home_sidebar',
        'type'        => 'number',
        'priority'    => 30,
        'input_attrs' => array('min'=>1,'max'=>50,'step'=>1),
    ));

    // Recent posts vertical gap (rem)
    $wp_customize->add_setting('home_recent_posts_item_gap', array(
        'default'           => 0.75, // updated default
        'sanitize_callback' => function($v){ return onespace_sanitize_float($v, 0, 4); },
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_recent_posts_item_gap', array(
        'label'       => __('Recent Posts Item Gap (rem)', 'onespace-theme2'),
        'section'     => 'onespace_home_sidebar',
        'type'        => 'number',
        'priority'    => 31,
        'input_attrs' => array('min'=>0,'max'=>4,'step'=>0.05),
    ));

    // Recent post source (latest / category / manual IDs)
    $wp_customize->add_setting('home_recent_posts_source', array(
        'default'           => 'latest',
        'sanitize_callback' => function($val){ return onespace_sanitize_select($val, array('latest','category','manual'), 'latest'); },
    ));
    $wp_customize->add_control('home_recent_posts_source', array(
        'label'    => __('Recent Posts Source', 'onespace-theme2'),
        'section'  => 'onespace_home_sidebar',
        'type'     => 'select',
        'choices'  => array(
            'latest'   => __('Latest', 'onespace-theme2'),
            'category' => __('Specific Category', 'onespace-theme2'),
            'manual'   => __('Manual IDs', 'onespace-theme2'),
        ),
        'priority' => 35,
    ));

    // Category slug
    $wp_customize->add_setting('home_recent_posts_category', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_title',
    ));
    $wp_customize->add_control('home_recent_posts_category', array(
        'label'       => __('Category Slug (when source = category)', 'onespace-theme2'),
        'section'     => 'onespace_home_sidebar',
        'type'        => 'text',
        'priority'    => 36,
    ));

    // Manual IDs (CSV)
    $wp_customize->add_setting('home_recent_posts_manual_ids', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_comma_separated_ids',
    ));
    $wp_customize->add_control('home_recent_posts_manual_ids', array(
        'label'       => __('Manual Post IDs (CSV)', 'onespace-theme2'),
        'section'     => 'onespace_home_sidebar',
        'type'        => 'text',
        'priority'    => 37,
        'description' => __('Enter comma-separated list of post IDs.', 'onespace-theme2'),
    ));

    // Show comment section toggle
    $wp_customize->add_setting('home_recent_comments_enable', array(
        'default'           => true,
        'sanitize_callback' => 'onespace_sanitize_checkbox',
    ));
    $wp_customize->add_control('home_recent_comments_enable', array(
        'label'    => __('Show Recent Comments', 'onespace-theme2'),
        'section'  => 'onespace_home_sidebar',
        'type'     => 'checkbox',
        'priority' => 40,
    ));

    // Recent comments count
    $wp_customize->add_setting('home_recent_comments_count', array(
        'default'           => 5,
        'sanitize_callback' => function($v){ return onespace_sanitize_integer($v, 1, 30); },
    ));
    $wp_customize->add_control('home_recent_comments_count', array(
        'label'       => __('Recent Comments Count', 'onespace-theme2'),
        'section'     => 'onespace_home_sidebar',
        'type'        => 'number',
        'priority'    => 45,
        'input_attrs' => array('min'=>1,'max'=>30,'step'=>1),
    ));

    // === HOME CONTENT AREA SETTINGS ===
    
    // Blog post source
    $wp_customize->add_setting('home_blog_post_source', array(
        'default'           => 'latest',
        'sanitize_callback' => function($val){ return onespace_sanitize_select($val, array('latest','oldest','date_range','manual'), 'latest'); },
    ));
    $wp_customize->add_control('home_blog_post_source', array(
        'label'    => __('Blog Post Source', 'onespace-theme2'),
        'section'  => 'onespace_home_content',
        'type'     => 'select',
        'choices'  => array(
            'latest'     => __('Latest Posts', 'onespace-theme2'),
            'oldest'     => __('Oldest Posts', 'onespace-theme2'),
            'date_range' => __('Calendar Date Range', 'onespace-theme2'),
            'manual'     => __('Manual Post Selection', 'onespace-theme2'),
        ),
        'priority' => 5,
    ));

    // Date range start (when source = date_range)
    $wp_customize->add_setting('home_blog_date_start', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('home_blog_date_start', array(
        'label'       => __('Start Date (YYYY-MM-DD)', 'onespace-theme2'),
        'section'     => 'onespace_home_content',
        'type'        => 'date',
        'priority'    => 6,
        'description' => __('Used when source is "Calendar Date Range"', 'onespace-theme2'),
    ));

    // Date range end (when source = date_range)
    $wp_customize->add_setting('home_blog_date_end', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('home_blog_date_end', array(
        'label'       => __('End Date (YYYY-MM-DD)', 'onespace-theme2'),
        'section'     => 'onespace_home_content',
        'type'        => 'date',
        'priority'    => 7,
        'description' => __('Used when source is "Calendar Date Range"', 'onespace-theme2'),
    ));

    // Manual post IDs (when source = manual)
    $wp_customize->add_setting('home_blog_manual_posts', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_comma_separated_ids',
    ));
    $wp_customize->add_control('home_blog_manual_posts', array(
        'label'       => __('Manual Post IDs (CSV)', 'onespace-theme2'),
        'section'     => 'onespace_home_content',
        'type'        => 'text',
        'priority'    => 8,
        'description' => __('Enter comma-separated post IDs when source is "Manual Post Selection"', 'onespace-theme2'),
    ));

    // Number of blog cards
    $wp_customize->add_setting('home_blog_cards_count', array(
        'default'           => 12,
        'sanitize_callback' => function($v){ return onespace_sanitize_integer($v, 1, 50); },
    ));
    $wp_customize->add_control('home_blog_cards_count', array(
        'label'       => __('Number of Blog Cards', 'onespace-theme2'),
        'section'     => 'onespace_home_content',
        'type'        => 'number',
        'priority'    => 9,
        'input_attrs' => array('min'=>1,'max'=>50,'step'=>1),
    ));

    // Blog cards per row
    $wp_customize->add_setting('home_blog_cards_per_row', array(
        'default'           => 3,
        'sanitize_callback' => function($v){ return onespace_sanitize_select($v, array('1','2','3'), '3'); },
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_blog_cards_per_row', array(
        'label'    => __('Blog Cards Per Row', 'onespace-theme2'),
        'section'  => 'onespace_home_content',
        'type'     => 'select',
        'choices'  => array(
            '1' => __('1 Card', 'onespace-theme2'),
            '2' => __('2 Cards', 'onespace-theme2'),
            '3' => __('3 Cards (Default)', 'onespace-theme2'),
        ),
        'priority' => 10,
    ));

    // Blog card text color
    $wp_customize->add_setting('home_blog_card_text_color', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_color_alpha',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'home_blog_card_text_color', array(
        'label'    => __('Blog Card Text Color', 'onespace-theme2'),
        'section'  => 'onespace_home_content',
        'priority' => 20,
        'description' => __('Leave empty to use theme default', 'onespace-theme2'),
    )));

    // Blog card padding (rem)
    $wp_customize->add_setting('home_blog_card_padding', array(
        'default'           => 1,
        'sanitize_callback' => function($v){ return onespace_sanitize_float($v, 0, 4); },
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control('home_blog_card_padding', array(
        'label'       => __('Blog Card Padding (rem)', 'onespace-theme2'),
        'section'     => 'onespace_home_content',
        'type'        => 'number',
        'priority'    => 30,
        'input_attrs' => array('min'=>0,'max'=>4,'step'=>0.1),
    ));

    // Enable pagination
    $wp_customize->add_setting('home_blog_enable_pagination', array(
        'default'           => true,
        'sanitize_callback' => 'onespace_sanitize_checkbox',
    ));
    $wp_customize->add_control('home_blog_enable_pagination', array(
        'label'    => __('Enable Blog Card Pagination', 'onespace-theme2'),
        'section'  => 'onespace_home_content',
        'type'     => 'checkbox',
        'priority' => 40,
        'description' => __('Add pagination navigation at the end of blog cards', 'onespace-theme2'),
    ));

    // Pagination style
    $wp_customize->add_setting('home_blog_pagination_style', array(
        'default'           => 'modern',
        'sanitize_callback' => function($val){ return onespace_sanitize_select($val, array('modern','classic','minimal'), 'modern'); },
    ));
    $wp_customize->add_control('home_blog_pagination_style', array(
        'label'    => __('Pagination Style', 'onespace-theme2'),
        'section'  => 'onespace_home_content',
        'type'     => 'select',
        'choices'  => array(
            'modern'  => __('Modern (Rounded buttons)', 'onespace-theme2'),
            'classic' => __('Classic (Standard links)', 'onespace-theme2'),
            'minimal' => __('Minimal (Simple text)', 'onespace-theme2'),
        ),
        'priority' => 41,
    ));

    // Pagination alignment
    $wp_customize->add_setting('home_blog_pagination_align', array(
        'default'           => 'center',
        'sanitize_callback' => function($val){ return onespace_sanitize_select($val, array('left','center','right'), 'center'); },
    ));
    $wp_customize->add_control('home_blog_pagination_align', array(
        'label'    => __('Pagination Alignment', 'onespace-theme2'),
        'section'  => 'onespace_home_content',
        'type'     => 'select',
        'choices'  => array(
            'left'   => __('Left', 'onespace-theme2'),
            'center' => __('Center', 'onespace-theme2'),
            'right'  => __('Right', 'onespace-theme2'),
        ),
        'priority' => 42,
    ));
}
add_action('customize_register', 'onespace_home_sidebar_customizer');
