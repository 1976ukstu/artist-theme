<?php
/**
 * Standalone Content Editor
 * Custom page for editing gallery content
 * Access via: dragicacarlin.com/wp-content/themes/artist-theme/editcontent.php
 */

// Simple password protection
session_start();
$correct_password = 'artist2025'; // Change this to whatever password you want

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle login
if (isset($_POST['password'])) {
    if ($_POST['password'] === $correct_password) {
        $_SESSION['authenticated'] = true;
    } else {
        $error_message = 'Incorrect password. Please try again.';
    }
}

// Check if user is authenticated
$is_authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

// Handle image upload
if ($is_authenticated && isset($_POST['action']) && $_POST['action'] === 'upload_image') {
    header('Content-Type: application/json');
    
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
        exit;
    }
    
    $file = $_FILES['image'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed']);
        exit;
    }
    
    if ($file['size'] > 10 * 1024 * 1024) { // 10MB limit
        echo json_encode(['success' => false, 'message' => 'File size too large. Maximum 10MB allowed']);
        exit;
    }
    
    // Create images directory if it doesn't exist
    $uploadDir = __DIR__ . '/images/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'gallery_' . uniqid() . '.' . $extension;
    $uploadPath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo json_encode([
            'success' => true, 
            'imagePath' => 'images/' . $filename,
            'message' => 'Image uploaded successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file']);
    }
    exit;
}

// Handle form submission for gallery content
$success_message = '';
if ($is_authenticated && isset($_POST['save_content'])) {
    $gallery_data = [
        'paintings' => [],
        'commissions' => [],
        'small_works' => []
    ];
    
    // Process paintings
    for ($i = 1; $i <= 9; $i++) {
        $gallery_data['paintings'][$i] = [
            'title' => sanitize_text_field($_POST["painting_{$i}_title"] ?? ''),
            'subtitle' => sanitize_text_field($_POST["painting_{$i}_subtitle"] ?? ''),
            'description' => sanitize_text_field($_POST["painting_{$i}_description"] ?? ''),
            'image' => sanitize_text_field($_POST["painting_{$i}_image"] ?? '')
        ];
    }
    
    // Process commissions
    for ($i = 1; $i <= 9; $i++) {
        $gallery_data['commissions'][$i] = [
            'title' => sanitize_text_field($_POST["commission_{$i}_title"] ?? ''),
            'description' => sanitize_text_field($_POST["commission_{$i}_description"] ?? ''),
            'image' => sanitize_text_field($_POST["commission_{$i}_image"] ?? '')
        ];
    }
    
    // Process small works
    for ($i = 1; $i <= 9; $i++) {
        $gallery_data['small_works'][$i] = [
            'title' => sanitize_text_field($_POST["small_work_{$i}_title"] ?? ''),
            'description' => sanitize_text_field($_POST["small_work_{$i}_description"] ?? ''),
            'image' => sanitize_text_field($_POST["small_work_{$i}_image"] ?? '')
        ];
    }
    
    // Save to JSON file
    $json_file = __DIR__ . '/gallery-content.json';
    if (file_put_contents($json_file, json_encode($gallery_data, JSON_PRETTY_PRINT))) {
        $success_message = 'Gallery content saved successfully!';
    } else {
        $error_message = 'Error saving content. Please check file permissions.';
    }
}

// Function to safely get text field value
function sanitize_text_field($text) {
    return htmlspecialchars(strip_tags(trim($text)), ENT_QUOTES, 'UTF-8');
}

// Load existing gallery content
function load_gallery_content() {
    $json_file = __DIR__ . '/gallery-content.json';
    if (file_exists($json_file)) {
        $content = file_get_contents($json_file);
        return json_decode($content, true);
    }
    
    // Return default content if file doesn't exist
    return [
        'paintings' => [
            1 => ['title' => 'Blue Shapes on Blue Background', 'subtitle' => 'Oil on Canvas, 150cm x 150cm', 'description' => 'A powerful exploration of monochromatic harmony.', 'image' => 'images/23.Blue Shapes on blue background, Oil on Canvas, 150cm x 150cm.jpg'],
            2 => ['title' => 'Painting 2', 'subtitle' => 'Mixed Media', 'description' => 'Description for painting 2.', 'image' => 'images/painting-2.jpg'],
            3 => ['title' => 'Painting 3', 'subtitle' => 'Acrylic on Canvas', 'description' => 'Description for painting 3.', 'image' => 'images/painting-3.jpg'],
            4 => ['title' => 'Painting 4', 'subtitle' => 'Oil on Canvas', 'description' => 'Description for painting 4.', 'image' => 'images/painting-4.jpg'],
            5 => ['title' => 'Painting 5', 'subtitle' => 'Mixed Media', 'description' => 'Description for painting 5.', 'image' => 'images/painting-5.jpg'],
            6 => ['title' => 'Painting 6', 'subtitle' => 'Acrylic on Canvas', 'description' => 'Description for painting 6.', 'image' => 'images/painting-6.jpg'],
            7 => ['title' => 'New Painting 7', 'subtitle' => 'Medium to be specified', 'description' => 'Description to be added.', 'image' => 'images/painting-1.jpg'],
            8 => ['title' => 'New Painting 8', 'subtitle' => 'Medium to be specified', 'description' => 'Description to be added.', 'image' => 'images/painting-2.jpg'],
            9 => ['title' => 'New Painting 9', 'subtitle' => 'Medium to be specified', 'description' => 'Description to be added.', 'image' => 'images/painting-3.jpg']
        ],
        'commissions' => [
            1 => ['title' => 'Custom Family Portrait', 'description' => 'A personalized oil painting capturing the essence of a beloved family.', 'image' => 'images/commission-1.jpg'],
            2 => ['title' => 'Corporate Art Installation', 'description' => 'Large-scale abstract piece designed specifically for a modern office environment.', 'image' => 'images/commission-2.jpg'],
            3 => ['title' => 'Memorial Tribute', 'description' => 'A sensitive and meaningful artwork created to honor the memory of a loved one.', 'image' => 'images/commission-3.jpg'],
            4 => ['title' => 'New Commission 4', 'description' => 'Description to be added.', 'image' => 'images/commission-4.jpg'],
            5 => ['title' => 'New Commission 5', 'description' => 'Description to be added.', 'image' => 'images/commission-1.jpg'],
            6 => ['title' => 'New Commission 6', 'description' => 'Description to be added.', 'image' => 'images/commission-2.jpg'],
            7 => ['title' => 'New Commission 7', 'description' => 'Description to be added.', 'image' => 'images/commission-3.jpg'],
            8 => ['title' => 'New Commission 8', 'description' => 'Description to be added.', 'image' => 'images/commission-4.jpg'],
            9 => ['title' => 'New Commission 9', 'description' => 'Description to be added.', 'image' => 'images/commission-1.jpg']
        ],
        'small_works' => [
            1 => ['title' => 'Contemplation', 'description' => 'A delicate study in light and shadow, exploring the quiet moments of reflection.', 'image' => 'images/small-work-1.jpg'],
            2 => ['title' => 'Urban Rhythms', 'description' => 'Capturing the energy and movement of city life in bold, expressive strokes.', 'image' => 'images/small-work-2.jpg'],
            3 => ['title' => "Nature's Whisper", 'description' => 'A gentle exploration of natural forms and organic textures in miniature.', 'image' => 'images/small-work-3.jpg'],
            4 => ['title' => 'Abstract Harmony', 'description' => 'An exploration of color relationships and compositional balance in a compact format.', 'image' => 'images/small-work-4.jpg'],
            5 => ['title' => 'Emotional Landscape', 'description' => 'A personal journey expressed through expressive mark-making and intuitive color choices.', 'image' => 'images/small-work-5.jpg'],
            6 => ['title' => 'Textural Studies', 'description' => 'An investigation into surface and material, creating depth through layered techniques.', 'image' => 'images/small-work-6.jpg'],
            7 => ['title' => 'New Small Work 7', 'description' => 'Description to be added.', 'image' => 'images/small-work-1.jpg'],
            8 => ['title' => 'New Small Work 8', 'description' => 'Description to be added.', 'image' => 'images/small-work-2.jpg'],
            9 => ['title' => 'New Small Work 9', 'description' => 'Description to be added.', 'image' => 'images/small-work-3.jpg']
        ]
    ];
}

$gallery_content = load_gallery_content();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Content Editor - Dragica Carlin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .login-form {
            padding: 60px;
            text-align: center;
        }
        
        .login-form h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 2rem;
        }
        
        .login-form input[type="password"] {
            width: 300px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }
        
        .login-form input[type="password"]:focus {
            outline: none;
            border-color: #4facfe;
        }
        
        .login-form button {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .login-form button:hover {
            transform: translateY(-2px);
        }
        
        .content {
            padding: 40px;
        }
        
        .section {
            margin-bottom: 50px;
        }
        
        .section h2 {
            color: #333;
            font-size: 1.8rem;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 3px solid #4facfe;
            display: inline-block;
        }
        
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .item-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .item-card:hover {
            transform: translateY(-5px);
        }
        
        .item-card h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.2rem;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            color: #555;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4facfe;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .image-preview {
            margin-top: 10px;
            text-align: center;
        }
        
        .image-preview img {
            max-width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }
        
        .image-upload-container {
            margin-bottom: 15px;
        }
        
        .drag-drop-area {
            border: 2px dashed #d0d0d0;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            background: #fafafa;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        
        .drag-drop-area:hover {
            border-color: #4facfe;
            background: #f0f8ff;
        }
        
        .drag-drop-area.dragover {
            border-color: #4facfe;
            background: #e6f3ff;
            transform: scale(1.02);
        }
        
        .upload-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .upload-text {
            color: #666;
            font-size: 0.95rem;
        }
        
        .upload-text strong {
            color: #4facfe;
        }
        
        .upload-text small {
            color: #999;
            font-size: 0.85rem;
        }
        
        .save-btn {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
            color: white;
            border: none;
            padding: 20px 50px;
            border-radius: 15px;
            font-size: 1.2rem;
            cursor: pointer;
            display: block;
            margin: 40px auto;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(86, 171, 47, 0.3);
        }
        
        .save-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(86, 171, 47, 0.4);
        }
        
        .success-message,
        .error-message {
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            text-align: center;
            font-weight: 500;
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
            .header h1 {
                font-size: 2rem;
            }
            
            .items-grid {
                grid-template-columns: 1fr;
            }
            
            .content {
                padding: 20px;
            }
            
            .logout-btn {
                position: static;
                margin-top: 20px;
                display: inline-block;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($is_authenticated): ?>
            <div class="header">
                <h1>🎨 Gallery Content Editor</h1>
                <p>Edit your paintings, commissions, and small works</p>
                <a href="?logout=1" class="logout-btn">Logout</a>
            </div>
            
            <div class="content">
                <?php if ($success_message): ?>
                    <div class="success-message">✅ <?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="error-message">❌ <?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <!-- Paintings Section -->
                    <div class="section">
                        <h2>🖼️ Paintings</h2>
                        <div class="items-grid">
                            <?php for ($i = 1; $i <= 9; $i++): 
                                $painting = $gallery_content['paintings'][$i] ?? [];
                            ?>
                                <div class="item-card">
                                    <h3>Painting <?php echo $i; ?></h3>
                                    
                                    <div class="form-group">
                                        <label for="painting_<?php echo $i; ?>_title">Title:</label>
                                        <input type="text" 
                                               id="painting_<?php echo $i; ?>_title" 
                                               name="painting_<?php echo $i; ?>_title" 
                                               value="<?php echo htmlspecialchars($painting['title'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="painting_<?php echo $i; ?>_subtitle">Medium/Dimensions:</label>
                                        <input type="text" 
                                               id="painting_<?php echo $i; ?>_subtitle" 
                                               name="painting_<?php echo $i; ?>_subtitle" 
                                               value="<?php echo htmlspecialchars($painting['subtitle'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="painting_<?php echo $i; ?>_description">Description:</label>
                                        <textarea id="painting_<?php echo $i; ?>_description" 
                                                  name="painting_<?php echo $i; ?>_description"><?php echo htmlspecialchars($painting['description'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="painting_<?php echo $i; ?>_image">Image:</label>
                                        <div class="image-upload-container">
                                            <div class="drag-drop-area" 
                                                 ondrop="handleDrop(event, 'painting_<?php echo $i; ?>_image')" 
                                                 ondragover="handleDragOver(event)"
                                                 ondragleave="handleDragLeave(event)"
                                                 onclick="document.getElementById('painting_<?php echo $i; ?>_file').click()">
                                                <div class="upload-icon">📁</div>
                                                <div class="upload-text">
                                                    <strong>Click to upload</strong> or drag and drop<br>
                                                    <small>PNG, JPG, GIF up to 10MB</small>
                                                </div>
                                            </div>
                                            <input type="file" 
                                                   id="painting_<?php echo $i; ?>_file" 
                                                   accept="image/*" 
                                                   style="display: none;"
                                                   onchange="handleFileSelect(this, 'painting_<?php echo $i; ?>_image')">
                                            <input type="text" 
                                                   id="painting_<?php echo $i; ?>_image" 
                                                   name="painting_<?php echo $i; ?>_image" 
                                                   value="<?php echo htmlspecialchars($painting['image'] ?? ''); ?>"
                                                   placeholder="Or enter image path manually"
                                                   onchange="updateImagePreview(this, 'painting_<?php echo $i; ?>_preview')">
                                        </div>
                                        <div class="image-preview" id="painting_<?php echo $i; ?>_preview">
                                            <?php if (!empty($painting['image'])): ?>
                                                <img src="../<?php echo htmlspecialchars($painting['image']); ?>" alt="Preview">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <!-- Commissions Section -->
                    <div class="section">
                        <h2>🎯 Commissions</h2>
                        <div class="items-grid">
                            <?php for ($i = 1; $i <= 9; $i++): 
                                $commission = $gallery_content['commissions'][$i] ?? [];
                            ?>
                                <div class="item-card">
                                    <h3>Commission <?php echo $i; ?></h3>
                                    
                                    <div class="form-group">
                                        <label for="commission_<?php echo $i; ?>_title">Title:</label>
                                        <input type="text" 
                                               id="commission_<?php echo $i; ?>_title" 
                                               name="commission_<?php echo $i; ?>_title" 
                                               value="<?php echo htmlspecialchars($commission['title'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="commission_<?php echo $i; ?>_description">Description:</label>
                                        <textarea id="commission_<?php echo $i; ?>_description" 
                                                  name="commission_<?php echo $i; ?>_description"><?php echo htmlspecialchars($commission['description'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="commission_<?php echo $i; ?>_image">Image:</label>
                                        <div class="image-upload-container">
                                            <div class="drag-drop-area" 
                                                 ondrop="handleDrop(event, 'commission_<?php echo $i; ?>_image')" 
                                                 ondragover="handleDragOver(event)"
                                                 ondragleave="handleDragLeave(event)"
                                                 onclick="document.getElementById('commission_<?php echo $i; ?>_file').click()">
                                                <div class="upload-icon">📁</div>
                                                <div class="upload-text">
                                                    <strong>Click to upload</strong> or drag and drop<br>
                                                    <small>PNG, JPG, GIF up to 10MB</small>
                                                </div>
                                            </div>
                                            <input type="file" 
                                                   id="commission_<?php echo $i; ?>_file" 
                                                   accept="image/*" 
                                                   style="display: none;"
                                                   onchange="handleFileSelect(this, 'commission_<?php echo $i; ?>_image')">
                                            <input type="text" 
                                                   id="commission_<?php echo $i; ?>_image" 
                                                   name="commission_<?php echo $i; ?>_image" 
                                                   value="<?php echo htmlspecialchars($commission['image'] ?? ''); ?>"
                                                   placeholder="Or enter image path manually"
                                                   onchange="updateImagePreview(this, 'commission_<?php echo $i; ?>_preview')">
                                        </div>
                                        <div class="image-preview" id="commission_<?php echo $i; ?>_preview">
                                            <?php if (!empty($commission['image'])): ?>
                                                <img src="../<?php echo htmlspecialchars($commission['image']); ?>" alt="Preview">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <!-- Small Works Section -->
                    <div class="section">
                        <h2>🎨 Small Works</h2>
                        <div class="items-grid">
                            <?php for ($i = 1; $i <= 9; $i++): 
                                $small_work = $gallery_content['small_works'][$i] ?? [];
                            ?>
                                <div class="item-card">
                                    <h3>Small Work <?php echo $i; ?></h3>
                                    
                                    <div class="form-group">
                                        <label for="small_work_<?php echo $i; ?>_title">Title:</label>
                                        <input type="text" 
                                               id="small_work_<?php echo $i; ?>_title" 
                                               name="small_work_<?php echo $i; ?>_title" 
                                               value="<?php echo htmlspecialchars($small_work['title'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="small_work_<?php echo $i; ?>_description">Description:</label>
                                        <textarea id="small_work_<?php echo $i; ?>_description" 
                                                  name="small_work_<?php echo $i; ?>_description"><?php echo htmlspecialchars($small_work['description'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="small_work_<?php echo $i; ?>_image">Image:</label>
                                        <div class="image-upload-container">
                                            <div class="drag-drop-area" 
                                                 ondrop="handleDrop(event, 'small_work_<?php echo $i; ?>_image')" 
                                                 ondragover="handleDragOver(event)"
                                                 ondragleave="handleDragLeave(event)"
                                                 onclick="document.getElementById('small_work_<?php echo $i; ?>_file').click()">
                                                <div class="upload-icon">📁</div>
                                                <div class="upload-text">
                                                    <strong>Click to upload</strong> or drag and drop<br>
                                                    <small>PNG, JPG, GIF up to 10MB</small>
                                                </div>
                                            </div>
                                            <input type="file" 
                                                   id="small_work_<?php echo $i; ?>_file" 
                                                   accept="image/*" 
                                                   style="display: none;"
                                                   onchange="handleFileSelect(this, 'small_work_<?php echo $i; ?>_image')">
                                            <input type="text" 
                                                   id="small_work_<?php echo $i; ?>_image" 
                                                   name="small_work_<?php echo $i; ?>_image" 
                                                   value="<?php echo htmlspecialchars($small_work['image'] ?? ''); ?>"
                                                   placeholder="Or enter image path manually"
                                                   onchange="updateImagePreview(this, 'small_work_<?php echo $i; ?>_preview')">
                                        </div>
                                        <div class="image-preview" id="small_work_<?php echo $i; ?>_preview">
                                            <?php if (!empty($small_work['image'])): ?>
                                                <img src="../<?php echo htmlspecialchars($small_work['image']); ?>" alt="Preview">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <button type="submit" name="save_content" class="save-btn">
                        💾 Save All Changes
                    </button>
                </form>
            </div>
            
        <?php else: ?>
            <div class="header">
                <h1>🔒 Gallery Content Editor</h1>
                <p>Please enter your password to access the editor</p>
            </div>
            
            <div class="login-form">
                <?php if (isset($error_message)): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <h2>Password Required</h2>
                <form method="post" action="">
                    <input type="password" name="password" placeholder="Enter password" required>
                    <br>
                    <button type="submit">Access Editor</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function updateImagePreview(input, previewId) {
            const preview = document.getElementById(previewId);
            const imagePath = input.value;
            
            if (imagePath.trim()) {
                // Create or update the image element
                let img = preview.querySelector('img');
                if (!img) {
                    img = document.createElement('img');
                    preview.appendChild(img);
                }
                
                // Handle both relative and absolute paths
                if (imagePath.startsWith('http') || imagePath.startsWith('/')) {
                    img.src = imagePath;
                } else if (imagePath.startsWith('images/')) {
                    img.src = '../' + imagePath;
                } else {
                    img.src = '../images/' + imagePath;
                }
                img.alt = 'Preview';
                img.style.display = 'block';
                
                // Add error handling for broken images
                img.onerror = function() {
                    this.style.display = 'none';
                    console.log('Could not load image:', this.src);
                };
            } else {
                // Clear the preview
                preview.innerHTML = '';
            }
        }
        
        // Drag and drop functionality
        function handleDragOver(event) {
            event.preventDefault();
            event.currentTarget.classList.add('dragover');
        }
        
        function handleDragLeave(event) {
            event.preventDefault();
            event.currentTarget.classList.remove('dragover');
        }
        
        function handleDrop(event, inputId) {
            event.preventDefault();
            event.currentTarget.classList.remove('dragover');
            
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                handleFileUpload(files[0], inputId);
            }
        }
        
        function handleFileSelect(fileInput, inputId) {
            if (fileInput.files.length > 0) {
                handleFileUpload(fileInput.files[0], inputId);
            }
        }
        
        function handleFileUpload(file, inputId) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file (PNG, JPG, GIF)');
                return;
            }
            
            // Validate file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                return;
            }
            
            // Create FormData for upload
            const formData = new FormData();
            formData.append('image', file);
            formData.append('action', 'upload_image');
            
            // Show upload progress
            const inputElement = document.getElementById(inputId);
            const originalPlaceholder = inputElement.placeholder;
            inputElement.placeholder = 'Uploading...';
            inputElement.disabled = true;
            
            // Upload the file
            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the input with the new image path
                    inputElement.value = data.imagePath;
                    inputElement.placeholder = originalPlaceholder;
                    inputElement.disabled = false;
                    
                    // Update the preview immediately with the uploaded image
                    const previewId = inputId.replace('_image', '_preview');
                    const preview = document.getElementById(previewId);
                    let img = preview.querySelector('img');
                    if (!img) {
                        img = document.createElement('img');
                        preview.appendChild(img);
                    }
                    
                    // Use the uploaded file URL for immediate preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        img.alt = 'Preview';
                        img.style.display = 'block';
                    };
                    
                    // Read the uploaded file for immediate preview
                    const fileInput = document.getElementById(inputId.replace('_image', '_file'));
                    if (fileInput && fileInput.files[0]) {
                        reader.readAsDataURL(fileInput.files[0]);
                    }
                    
                    // Show success message
                    showMessage('Image uploaded successfully!', 'success');
                } else {
                    throw new Error(data.message || 'Upload failed');
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                inputElement.placeholder = originalPlaceholder;
                inputElement.disabled = false;
                showMessage('Upload failed: ' + error.message, 'error');
            });
        }
        
        function showMessage(message, type) {
            // Create or update message element
            let messageEl = document.getElementById('upload-message');
            if (!messageEl) {
                messageEl = document.createElement('div');
                messageEl.id = 'upload-message';
                messageEl.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 15px 20px;
                    border-radius: 8px;
                    font-weight: 500;
                    z-index: 10000;
                    max-width: 300px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                `;
                document.body.appendChild(messageEl);
            }
            
            messageEl.textContent = message;
            messageEl.className = type === 'success' ? 'success-message' : 'error-message';
            messageEl.style.display = 'block';
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                if (messageEl) {
                    messageEl.style.display = 'none';
                }
            }, 3000);
        }
        
        // Auto-save draft to localStorage
        function saveDraft() {
            const formData = new FormData(document.querySelector('form'));
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            localStorage.setItem('gallery_draft', JSON.stringify(data));
        }
        
        // Load draft from localStorage
        function loadDraft() {
            const draft = localStorage.getItem('gallery_draft');
            if (draft) {
                const data = JSON.parse(draft);
                for (let [key, value] of Object.entries(data)) {
                    const element = document.querySelector(`[name="${key}"]`);
                    if (element) {
                        element.value = value;
                    }
                }
            }
        }
        
        // Save draft every 30 seconds
        if (document.querySelector('form')) {
            setInterval(saveDraft, 30000);
            loadDraft(); // Load draft on page load
        }
    </script>
</body>
</html>
