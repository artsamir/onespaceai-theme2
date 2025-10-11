<?php
/**
 * Comment Form Customizer
 *
 * @package OnespaceTheme2
 * @since 1.0.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Customizer settings for the Comment Form
 */
function onespace_customize_register_comment_form($wp_customize) {
    // Section
    $wp_customize->add_section('onespace_comment_form', array(
        'title'       => __('Comment Form', 'onespace-theme2'),
        'priority'    => 160,
        'description' => __('Customize labels, placeholders and add extra fields to the comment form. For Extra Fields, use JSON. Example:\n[\n  {"type":"text","name":"phone","label":"Phone","placeholder":"Your phone", "required":false},\n  {"type":"checkbox","name":"notify","label":"Email me about replies"},\n  {"type":"radio","name":"rating","label":"Rate this post","options":["👍","👎"]}\n]', 'onespace-theme2'),
    ));

    // Enable custom form
    $wp_customize->add_setting('comment_form_enable_custom', array(
        'default'           => true,
        'sanitize_callback' => 'onespace_sanitize_checkbox',
    ));
    $wp_customize->add_control('comment_form_enable_custom', array(
        'section' => 'onespace_comment_form',
        'label'   => __('Enable custom comment form', 'onespace-theme2'),
        'type'    => 'checkbox',
    ));

    // Texts: heading, consent, submit
    $wp_customize->add_setting('comment_form_heading_text', array(
        'default'           => __('Leave a Comment', 'onespace-theme2'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('comment_form_heading_text', array(
        'section' => 'onespace_comment_form',
        'label'   => __('Form heading', 'onespace-theme2'),
        'type'    => 'text',
    ));

    $wp_customize->add_setting('comment_form_consent_text', array(
        'default'           => __('I agree to the terms and privacy policy.', 'onespace-theme2'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('comment_form_consent_text', array(
        'section' => 'onespace_comment_form',
        'label'   => __('Consent text', 'onespace-theme2'),
        'type'    => 'text',
    ));

    $wp_customize->add_setting('comment_form_submit_text', array(
        'default'           => __('Post Comment', 'onespace-theme2'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('comment_form_submit_text', array(
        'section' => 'onespace_comment_form',
        'label'   => __('Submit button text', 'onespace-theme2'),
        'type'    => 'text',
    ));

    // Labels
    $labels = array(
        'label_comment' => __('Comment *', 'onespace-theme2'),
        'label_name'    => __('Name *', 'onespace-theme2'),
        'label_email'   => __('Email *', 'onespace-theme2'),
        'label_website' => __('Website', 'onespace-theme2'),
    );
    foreach ($labels as $key => $default) {
        $wp_customize->add_setting('comment_form_' . $key, array(
            'default'           => $default,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('comment_form_' . $key, array(
            'section' => 'onespace_comment_form',
            'label'   => sprintf(__('Label: %s', 'onespace-theme2'), $default),
            'type'    => 'text',
        ));
    }

    // Placeholders
    $placeholders = array(
        'ph_comment' => __('Share your thoughts...', 'onespace-theme2'),
        'ph_name'    => __('Your name', 'onespace-theme2'),
        'ph_email'   => 'your@email.com',
        'ph_website' => 'https://yourwebsite.com',
    );
    foreach ($placeholders as $key => $default) {
        $wp_customize->add_setting('comment_form_' . $key, array(
            'default'           => $default,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('comment_form_' . $key, array(
            'section' => 'onespace_comment_form',
            'label'   => sprintf(__('Placeholder: %s', 'onespace-theme2'), $default),
            'type'    => 'text',
        ));
    }

    // Toggle website field
    $wp_customize->add_setting('comment_form_include_website', array(
        'default'           => true,
        'sanitize_callback' => 'onespace_sanitize_checkbox',
    ));
    $wp_customize->add_control('comment_form_include_website', array(
        'section' => 'onespace_comment_form',
        'label'   => __('Show Website field', 'onespace-theme2'),
        'type'    => 'checkbox',
    ));

    // Extra fields JSON
    $wp_customize->add_setting('comment_form_extra_fields_json', array(
        'default'           => '',
        'sanitize_callback' => 'onespace_sanitize_textarea',
    ));
    $wp_customize->add_control('comment_form_extra_fields_json', array(
        'section'     => 'onespace_comment_form',
        'label'       => __('Extra Fields (JSON)', 'onespace-theme2'),
        'type'        => 'textarea',
        'description' => __('Provide JSON array describing extra fields. Supported types: text, textarea, email, url, number, checkbox, radio, select, label, button. Each item: {"type":"text","name":"phone","label":"Phone","placeholder":"Your phone","required":false}. For radio/select add "options":["Option 1","Option 2"].', 'onespace-theme2'),
    ));
}
add_action('customize_register', 'onespace_customize_register_comment_form');
