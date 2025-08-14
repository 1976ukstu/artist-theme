<?php
// Test Database Connection and Setup
require_once 'gallery-database.php';

echo "<h1>🗄️ Gallery Database Test</h1>";

try {
    // Initialize database
    $gallery = new GalleryDatabase();
    echo "<p>✅ <strong>Database connection successful!</strong></p>";
    
    // Get current stats
    $stats = $gallery->getStats();
    echo "<h2>📊 Current Database Stats:</h2>";
    
    if (empty($stats)) {
        echo "<p>📝 Database is empty - ready for first artworks!</p>";
    } else {
        echo "<ul>";
        foreach ($stats as $stat) {
            echo "<li><strong>" . ucfirst($stat['category']) . ":</strong> " . $stat['count'] . " artworks</li>";
        }
        echo "</ul>";
    }
    
    // Test adding a sample artwork (we'll remove this later)
    echo "<h2>🧪 Testing Add Artwork Function:</h2>";
    $testId = $gallery->addArtwork(
        "Test Painting", 
        "test-image.jpg", 
        "paintings", 
        "This is a test artwork", 
        "24x36 inches", 
        2024
    );
    
    if ($testId) {
        echo "<p>✅ <strong>Successfully added test artwork with ID: $testId</strong></p>";
        
        // Get paintings to verify
        $paintings = $gallery->getArtworksByCategory('paintings');
        echo "<p>📋 Found " . count($paintings) . " painting(s) in database</p>";
        
        // Clean up test data
        $gallery->deleteArtwork($testId);
        echo "<p>🧹 Test artwork cleaned up</p>";
    } else {
        echo "<p>❌ Failed to add test artwork</p>";
    }
    
    // Test backup functionality
    echo "<h2>💾 Testing Backup Function:</h2>";
    $backupPath = $gallery->createBackup();
    if ($backupPath) {
        echo "<p>✅ <strong>Backup created successfully:</strong> " . basename($backupPath) . "</p>";
    } else {
        echo "<p>❌ Backup failed</p>";
    }
    
    echo "<hr>";
    echo "<p><strong>🚀 Database is ready for the dashboard interface!</strong></p>";
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Database Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your server's SQLite support.</p>";
}
?>

<style>
body {
    font-family: 'Helvetica', Arial, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: #f8f9fa;
}
h1, h2 {
    color: #2c3e50;
}
p {
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
ul {
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>