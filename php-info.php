<?php
// PHP Version Check Script
// Upload this file to your website root directory
// Then visit: dragicacarlin.1976uk.com/php-info.php

echo "<h1>PHP Version Information</h1>";
echo "<p><strong>Current PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>Operating System:</strong> " . PHP_OS . "</p>";

// Check if PHP version is outdated
$current_version = phpversion();
$min_recommended = '8.0';

if (version_compare($current_version, $min_recommended, '<')) {
    echo "<div style='background: #ffebee; border: 1px solid #f44336; padding: 15px; margin: 10px 0; border-radius: 4px;'>";
    echo "<h3 style='color: #d32f2f; margin: 0 0 10px 0;'>⚠️ PHP UPDATE NEEDED</h3>";
    echo "<p>Your PHP version ($current_version) is outdated.</p>";
    echo "<p>WordPress recommends PHP $min_recommended or higher.</p>";
    echo "</div>";
} else {
    echo "<div style='background: #e8f5e8; border: 1px solid #4caf50; padding: 15px; margin: 10px 0; border-radius: 4px;'>";
    echo "<h3 style='color: #2e7d32; margin: 0 0 10px 0;'>✅ PHP VERSION OK</h3>";
    echo "<p>Your PHP version ($current_version) is up to date!</p>";
    echo "</div>";
}

echo "<hr>";
echo "<h2>Full PHP Configuration (for debugging)</h2>";
echo "<p><em>This shows all PHP settings. Scroll down to see everything.</em></p>";

// Show full PHP info
phpinfo();
?>
