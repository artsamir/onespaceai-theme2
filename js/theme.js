/**
 * Theme JavaScript
 * Frontend functionality for OnespaceTheme2
 *
 * @package OnespaceTheme2
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Initialize theme
    function initTheme() {
        setupDarkLightToggle();
        setupMobileMenu();
        setupAccessibility();
        setupMobileSearch();
    }

    /**
     * Setup dark/light mode toggle
     */
    function setupDarkLightToggle() {
        const toggle = document.querySelector('.dark-light-toggle');
        
        if (!toggle) return;

        // Get current mode from localStorage or default to light
        let currentMode = localStorage.getItem('onespace-theme-mode') || 'light';
        
        // Apply initial mode
        applyThemeMode(currentMode);
        
        // Toggle functionality
        toggle.addEventListener('click', function() {
            currentMode = currentMode === 'light' ? 'dark' : 'light';
            applyThemeMode(currentMode);
            localStorage.setItem('onespace-theme-mode', currentMode);
        });

        // Keyboard support
        toggle.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggle.click();
            }
        });
    }

    /**
     * Apply theme mode
     */
    function applyThemeMode(mode) {
        const body = document.body;
        const toggle = document.querySelector('.dark-light-toggle');
        
        if (!toggle) return;

        const lightIcon = toggle.querySelector('.light-icon');
        const darkIcon = toggle.querySelector('.dark-icon');

        if (mode === 'dark') {
            body.classList.add('dark-mode');
            body.classList.remove('light-mode');
            
            if (lightIcon) lightIcon.style.display = 'none';
            if (darkIcon) darkIcon.style.display = 'block';
        } else {
            body.classList.add('light-mode');
            body.classList.remove('dark-mode');
            
            if (lightIcon) lightIcon.style.display = 'block';
            if (darkIcon) darkIcon.style.display = 'none';
        }
    }

    /**
     * Mobile search interactions
     */
    function setupMobileSearch() {
        const body = document.body;
        const searchToggle = document.querySelector('.mobile-search-toggle');
        const searchForm = document.getElementById('header-search-form');
        const searchInput = searchForm ? searchForm.querySelector('input[type="search"]') : null;
        const themeToggle = document.querySelector('.dark-light-toggle');

        if (!searchToggle || !searchForm) return;

        function openSearch() {
            body.classList.add('search-open');
            searchToggle.setAttribute('aria-expanded', 'true');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 0);
            }
        }

        function closeSearch() {
            body.classList.remove('search-open');
            searchToggle.setAttribute('aria-expanded', 'false');
        }

        // Toggle search on icon click
        searchToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (body.classList.contains('search-open')) {
                closeSearch();
            } else {
                openSearch();
            }
        });

        // Close when clicking outside search area
        document.addEventListener('click', function(e) {
            if (!body.classList.contains('search-open')) return;
            if (searchForm.contains(e.target) || searchToggle.contains(e.target)) return;
            closeSearch();
        });

        // Close when pressing ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && body.classList.contains('search-open')) {
                closeSearch();
            }
        });

        // Close search when theme toggle is activated
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                if (body.classList.contains('search-open')) {
                    closeSearch();
                }
            });
        }
    }

    /**
     * Setup mobile menu functionality
     */
    function setupMobileMenu() {
        // Create mobile menu toggle if it doesn't exist
        const nav = document.querySelector('.main-navigation');
        if (!nav) return;

        let menuToggle = document.querySelector('.menu-toggle');
        
        if (!menuToggle) {
            menuToggle = document.createElement('button');
            menuToggle.className = 'menu-toggle';
            menuToggle.setAttribute('aria-controls', 'primary-menu');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.innerHTML = '<span class="screen-reader-text">Menu</span><span class="hamburger"></span>';
            
            nav.parentNode.insertBefore(menuToggle, nav);
        }

        const menu = nav.querySelector('ul');
        if (!menu) return;

        menu.setAttribute('id', 'primary-menu');

        // Toggle functionality
        menuToggle.addEventListener('click', function() {
            const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !expanded);
            nav.classList.toggle('toggled');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!nav.contains(e.target) && !menuToggle.contains(e.target)) {
                menuToggle.setAttribute('aria-expanded', 'false');
                nav.classList.remove('toggled');
            }
        });

        // Handle submenu accessibility on mobile
        const subMenus = nav.querySelectorAll('.menu-item-has-children > a');
        subMenus.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const submenu = link.nextElementSibling;
                    if (submenu) {
                        submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                    }
                }
            });
        });
    }

    /**
     * Setup accessibility features
     */
    function setupAccessibility() {
        // Skip to content link
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', function(e) {
                const target = document.querySelector(skipLink.getAttribute('href'));
                if (target) {
                    target.focus();
                }
            });
        }

        // Focus management for dropdowns
        const dropdownToggles = document.querySelectorAll('.menu-item-has-children > a');
        dropdownToggles.forEach(function(toggle) {
            toggle.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const submenu = toggle.nextElementSibling;
                    if (submenu) {
                        const firstLink = submenu.querySelector('a');
                        if (firstLink) {
                            firstLink.focus();
                        }
                    }
                }
            });
        });

        // Search form accessibility
        const searchForm = document.querySelector('.header-search');
        if (searchForm) {
            const searchInput = searchForm.querySelector('input[type="search"]');
            const searchSubmit = searchForm.querySelector('input[type="submit"]');
            
            if (searchInput && searchSubmit) {
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        searchForm.submit();
                    }
                });
            }
        }
    }

    // Initialize when DOM is ready
    $(document).ready(function() {
        initTheme();
    });

    // Handle window resize
    $(window).on('resize', function() {
        // Reset mobile menu state on resize
        const menuToggle = document.querySelector('.menu-toggle');
        const nav = document.querySelector('.main-navigation');
        
        if (menuToggle && nav && window.innerWidth > 768) {
            menuToggle.setAttribute('aria-expanded', 'false');
            nav.classList.remove('toggled');
        }
    });

})(jQuery);