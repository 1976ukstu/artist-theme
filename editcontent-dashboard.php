<?php
/**
 * Revolutionary Artist Website Management Dashboard
 * Complete control over all website content
 */

// Simple password protection
session_start();
$correct_password = 'artist2025';

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

$is_authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

// Handle content updates
if ($is_authenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $content_file = 'gallery-content.json';
    
    if ($_POST['action'] === 'save_gallery') {
        // Save gallery content (existing functionality)
        $content = json_decode(file_get_contents($content_file), true) ?: [];
        
        // Update paintings
        for ($i = 1; $i <= 9; $i++) {
            if (isset($_POST["painting_{$i}_title"])) {
                $content['paintings'][$i] = [
                    'title' => sanitize_text_field($_POST["painting_{$i}_title"]),
                    'subtitle' => sanitize_text_field($_POST["painting_{$i}_subtitle"]),
                    'description' => sanitize_textarea_field($_POST["painting_{$i}_description"]),
                    'image' => sanitize_text_field($_POST["painting_{$i}_image"])
                ];
            }
        }
        
        // Update commissions
        for ($i = 1; $i <= 9; $i++) {
            if (isset($_POST["commission_{$i}_title"])) {
                $content['commissions'][$i] = [
                    'title' => sanitize_text_field($_POST["commission_{$i}_title"]),
                    'description' => sanitize_textarea_field($_POST["commission_{$i}_description"]),
                    'image' => sanitize_text_field($_POST["commission_{$i}_image"])
                ];
            }
        }
        
        // Update small works
        for ($i = 1; $i <= 9; $i++) {
            if (isset($_POST["small_work_{$i}_title"])) {
                $content['small_works'][$i] = [
                    'title' => sanitize_text_field($_POST["small_work_{$i}_title"]),
                    'description' => sanitize_textarea_field($_POST["small_work_{$i}_description"]),
                    'image' => sanitize_text_field($_POST["small_work_{$i}_image"])
                ];
            }
        }
        
        file_put_contents($content_file, json_encode($content, JSON_PRETTY_PRINT));
        $success_message = 'Gallery content updated successfully!';
    }
    
    if ($_POST['action'] === 'save_text_page') {
        // Save text page content
        $content = json_decode(file_get_contents($content_file), true) ?: [];
        
        $content['text_page'] = [
            'main_heading' => sanitize_text_field($_POST['text_main_heading']),
            'intro_text' => sanitize_textarea_field($_POST['text_intro']),
            'section_1_title' => sanitize_text_field($_POST['text_section_1_title']),
            'section_1_content' => sanitize_textarea_field($_POST['text_section_1_content']),
            'section_2_title' => sanitize_text_field($_POST['text_section_2_title']),
            'section_2_content' => sanitize_textarea_field($_POST['text_section_2_content']),
            'section_3_title' => sanitize_text_field($_POST['text_section_3_title']),
            'section_3_content' => sanitize_textarea_field($_POST['text_section_3_content']),
            'conclusion_text' => sanitize_textarea_field($_POST['text_conclusion'])
        ];
        
        file_put_contents($content_file, json_encode($content, JSON_PRETTY_PRINT));
        $success_message = 'Text page updated successfully!';
    }
}

// Load current content
$current_content = [];
if (file_exists('gallery-content.json')) {
    $current_content = json_decode(file_get_contents('gallery-content.json'), true) ?: [];
}

function sanitize_text_field($text) {
    return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
}

function sanitize_textarea_field($text) {
    return htmlspecialchars(trim($text), ENT_QUOTES, 'UTF-8');
}

// Get current section from URL parameter
$current_section = $_GET['section'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Website Management Dashboard</title>
    <style>
        body, .dashboard-container, .login-container {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }
    
    .login-form {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        text-align: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .login-form h2 {
        margin-bottom: 30px;
        color: #2c3e50;
        font-size: 28px;
    }
    
    .login-form input[type="password"] {
        width: 100%;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 16px;
        margin-bottom: 20px;
        transition: border-color 0.3s;
    }
    
    .login-form input[type="password"]:focus {
        outline: none;
        border-color: #667eea;
    }
    
    .login-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 30px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
        transition: transform 0.3s;
    }
    
    .login-btn:hover {
        transform: translateY(-2px);
    }
    
    /* Dashboard Styles */
    .dashboard-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .dashboard-header {
        text-align: center;
        margin-bottom: 40px;
        color: white;
    }
    
    .dashboard-header h1 {
        font-size: 36px;
        margin-bottom: 10px;
        font-weight: 300;
    }
    
    .dashboard-header p {
        font-size: 18px;
        opacity: 0.9;
    }
    
    .logout-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .logout-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }
    
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }
    
    .dashboard-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }
    
    .dashboard-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .dashboard-card .icon {
        font-size: 48px;
        margin-bottom: 20px;
        display: block;
    }
    
    .dashboard-card h3 {
        font-size: 24px;
        margin-bottom: 10px;
        color: #2c3e50;
    }
    
    .dashboard-card p {
        color: #666;
        margin-bottom: 15px;
        line-height: 1.6;
    }
    
    .dashboard-card .item-count {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }
    
    /* Content Editor Styles */
    .content-editor {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 30px;
        margin: 20px 0;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .section-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e0e0e0;
    }
    
    .section-header h2 {
        color: #2c3e50;
        font-size: 28px;
    }
    
    .back-btn {
        background: #95a5a6;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .back-btn:hover {
        background: #7f8c8d;
        transform: translateY(-2px);
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 30px;
    }
    
    .form-card {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 15px;
        border: 2px solid #e9ecef;
        transition: border-color 0.3s;
    }
    
    .form-card:hover {
        border-color: #667eea;
    }
    
    .form-card h4 {
        color: #2c3e50;
        margin-bottom: 20px;
        font-size: 18px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
        font-family: inherit;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }
    
    .image-preview {
        width: 100%;
        max-width: 200px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        margin-top: 10px;
        border: 2px solid #e0e0e0;
    }

    .image-upload-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-top: 10px;
}

.current-image-section {
    text-align: center;
}

.current-image-section h5 {
    margin-bottom: 10px;
    color: #666;
    font-size: 14px;
}

.drag-drop-area {
    border: 2px dashed #667eea;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    background: #f8f9ff;
    transition: all 0.3s;
    cursor: pointer;
    position: relative;
}

.drag-drop-area:hover {
    border-color: #5a6fd8;
    background: #f0f2ff;
}

.drag-drop-area.dragover {
    border-color: #4c63d2;
    background: #e8ebff;
    transform: scale(1.02);
}

.drag-drop-area .upload-icon {
    font-size: 24px;
    color: #667eea;
    margin-bottom: 10px;
}

.drag-drop-area p {
    margin: 5px 0;
    color: #666;
    font-size: 14px;
}

.drag-drop-area .upload-hint {
    font-size: 12px;
    color: #999;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.new-image-preview {
    width: 100%;
    max-width: 200px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    margin-top: 10px;
    border: 2px solid #e0e0e0;
    display: none;
}

.upload-success {
    background: #d4edda;
    color: #155724;
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 12px;
    margin-top: 10px;
    display: none;
}

.upload-progress {
    width: 100%;
    height: 6px;
    background: #e0e0e0;
    border-radius: 3px;
    margin-top: 10px;
    overflow: hidden;
    display: none;
}

.upload-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    width: 0%;
    transition: width 0.3s;
}
    
    .save-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 40px;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        cursor: pointer;
        margin: 30px auto;
        display: block;
        transition: transform 0.3s;
    }
    
    .save-btn:hover {
        transform: translateY(-2px);
    }
    
    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #c3e6cb;
    }
    
    .error-message {
        background: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        border: 1px solid #f5c6cb;
    }
    
    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .dashboard-card {
            padding: 20px;
        }
        
        .dashboard-header h1 {
            font-size: 28px;
        }
    }
</style>

</head> 
<body> 

<?php if (!$is_authenticated): ?>

<div class="login-container">
    <form method="post" class="login-form">
        <h2>🎨 Artist Dashboard</h2>
        <p style="margin-bottom: 20px; color: #666;">Enter your password to access the content management system</p>
        
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit" class="login-btn">Access Dashboard</button>
    </form>
</div>

<?php else: ?>

<a href="?logout" class="logout-btn">Logout</a>

<?php if ($current_section === 'dashboard'): ?>
    <!-- MAIN DASHBOARD -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>🎨 Artist Website Management</h1>
            <p>Complete control over your website content - no WordPress complexity needed!</p>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <div class="dashboard-grid">
            <!-- Paintings Section -->
            <div class="dashboard-card" onclick="window.location.href='?section=paintings'">
                <span class="icon">🎨</span>
                <h3>Paintings Gallery</h3>
                <p>Manage your painting collection - titles, descriptions, images, and medium details</p>
                <span class="item-count">9 Paintings</span>
            </div>
            
            <!-- Commissions Section -->
            <div class="dashboard-card" onclick="window.location.href='?section=commissions'">
                <span class="icon">🏛️</span>
                <h3>Commissions</h3>
                <p>Edit commission types, descriptions, and showcase images</p>
                <span class="item-count">9 Commission Types</span>
            </div>
            
            <!-- Small Works Section -->
            <div class="dashboard-card" onclick="window.location.href='?section=small_works'">
                <span class="icon">🖼️</span>
                <h3>Small Works</h3>
                <p>Manage your collection of smaller pieces and studies</p>
                <span class="item-count">9 Small Works</span>
            </div>
            
            <!-- Text Page Section -->
            <div class="dashboard-card" onclick="window.location.href='?section=text_page'">
                <span class="icon">📝</span>
                <h3>Text Page Content</h3>
                <p>Edit all text content, artist statement, and narrative sections</p>
                <span class="item-count">All Text Content</span>
            </div>
            
            <!-- This Week Section -->
            <div class="dashboard-card" onclick="window.location.href='?section=this_week'">
                <span class="icon">📅</span>
                <h3>This Week Updates</h3>
                <p>Manage weekly updates, news, and current projects</p>
                <span class="item-count">Coming Soon</span>
            </div>
            
            <!-- Contact Page Section -->
            <div class="dashboard-card" onclick="window.location.href='?section=contact'">
                <span class="icon">📧</span>
                <h3>Contact Information</h3>
                <p>Update contact details, studio information, and availability</p>
                <span class="item-count">Coming Soon</span>
            </div>
        </div>
    </div>
    
<?php elseif ($current_section === 'paintings'): ?>
    <!-- PAINTINGS EDITOR -->
    <div class="dashboard-container">
        <div class="content-editor">
            <div class="section-header">
                <h2>🎨 Paintings Gallery Editor</h2>
                <a href="?section=dashboard" class="back-btn">← Back to Dashboard</a>
            </div>
            
            <form method="post">
                <input type="hidden" name="action" value="save_gallery">
                
                <div class="form-grid">
                    <?php for ($i = 1; $i <= 9; $i++): 
                        $painting = $current_content['paintings'][$i] ?? [];
                    ?>
                        <div class="form-card">
                            <h4>Painting <?php echo $i; ?></h4>
                            
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" name="painting_<?php echo $i; ?>_title" 
                                       value="<?php echo htmlspecialchars($painting['title'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Medium & Dimensions:</label>
                                <input type="text" name="painting_<?php echo $i; ?>_subtitle" 
                                       value="<?php echo htmlspecialchars($painting['subtitle'] ?? ''); ?>"
                                       placeholder="e.g., Oil on Canvas, 150cm x 150cm">
                            </div>
                            
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea name="painting_<?php echo $i; ?>_description" rows="4"><?php echo htmlspecialchars($painting['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Image Path:</label>
                                <input type="text" name="painting_<?php echo $i; ?>_image" 
                                       value="<?php echo htmlspecialchars($painting['image'] ?? ''); ?>"
                                       placeholder="images/painting-name.jpg"
                                       onchange="updatePreview(this, 'painting_<?php echo $i; ?>_preview')">
                                
                                <?php if (!empty($painting['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($painting['image']); ?>" 
                                         alt="Preview" class="image-preview" 
                                         id="painting_<?php echo $i; ?>_preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
                
                <button type="submit" class="save-btn">💾 Save All Paintings</button>
            </form>
        </div>
    </div>
    
<?php elseif ($current_section === 'commissions'): ?>
    <!-- COMMISSIONS EDITOR -->
    <div class="dashboard-container">
        <div class="content-editor">
            <div class="section-header">
                <h2>🏛️ Commissions Editor</h2>
                <a href="?section=dashboard" class="back-btn">← Back to Dashboard</a>
            </div>
            
            <form method="post">
                <input type="hidden" name="action" value="save_gallery">
                
                <div class="form-grid">
                    <?php for ($i = 1; $i <= 9; $i++): 
                        $commission = $current_content['commissions'][$i] ?? [];
                    ?>
                        <div class="form-card">
                            <h4>Commission Type <?php echo $i; ?></h4>
                            
                            <div class="form-group">
                                <label>Commission Title:</label>
                                <input type="text" name="commission_<?php echo $i; ?>_title" 
                                       value="<?php echo htmlspecialchars($commission['title'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea name="commission_<?php echo $i; ?>_description" rows="4"><?php echo htmlspecialchars($commission['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Image Path:</label>
                                <input type="text" name="commission_<?php echo $i; ?>_image" 
                                       value="<?php echo htmlspecialchars($commission['image'] ?? ''); ?>"
                                       placeholder="images/commission-name.jpg"
                                       onchange="updatePreview(this, 'commission_<?php echo $i; ?>_preview')">
                                
                                <?php if (!empty($commission['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($commission['image']); ?>" 
                                         alt="Preview" class="image-preview" 
                                         id="commission_<?php echo $i; ?>_preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
                
                <button type="submit" class="save-btn">💾 Save All Commissions</button>
            </form>
        </div>
    </div>
    
<?php elseif ($current_section === 'small_works'): ?>
    <!-- SMALL WORKS EDITOR -->
    <div class="dashboard-container">
        <div class="content-editor">
            <div class="section-header">
                <h2>🖼️ Small Works Editor</h2>
                <a href="?section=dashboard" class="back-btn">← Back to Dashboard</a>
            </div>
            
            <form method="post">
                <input type="hidden" name="action" value="save_gallery">
                
                <div class="form-grid">
                    <?php for ($i = 1; $i <= 9; $i++): 
                        $small_work = $current_content['small_works'][$i] ?? [];
                    ?>
                        <div class="form-card">
                            <h4>Small Work <?php echo $i; ?></h4>
                            
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" name="small_work_<?php echo $i; ?>_title" 
                                       value="<?php echo htmlspecialchars($small_work['title'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea name="small_work_<?php echo $i; ?>_description" rows="4"><?php echo htmlspecialchars($small_work['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Image Path:</label>
                                <input type="text" name="small_work_<?php echo $i; ?>_image" 
                                       value="<?php echo htmlspecialchars($small_work['image'] ?? ''); ?>"
                                       placeholder="images/small-work-name.jpg"
                                       onchange="updatePreview(this, 'small_work_<?php echo $i; ?>_preview')">
                                
                                <?php if (!empty($small_work['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($small_work['image']); ?>" 
                                         alt="Preview" class="image-preview" 
                                         id="small_work_<?php echo $i; ?>_preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
                
                <button type="submit" class="save-btn">💾 Save All Small Works</button>
            </form>
        </div>
    </div>
    
<?php elseif ($current_section === 'text_page'): ?>
    <!-- TEXT PAGE EDITOR -->
    <div class="dashboard-container">
        <div class="content-editor">
            <div class="section-header">
                <h2>📝 Text Page Content Editor</h2>
                <a href="?section=dashboard" class="back-btn">← Back to Dashboard</a>
            </div>
            
            <form method="post">
                <input type="hidden" name="action" value="save_text_page">
                
                <div class="form-grid">
                    <div class="form-card">
                        <h4>Main Page Heading</h4>
                        <div class="form-group">
                            <label>Page Title:</label>
                            <input type="text" name="text_main_heading" 
                                   value="<?php echo htmlspecialchars($current_content['text_page']['main_heading'] ?? 'About My Work'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-card">
                        <h4>Introduction</h4>
                        <div class="form-group">
                            <label>Opening Text:</label>
                            <textarea name="text_intro" rows="6"><?php echo htmlspecialchars($current_content['text_page']['intro_text'] ?? 'Welcome to my artistic journey...'); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-card">
                        <h4>Section 1</h4>
                        <div class="form-group">
                            <label>Section Title:</label>
                            <input type="text" name="text_section_1_title" 
                                   value="<?php echo htmlspecialchars($current_content['text_page']['section_1_title'] ?? 'My Artistic Philosophy'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Section Content:</label>
                            <textarea name="text_section_1_content" rows="6"><?php echo htmlspecialchars($current_content['text_page']['section_1_content'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-card">
                        <h4>Section 2</h4>
                        <div class="form-group">
                            <label>Section Title:</label>
                            <input type="text" name="text_section_2_title" 
                                   value="<?php echo htmlspecialchars($current_content['text_page']['section_2_title'] ?? 'Techniques & Materials'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Section Content:</label>
                            <textarea name="text_section_2_content" rows="6"><?php echo htmlspecialchars($current_content['text_page']['section_2_content'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-card">
                        <h4>Section 3</h4>
                        <div class="form-group">
                            <label>Section Title:</label>
                            <input type="text" name="text_section_3_title" 
                                   value="<?php echo htmlspecialchars($current_content['text_page']['section_3_title'] ?? 'Inspiration & Process'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Section Content:</label>
                            <textarea name="text_section_3_content" rows="6"><?php echo htmlspecialchars($current_content['text_page']['section_3_content'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form-card">
                        <h4>Conclusion</h4>
                        <div class="form-group">
                            <label>Closing Text:</label>
                            <textarea name="text_conclusion" rows="4"><?php echo htmlspecialchars($current_content['text_page']['conclusion_text'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="save-btn">💾 Save Text Page Content</button>
            </form>
        </div>
    </div>
    
<?php else: ?>
    <!-- COMING SOON SECTIONS -->
    <div class="dashboard-container">
        <div class="content-editor">
            <div class="section-header">
                <h2>🚧 Coming Soon</h2>
                <a href="?section=dashboard" class="back-btn">← Back to Dashboard</a>
            </div>
            
            <div style="text-align: center; padding: 60px 20px;">
                <h3 style="color: #2c3e50; margin-bottom: 20px;">This section is being developed!</h3>
                <p style="color: #666; font-size: 18px;">We're working on making every aspect of your website editable.</p>
                <p style="color: #666;">This feature will be available soon!</p>
            </div>
        </div>
    </div>
<?php endif; ?>  

<?php endif; ?>

// Replace the existing updatePreview function and add these new functions:

<script>
function updatePreview(input, previewId) {
    const preview = document.getElementById(previewId);
    if (preview) {
        preview.src = input.value;
        preview.style.display = input.value ? 'block' : 'none';
    }
}

// Enhanced file upload handling
function handleFileUpload(input, itemId, type) {
    const file = input.files[0];
    if (!file) return;
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file (JPG, PNG, GIF)');
        return;
    }
    
    // Validate file size (10MB limit)
    if (file.size > 10 * 1024 * 1024) {
        alert('File size must be less than 10MB');
        return;
    }
    
    // Show progress
    const progressContainer = document.getElementById(`progress_${itemId}`);
    const progressBar = document.getElementById(`progress_bar_${itemId}`);
    const newPreview = document.getElementById(`new_preview_${itemId}`);
    const successMessage = document.getElementById(`success_${itemId}`);
    const pathInput = document.getElementById(`${type}_${itemId}_path`);
    
    progressContainer.style.display = 'block';
    
    // Simulate upload progress (in real implementation, this would be actual upload progress)
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 30;
        if (progress > 100) progress = 100;
        
        progressBar.style.width = progress + '%';
        
        if (progress >= 100) {
            clearInterval(progressInterval);
            
            // Upload complete - show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                newPreview.src = e.target.result;
                newPreview.style.display = 'block';
                
                // Generate new filename
                const timestamp = Date.now();
                const extension = file.name.split('.').pop();
                const newFileName = `images/${type}-${itemId}-${timestamp}.${extension}`;
                
                // Update the path input
                pathInput.value = newFileName;
                
                // Show success message
                progressContainer.style.display = 'none';
                successMessage.style.display = 'block';
                
                // Hide success message after 3 seconds
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 3000);
                
                // Upload file to server (this would be implemented with actual file upload)
                uploadFileToServer(file, newFileName);
            };
            reader.readAsDataURL(file);
        }
    }, 100);
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add drag and drop listeners to all drop areas
    const dropAreas = document.querySelectorAll('.drag-drop-area');
    
    dropAreas.forEach(dropArea => {
        const dropId = dropArea.id.split('_')[1];
        const fileInput = dropArea.querySelector('.file-input');
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.add('dragover'), false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.remove('dragover'), false);
        });
        
        // Handle dropped files
        dropArea.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                const changeEvent = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(changeEvent);
            }
        }, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    // Auto-save functionality (existing)
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', autoSave);
    });
});

// File upload to server (placeholder - would need actual server-side implementation)
function uploadFileToServer(file, fileName) {
    // This would implement actual file upload to the server
    // For now, it's a placeholder that would integrate with your hosting file system
    console.log(`Uploading ${file.name} as ${fileName}`);
    
    // In a real implementation, this would use FormData and fetch/XMLHttpRequest
    // to upload the file to your hosting provider's file system
}

// Auto-save functionality (existing)
let autoSaveTimeout;
function autoSave() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        console.log('Auto-save triggered');
    }, 30000);
}
</script>
</body>
</html>