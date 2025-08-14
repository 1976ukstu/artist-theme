<?php
// Gallery Database Setup and Management
// This creates and manages our artwork database

class GalleryDatabase {
    private $db;
    private $dbPath;
    
    public function __construct() {
        $this->dbPath = __DIR__ . '/gallery.db';
        $this->initDatabase();
    }
    
    private function initDatabase() {
        try {
            // Create or open the SQLite database
            $this->db = new PDO('sqlite:' . $this->dbPath);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create artworks table if it doesn't exist
            $this->createTables();
            
        } catch(PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database setup failed");
        }
    }
    
    private function createTables() {
        $sql = "
        CREATE TABLE IF NOT EXISTS artworks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            filename TEXT NOT NULL,
            category TEXT NOT NULL DEFAULT 'paintings',
            display_order INTEGER DEFAULT 0,
            upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            description TEXT,
            dimensions TEXT,
            year INTEGER,
            status TEXT DEFAULT 'active'
        )";
        
        $this->db->exec($sql);
        
        // Create backup log table
        $backupSql = "
        CREATE TABLE IF NOT EXISTS backup_log (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            backup_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            action TEXT,
            details TEXT
        )";
        
        $this->db->exec($backupSql);
    }
    
    // Add artwork to database
    public function addArtwork($title, $filename, $category = 'paintings', $description = '', $dimensions = '', $year = null) {
        try {
            // Get next display order
            $stmt = $this->db->prepare("SELECT MAX(display_order) as max_order FROM artworks WHERE category = ?");
            $stmt->execute([$category]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nextOrder = ($result['max_order'] ?? 0) + 1;
            
            // Insert new artwork
            $stmt = $this->db->prepare("
                INSERT INTO artworks (title, filename, category, display_order, description, dimensions, year) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([$title, $filename, $category, $nextOrder, $description, $dimensions, $year]);
            
            if ($result) {
                $this->logAction("Added artwork: $title to $category");
                return $this->db->lastInsertId();
            }
            
            return false;
            
        } catch(PDOException $e) {
            error_log("Add artwork failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Get all artworks by category
    public function getArtworksByCategory($category = 'paintings') {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM artworks 
                WHERE category = ? AND status = 'active' 
                ORDER BY display_order ASC
            ");
            $stmt->execute([$category]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            error_log("Get artworks failed: " . $e->getMessage());
            return [];
        }
    }
    
    // Update display order (for drag & drop)
    public function updateDisplayOrder($artworkId, $newOrder, $category) {
        try {
            $stmt = $this->db->prepare("
                UPDATE artworks 
                SET display_order = ? 
                WHERE id = ? AND category = ?
            ");
            
            $result = $stmt->execute([$newOrder, $artworkId, $category]);
            
            if ($result) {
                $this->logAction("Updated display order for artwork ID: $artworkId");
            }
            
            return $result;
            
        } catch(PDOException $e) {
            error_log("Update order failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Delete artwork (soft delete - marks as inactive)
    public function deleteArtwork($artworkId) {
        try {
            $stmt = $this->db->prepare("
                UPDATE artworks 
                SET status = 'deleted' 
                WHERE id = ?
            ");
            
            $result = $stmt->execute([$artworkId]);
            
            if ($result) {
                $this->logAction("Deleted artwork ID: $artworkId");
            }
            
            return $result;
            
        } catch(PDOException $e) {
            error_log("Delete artwork failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Create backup
    public function createBackup() {
        try {
            $backupPath = __DIR__ . '/backups/gallery_backup_' . date('Y-m-d_H-i-s') . '.db';
            
            // Create backups directory if it doesn't exist
            $backupDir = dirname($backupPath);
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            // Copy database file
            if (copy($this->dbPath, $backupPath)) {
                $this->logAction("Created backup: " . basename($backupPath));
                return $backupPath;
            }
            
            return false;
            
        } catch(Exception $e) {
            error_log("Backup failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Log actions for debugging
    private function logAction($action) {
        try {
            $stmt = $this->db->prepare("INSERT INTO backup_log (action) VALUES (?)");
            $stmt->execute([$action]);
        } catch(PDOException $e) {
            error_log("Log action failed: " . $e->getMessage());
        }
    }
    
    // Get database stats
    public function getStats() {
        try {
            $stmt = $this->db->query("
                SELECT 
                    category,
                    COUNT(*) as count 
                FROM artworks 
                WHERE status = 'active' 
                GROUP BY category
            ");
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            error_log("Get stats failed: " . $e->getMessage());
            return [];
        }
    }
}
?>