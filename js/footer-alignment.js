/**
 * Footer Menu Alignment Force Script
 * This script ensures footer menu alignment works regardless of CSS conflicts
 */

document.addEventListener('DOMContentLoaded', function() {
    function applyFooterMenuAlignment() {
        const footerMenu = document.querySelector('#footer-menu');
        const bodyClasses = document.body.classList;
        
        if (!footerMenu) {
            console.log('Footer menu not found');
            return;
        }
        
        console.log('Footer menu found:', footerMenu);
        console.log('Body classes:', document.body.className);
        
        // Force flex display and basic properties
        footerMenu.style.display = 'flex';
        footerMenu.style.flexWrap = 'wrap';
        footerMenu.style.width = '100%';
        footerMenu.style.listStyle = 'none';
        footerMenu.style.margin = '0';
        footerMenu.style.padding = '0';
        footerMenu.style.gap = '1.5rem';
        
        // Get background color from CSS variable
        const computedStyle = getComputedStyle(document.documentElement);
        const backgroundColor = computedStyle.getPropertyValue('--footer-menu-background').trim() || 'transparent';
        
        // Apply alignment based on body class
        if (bodyClasses.contains('footer-menu-left')) {
            footerMenu.style.justifyContent = 'flex-start';
            footerMenu.style.background = backgroundColor;
            console.log('Applied LEFT alignment');
        } else if (bodyClasses.contains('footer-menu-right')) {
            footerMenu.style.justifyContent = 'flex-end';
            footerMenu.style.background = backgroundColor;
            console.log('Applied RIGHT alignment');
        } else {
            footerMenu.style.justifyContent = 'center';
            footerMenu.style.background = backgroundColor;
            console.log('Applied CENTER alignment (default)');
        }
    }
    
    // Expose function globally for customizer access
    window.applyFooterMenuAlignment = applyFooterMenuAlignment;
    
    // Apply alignment on load
    applyFooterMenuAlignment();
    
    // Watch for body class changes (from Customizer)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                console.log('Body class changed, reapplying footer alignment');
                applyFooterMenuAlignment();
            }
        });
    });
    
    observer.observe(document.body, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    console.log('Footer menu alignment script loaded');
});