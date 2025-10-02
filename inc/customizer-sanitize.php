<?php
/**
 * Customizer sanitization functions
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Sanitize tagline position
 *
 * @param string $value The tagline position value
 * @return string Sanitized tagline position
 */
function onespace_sanitize_tagline_position($value) {
    $valid_positions = array(
        'above-title',
        'below-title',
        'left-title',
        'right-title',
        'hidden'
    );
    
    return in_array($value, $valid_positions, true) ? $value : 'below-title';
}

/**
 * Sanitize font style
 *
 * @param string $value The font style value
 * @return string Sanitized font style
 */
function onespace_sanitize_font_style($value) {
    $valid_styles = array(
        'normal',
        'italic',
        'oblique'
    );
    
    return in_array($value, $valid_styles, true) ? $value : 'normal';
}

/**
 * Sanitize font weight
 *
 * @param string|int $value The font weight value
 * @return string Sanitized font weight
 */
function onespace_sanitize_font_weight($value) {
    $valid_weights = array(
        '100', '200', '300', '400', '500', '600', '700', '800', '900',
        'normal', 'bold', 'bolder', 'lighter'
    );
    
    $value = (string) $value;
    return in_array($value, $valid_weights, true) ? $value : '400';
}

/**
 * Sanitize float values
 *
 * @param mixed $value The float value to sanitize
 * @param float $min Minimum allowed value
 * @param float $max Maximum allowed value
 * @return float Sanitized float value
 */
function onespace_sanitize_float($value, $min = 0, $max = 999) {
    $value = floatval($value);
    
    if ($value < $min) {
        return $min;
    }
    
    if ($value > $max) {
        return $max;
    }
    
    return $value;
}

/**
 * Sanitize integer values
 *
 * @param mixed $value The integer value to sanitize
 * @param int $min Minimum allowed value
 * @param int $max Maximum allowed value
 * @return int Sanitized integer value
 */
function onespace_sanitize_integer($value, $min = 0, $max = 9999) {
    $value = intval($value);
    
    if ($value < $min) {
        return $min;
    }
    
    if ($value > $max) {
        return $max;
    }
    
    return $value;
}

/**
 * Sanitize dimension unit
 *
 * @param string $value The dimension unit value
 * @return string Sanitized dimension unit
 */
function onespace_sanitize_dimension_unit($value) {
    $valid_units = array(
        'px',
        'em',
        'rem',
        '%',
        'vh',
        'vw'
    );
    
    return in_array($value, $valid_units, true) ? $value : 'px';
}

/**
 * Sanitize search scope
 *
 * @param string $value The search scope value
 * @return string Sanitized search scope
 */
function onespace_sanitize_search_scope($value) {
    $valid_scopes = array(
        'site',
        'custom',
        'manual'
    );
    
    return in_array($value, $valid_scopes, true) ? $value : 'site';
}

/**
 * Sanitize toggle icon mode
 *
 * @param string $value The toggle icon mode value
 * @return string Sanitized toggle icon mode
 */
function onespace_sanitize_toggle_icon_mode($value) {
    $valid_modes = array(
        'text',
        'image'
    );
    
    return in_array($value, $valid_modes, true) ? $value : 'text';
}

/**
 * Sanitize comma-separated IDs
 *
 * @param string $value Comma-separated list of IDs
 * @return string Sanitized comma-separated IDs
 */
function onespace_sanitize_comma_separated_ids($value) {
    if (empty($value)) {
        return '';
    }
    
    $ids = explode(',', $value);
    $sanitized_ids = array();
    
    foreach ($ids as $id) {
        $id = trim($id);
        if (is_numeric($id) && intval($id) > 0) {
            $sanitized_ids[] = intval($id);
        }
    }
    
    return implode(',', $sanitized_ids);
}

/**
 * Sanitize URL
 *
 * @param string $value The URL value
 * @return string Sanitized URL
 */
function onespace_sanitize_url($value) {
    return esc_url_raw($value);
}

/**
 * Sanitize text with HTML
 *
 * @param string $value The text value
 * @return string Sanitized text
 */
function onespace_sanitize_text_html($value) {
    return wp_kses_post($value);
}

/**
 * Sanitize hex color with alpha
 *
 * @param string $value The color value
 * @return string Sanitized color value
 */
function onespace_sanitize_color_alpha($value) {
    // If it's a hex color, sanitize it
    if (strpos($value, '#') === 0) {
        return sanitize_hex_color($value);
    }
    
    // If it's an rgba color, validate the format
    if (strpos($value, 'rgba') === 0) {
        $pattern = '/^rgba\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(0|1|0?\.\d+)\s*\)$/';
        if (preg_match($pattern, $value, $matches)) {
            $r = intval($matches[1]);
            $g = intval($matches[2]);
            $b = intval($matches[3]);
            $a = floatval($matches[4]);
            
            // Validate RGB values (0-255) and alpha (0-1)
            if ($r <= 255 && $g <= 255 && $b <= 255 && $a <= 1) {
                return $value;
            }
        }
    }
    
    // If it's an rgb color, validate the format
    if (strpos($value, 'rgb') === 0 && strpos($value, 'rgba') !== 0) {
        $pattern = '/^rgb\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*\)$/';
        if (preg_match($pattern, $value, $matches)) {
            $r = intval($matches[1]);
            $g = intval($matches[2]);
            $b = intval($matches[3]);
            
            // Validate RGB values (0-255)
            if ($r <= 255 && $g <= 255 && $b <= 255) {
                return $value;
            }
        }
    }
    
    // Return empty string if validation fails
    return '';
}

/**
 * Sanitize CSS property
 *
 * @param string $value The CSS property value
 * @return string Sanitized CSS property
 */
function onespace_sanitize_css_property($value) {
    // Remove any potentially harmful characters
    $value = preg_replace('/[<>"\']/', '', $value);
    
    // Allow common CSS units and values
    $allowed_pattern = '/^[a-zA-Z0-9\s\-_%.,()#]+$/';
    
    if (preg_match($allowed_pattern, $value)) {
        return sanitize_text_field($value);
    }
    
    return '';
}

/**
 * Sanitize font family
 *
 * @param string $value The font family value
 * @return string Sanitized font family
 */
function onespace_sanitize_font_family($value) {
    // Allow common font family formats
    $value = sanitize_text_field($value);
    
    // Remove any potentially harmful quotes or special characters
    $value = preg_replace('/[<>]/', '', $value);
    
    return $value;
}

/**
 * Sanitize checkbox (boolean)
 *
 * @param mixed $value The checkbox value
 * @return bool Sanitized boolean value
 */
function onespace_sanitize_checkbox($value) {
    return (bool) $value;
}

/**
 * Sanitize select options
 *
 * @param string $value The select value
 * @param array $valid_options Array of valid options
 * @param string $default Default value if validation fails
 * @return string Sanitized select value
 */
function onespace_sanitize_select($value, $valid_options, $default = '') {
    return in_array($value, $valid_options, true) ? $value : $default;
}

/**
 * Sanitize textarea content
 *
 * @param string $value The textarea content
 * @return string Sanitized textarea content
 */
function onespace_sanitize_textarea($value) {
    return sanitize_textarea_field($value);
}

/**
 * Sanitize image upload
 *
 * @param string $value The image URL
 * @return string Sanitized image URL
 */
function onespace_sanitize_image($value) {
    // Check if it's a valid image URL
    $filetype = wp_check_filetype($value);
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'svg', 'webp');
    
    if (in_array($filetype['ext'], $allowed_types, true)) {
        return esc_url_raw($value);
    }
    
    return '';
}

/**
 * Sanitize range slider value
 *
 * @param mixed $value The range value
 * @param float $min Minimum value
 * @param float $max Maximum value
 * @param float $step Step value
 * @return float Sanitized range value
 */
function onespace_sanitize_range($value, $min = 0, $max = 100, $step = 1) {
    $value = floatval($value);
    
    // Ensure value is within range
    if ($value < $min) {
        return $min;
    }
    
    if ($value > $max) {
        return $max;
    }
    
    // Round to nearest step
    if ($step > 0) {
        $value = round(($value - $min) / $step) * $step + $min;
    }
    
    return $value;
}