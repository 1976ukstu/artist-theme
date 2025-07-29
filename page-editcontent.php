<?php
/**
 * Template Name: Content Editor
 * Custom page for editing gallery content
 * URL: dragicacarlin.com/editcontent
 */

// Simple password protection
session_start();
$correct_password = 'artist2025'; // Change this to whatever password you want
$is_authenticated = isset($_SESSION['content_editor_auth']) && $_SESSION['content_editor_auth'] === true;

if (isset($_POST['password'])) {
    if ($_POST['password'] === $correct_password) {
        $_SESSION['content_editor_auth'] = true;
        $is_authenticated = true;
    } else {
        $error_message = 'Incorrect password. Please try again.';
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Load current content
$content_file = get_template_directory() . '/gallery-content.json';
$gallery_content = [];

if (file_exists($content_file)) {
    $gallery_content = json_decode(file_get_contents($content_file), true);
}

// Default content structure if file doesn't exist
if (empty($gallery_content)) {
    $gallery_content = [
        'paintings' => [
            1 => ['title' => 'Blue Shapes on Blue Background', 'subtitle' => 'Oil on Canvas, 150cm x 150cm', 'description' => 'An exploration of form and color relationships within a monochromatic palette, creating depth through subtle variations in tone and texture.', 'image' => 'images/23.Blue Shapes on blue background, Oil on Canvas, 150cm x 150cm.jpg'],
            2 => ['title' => 'Abstract Expression', 'subtitle' => 'Acrylic on Canvas, 120cm x 100cm', 'description' => 'A dynamic composition exploring the relationship between spontaneous mark-making and deliberate color choices.', 'image' => 'images/painting-2.jpg'],
            3 => ['title' => 'Emotional Landscape', 'subtitle' => 'Oil on Canvas, 100cm x 80cm', 'description' => 'A personal interpretation of natural forms, expressing the emotional connection between artist and environment.', 'image' => 'images/painting-3.jpg'],
            4 => ['title' => 'Urban Rhythms', 'subtitle' => 'Mixed Media on Canvas, 90cm x 70cm', 'description' => 'Capturing the energy and movement of contemporary urban life through bold gestural marks and vibrant color.', 'image' => 'images/painting-4.jpg'],
            5 => ['title' => 'Contemplative Space', 'subtitle' => 'Oil on Canvas, 110cm x 90cm', 'description' => 'A meditation on interior space and light, exploring the quiet moments of daily life through subtle color transitions.', 'image' => 'images/painting-5.jpg'],
            6 => ['title' => 'Textural Studies', 'subtitle' => 'Acrylic and Mixed Media, 85cm x 65cm', 'description' => 'An investigation into surface quality and material properties, building layers of meaning through physical texture.', 'image' => 'images/painting-6.jpg'],
            7 => ['title' => 'New Work 7', 'subtitle' => 'Medium and Dimensions TBD', 'description' => 'Description to be added.', 'image' => 'images/painting-1.jpg'],
            8 => ['title' => 'New Work 8', 'subtitle' => 'Medium and Dimensions TBD', 'description' => 'Description to be added.', 'image' => 'images/painting-2.jpg'],
            9 => ['title' => 'New Work 9', 'subtitle' => 'Medium and Dimensions TBD', 'description' => 'Description to be added.', 'image' => 'images/painting-3.jpg']
        ],
        'commissions' => [
            1 => ['title' => 'Custom Portraits', 'description' => 'Personalized portraits from photographs, capturing the essence and personality of your loved ones.', 'image' => 'images/commission-1.jpg'],
            2 => ['title' => 'Pet Portraits', 'description' => 'Beautiful artwork celebrating your beloved pets with attention to their unique characteristics.', 'image' => 'images/commission-2.jpg'],
            3 => ['title' => 'Landscape Paintings', 'description' => 'Custom landscapes of meaningful places - your home, favorite vacation spot, or dream destination.', 'image' => 'images/commission-3.jpg'],
            4 => ['title' => 'Interior Murals', 'description' => 'Large-scale murals that transform rooms into artistic environments, from children\'s rooms to businesses.', 'image' => 'images/mural-1.jpg'],
            5 => ['title' => 'Exterior Murals', 'description' => 'Weather-resistant outdoor murals for buildings, fences, and public spaces.', 'image' => 'images/mural-2.jpg'],
            6 => ['title' => 'Abstract Artwork', 'description' => 'Custom abstract pieces designed to complement your interior design and color scheme.', 'image' => 'images/commission-4.jpg'],
            7 => ['title' => 'New Commission Type 7', 'description' => 'Description to be added.', 'image' => 'images/commission-1.jpg'],
            8 => ['title' => 'New Commission Type 8', 'description' => 'Description to be added.', 'image' => 'images/commission-2.jpg'],
            9 => ['title' => 'New Commission Type 9', 'description' => 'Description to be added.', 'image' => 'images/commission-3.jpg']
        ],
        'small_works' => [
            1 => ['title' => 'Contemplation', 'description' => 'A delicate study in light and shadow, exploring the quiet moments of reflection.', 'image' => 'images/small-work-1.jpg'],
            2 => ['title' => 'Urban Rhythms', 'description' => 'Capturing the energy and movement of city life in bold, expressive strokes.', 'image' => 'images/small-work-2.jpg'],
            3 => ['title' => 'Nature\'s Whisper', 'description' => 'A gentle exploration of natural forms and organic textures in miniature.', 'image' => 'images/small-work-3.jpg'],
            4 => ['title' => 'Abstract Harmony', 'description' => 'An exploration of color relationships and compositional balance in a compact format.', 'image' => 'images/small-work-4.jpg'],
            5 => ['title' => 'Emotional Landscape', 'description' => 'A personal journey expressed through expressive mark-making and intuitive color choices.', 'image' => 'images/small-work-5.jpg'],
            6 => ['title' => 'Textural Studies', 'description' => 'An investigation into surface and material, creating depth through layered techniques.', 'image' => 'images/small-work-6.jpg'],
            7 => ['title' => 'New Small Work 7', 'description' => 'Description to be added.', 'image' => 'images/small-work-1.jpg'],
            8 => ['title' => 'New Small Work 8', 'description' => 'Description to be added.', 'image' => 'images/small-work-2.jpg'],
            9 => ['title' => 'New Small Work 9', 'description' => 'Description to be added.', 'image' => 'images/small-work-3.jpg']
        ]
    ];
}

// Handle form submissions
if ($_POST && $is_authenticated && isset($_POST['action']) && $_POST['action'] === 'save_content') {
    $category = $_POST['category'];
    $card_id = $_POST['card_id'];
    
    if (isset($gallery_content[$category][$card_id])) {
        $gallery_content[$category][$card_id]['title'] = sanitize_text_field($_POST['title']);
        $gallery_content[$category][$card_id]['description'] = sanitize_textarea_field($_POST['description']);
        $gallery_content[$category][$card_id]['image'] = sanitize_text_field($_POST['image']);
        
        // Handle subtitle for paintings
        if ($category === 'paintings') {
            $gallery_content[$category][$card_id]['subtitle'] = sanitize_text_field($_POST['subtitle']);
        }
        
        // Save to file
        file_put_contents($content_file, json_encode($gallery_content, JSON_PRETTY_PRINT));
        $success_message = 'Content saved successfully!';
    }
}

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
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .header h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .login-form {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            max-width: 400px;
            margin: 50px auto;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .login-form h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-small {
            padding: 8px 20px;
            font-size: 0.9rem;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }
        
        .gallery-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .gallery-section h2 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 25px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card-editor {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .card-editor:hover {
            border-color: #667eea;
            transform: translateY(-2px);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .image-preview {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 2px solid #ddd;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .form-row.full {
            grid-template-columns: 1fr;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .logout-btn {
            float: right;
            margin-top: -10px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!$is_authenticated): ?>
            <div class="login-form">
                <h2>🎨 Content Editor Access</h2>
                <p style="margin-bottom: 20px; color: #666;">Enter your password to manage gallery content</p>
                
                <?php if (isset($error_message)): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn">Access Editor</button>
                </form>
            </div>
        <?php else: ?>
            <div class="header">
                <form method="POST" class="logout-btn">
                    <button type="submit" name="logout" class="btn btn-small btn-danger">Logout</button>
                </form>
                <h1>🎨 Gallery Content Manager</h1>
                <p>Edit your artwork information easily and see changes instantly on your website</p>
            </div>
            
            <?php if (isset($success_message)): ?>
                <div class="success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <!-- PAINTINGS SECTION -->
            <div class="gallery-section">
                <h2>🖼️ Paintings Gallery</h2>
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <?php $painting = $gallery_content['paintings'][$i] ?? []; ?>
                    <div class="card-editor">
                        <form method="POST">
                            <input type="hidden" name="action" value="save_content">
                            <input type="hidden" name="category" value="paintings">
                            <input type="hidden" name="card_id" value="<?php echo $i; ?>">
                            
                            <div class="card-header">
                                <div class="card-title">Painting Card <?php echo $i; ?></div>
                                <?php if (!empty($painting['image'])): ?>
                                    <img src="<?php echo get_template_directory_uri() . '/' . $painting['image']; ?>" 
                                         alt="Preview" class="image-preview">
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Title:</label>
                                    <input type="text" name="title" value="<?php echo esc_attr($painting['title'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Medium & Dimensions:</label>
                                    <input type="text" name="subtitle" value="<?php echo esc_attr($painting['subtitle'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row full">
                                <div class="form-group">
                                    <label>Image Path (e.g., images/painting-1.jpg):</label>
                                    <input type="text" name="image" value="<?php echo esc_attr($painting['image'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row full">
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description"><?php echo esc_textarea($painting['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-small">Save Painting <?php echo $i; ?></button>
                        </form>
                    </div>
                <?php endfor; ?>
            </div>
            
            <!-- COMMISSIONS SECTION -->
            <div class="gallery-section">
                <h2>🏛️ Commissions & Murals</h2>
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <?php $commission = $gallery_content['commissions'][$i] ?? []; ?>
                    <div class="card-editor">
                        <form method="POST">
                            <input type="hidden" name="action" value="save_content">
                            <input type="hidden" name="category" value="commissions">
                            <input type="hidden" name="card_id" value="<?php echo $i; ?>">
                            
                            <div class="card-header">
                                <div class="card-title">Commission Card <?php echo $i; ?></div>
                                <?php if (!empty($commission['image'])): ?>
                                    <img src="<?php echo get_template_directory_uri() . '/' . $commission['image']; ?>" 
                                         alt="Preview" class="image-preview">
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Title:</label>
                                    <input type="text" name="title" value="<?php echo esc_attr($commission['title'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Image Path (e.g., images/commission-1.jpg):</label>
                                    <input type="text" name="image" value="<?php echo esc_attr($commission['image'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row full">
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description"><?php echo esc_textarea($commission['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-small">Save Commission <?php echo $i; ?></button>
                        </form>
                    </div>
                <?php endfor; ?>
            </div>
            
            <!-- SMALL WORKS SECTION -->
            <div class="gallery-section">
                <h2>🖼️ Small Works</h2>
                <?php for ($i = 1; $i <= 9; $i++): ?>
                    <?php $small_work = $gallery_content['small_works'][$i] ?? []; ?>
                    <div class="card-editor">
                        <form method="POST">
                            <input type="hidden" name="action" value="save_content">
                            <input type="hidden" name="category" value="small_works">
                            <input type="hidden" name="card_id" value="<?php echo $i; ?>">
                            
                            <div class="card-header">
                                <div class="card-title">Small Work Card <?php echo $i; ?></div>
                                <?php if (!empty($small_work['image'])): ?>
                                    <img src="<?php echo get_template_directory_uri() . '/' . $small_work['image']; ?>" 
                                         alt="Preview" class="image-preview">
                                <?php endif; ?>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Title:</label>
                                    <input type="text" name="title" value="<?php echo esc_attr($small_work['title'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Image Path (e.g., images/small-work-1.jpg):</label>
                                    <input type="text" name="image" value="<?php echo esc_attr($small_work['image'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-row full">
                                <div class="form-group">
                                    <label>Description:</label>
                                    <textarea name="description"><?php echo esc_textarea($small_work['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-small">Save Small Work <?php echo $i; ?></button>
                        </form>
                    </div>
                <?php endfor; ?>
            </div>
            
        <?php endif; ?>
    </div>
</body>
</html>
