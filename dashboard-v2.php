<?php
// BULLETPROOF Gallery Dashboard v2.0 - Database Edition
require_once 'gallery-database.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $gallery = new GalleryDatabase();
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'upload':
                // Handle file upload
                $result = handleFileUpload($gallery);
                echo json_encode($result);
                break;
                
            case 'reorder':
                // Handle drag & drop reordering
                $artworkId = $_POST['artwork_id'];
                $newOrder = $_POST['new_order'];
                $category = $_POST['category'];
                
                $success = $gallery->updateDisplayOrder($artworkId, $newOrder, $category);
                echo json_encode(['success' => $success]);
                break;
                
            case 'delete':
                // Handle artwork deletion
                $artworkId = $_POST['artwork_id'];
                $success = $gallery->deleteArtwork($artworkId);
                echo json_encode(['success' => $success]);
                break;
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    exit;
}

function handleFileUpload($gallery) {
    if (!isset($_FILES['artwork']) || $_FILES['artwork']['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'No file uploaded or upload error'];
    }
    
    $file = $_FILES['artwork'];
    $title = $_POST['title'] ?? 'Untitled';
    $category = $_POST['category'] ?? 'paintings';
    $description = $_POST['description'] ?? '';
    $dimensions = $_POST['dimensions'] ?? '';
    $year = $_POST['year'] ?? null;
    
    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type. Please upload an image.'];
    }
    
    // Create uploads directory if it doesn't exist
    $uploadDir = __DIR__ . '/uploaded-artworks/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'artwork_' . time() . '_' . uniqid() . '.' . $extension;
    $filePath = $uploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Add to database
        $artworkId = $gallery->addArtwork($title, $filename, $category, $description, $dimensions, $year);
        
        if ($artworkId) {
            return [
                'success' => true, 
                'artwork_id' => $artworkId,
                'filename' => $filename,
                'message' => 'Artwork uploaded successfully!'
            ];
        } else {
            // Clean up file if database insert failed
            unlink($filePath);
            return ['success' => false, 'error' => 'Database insert failed'];
        }
    }
    
    return ['success' => false, 'error' => 'File upload failed'];
}

// Get current artworks
$gallery = new GalleryDatabase();
$paintings = $gallery->getArtworksByCategory('paintings');
$commissions = $gallery->getArtworksByCategory('commissions');
$smallWorks = $gallery->getArtworksByCategory('small-works');
$stats = $gallery->getStats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎨 Bulletproof Gallery Dashboard v2.0</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .dashboard-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .dashboard-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .stats-bar {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
        }
        
        .stat-item {
            background: rgba(255,255,255,0.2);
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }
        
        .dashboard-content {
            padding: 40px;
        }
        
        .upload-section {
            background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            border: 2px dashed #28a745;
        }
        
        .upload-form {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
        }
        
        .upload-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }
        
        .categories-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
        }
        
        .category-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .category-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .category-header h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .artwork-grid {
            padding: 20px;
            min-height: 200px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .artwork-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            cursor: move;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .artwork-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: #667eea;
        }
        
        .artwork-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        
        .artwork-title {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .artwork-details {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.3s ease;
        }
        
        .delete-btn:hover {
            background: #c82333;
        }
        
        .empty-category {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 40px 20px;
        }
        
        .success-message, .error-message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .upload-form {
                grid-template-columns: 1fr;
            }
            
            .categories-container {
                grid-template-columns: 1fr;
            }
            
            .stats-bar {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>🎨 Bulletproof Gallery Dashboard v2.0</h1>
            <p>Database-Powered Art Management System</p>
            
            <div class="stats-bar">
                <?php
                $statsDisplay = [
                    'paintings' => 0,
                    'commissions' => 0,
                    'small-works' => 0
                ];
                
                foreach ($stats as $stat) {
                    $statsDisplay[$stat['category']] = $stat['count'];
                }
                ?>
                <div class="stat-item">
                    <span class="stat-number"><?= $statsDisplay['paintings'] ?></span>
                    Paintings
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= $statsDisplay['commissions'] ?></span>
                    Commissions
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?= $statsDisplay['small-works'] ?></span>
                    Small Works
                </div>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div id="message-area"></div>
            
            <!-- Upload Section -->
            <div class="upload-section">
                <h2 style="margin-bottom: 20px; color: #2c3e50;">📤 Upload New Artwork</h2>
                <form id="upload-form" class="upload-form">
                    <div class="form-group">
                        <label for="artwork-file">Select Image</label>
                        <input type="file" id="artwork-file" name="artwork" accept="image/*" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="artwork-title">Title</label>
                        <input type="text" id="artwork-title" name="title" placeholder="Enter artwork title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="artwork-category">Category</label>
                        <select id="artwork-category" name="category" required>
                            <option value="paintings">Paintings</option>
                            <option value="commissions">Commissions</option>
                            <option value="small-works">Small Works</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="artwork-dimensions">Dimensions</label>
                        <input type="text" id="artwork-dimensions" name="dimensions" placeholder="e.g., 24x36 inches">
                    </div>
                    
                    <div class="form-group">
                        <label for="artwork-year">Year</label>
                        <input type="number" id="artwork-year" name="year" placeholder="2024" min="1900" max="2030">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="upload-btn">🚀 Upload Artwork</button>
                    </div>
                </form>
            </div>
            
            <!-- Categories -->
            <div class="categories-container">
                <!-- Paintings -->
                <div class="category-section">
                    <div class="category-header">
                        <h3>🖼️ Paintings</h3>
                        <p><?= count($paintings) ?> artworks</p>
                    </div>
                    <div class="artwork-grid" data-category="paintings">
                        <?php if (empty($paintings)): ?>
                            <div class="empty-category">No paintings yet. Upload your first masterpiece!</div>
                        <?php else: ?>
                            <?php foreach ($paintings as $artwork): ?>
                                <div class="artwork-item" data-id="<?= $artwork['id'] ?>">
                                    <img src="uploaded-artworks/<?= htmlspecialchars($artwork['filename']) ?>" 
                                         alt="<?= htmlspecialchars($artwork['title']) ?>" 
                                         class="artwork-image">
                                    <div class="artwork-title"><?= htmlspecialchars($artwork['title']) ?></div>
                                    <div class="artwork-details">
                                        <?= $artwork['dimensions'] ? htmlspecialchars($artwork['dimensions']) : 'No dimensions' ?><br>
                                        <?= $artwork['year'] ? $artwork['year'] : 'No year' ?>
                                    </div>
                                    <button class="delete-btn" onclick="deleteArtwork(<?= $artwork['id'] ?>)">🗑️ Delete</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Commissions -->
                <div class="category-section">
                    <div class="category-header">
                        <h3>🎯 Commissions</h3>
                        <p><?= count($commissions) ?> artworks</p>
                    </div>
                    <div class="artwork-grid" data-category="commissions">
                        <?php if (empty($commissions)): ?>
                            <div class="empty-category">No commissions yet. Upload your commissioned works!</div>
                        <?php else: ?>
                            <?php foreach ($commissions as $artwork): ?>
                                <div class="artwork-item" data-id="<?= $artwork['id'] ?>">
                                    <img src="uploaded-artworks/<?= htmlspecialchars($artwork['filename']) ?>" 
                                         alt="<?= htmlspecialchars($artwork['title']) ?>" 
                                         class="artwork-image">
                                    <div class="artwork-title"><?= htmlspecialchars($artwork['title']) ?></div>
                                    <div class="artwork-details">
                                        <?= $artwork['dimensions'] ? htmlspecialchars($artwork['dimensions']) : 'No dimensions' ?><br>
                                        <?= $artwork['year'] ? $artwork['year'] : 'No year' ?>
                                    </div>
                                    <button class="delete-btn" onclick="deleteArtwork(<?= $artwork['id'] ?>)">🗑️ Delete</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Small Works -->
                <div class="category-section">
                    <div class="category-header">
                        <h3>🎨 Small Works</h3>
                        <p><?= count($smallWorks) ?> artworks</p>
                    </div>
                    <div class="artwork-grid" data-category="small-works">
                        <?php if (empty($smallWorks)): ?>
                            <div class="empty-category">No small works yet. Upload your smaller pieces!</div>
                        <?php else: ?>
                            <?php foreach ($smallWorks as $artwork): ?>
                                <div class="artwork-item" data-id="<?= $artwork['id'] ?>">
                                    <img src="uploaded-artworks/<?= htmlspecialchars($artwork['filename']) ?>" 
                                         alt="<?= htmlspecialchars($artwork['title']) ?>" 
                                         class="artwork-image">
                                    <div class="artwork-title"><?= htmlspecialchars($artwork['title']) ?></div>
                                    <div class="artwork-details">
                                        <?= $artwork['dimensions'] ? htmlspecialchars($artwork['dimensions']) : 'No dimensions' ?><br>
                                        <?= $artwork['year'] ? $artwork['year'] : 'No year' ?>
                                    </div>
                                    <button class="delete-btn" onclick="deleteArtwork(<?= $artwork['id'] ?>)">🗑️ Delete</button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Upload form handling
        document.getElementById('upload-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'upload');
            
            const submitBtn = this.querySelector('.upload-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = '⏳ Uploading...';
            submitBtn.disabled = true;
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('✅ ' + data.message, 'success');
                    this.reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showMessage('❌ ' + data.error, 'error');
                }
            })
            .catch(error => {
                showMessage('❌ Upload failed: ' + error.message, 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Delete artwork
        function deleteArtwork(artworkId) {
            if (!confirm('Are you sure you want to delete this artwork?')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('artwork_id', artworkId);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('✅ Artwork deleted successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showMessage('❌ Failed to delete artwork', 'error');
                }
            })
            .catch(error => {
                showMessage('❌ Delete failed: ' + error.message, 'error');
            });
        }
        
        // Show messages
        function showMessage(message, type) {
            const messageArea = document.getElementById('message-area');
            const messageDiv = document.createElement('div');
            messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
            messageDiv.textContent = message;
            
            messageArea.innerHTML = '';
            messageArea.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>