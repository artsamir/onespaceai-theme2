<?php
/*
Template Name: Footer Menu Debug
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Footer Menu Debug - <?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
    <style>
        body { 
            margin: 20px; 
            font-family: Arial, sans-serif; 
            background: #f0f0f0;
        }
        .debug-section { 
            background: #fff; 
            padding: 20px; 
            margin: 20px 0; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .debug-info { 
            background: #f8f9fa; 
            padding: 10px; 
            border-left: 4px solid #007cba; 
            margin: 10px 0; 
            font-family: monospace; 
        }
        h2 { color: #333; margin-top: 0; }
        h3 { color: #666; }
        .test-controls { margin: 20px 0; }
        .test-controls button { 
            padding: 10px 20px; 
            margin: 5px; 
            background: #007cba; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }
        .test-controls button:hover { background: #005a87; }
    </style>
</head>
<body <?php body_class(); ?>>

<div class="debug-section">
    <h2>Footer Menu Debug Information</h2>
    
    <h3>Current Theme Settings:</h3>
    <div class="debug-info">
        Footer Menu Position: <?php echo esc_html(get_theme_mod('footer_menu_position', 'center')); ?><br>
        Copyright Position: <?php echo esc_html(get_theme_mod('footer_copyright_position', 'left')); ?><br>
        Body Classes: <?php echo esc_html(implode(' ', get_body_class())); ?>
    </div>
    
    <h3>Menu Location Debug:</h3>
    <div class="debug-info">
        <?php
        // Check if footer menu location is registered
        $locations = get_registered_nav_menus();
        echo "Registered menu locations:<br>";
        foreach ($locations as $location => $description) {
            echo "- {$location}: {$description}<br>";
        }
        
        // Check if any menu is assigned to footer location
        $menu_locations = get_nav_menu_locations();
        echo "<br>Assigned menus:<br>";
        foreach ($menu_locations as $location => $menu_id) {
            $menu = wp_get_nav_menu_object($menu_id);
            $menu_name = $menu ? $menu->name : 'None';
            echo "- {$location}: {$menu_name} (ID: {$menu_id})<br>";
        }
        
        // Check if footer location has a menu
        $has_footer_menu = has_nav_menu('footer');
        echo "<br>has_nav_menu('footer'): " . ($has_footer_menu ? 'Yes' : 'No') . "<br>";
        ?>
    </div>

    <h3>CSS Variables (from functions.php):</h3>
    <div class="debug-info">
        <?php
        // Get CSS variables that would be generated
        $css_vars = array();
        
        // Footer menu offsets
        $offsets = array(
            'footer_menu_offset_top' => '--footer-menu-offset-top',
            'footer_menu_offset_right' => '--footer-menu-offset-right',
            'footer_menu_offset_bottom' => '--footer-menu-offset-bottom',
            'footer_menu_offset_left' => '--footer-menu-offset-left',
        );
        foreach ($offsets as $mod => $var) {
            $value = get_theme_mod($mod, 0);
            $css_vars[$var] = intval($value) . 'px';
        }
        
        // Colors
        $css_vars['--footer-menu-text-color'] = get_theme_mod('footer_menu_text_color', '#ffffff');
        $css_vars['--footer-menu-hover-text-color'] = get_theme_mod('footer_menu_hover_text_color', '#e9ecef');
        $css_vars['--footer-menu-hover-bg'] = get_theme_mod('footer_menu_hover_bg_color', 'rgba(255,255,255,0.08)');
        
        // Spacing
        $css_vars['--footer-menu-letter-spacing'] = floatval(get_theme_mod('footer_menu_letter_spacing', 0)) . 'em';
        $css_vars['--footer-menu-padding-x'] = floatval(get_theme_mod('footer_menu_padding_x', 0.75)) . 'rem';
        $css_vars['--footer-menu-padding-y'] = floatval(get_theme_mod('footer_menu_padding_y', 0.5)) . 'rem';
        
        foreach ($css_vars as $var => $value) {
            echo esc_html($var . ': ' . $value) . '<br>';
        }
        ?>
    </div>
    
    <h3>Test Controls:</h3>
    <div class="test-controls">
        <button onclick="setPosition('left')">Set Left</button>
        <button onclick="setPosition('center')">Set Center</button>
        <button onclick="setPosition('right')">Set Right</button>
        <button onclick="showComputedStyles()">Show Computed Styles</button>
    </div>
</div>

<div class="debug-section">
    <h2>Footer Menu Test</h2>
    
    <?php if (has_nav_menu('footer')) : ?>
        <div class="site-footer">
            <div class="footer-middle">
                <div class="footer-content">
                    <nav class="footer-navigation" role="navigation" aria-label="Footer Navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'container'      => false,
                            'depth'          => 1,
                        ));
                        ?>
                    </nav>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="debug-info">
            No footer menu is assigned. Please go to Appearance > Menus and assign a menu to the "Footer Menu" location.
        </div>
        
        <!-- Fallback test menu -->
        <div class="site-footer">
            <div class="footer-middle">
                <div class="footer-content">
                    <nav class="footer-navigation">
                        <ul>
                            <li><a href="#">Test Link 1</a></li>
                            <li><a href="#">Test Link 2</a></li>
                            <li><a href="#">Test Link 3</a></li>
                            <li><a href="#">Test Link 4</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="debug-section">
    <h2>CSS Rules Inspection</h2>
    <div id="css-debug" class="debug-info">
        Click "Show Computed Styles" above to see the current CSS rules.
    </div>
</div>

<script>
function setPosition(position) {
    // Remove existing classes
    document.body.classList.remove('footer-menu-left', 'footer-menu-center', 'footer-menu-right');
    // Add new class
    document.body.classList.add('footer-menu-' + position);
    
    console.log('Set footer menu position to:', position);
    console.log('Body classes:', document.body.className);
}

function showComputedStyles() {
    const nav = document.querySelector('.footer-navigation ul');
    if (nav) {
        const styles = window.getComputedStyle(nav);
        const info = document.getElementById('css-debug');
        info.innerHTML = `
            <strong>Computed styles for .footer-navigation ul:</strong><br>
            display: ${styles.display}<br>
            justify-content: ${styles.justifyContent}<br>
            flex-wrap: ${styles.flexWrap}<br>
            width: ${styles.width}<br>
            gap: ${styles.gap}<br>
            margin: ${styles.margin}<br>
            padding: ${styles.padding}<br>
            list-style: ${styles.listStyle}<br>
            <br>
            <strong>Body classes:</strong> ${document.body.className}<br>
            <br>
            <strong>Element info:</strong><br>
            Element found: ${nav ? 'Yes' : 'No'}<br>
            Element selector: .footer-navigation ul<br>
            Number of menu items: ${nav.children.length}
        `;
    } else {
        document.getElementById('css-debug').innerHTML = 'Footer navigation UL element not found!';
    }
}

// Auto-show computed styles on load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(showComputedStyles, 500);
});
</script>

<?php wp_footer(); ?>
</body>
</html>