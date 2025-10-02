/**
 * Customizer Preview JavaScript
 * Handles live updates via postMessage transport
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Initialize preview updates
    function initPreviewUpdates() {
        
        // Base header settings
        setupBaseHeaderUpdates();
        
        // Menu settings
        setupMenuUpdates();
        
        // Search settings
        setupSearchUpdates();
        
        // Dark/Light toggle settings
        setupToggleUpdates();
        
        // Search result control settings
        setupSearchResultUpdates();

        // Footer settings
        setupFooterUpdates();
    }

    /**
     * Setup base header updates
     */
    function setupBaseHeaderUpdates() {
        
        // Header background color
        wp.customize('header_background_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--header-bg-color', to);
            });
        });

        // Tagline position
        wp.customize('header_tagline_position', function(value) {
            value.bind(function(to) {
                updateBodyClass('tagline-', to);
            });
        });

        // Tagline gap
        wp.customize('header_tagline_gap', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--tagline-gap', parseFloat(to) + 'rem');
            });
        });

        // Tagline offsets
        wp.customize('header_tagline_offset_x', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--tagline-offset-x', parseFloat(to) + 'rem');
            });
        });

        wp.customize('header_tagline_offset_y', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--tagline-offset-y', parseFloat(to) + 'rem');
            });
        });

        // Logo dimensions
        wp.customize('header_logo_width_value', function(value) {
            value.bind(function(to) {
                const unit = wp.customize('header_logo_width_unit').get();
                updateCSSProperty('--logo-width', parseInt(to) + unit);
            });
        });

        wp.customize('header_logo_width_unit', function(value) {
            value.bind(function(to) {
                const width = wp.customize('header_logo_width_value').get();
                updateCSSProperty('--logo-width', parseInt(width) + to);
            });
        });

        wp.customize('header_logo_height_value', function(value) {
            value.bind(function(to) {
                const unit = wp.customize('header_logo_height_unit').get();
                updateCSSProperty('--logo-height', parseInt(to) + unit);
            });
        });

        wp.customize('header_logo_height_unit', function(value) {
            value.bind(function(to) {
                const height = wp.customize('header_logo_height_value').get();
                updateCSSProperty('--logo-height', parseInt(height) + to);
            });
        });
    }

    /**
     * Setup footer updates
     */
    function setupFooterUpdates() {
        // Copyright text
        wp.customize('footer_copyright_text', function(value) {
            value.bind(function(to) {
                const year = new Date().getFullYear();
                const site = document.title.replace(/\s*[–|-].*$/, '');
                const rendered = (to || '').replaceAll('{year}', year).replaceAll('{site}', site);
                const el = document.querySelector('.footer-credits .copyright');
                if (el) el.innerHTML = rendered || el.innerHTML;
            });
        });

        // Font family
        wp.customize('footer_credits_font_family', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--footer-credits-font-family', to || 'inherit');
            });
        });

        // Font size
        wp.customize('footer_credits_font_size', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--footer-credits-font-size', parseFloat(to) + 'rem');
            });
        });

        // Text color
        wp.customize('footer_credits_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--footer-credits-color', to);
            });
        });

        // Footer background color
        wp.customize('footer_background_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--footer-bg', to);
            });
        });

        // Copyright position (toggle body class)
        wp.customize('footer_copyright_position', function(value) {
            value.bind(function(to) {
                const body = document.body;
                body.classList.remove('footer-copyright-left', 'footer-copyright-center', 'footer-copyright-right');
                const cls = (to === 'center' ? 'footer-copyright-center' : (to === 'right' ? 'footer-copyright-right' : 'footer-copyright-left'));
                body.classList.add(cls);
            });
        });

        // Footer menu offsets (px)
        ['footer_menu_offset_top','footer_menu_offset_right','footer_menu_offset_bottom','footer_menu_offset_left'].forEach(function(setting){
            wp.customize(setting, function(value){
                value.bind(function(to){
                    const cssName = '--' + setting.replace(/_/g, '-');
                    updateCSSProperty(cssName, parseInt(to) + 'px');
                });
            });
        });

        // Footer menu colors
        wp.customize('footer_menu_text_color', function(value){
            value.bind(function(to){ updateCSSProperty('--footer-menu-text-color', to); });
        });
        wp.customize('footer_menu_hover_text_color', function(value){
            value.bind(function(to){ updateCSSProperty('--footer-menu-hover-text-color', to); });
        });
        wp.customize('footer_menu_hover_bg_color', function(value){
            value.bind(function(to){ updateCSSProperty('--footer-menu-hover-bg', to); });
        });

        // Footer menu letter spacing/padding
        wp.customize('footer_menu_letter_spacing', function(value){
            value.bind(function(to){ updateCSSProperty('--footer-menu-letter-spacing', parseFloat(to) + 'em'); });
        });
        wp.customize('footer_menu_padding_x', function(value){
            value.bind(function(to){ updateCSSProperty('--footer-menu-padding-x', parseFloat(to) + 'rem'); });
        });
        wp.customize('footer_menu_padding_y', function(value){
            value.bind(function(to){ updateCSSProperty('--footer-menu-padding-y', parseFloat(to) + 'rem'); });
        });
    }

    /**
     * Setup menu updates
     */
    function setupMenuUpdates() {
        
        // Menu text color
        wp.customize('header_menu_text_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-text-color', to);
            });
        });

        // Menu background hover
        wp.customize('header_menu_bg_hover', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-bg-hover', to);
            });
        });

        // Menu padding
        wp.customize('header_menu_padding_x', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-padding-x', parseFloat(to) + 'rem');
            });
        });

        wp.customize('header_menu_padding_y', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-padding-y', parseFloat(to) + 'rem');
            });
        });

        // Menu radius
        wp.customize('header_menu_radius', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-radius', parseFloat(to) + 'px');
            });
        });

        // Menu font properties
        wp.customize('header_menu_font_size', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-font-size', parseFloat(to) + 'rem');
            });
        });

        wp.customize('header_menu_font_weight', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-font-weight', to);
            });
        });

        wp.customize('header_menu_font_family', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-font-family', to || 'inherit');
            });
        });

        wp.customize('header_menu_font_style', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-font-style', to);
            });
        });

        wp.customize('header_menu_letter_spacing', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-letter-spacing', parseFloat(to) + 'em');
            });
        });

        wp.customize('header_menu_line_height', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-line-height', parseFloat(to));
            });
        });

        // Menu offsets
        wp.customize('header_menu_offset_left', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-offset-left', parseFloat(to) + 'rem');
            });
        });

        wp.customize('header_menu_offset_right', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--menu-offset-right', parseFloat(to) + 'rem');
            });
        });

        // Submenu colors
        wp.customize('header_submenu_bg', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--submenu-bg', to);
            });
        });

        wp.customize('header_submenu_hover_bg', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--submenu-hover-bg', to);
            });
        });

        wp.customize('header_submenu_text_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--submenu-text-color', to);
            });
        });
    }

    /**
     * Setup search updates
     */
    function setupSearchUpdates() {
        
        // Search dimensions
        wp.customize('header_search_width', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-width', parseInt(to) + 'px');
            });
        });

        wp.customize('header_search_height', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-height', parseInt(to) + 'px');
            });
        });

        // Search font properties
        wp.customize('header_search_font_size', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-font-size', parseFloat(to) + 'rem');
            });
        });

        wp.customize('header_search_font_family', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-font-family', to || 'inherit');
            });
        });

        // Search placeholder text - update immediately
        wp.customize('header_search_placeholder', function(value) {
            value.bind(function(to) {
                $('.header-search input[type="search"]').attr('placeholder', to);
            });
        });

        // Search colors
        wp.customize('header_search_placeholder_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-placeholder-color', to);
            });
        });

        wp.customize('header_search_bg', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-bg', to);
            });
        });

        wp.customize('header_search_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-color', to);
            });
        });

        // Search border properties
        wp.customize('header_search_radius', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-radius', parseFloat(to) + 'px');
            });
        });

        wp.customize('header_search_border_width', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-border-width', parseFloat(to) + 'px');
            });
        });

        wp.customize('header_search_border_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--search-border-color', to);
            });
        });
    }

    /**
     * Setup toggle updates
     */
    function setupToggleUpdates() {
        
        // Toggle icon mode - update display immediately
        wp.customize('header_toggle_icon_mode', function(value) {
            value.bind(function(to) {
                updateToggleIconDisplay();
            });
        });

        // Toggle icon text
        wp.customize('header_toggle_icon_light', function(value) {
            value.bind(function(to) {
                $('.light-icon .toggle-icon').text(to);
            });
        });

        wp.customize('header_toggle_icon_dark', function(value) {
            value.bind(function(to) {
                $('.dark-icon .toggle-icon').text(to);
            });
        });

        // Toggle icon images
        wp.customize('header_toggle_icon_light_image', function(value) {
            value.bind(function(to) {
                if (to) {
                    $('.light-icon .toggle-icon img').attr('src', to);
                }
            });
        });

        wp.customize('header_toggle_icon_dark_image', function(value) {
            value.bind(function(to) {
                if (to) {
                    $('.dark-icon .toggle-icon img').attr('src', to);
                }
            });
        });

        // Toggle colors
        wp.customize('header_toggle_icon_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-icon-color', to);
            });
        });

        wp.customize('header_toggle_bg_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-bg-color', to);
            });
        });

        // Toggle border
        wp.customize('header_toggle_border_width', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-border-width', parseFloat(to) + 'px');
            });
        });

        wp.customize('header_toggle_border_color', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-border-color', to);
            });
        });

        // Toggle dimensions
        wp.customize('header_toggle_icon_width', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-icon-width', parseInt(to) + 'px');
            });
        });

        wp.customize('header_toggle_icon_height', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-icon-height', parseInt(to) + 'px');
            });
        });

        wp.customize('header_toggle_icon_padding', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-icon-padding', parseFloat(to) + 'rem');
            });
        });

        wp.customize('header_toggle_icon_radius', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-icon-radius', parseFloat(to) + 'px');
            });
        });

        // Toggle margins
        const marginProperties = [
            'header_toggle_margin_left',
            'header_toggle_margin_right',
            'header_toggle_margin_top',
            'header_toggle_margin_bottom'
        ];

        marginProperties.forEach(function(property) {
            wp.customize(property, function(value) {
                value.bind(function(to) {
                    const cssProperty = '--' + property.replace('header_', '').replace(/_/g, '-');
                    updateCSSProperty(cssProperty, parseFloat(to) + 'rem');
                });
            });
        });

        // Toggle offsets
        wp.customize('header_toggle_offset_x', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-offset-x', parseFloat(to) + 'rem');
            });
        });

        wp.customize('header_toggle_offset_y', function(value) {
            value.bind(function(to) {
                updateCSSProperty('--toggle-offset-y', parseFloat(to) + 'rem');
            });
        });
    }

    /**
     * Setup search result control updates
     */
    function setupSearchResultUpdates() {
        
        // Search scope changes - update form action
        wp.customize('header_search_scope', function(value) {
            value.bind(function(to) {
                updateSearchFormAction();
            });
        });

        wp.customize('header_search_custom_url', function(value) {
            value.bind(function(to) {
                updateSearchFormAction();
            });
        });

        // Manual IDs don't need immediate preview update
        // They would affect search functionality but not visual appearance
    }

    /**
     * Update CSS custom property
     */
    function updateCSSProperty(property, value) {
        if (!value) return;
        
        const existingStyle = document.getElementById('onespace-preview-css');
        let style = existingStyle;
        
        if (!style) {
            style = document.createElement('style');
            style.id = 'onespace-preview-css';
            document.head.appendChild(style);
        }
        
        // Get existing rules or create new ones
        let css = style.textContent || '';
        const rootRegex = /:root\s*\{([^}]*)\}/;
        const match = css.match(rootRegex);
        
        let properties = {};
        if (match) {
            // Parse existing properties
            const existingProps = match[1].split(';');
            existingProps.forEach(function(prop) {
                const colonIndex = prop.indexOf(':');
                if (colonIndex > 0) {
                    const key = prop.substring(0, colonIndex).trim();
                    const val = prop.substring(colonIndex + 1).trim();
                    if (key && val) {
                        properties[key] = val;
                    }
                }
            });
        }
        
        // Update the property
        properties[property] = value;
        
        // Rebuild CSS
        let newCSS = ':root { ';
        Object.keys(properties).forEach(function(key) {
            newCSS += key + ': ' + properties[key] + '; ';
        });
        newCSS += '}';
        
        style.textContent = newCSS;
    }

    /**
     * Update body class for tagline position
     */
    function updateBodyClass(prefix, value) {
        const body = document.body;
        const classList = Array.from(body.classList);
        
        // Remove existing classes with the prefix
        classList.forEach(function(className) {
            if (className.startsWith(prefix)) {
                body.classList.remove(className);
            }
        });
        
        // Add new class
        if (value) {
            body.classList.add(prefix + value);
        }
    }

    /**
     * Update toggle icon display based on mode
     */
    function updateToggleIconDisplay() {
        const mode = wp.customize('header_toggle_icon_mode').get();
        const toggle = document.querySelector('.dark-light-toggle');
        
        if (!toggle) return;
        
        const lightIcon = toggle.querySelector('.light-icon .toggle-icon');
        const darkIcon = toggle.querySelector('.dark-icon .toggle-icon');
        
        if (mode === 'text') {
            const lightText = wp.customize('header_toggle_icon_light').get();
            const darkText = wp.customize('header_toggle_icon_dark').get();
            
            if (lightIcon) lightIcon.innerHTML = lightText;
            if (darkIcon) darkIcon.innerHTML = darkText;
        } else if (mode === 'image') {
            const lightImage = wp.customize('header_toggle_icon_light_image').get();
            const darkImage = wp.customize('header_toggle_icon_dark_image').get();
            
            if (lightIcon && lightImage) {
                lightIcon.innerHTML = '<img src="' + lightImage + '" alt="Light mode">';
            }
            if (darkIcon && darkImage) {
                darkIcon.innerHTML = '<img src="' + darkImage + '" alt="Dark mode">';
            }
        }
    }

    /**
     * Update search form action based on scope
     */
    function updateSearchFormAction() {
        const scope = wp.customize('header_search_scope').get();
        const customUrl = wp.customize('header_search_custom_url').get();
        const searchForm = document.querySelector('.header-search');
        
        if (!searchForm) return;
        
        let actionUrl = window.location.origin + '/';
        
        if (scope === 'custom' && customUrl) {
            actionUrl = customUrl;
        }
        
        searchForm.setAttribute('action', actionUrl);
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initPreviewUpdates();
    });

})(jQuery);