<?php
/*
Plugin Name: PHP Version Checker
Description: Shows PHP version in WordPress admin
Version: 1.0
*/

// Add admin menu
add_action('admin_menu', 'php_checker_menu');

function php_checker_menu() {
    add_management_page(
        'PHP Version Check',
        'PHP Version',
        'manage_options',
        'php-version-check',
        'php_checker_page'
    );
}

function php_checker_page() {
    echo '<div class="wrap">';
    echo '<h1>PHP Version Information</h1>';
    
    $current_version = phpversion();
    $min_recommended = '8.0';
    
    echo '<div class="notice notice-info">';
    echo '<p><strong>Current PHP Version:</strong> ' . $current_version . '</p>';
    echo '<p><strong>Server Software:</strong> ' . $_SERVER['SERVER_SOFTWARE'] . '</p>';
    echo '</div>';
    
    if (version_compare($current_version, $min_recommended, '<')) {
        echo '<div class="notice notice-error">';
        echo '<h3>⚠️ PHP UPDATE NEEDED</h3>';
        echo '<p>Your PHP version (' . $current_version . ') is outdated.</p>';
        echo '<p>WordPress recommends PHP ' . $min_recommended . ' or higher.</p>';
        echo '<p><strong>Next steps:</strong></p>';
        echo '<ol>';
        echo '<li>Log into your HostGator cPanel</li>';
        echo '<li>Look for "Select PHP Version" in the Software section</li>';
        echo '<li>Choose PHP 8.1 (recommended)</li>';
        echo '<li>Click "Set as current"</li>';
        echo '</ol>';
        echo '<p>If you can\'t find the PHP selector, contact HostGator support at 1-866-964-2867</p>';
        echo '</div>';
    } else {
        echo '<div class="notice notice-success">';
        echo '<h3>✅ PHP VERSION OK</h3>';
        echo '<p>Your PHP version (' . $current_version . ') is up to date!</p>';
        echo '</div>';
    }
    
    echo '</div>';
}
?>
