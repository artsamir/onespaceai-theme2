/**
 * Customizer Controls JavaScript
 * Handles accordion UI, conditional logic, and control grouping
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Debug logging
    function debugLog(message, data) {
        if (window.onespace_customizer_data && window.onespace_customizer_data.debug) {
            console.log('[OnespaceTheme2 Customizer]', message, data || '');
        }
    }

    // Grouping is disabled because settings are organized as top-level sections now.

    // Initialize customizer controls
    function initCustomizerControls() {
        debugLog('Initializing customizer controls...');

        // Wait for the customizer to be ready
        wp.customize.bind('ready', function() {
            setTimeout(function() {
                // Accordion grouping disabled; controls live in dedicated sections
                setupConditionalLogic();
                debugLog('Customizer controls initialized successfully');
            }, 500); // Short delay to ensure DOM is ready
        });
    }

    // Initialize accordion groups
    // initializeAccordionGroups removed

    // Create individual accordion group
    function createAccordionGroup(container, groupName, config) {
        debugLog('Creating group:', groupName);

        const groupId = groupName.toLowerCase().replace(/[^a-z0-9]+/g, '-');
        const expanded = config.expanded || false;

        // Create group HTML
        const groupHtml = `
            <div class="onespace-group" data-group="${groupId}">
                <div class="onespace-group__header" 
                     role="button" 
                     tabindex="0" 
                     aria-expanded="${expanded}" 
                     aria-controls="onespace-group-${groupId}">
                    <h3>${groupName}</h3>
                    <span class="onespace-group__chevron" aria-hidden="true"></span>
                </div>
                <div class="onespace-group__body" 
                     id="onespace-group-${groupId}" 
                     style="display: ${expanded ? 'block' : 'none'}">
                </div>
            </div>
        `;

        const $group = $(groupHtml);
        container.append($group);

        // Move controls to group
        moveControlsToGroup($group.find('.onespace-group__body'), config.controls, groupName);

        // Setup accordion behavior
        setupAccordionBehavior($group);
    }

    // Move controls to accordion group
    function moveControlsToGroup($groupBody, controlIds, groupName) {
        debugLog('Moving controls to group:', groupName);

        const movedControls = [];
        const missingControls = [];

        controlIds.forEach(function(controlId) {
            const control = wp.customize.control(controlId);
            
            if (control && control.container) {
                const $controlElement = control.container;
                $groupBody.append($controlElement);
                movedControls.push(controlId);
                debugLog('Moved control:', controlId);
            } else {
                missingControls.push(controlId);
                debugLog('Control not found:', controlId);
            }
        });

        debugLog(`Group "${groupName}" final status:`, {
            moved: movedControls,
            missing: missingControls,
            total: controlIds.length
        });
    }

    // Setup accordion behavior
    function setupAccordionBehavior($group) {
        const $header = $group.find('.onespace-group__header');
        const $body = $group.find('.onespace-group__body');
        const $chevron = $header.find('.onespace-group__chevron');

        // Click handler
        $header.on('click', function() {
            toggleAccordion($group, $header, $body, $chevron);
        });

        // Keyboard handler
        $header.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleAccordion($group, $header, $body, $chevron);
            }
        });
    }

    // Toggle accordion state
    function toggleAccordion($group, $header, $body, $chevron) {
        const isExpanded = $header.attr('aria-expanded') === 'true';
        const newState = !isExpanded;

        $header.attr('aria-expanded', newState);
        $body.slideToggle(300);
        $chevron.toggleClass('expanded', newState);

        debugLog('Toggled accordion:', {
            group: $group.data('group'),
            expanded: newState
        });
    }

    // Setup conditional logic
    function setupConditionalLogic() {
        debugLog('Setting up conditional logic...');

        // Search border color conditional on border width
        setupSearchBorderConditional();

        // Toggle icon mode conditionals
        setupToggleIconModeConditionals();

        // Search scope conditionals
        setupSearchScopeConditionals();
    }

    // Search border color conditional logic
    function setupSearchBorderConditional() {
        const borderWidthControl = wp.customize.control('header_search_border_width');
        const borderColorControl = wp.customize.control('header_search_border_color');

        if (!borderWidthControl || !borderColorControl) {
            debugLog('Search border controls not found');
            return;
        }

        function updateBorderColorState() {
            const borderWidth = wp.customize('header_search_border_width').get();
            const isEnabled = parseFloat(borderWidth) > 0;

            if (isEnabled) {
                borderColorControl.container.removeClass('onespace-disabled');
                borderColorControl.container.css('opacity', '1');
            } else {
                borderColorControl.container.addClass('onespace-disabled');
                borderColorControl.container.css('opacity', '0.5');
            }

            debugLog('Search border color state updated:', { enabled: isEnabled, width: borderWidth });
        }

        // Initial state
        updateBorderColorState();

        // Listen for changes
        wp.customize('header_search_border_width', function(value) {
            value.bind(updateBorderColorState);
        });
    }

    // Toggle icon mode conditional logic
    function setupToggleIconModeConditionals() {
        const modeControl = wp.customize.control('header_toggle_icon_mode');

        if (!modeControl) {
            debugLog('Toggle icon mode control not found');
            return;
        }

        const textControls = [
            'header_toggle_icon_light',
            'header_toggle_icon_dark'
        ];

        const imageControls = [
            'header_toggle_icon_light_image',
            'header_toggle_icon_dark_image'
        ];

        function updateToggleIconControls() {
            const mode = wp.customize('header_toggle_icon_mode').get();

            debugLog('Updating toggle icon controls for mode:', mode);

            if (mode === 'text') {
                showControls(textControls);
                hideControls(imageControls);
            } else if (mode === 'image') {
                hideControls(textControls);
                showControls(imageControls);
            }
        }

        // Initial state
        updateToggleIconControls();

        // Listen for changes
        wp.customize('header_toggle_icon_mode', function(value) {
            value.bind(updateToggleIconControls);
        });
    }

    // Search scope conditional logic
    function setupSearchScopeConditionals() {
        const scopeControl = wp.customize.control('header_search_scope');

        if (!scopeControl) {
            debugLog('Search scope control not found');
            return;
        }

        const customControls = ['header_search_custom_url'];
        const manualControls = ['header_search_manual_ids'];

        function updateSearchScopeControls() {
            const scope = wp.customize('header_search_scope').get();

            debugLog('Updating search scope controls for scope:', scope);

            if (scope === 'custom') {
                showControls(customControls);
                hideControls(manualControls);
            } else if (scope === 'manual') {
                hideControls(customControls);
                showControls(manualControls);
            } else {
                hideControls(customControls);
                hideControls(manualControls);
            }
        }

        // Initial state
        updateSearchScopeControls();

        // Listen for changes
        wp.customize('header_search_scope', function(value) {
            value.bind(updateSearchScopeControls);
        });
    }

    // Show controls by ID
    function showControls(controlIds) {
        controlIds.forEach(function(controlId) {
            const control = wp.customize.control(controlId);
            if (control && control.container) {
                control.container.show();
                debugLog('Showed control:', controlId);
            }
        });
    }

    // Hide controls by ID
    function hideControls(controlIds) {
        controlIds.forEach(function(controlId) {
            const control = wp.customize.control(controlId);
            if (control && control.container) {
                control.container.hide();
                debugLog('Hid control:', controlId);
            }
        });
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        debugLog('DOM ready, initializing customizer controls...');
        initCustomizerControls();
    });

    // Also try to initialize with MutationObserver as backup
    if (window.MutationObserver) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    // No section-specific DOM grouping anymore; still initialize conditionals once
                    setTimeout(initCustomizerControls, 100);
                    observer.disconnect();
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Disconnect observer after 10 seconds to prevent memory leaks
        setTimeout(function() {
            observer.disconnect();
        }, 10000);
    }

})(jQuery);