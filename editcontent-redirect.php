<?php
/**
 * Simple redirect to content editor
 * This file should be placed in the WordPress root directory
 * Access via: dragicacarlin.1976uk.com/editcontent
 */

// Redirect to the actual editor
$editor_url = '/wp-content/themes/artist-theme/editcontent.php';

// Check if the file exists
$editor_path = $_SERVER['DOCUMENT_ROOT'] . $editor_url;
if (file_exists($editor_path)) {
    header('Location: ' . $editor_url);
    exit;
} else {
    // Fallback error message
    header('HTTP/1.0 404 Not Found');
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Content Editor Not Found</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
            .error { color: #d32f2f; }
            .info { color: #1976d2; margin-top: 20px; }
        </style>
    </head>
    <body>
        <h1 class="error">Content Editor Not Found</h1>
        <p>The gallery content editor could not be found.</p>
        <div class="info">
            <p><strong>Expected location:</strong><br>' . $editor_path . '</p>
            <p><strong>Try accessing directly:</strong><br>
            <a href="' . $editor_url . '">' . $_SERVER['HTTP_HOST'] . $editor_url . '</a></p>
        </div>
    </body>
    </html>';
}
?>
