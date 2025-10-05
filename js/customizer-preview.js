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
        
        // Mobile icon styling
        setupMobileIconUpdates();
        
        // Mobile section settings
        setupMobileUpdates();
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
        
        // Header height
        wp.customize('header_height', function(value) {
            value.bind(function(to) {
                updateHeaderHeight(to);
            });
        });

        // Tagline position
        wp.customize('header_tagline_position', function(value) {
            value.bind(function(to) {
                updateBodyClass('tagline-', to);
            });
        });
        
        // Mobile logo handlers moved to setupMobileUpdates function

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
        console.log('🔧 Setting up footer updates...');
        
        // Test if footer settings are available
        console.log('Testing footer_menu_position:', wp.customize('footer_menu_position'));
        console.log('Testing footer_menu_background_color:', wp.customize('footer_menu_background_color'));
        // Copyright text
        wp.customize('footer_copyright_text', function(value) {
            value.bind(function(to) {
                const year = new Date().getFullYear();
                const site = document.title.replace(/\s*[–|-].*$/, '');
                let rendered = (to || '').replaceAll('{year}', year).replaceAll('{site}', site);
                const el = document.querySelector('.footer-credits .copyright');
                if (el) {
                    // Allow clearing text in real-time; fallback placeholder when empty
                    if (rendered.trim() === '') {
                        rendered = '';
                    }
                    el.innerHTML = rendered;
                }
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

        // Footer menu position (toggle body class and trigger alignment)
        wp.customize('footer_menu_position', function(value) {
            value.bind(function(to) {
                console.log('🔄 Footer menu position changed to:', to);
                
                const body = document.body;
                body.classList.remove('footer-menu-left','footer-menu-center','footer-menu-right');
                let cls = 'footer-menu-center';
                if (to === 'left') cls = 'footer-menu-left';
                else if (to === 'right') cls = 'footer-menu-right';
                body.classList.add(cls);
                
                console.log('✅ Body class updated to:', cls);
                
                // Use the working manual function approach directly
                function forceFooterAlignment(position, attempts) {
                    attempts = attempts || 0;
                    
                    const menu = document.querySelector('#footer-menu');
                    console.log('🎯 Menu element found:', !!menu);
                    
                    if (menu) {
                        // Get current background color from CSS variable
                        const computedStyle = getComputedStyle(document.documentElement);
                        const backgroundColor = computedStyle.getPropertyValue('--footer-menu-background').trim() || 'transparent';
                        
                        // Apply direct styling like the working console function
                        menu.style.display = 'flex';
                        menu.style.flexWrap = 'wrap';
                        menu.style.width = '100%';
                        menu.style.listStyle = 'none';
                        menu.style.margin = '0';
                        menu.style.padding = '0';
                        menu.style.gap = '1.5rem';
                        menu.style.justifyContent = position === 'left' ? 'flex-start' : 
                                                   position === 'right' ? 'flex-end' : 'center';
                        menu.style.background = backgroundColor;
                        
                        console.log('✅ Direct styling applied:', position, backgroundColor);
                        
                        // Also try the original function if available
                        if (typeof window.applyFooterMenuAlignment === 'function') {
                            setTimeout(() => {
                                window.applyFooterMenuAlignment();
                                console.log('✅ Original alignment function called');
                            }, 10);
                        }
                    } else if (attempts < 5) {
                        // Retry if menu not found yet
                        console.log('⚠️ Menu not found, retrying...', attempts + 1);
                        setTimeout(() => forceFooterAlignment(position, attempts + 1), 100);
                    } else {
                        console.log('❌ Menu element not found after retries');
                    }
                }
                
                forceFooterAlignment(to);
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
        
        // Footer menu background color
        wp.customize('footer_menu_background_color', function(value){
            value.bind(function(to){ 
                console.log('🎨 Footer menu background changed to:', to);
                
                updateCSSProperty('--footer-menu-background', to);
                
                // Apply background immediately to menu element
                const menu = document.querySelector('#footer-menu');
                if (menu) {
                    menu.style.background = to || 'transparent';
                    console.log('✅ Background applied directly to menu:', to);
                }
                
                // Also trigger alignment function to apply new background immediately
                if (typeof window.applyFooterMenuAlignment === 'function') {
                    setTimeout(() => {
                        window.applyFooterMenuAlignment();
                        console.log('✅ Alignment function called for background update');
                    }, 50);
                }
            });
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
        if (value === undefined || value === null) return; // allow empty string to clear
        
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
        
        if (value === '') {
            // Remove property if explicitly cleared
            delete properties[property];
        } else {
            properties[property] = value;
        }
        
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

    /**
     * Debug function to test Customizer settings availability
     */
    function debugCustomizerSettings() {
        console.log('🔍 Debugging Customizer settings...');
        
        // Check if wp.customize exists
        if (typeof wp === 'undefined' || typeof wp.customize === 'undefined') {
            console.log('❌ wp.customize not available');
            return;
        }
        
        console.log('✅ wp.customize is available');
        
        // Test specific settings
        const testSettings = [
            'footer_copyright_position',    // This one works
            'footer_menu_position',         // This one doesn't
            'footer_menu_background_color'  // This one doesn't
        ];
        
        testSettings.forEach(function(settingName) {
            const setting = wp.customize(settingName);
            if (setting) {
                const value = setting.get();
                console.log(`✅ Setting "${settingName}": ${value}`);
                
                // Test if we can bind to it
                try {
                    setting.bind(function(newValue) {
                        console.log(`🔄 TEST: ${settingName} changed to: ${newValue}`);
                    });
                    console.log(`✅ Successfully bound to: ${settingName}`);
                } catch (error) {
                    console.log(`❌ Failed to bind to: ${settingName}`, error);
                }
            } else {
                console.log(`❌ Setting "${settingName}" not found`);
            }
        });
    }
    
    /**
     * Update mobile logo
     */
    function updateMobileLogo(attachmentId) {
        const $mobileLogo = $('.mobile-logo');
        const $siteLogo = $('.site-logo');
        
        if (attachmentId) {
            // Add class to show mobile logo is set
            $siteLogo.addClass('has-mobile-logo');
            
            // If mobile logo container doesn't exist, create it
            if (!$mobileLogo.length) {
                const mobileLogoHtml = '<div class="mobile-logo"><a href="' + window.location.origin + '" class="mobile-custom-logo-link" rel="home"><img class="mobile-custom-logo" src="" alt=""></a></div>';
                $('.desktop-logo').after(mobileLogoHtml);
            }
            
            // Update the mobile logo image
            wp.media.attachment(attachmentId).fetch().then(function() {
                const attachment = wp.media.attachment(attachmentId);
                const imageUrl = attachment.get('url');
                const imageAlt = attachment.get('alt') || '';
                
                $('.mobile-custom-logo').attr('src', imageUrl).attr('alt', imageAlt);
                
                // Apply dimensions after image is set
                updateMobileLogoDimensions();
            });
        } else {
            // Remove mobile logo and class
            $siteLogo.removeClass('has-mobile-logo');
            $mobileLogo.remove();
        }
    }
    
    /**
     * Update mobile logo dimensions
     */
    function updateMobileLogoDimensions() {
        const widthValue = wp.customize('mobile_logo_width_value')() || 120;
        const widthUnit = wp.customize('mobile_logo_width_unit')() || 'px';
        const heightValue = wp.customize('mobile_logo_height_value')() || 40;
        const heightUnit = wp.customize('mobile_logo_height_unit')() || 'px';
        
        const width = widthValue + widthUnit;
        const height = heightValue + heightUnit;
        
        // Update CSS custom properties
        updateCSSProperty('--mobile-logo-width', width);
        updateCSSProperty('--mobile-logo-height', height);
        
        // Also directly update the mobile logo if it exists
        $('.mobile-logo img').css({
            'width': width,
            'height': height,
            'object-fit': 'contain'
        });
    }
    
    /**
     * Update mobile logo visibility
     */
    function updateMobileLogoVisibility(show) {
        const $siteLogo = $('.site-logo');
        if (show) {
            $siteLogo.removeClass('hide-mobile-logo');
        } else {
            $siteLogo.addClass('hide-mobile-logo');
        }
    }
    
    /**
     * Update mobile tagline visibility
     */
    function updateMobileTaglineVisibility(show) {
        const $siteLogo = $('.site-logo');
        const $siteTaglines = $('.site-tagline, .mobile-tagline');
        
        if (show) {
            $siteLogo.removeClass('hide-mobile-tagline');
            // Also directly show taglines to ensure immediate visibility
            $siteTaglines.removeClass('mobile-hidden');
        } else {
            $siteLogo.addClass('hide-mobile-tagline');
            // Also directly hide taglines for immediate effect
            $siteTaglines.addClass('mobile-hidden');
        }
    }
    
    /**
     * Update mobile logo alignment
     * Note: On mobile (768px and below), logo is forced to left corner for optimal UX
     */
    function updateMobileLogoAlignment(alignment) {
        const $siteBranding = $('.site-branding');
        const $mobileLogo = $('.mobile-logo');
        
        // Remove existing alignment classes
        $siteBranding.removeClass('mobile-logo-left mobile-logo-center mobile-logo-right');
        $mobileLogo.removeClass('mobile-logo-left mobile-logo-center mobile-logo-right');
        
        // Add new alignment class
        const alignmentClass = 'mobile-logo-' + alignment;
        $siteBranding.addClass(alignmentClass);
        $mobileLogo.addClass(alignmentClass);
        
        // Update CSS custom property for alignment
        updateCSSProperty('--mobile-logo-alignment', alignment);
        
        // Note: Mobile responsiveness is handled by CSS media queries
        // Logo is forced to left corner on mobile for better UX
    }
    
    /**
     * Update header height
     */
    function updateHeaderHeight(height) {
        const heightValue = height + 'px';
        
        // Update CSS custom property
        updateCSSProperty('--header-height', heightValue);
        
        // Apply direct CSS for immediate effect
        const style = `
            <style id="header-height-preview">
            .site-header {
                height: ${heightValue} !important;
                min-height: ${heightValue} !important;
            }
            .site-header .site-branding {
                height: ${heightValue} !important;
                display: flex !important;
                align-items: center !important;
            }
            /* Mobile override for 768px */
            @media (max-width: 768px) {
                .site-header {
                    height: 72px !important;
                    min-height: 72px !important;
                }
            }
            </style>
        `;
        
        $('#header-height-preview').remove();
        $('head').append(style);
    }

    /**
     * Setup mobile icon styling updates
     */
    function setupMobileIconUpdates() {
        
        // Mobile icon styling controls
        function updateMobileIconStyles() {
            var darkLightSize = wp.customize('mobile_dark_light_icon_size')() || 16;
            var darkLightPadding = wp.customize('mobile_dark_light_padding')() || 8;
            var darkLightMargin = wp.customize('mobile_dark_light_margin')() || 4;
            var searchSize = wp.customize('mobile_search_icon_size')() || 16;
            var searchPadding = wp.customize('mobile_search_padding')() || 8;
            var searchMargin = wp.customize('mobile_search_margin')() || 4;
            var menuSize = wp.customize('mobile_menu_icon_size')() || 18;
            var menuPadding = wp.customize('mobile_menu_padding')() || 8;
            var menuMargin = wp.customize('mobile_menu_margin')() || 4;
            
            var style = '<style id="mobile-icon-preview-styles">';
            style += '.dark-light-toggle .toggle-icon { font-size: ' + darkLightSize + 'px !important; padding: ' + darkLightPadding + 'px !important; margin: ' + darkLightMargin + 'px !important; }';
            style += '.mobile-search-toggle .toggle-icon { font-size: ' + searchSize + 'px !important; padding: ' + searchPadding + 'px !important; margin: ' + searchMargin + 'px !important; }';
            style += '.menu-toggle .toggle-icon { font-size: ' + menuSize + 'px !important; padding: ' + menuPadding + 'px !important; margin: ' + menuMargin + 'px !important; }';
            style += '</style>';
            
            $('#mobile-icon-preview-styles').remove();
            $('head').append(style);
        }
        
        // Dark/Light toggle styling
        wp.customize('mobile_dark_light_icon_size', function(value) {
            value.bind(updateMobileIconStyles);
        });
        wp.customize('mobile_dark_light_padding', function(value) {
            value.bind(updateMobileIconStyles);
        });
        wp.customize('mobile_dark_light_margin', function(value) {
            value.bind(updateMobileIconStyles);
        });
        
        // Search toggle styling
        wp.customize('mobile_search_icon_size', function(value) {
            value.bind(updateMobileIconStyles);
        });
        wp.customize('mobile_search_padding', function(value) {
            value.bind(updateMobileIconStyles);
        });
        wp.customize('mobile_search_margin', function(value) {
            value.bind(updateMobileIconStyles);
        });
        
        // Menu toggle styling
        wp.customize('mobile_menu_icon_size', function(value) {
            value.bind(updateMobileIconStyles);
        });
        wp.customize('mobile_menu_padding', function(value) {
            value.bind(updateMobileIconStyles);
        });
        wp.customize('mobile_menu_margin', function(value) {
            value.bind(updateMobileIconStyles);
        });
        
    }
    
    /**
     * Setup mobile section updates
     */
    function setupMobileUpdates() {
        // Mobile header spacing controls
        function updateMobileHeaderSpacing() {
            var paddingTop = wp.customize('mobile_header_padding_top')() || 8;
            var paddingBottom = wp.customize('mobile_header_padding_bottom')() || 8;
            var paddingLeft = wp.customize('mobile_header_padding_left')() || 16;
            var paddingRight = wp.customize('mobile_header_padding_right')() || 16;
            var marginTop = wp.customize('mobile_header_margin_top')() || 0;
            var marginBottom = wp.customize('mobile_header_margin_bottom')() || 0;
            
            var style = '<style id="mobile-header-spacing-preview">';
            style += '@media (max-width: 768px) {';
            style += '.site-header { ';
            style += 'padding: ' + paddingTop + 'px ' + paddingRight + 'px ' + paddingBottom + 'px ' + paddingLeft + 'px !important; ';
            style += 'margin: ' + marginTop + 'px 0 ' + marginBottom + 'px 0 !important; ';
            style += '}';
            style += '}';
            style += '</style>';
            
            $('#mobile-header-spacing-preview').remove();
            $('head').append(style);
        }
        
        // Mobile header padding controls
        wp.customize('mobile_header_padding_top', function(value) {
            value.bind(updateMobileHeaderSpacing);
        });
        wp.customize('mobile_header_padding_bottom', function(value) {
            value.bind(updateMobileHeaderSpacing);
        });
        wp.customize('mobile_header_padding_left', function(value) {
            value.bind(updateMobileHeaderSpacing);
        });
        wp.customize('mobile_header_padding_right', function(value) {
            value.bind(updateMobileHeaderSpacing);
        });
        
        // Mobile header margin controls
        wp.customize('mobile_header_margin_top', function(value) {
            value.bind(updateMobileHeaderSpacing);
        });
        wp.customize('mobile_header_margin_bottom', function(value) {
            value.bind(updateMobileHeaderSpacing);
        });
        
        // Mobile logo controls
        wp.customize('mobile_logo', function(value) {
            value.bind(function(to) {
                updateMobileLogo(to);
            });
        });
        
        wp.customize('mobile_logo_width_value', function(value) {
            value.bind(function(to) {
                updateMobileLogoDimensions();
            });
        });
        
        wp.customize('mobile_logo_width_unit', function(value) {
            value.bind(function(to) {
                updateMobileLogoDimensions();
            });
        });
        
        wp.customize('mobile_logo_height_value', function(value) {
            value.bind(function(to) {
                updateMobileLogoDimensions();
            });
        });
        
        wp.customize('mobile_logo_height_unit', function(value) {
            value.bind(function(to) {
                updateMobileLogoDimensions();
            });
        });
        
        wp.customize('show_mobile_logo', function(value) {
            value.bind(function(to) {
                updateMobileLogoVisibility(to);
            });
        });
        
        wp.customize('show_mobile_tagline', function(value) {
            value.bind(function(to) {
                updateMobileTaglineVisibility(to);
            });
        });
        
        // Mobile logo alignment
        wp.customize('mobile_logo_alignment', function(value) {
            value.bind(function(to) {
                updateMobileLogoAlignment(to);
            });
        });
    }

    // Initialize when document is ready
    $(document).ready(function() {
        // Debug first
        setTimeout(debugCustomizerSettings, 1000);
        
        // Then initialize
        initPreviewUpdates();
    });

})(jQuery);