
<?php

/**
 * Revolutionary Artist Website Management Dashboard
 * Complete control over all website content
 */











// Simple password protection
session_start();
$correct_password = 'artist2025';

// Handle file uploads
if (isset($_FILES['uploaded_image']) && $_FILES['uploaded_image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'images/';
    
    // Create images directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file = $_FILES['uploaded_image'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Validate file type
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($file_extension, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        exit;
    }
    
    // Generate unique filename
    $timestamp = time();
    $safe_name = preg_replace('/[^a-zA-Z0-9.-]/', '-', $file['name']);
    $new_filename = "painting-{$timestamp}-{$safe_name}";
    $upload_path = $upload_dir . $new_filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        echo json_encode([
            'success' => true, 
            'path' => $upload_path,
            'message' => 'File uploaded successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Upload failed']);
    }
    exit;
}

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
    $content = json_decode(file_get_contents($content_file), true) ?: [];
    // Determine which section is being edited
    if (isset($_POST['section']) && $_POST['section'] === 'paintings') {
        $content['paintings'] = [];
        $painting_count = 0;
        foreach ($_POST as $key => $value) {
            if (preg_match('/^painting_(\d+)_title$/', $key, $matches)) {
                $i = intval($matches[1]);
                if (!empty(trim($_POST["painting_{$i}_title"]))) {
                    $painting_count++;
                    $content['paintings'][$painting_count] = [
                        'title' => sanitize_text_field($_POST["painting_{$i}_title"]),
                        'subtitle' => isset($_POST["painting_{$i}_subtitle"]) ? sanitize_text_field($_POST["painting_{$i}_subtitle"]) : '',
                        'description' => isset($_POST["painting_{$i}_description"]) ? sanitize_textarea_field($_POST["painting_{$i}_description"]) : '',
                        'image' => isset($_POST["painting_{$i}_image"]) ? sanitize_text_field($_POST["painting_{$i}_image"]) : ''
                    ];
                }
            }
        }
        $content['total_paintings'] = $painting_count;
    }
    if (isset($_POST['section']) && $_POST['section'] === 'commissions') {
        $content['commissions'] = [];
        $commission_count = 0;
        foreach ($_POST as $key => $value) {
            if (preg_match('/^commission_(\d+)_title$/', $key, $matches)) {
                $i = intval($matches[1]);
                if (!empty(trim($_POST["commission_{$i}_title"]))) {
                    $commission_count++;
                    $content['commissions'][$commission_count] = [
                        'title' => sanitize_text_field($_POST["commission_{$i}_title"]),
                        'subtitle' => isset($_POST["commission_{$i}_subtitle"]) ? sanitize_text_field($_POST["commission_{$i}_subtitle"]) : '',
                        'description' => isset($_POST["commission_{$i}_description"]) ? sanitize_textarea_field($_POST["commission_{$i}_description"]) : '',
                        'image' => isset($_POST["commission_{$i}_image"]) ? sanitize_text_field($_POST["commission_{$i}_image"]) : ''
                    ];
                }
            }
        }
        $content['total_commissions'] = $commission_count;
    }
    if (isset($_POST['section']) && $_POST['section'] === 'small_works') {
        $content['small_works'] = [];
        $small_work_count = 0;
        foreach ($_POST as $key => $value) {
            if (preg_match('/^small_work_(\d+)_title$/', $key, $matches)) {
                $i = intval($matches[1]);
                if (!empty(trim($_POST["small_work_{$i}_title"]))) {
                    $small_work_count++;
                    $content['small_works'][$small_work_count] = [
                        'title' => sanitize_text_field($_POST["small_work_{$i}_title"]),
                        'description' => isset($_POST["small_work_{$i}_description"]) ? sanitize_textarea_field($_POST["small_work_{$i}_description"]) : '',
                        'image' => isset($_POST["small_work_{$i}_image"]) ? sanitize_text_field($_POST["small_work_{$i}_image"]) : ''
                    ];
                }
            }
        }
        $content['total_small_works'] = $small_work_count;
    }
    // Save to JSON file
    if (file_put_contents($content_file, json_encode($content, JSON_PRETTY_PRINT))) {
        $success_message = "✨ Gallery updated successfully! Removed items are permanently deleted!";
    } else {
        $error_message = "❌ Error saving gallery content. Please try again.";
    }
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
/* ENHANCED BEAUTIFUL DASHBOARD BUTTONS - BIGGER & BETTER */
.dashboard-btn {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 12px !important;
    padding: 20px 35px !important; /* BIGGER: increased from 15px 25px */
    border: none !important;
    border-radius: 16px !important; /* MORE ROUNDED: increased from 12px */
    font-size: 1.1rem !important; /* BIGGER TEXT: increased from 1rem */
    font-weight: 600 !important; /* BOLDER: increased from 500 */
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    font-family: 'Helvetica', 'Helvetica Neue', Arial, sans-serif !important;
    min-width: 200px !important; /* WIDER: increased from 160px */
    min-height: 56px !important; /* TALLER: ensures perfect text centering */
    text-decoration: none !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15) !important; /* BIGGER SHADOW */
    text-align: center !important;
    line-height: 1.2 !important; /* PERFECT TEXT CENTERING */
}

.add-btn {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
    color: white !important;
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.3) !important; /* ENHANCED SHADOW */
}

.add-btn:hover {
    transform: translateY(-3px) !important; /* BIGGER LIFT: increased from -2px */
    box-shadow: 0 12px 35px rgba(39, 174, 96, 0.4) !important; /* DRAMATIC HOVER SHADOW */
}

.remove-btn {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
    color: white !important;
    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3) !important;
}

.remove-btn:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 12px 35px rgba(231, 76, 60, 0.4) !important;
}

.save-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3) !important;
    width: 100% !important;
    font-size: 1.2rem !important; /* BIGGER SAVE BUTTON TEXT */
    padding: 22px 40px !important; /* EXTRA BIG SAVE BUTTON */
    min-height: 60px !important;
}

.save-btn:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4) !important;
}

.btn-icon {
    font-size: 1.4rem !important; /* BIGGER ICONS */
    line-height: 1 !important;
}

.btn-text {
    font-weight: 600 !important;
    letter-spacing: 0.3px !important; /* SUBTLE LETTER SPACING */
}
</style>
    
<style>

/* PERFECT INPUT FIELD ALIGNMENT */
.form-group input,
.form-group textarea {
    width: 100% !important;
    padding: 12px 15px !important;
    border: 2px solid #e0e0e0 !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    transition: border-color 0.3s !important;
    font-family: inherit !important;
    box-sizing: border-box !important;
    margin: 0 !important;
}

.form-group {
    margin-bottom: 20px !important;
    width: 100% !important;
}

.form-card {
    padding: 25px !important;
    box-sizing: border-box !important;
}

        body, .dashboard-container, .login-container {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Helvetica Neue', Arial, sans-serif;
            background: url('images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
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
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 40px;
    }
    
    .dashboard-card {
    background: rgba(255, 255, 255, 0.65);
    border-radius: 24px;
    padding: 32px;
    text-align: center;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    cursor: pointer;
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(18px) saturate(180%);
    -webkit-backdrop-filter: blur(18px) saturate(180%);
    overflow: hidden;
    /* Glassmorphism effect */
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
            <div class="dashboard-card" onclick="window.open('?section=paintings', '_blank')">
                <span class="icon">🎨</span>
                <h3>Paintings Gallery</h3>
                <p>Manage your painting collection - titles, descriptions, images, and medium details</p>
                <span class="item-count">9 Paintings</span>
            </div>
            
            <!-- Commissions Section -->
            <div class="dashboard-card" onclick="window.open('?section=commissions', '_blank')">
                <span class="icon">🏛️</span>
                <h3>Commissions</h3>
                <p>Edit commission types, descriptions, and showcase images</p>
                <span class="item-count">9 Commission Types</span>
            </div>
            
            <!-- Small Works Section -->
            <div class="dashboard-card" onclick="window.open('?section=small_works', '_blank')">
                <span class="icon">🖼️</span>
                <h3>Small Works</h3>
                <p>Manage your collection of smaller pieces and studies</p>
                <span class="item-count">9 Small Works</span>
            </div>
            
            <!-- Text Page Section -->
            <div class="dashboard-card" onclick="window.open('?section=text_page', '_blank')">
                <span class="icon">📝</span>
                <h3>Text Page Content</h3>
                <p>Edit all text content, artist statement, and narrative sections</p>
                <span class="item-count">All Text Content</span>
            </div>
            
            <!-- This Week Section -->
            <div class="dashboard-card" onclick="window.open('?section=this_week', '_blank')">
                <span class="icon">📅</span>
                <h3>This Week Updates</h3>
                <p>Manage weekly updates, news, and current projects</p>
                <span class="item-count">Coming Soon</span>
            </div>
            
            <!-- SMALL WORKS EDITOR -->
            <div class="dashboard-container">
                <div class="content-editor">
                    <div class="section-header">
                        <h2>🖼️ Small Works Editor</h2>
                        <a href="?section=dashboard" class="back-btn">← Back to Dashboard</a>
                    </div>
                    <form method="post">
                        <input type="hidden" name="action" value="save_gallery">
                        <input type="hidden" name="section" value="small_works">
                        <div class="form-grid">
                            <?php for ($i = 1; $i <= 9; $i++): 
                                $small_work = $current_content['small_works'][$i] ?? [];
                            ?>
                                <div class="form-card" data-card-number="<?php echo $i; ?>">
                                    <h4>Small Work <?php echo $i; ?></h4>
                                    <div class="form-group">
                                        <label>Title:</label>
                                        <input type="text" name="small_work_<?php echo $i; ?>_title" value="<?php echo htmlspecialchars($small_work['title'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Description:</label>
                                        <textarea name="small_work_<?php echo $i; ?>_description" rows="4"><?php echo htmlspecialchars($small_work['description'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Image Path:</label>
                                        <input type="text" name="small_work_<?php echo $i; ?>_image" value="<?php echo htmlspecialchars($small_work['image'] ?? ''); ?>" placeholder="images/small-work-name.jpg" onchange="updatePreview(this, 'small_work_<?php echo $i; ?>_preview')">
                                        <?php if (!empty($small_work['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($small_work['image']); ?>" alt="Preview" class="image-preview" id="small_work_<?php echo $i; ?>_preview">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <div class="dashboard-actions">
                            <div class="card-management-section">
                                <h3>Gallery Management</h3>
                                <div class="card-buttons-group">
                                    <button type="button" id="add-smallwork-btn" class="dashboard-btn add-btn">
                                        <span class="btn-icon">+</span>
                                        <span class="btn-text">Add New Small Work</span>
                                    </button>
                                    <button type="button" id="remove-smallwork-btn" class="dashboard-btn remove-btn" disabled>
                                        <span class="btn-icon">-</span>
                                        <span class="btn-text">Remove Selected</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="save-section">
                            <button type="submit" class="dashboard-btn save-btn">
                                <span class="btn-icon">💾</span>
                                <span class="btn-text">Save and Publish</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                
                <div class="dashboard-actions">
                    <div class="card-management-section">
                        <h3>Gallery Management</h3>
                        <div class="card-buttons-group">
                            <button type="button" id="add-card-btn" class="dashboard-btn add-btn">
                                <span class="btn-icon">+</span>
                                <span class="btn-text">Add New Painting</span>
                            </button>
                            <button type="button" id="remove-card-btn" class="dashboard-btn remove-btn" disabled>
                                <span class="btn-icon">-</span>
                                <span class="btn-text">Remove Selected</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="save-section">
                    <button type="submit" class="dashboard-btn save-btn">
                        <span class="btn-icon">💾</span>
                        <span class="btn-text">Save and Publish</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

<?php elseif ($current_section === 'commissions'): ?>
    <!-- COMMISSIONS EDITOR -->
    <div class="dashboard-container">
        <div class="content-editor">
            <div class="section-header">
                <h2>🏛️ Commissions and Murals Editor</h2>
                <a href="?section=dashboard" class="back-btn">← Back to Dashboard</a>
            </div>
            <form method="post">
                <input type="hidden" name="action" value="save_gallery">
                <input type="hidden" name="section" value="commissions">
                <div class="form-grid">
                    <?php for ($i = 1; $i <= 9; $i++): 
                        $commission = $current_content['commissions'][$i] ?? [];
                    ?>
                        <div class="form-card" data-card-number="<?php echo $i; ?>">
                            <h4>Commission Type <?php echo $i; ?></h4>
                            <div class="form-group">
                                <label>Title:</label>
                                <input type="text" name="commission_<?php echo $i; ?>_title" value="<?php echo htmlspecialchars($commission['title'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Type & Details:</label>
                                <input type="text" name="commission_<?php echo $i; ?>_subtitle" value="<?php echo htmlspecialchars($commission['subtitle'] ?? ''); ?>" placeholder="e.g., Mural, Portrait Commission, Corporate Art">
                            </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea name="commission_<?php echo $i; ?>_description" rows="4"><?php echo htmlspecialchars($commission['description'] ?? ''); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Image Path:</label>
                                <input type="text" name="commission_<?php echo $i; ?>_image" value="<?php echo htmlspecialchars($commission['image'] ?? ''); ?>" placeholder="images/commission-name.jpg" onchange="updatePreview(this, 'commission_<?php echo $i; ?>_preview')">
                                <?php if (!empty($commission['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($commission['image']); ?>" alt="Preview" class="image-preview" id="commission_<?php echo $i; ?>_preview">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
                <div class="dashboard-actions">
                    <div class="card-management-section">
                        <h3>Gallery Management</h3>
                        <div class="card-buttons-group">
                            <button type="button" id="add-commission-btn" class="dashboard-btn add-btn">
                                <span class="btn-icon">+</span>
                                <span class="btn-text">Add New Commission</span>
                            </button>
                            <button type="button" id="remove-commission-btn" class="dashboard-btn remove-btn" disabled>
                                <span class="btn-icon">-</span>
                                <span class="btn-text">Remove Selected</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="save-section">
                    <button type="submit" class="dashboard-btn save-btn">
                        <span class="btn-icon">💾</span>
                        <span class="btn-text">Save and Publish</span>
                    </button>
                </div>
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
                <input type="hidden" name="section" value="small_works">
                
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
                
                <div class="dashboard-actions">
                    <div class="card-management-section">
                        <h3>Gallery Management</h3>
                        <div class="card-buttons-group">
                            <button type="button" id="add-card-btn" class="dashboard-btn add-btn">
                                <span class="btn-icon">+</span>
                                <span class="btn-text">Add New Small Work</span>
                            </button>
                            <button type="button" id="remove-card-btn" class="dashboard-btn remove-btn" disabled>
                                <span class="btn-icon">-</span>
                                <span class="btn-text">Remove Selected</span>
                                </button>
                        </div>
                    </div>
                                </div>

                <div class="save-section">
        <button type="submit" class="dashboard-btn save-btn">
            <span class="btn-icon">💾</span>
            <span class="btn-text">Save and Publish</span>
        </button>
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

<script>
function updatePreview(input, previewId) {
    const preview = document.getElementById(previewId);
    if (preview) {
        preview.src = input.value;
        preview.style.display = input.value ? 'block' : 'none';
    }
}
</script>

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

<script>
// ==============================================
//    MAGICAL "ADD CARD" FUNCTIONALITY
// ==============================================

// Modal HTML - we'll inject this dynamically
const addPaintingModalHTML = `
<div class="modal-overlay" id="add-painting-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>✨ Add New Painting</h3>
            <button class="modal-close" id="close-add-modal">×</button>
        </div>
        <div class="modal-body">
            <form id="add-painting-form">
                <div class="form-group">
                    <label for="new-painting-title">🎨 Painting Title *</label>
                    <input type="text" id="new-painting-title" name="title" required 
                           placeholder="Enter the title of your painting">
                </div>
                
                <div class="form-group">
                    <label for="new-painting-subtitle">📏 Medium & Dimensions *</label>
                    <input type="text" id="new-painting-subtitle" name="subtitle" required 
                           placeholder="e.g., Oil on Canvas, 150cm x 150cm">
                </div>
                
                <div class="form-group">
                    <label for="new-painting-description">📝 Description *</label>
                    <textarea id="new-painting-description" name="description" rows="4" required 
                              placeholder="Tell the story of this painting..."></textarea>
                </div>
                
                <div class="form-group">
    <label for="new-painting-image">🖼️ Image Upload *</label>
    
    <div class="drag-drop-zone" id="drag-drop-zone">
        <div class="drag-drop-content">
            <div class="upload-icon">📤</div>
            <p class="upload-text">Drag & drop your image here</p>
            <p class="upload-or">or</p>
            <button type="button" class="browse-btn" onclick="document.getElementById('file-input').click()">Browse Files</button>
            <input type="file" id="file-input" accept="image/*" style="display: none;">
        </div>
        
        <div class="upload-progress" id="upload-progress" style="display: none;">
            <div class="progress-bar" id="progress-bar"></div>
            <span class="progress-text" id="progress-text">0%</span>
        </div>
        
        <div class="upload-success" id="upload-success" style="display: none;">
            <span class="success-icon">✅</span>
            <span class="success-text">Image uploaded successfully!</span>
        </div>
    </div>
    
    <input type="text" id="new-painting-image" name="image" required 
           placeholder="Auto-generated path will appear here" readonly style="margin-top: 15px;">
    
    <div class="image-preview-container">
        <img id="new-painting-preview" class="new-image-preview" style="display: none;">
    </div>
</div>
                
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="dashboard-btn secondary-btn" id="cancel-add-painting">Cancel</button>
            <button type="button" class="dashboard-btn add-btn" id="confirm-add-painting">
                <span class="btn-icon">✨</span>
                <span class="btn-text">Add This Painting</span>
            </button>
        </div>
    </div>
</div>`;

// Beautiful Modal CSS
const modalCSS = `
<style>
/* STUNNING MODAL STYLES */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
    z-index: 10000;
    display: none;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal-overlay.active {
    display: flex;
    opacity: 1;
}

.modal-content {
    background: white;
    border-radius: 20px;
    max-width: 700px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    transform: scale(0.9) translateY(30px);
    transition: transform 0.3s ease;
}

.modal-overlay.active .modal-content {
    transform: scale(1) translateY(0);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px 35px;
    border-bottom: 2px solid #f0f0f0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px 20px 0 0;
}

.modal-header h3 {
    font-size: 1.8rem;
    margin: 0;
    font-weight: 500;
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    font-size: 1.8rem;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 8px 12px;
    border-radius: 10px;
    line-height: 1;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.modal-body {
    padding: 35px;
}

.modal-body .form-group {
    margin-bottom: 25px;
}

.modal-body .form-group label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 1.1rem;
}

.modal-body .form-group input,
.modal-body .form-group textarea {
    width: 100%;
    padding: 15px 18px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: 'Helvetica', 'Helvetica Neue', Arial, sans-serif;
    box-sizing: border-box;
}

.modal-body .form-group input:focus,
.modal-body .form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.form-help {
    display: block;
    font-size: 0.9rem;
    color: #666;
    margin-top: 8px;
    font-style: italic;
}

.new-image-preview {
    width: 100%;
    max-width: 300px;
    height: 180px;
    object-fit: cover;
    border-radius: 12px;
    margin-top: 15px;
    border: 3px solid #e0e0e0;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    padding: 30px 35px;
    border-top: 2px solid #f0f0f0;
    background: #f8f9fa;
    border-radius: 0 0 20px 20px;
}

.secondary-btn {
    background: #6c757d !important;
    color: white !important;
}

.secondary-btn:hover {
    background: #5a6268 !important;
    transform: translateY(-2px) !important;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 20px;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 25px;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .modal-footer .dashboard-btn {
        width: 100%;
    }
}
</style>`;

// Inject modal CSS and HTML when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Add modal CSS to head
    document.head.insertAdjacentHTML('beforeend', modalCSS);
    
    // Add modal HTML to body
    document.body.insertAdjacentHTML('beforeend', addPaintingModalHTML);
    
    // Get modal elements
    const modal = document.getElementById('add-painting-modal');
    const addBtn = document.getElementById('add-card-btn');
    const closeBtn = document.getElementById('close-add-modal');
    const cancelBtn = document.getElementById('cancel-add-painting');
    const confirmBtn = document.getElementById('confirm-add-painting');
    const imageInput = document.getElementById('new-painting-image');
    const imagePreview = document.getElementById('new-painting-preview');
    
    // Open modal when "Add New Painting" is clicked
    if (addBtn) {
        addBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });
    }
    
    // Close modal functions
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = ''; // Restore scrolling
        // Clear form
        document.getElementById('add-painting-form').reset();
        imagePreview.style.display = 'none';
    }
    
    // Close modal events
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
    
    // Image preview functionality
    imageInput.addEventListener('input', function() {
        if (this.value) {
            imagePreview.src = this.value;
            imagePreview.style.display = 'block';
        } else {
            imagePreview.style.display = 'none';
        }
    });
    
    // Add painting functionality
    confirmBtn.addEventListener('click', function() {
        const title = document.getElementById('new-painting-title').value.trim();
        const subtitle = document.getElementById('new-painting-subtitle').value.trim();
        const description = document.getElementById('new-painting-description').value.trim();
        const image = document.getElementById('new-painting-image').value.trim();
        
        // Validate required fields
        if (!title || !subtitle || !description || !image) {
            alert('🚨 Please fill in all required fields!');
            return;
        }
        
        // Success notification
        showSuccessNotification('🎨 New painting added successfully! Save and publish to make it live.');
        
        // Add new form card to the grid (visual feedback)
        addNewPaintingCard(title, subtitle, description, image);
        
        // Close modal
        closeModal();
    });
    
    // Success notification function
    function showSuccessNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'success-notification';
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">✨</span>
                <span class="notification-text">${message}</span>
                <button class="notification-close">×</button>
            </div>
        `;
        
        // Add notification styles
        const notificationCSS = `
            <style>
            .success-notification {
                position: fixed;
                top: 30px;
                right: 30px;
                background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
                color: white;
                padding: 20px 25px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(39, 174, 96, 0.3);
                z-index: 10001;
                transform: translateX(400px);
                transition: transform 0.4s ease;
                max-width: 400px;
            }
            
            .success-notification.show {
                transform: translateX(0);
            }
            
            .notification-content {
                display: flex;
                align-items: center;
                gap: 15px;
            }
            
            .notification-icon {
                font-size: 1.5rem;
            }
            
            .notification-text {
                flex: 1;
                font-weight: 500;
            }
            
            .notification-close {
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                font-size: 1.2rem;
                cursor: pointer;
                padding: 5px 8px;
                border-radius: 5px;
                transition: background 0.3s ease;
            }
            
            .notification-close:hover {
                background: rgba(255, 255, 255, 0.3);
            }
            </style>
        `;
        
        if (!document.querySelector('.notification-styles')) {
            const styleTag = document.createElement('style');
            styleTag.className = 'notification-styles';
            styleTag.innerHTML = notificationCSS.replace('<style>', '').replace('</style>', '');
            document.head.appendChild(styleTag);
        }
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => notification.classList.add('show'), 100);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 400);
        }, 5000);
        
        // Manual close
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 400);
        });
    }
    
    // Add new painting card to grid (visual feedback)
    function addNewPaintingCard(title, subtitle, description, image) {
        const formGrid = document.querySelector('.form-grid');
        const newCardIndex = formGrid.children.length + 1;
        
        const newCard = document.createElement('div');
        newCard.className = 'form-card new-card';
        newCard.innerHTML = `
            <h4>Painting ${newCardIndex} <span style="color: #27ae60; font-size: 0.8em;">✨ NEW</span></h4>
            
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="painting_${newCardIndex}_title" value="${title}">
            </div>
            
            <div class="form-group">
                <label>Medium & Dimensions:</label>
                <input type="text" name="painting_${newCardIndex}_subtitle" value="${subtitle}">
            </div>
            
            <div class="form-group">
                <label>Description:</label>
                <textarea name="painting_${newCardIndex}_description" rows="4">${description}</textarea>
            </div>
            
            <div class="form-group">
                <label>Image Path:</label>
                <input type="text" name="painting_${newCardIndex}_image" value="${image}">
                <img src="${image}" alt="Preview" class="image-preview">
            </div>
        `;
        
        // Add entrance animation
        newCard.style.opacity = '0';
        newCard.style.transform = 'translateY(30px)';
        newCard.style.transition = 'all 0.5s ease';
        
        formGrid.appendChild(newCard);
        
        // Animate in
        setTimeout(() => {
            newCard.style.opacity = '1';
            newCard.style.transform = 'translateY(0)';
        }, 100);
        
        // Highlight briefly
        setTimeout(() => {
            newCard.style.background = '#e8f5e8';
            setTimeout(() => {
                newCard.style.background = '#f8f9fa';
            }, 2000);
        }, 600);
    }
});

// ==============================================
//    "REMOVE SELECTED" FUNCTIONALITY
// ==============================================

// Add selection functionality to cards
document.addEventListener('DOMContentLoaded', function() {
    addSelectionFunctionality();
    setupRemoveSelectedButton();
});

function addSelectionFunctionality() {
    // Add selection checkboxes to all cards
    const formCards = document.querySelectorAll('.form-card');
    
    formCards.forEach((card, index) => {
        // Skip if already has checkbox
        if (card.querySelector('.card-selector')) return;
        
        const cardNumber = index + 1;
        
        // Create selection checkbox
        const selectorHTML = `
            <div class="card-selection-container">
                <label class="card-selector">
                    <input type="checkbox" class="card-checkbox" data-card="${cardNumber}">
                    <span class="checkbox-custom"></span>
                    <span class="selector-text">Select for removal</span>
                </label>
            </div>
        `;
        
        // Add to top of card
        card.insertAdjacentHTML('afterbegin', selectorHTML);
        
        // Add selection styling
        const checkbox = card.querySelector('.card-checkbox');
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                card.classList.add('selected-for-removal');
            } else {
                card.classList.remove('selected-for-removal');
            }
            updateRemoveButton();
        });
    });
    
    // Add selection CSS
    if (!document.querySelector('.selection-styles')) {
        const selectionCSS = `
            <style class="selection-styles">
            .card-selection-container {
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 2px solid #f0f0f0;
            }
            
            .card-selector {
                display: flex;
                align-items: center;
                gap: 10px;
                cursor: pointer;
                padding: 8px 12px;
                background: rgba(231, 76, 60, 0.1);
                border-radius: 8px;
                transition: all 0.3s ease;
                font-size: 14px;
                font-weight: 500;
            }
            
            .card-selector:hover {
                background: rgba(231, 76, 60, 0.15);
            }
            
            .card-checkbox {
                width: 18px;
                height: 18px;
                cursor: pointer;
            }
            
            .selector-text {
                color: #e74c3c;
                font-weight: 500;
            }
            
            .selected-for-removal {
                background: rgba(231, 76, 60, 0.1) !important;
                border: 3px solid #e74c3c !important;
                transform: scale(0.98);
                opacity: 0.8;
            }
            
            .selected-for-removal h4 {
                color: #e74c3c !important;
            }
            
            .selected-for-removal .card-selector {
                background: rgba(231, 76, 60, 0.2);
            }
            
            .remove-selected-active {
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
                color: white !important;
                cursor: pointer !important;
                opacity: 1 !important;
            }
            
            .remove-selected-disabled {
                opacity: 0.5 !important;
                cursor: not-allowed !important;
            }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', selectionCSS);
    }
}

function setupRemoveSelectedButton() {
    const removeBtn = document.getElementById('remove-card-btn');
    if (!removeBtn) return;
    
    removeBtn.addEventListener('click', function() {
        const selectedCards = document.querySelectorAll('.card-checkbox:checked');
        
        if (selectedCards.length === 0) {
            alert('🚨 Please select at least one painting to remove!');
            return;
        }
        
        // Get selected card numbers
        const cardNumbers = Array.from(selectedCards).map(cb => cb.dataset.card);
        const cardNames = cardNumbers.map(num => {
            const titleInput = document.querySelector(`input[name="painting_${num}_title"]`);
            return titleInput ? titleInput.value || `Painting ${num}` : `Painting ${num}`;
        });
        
        // Confirmation dialog
        const confirmMessage = `🗑️ Are you sure you want to remove these ${selectedCards.length} painting(s)?\n\n${cardNames.join('\n')}\n\n⚠️ This action cannot be undone!`;
        
        if (confirm(confirmMessage)) {
            // Remove selected cards with animation
            selectedCards.forEach((checkbox, index) => {
                const card = checkbox.closest('.form-card');
                
                // Animate out
                card.style.transition = 'all 0.5s ease';
                card.style.transform = 'translateX(-100%)';
                card.style.opacity = '0';
                
                // Remove from DOM after animation
                setTimeout(() => {
                    card.remove();
                    
                    // Show success notification when last card is removed
                    if (index === selectedCards.length - 1) {
                        showSuccessNotification(`🗑️ ${selectedCards.length} painting(s) removed successfully! Don't forget to Save and Publish.`);
                        updateRemoveButton();
                    }
                }, 500);
            });
        }
    });
}

function updateRemoveButton() {
    const removeBtn = document.getElementById('remove-card-btn');
    const selectedCards = document.querySelectorAll('.card-checkbox:checked');
    
    if (selectedCards.length > 0) {
        removeBtn.disabled = false;
        removeBtn.classList.remove('remove-selected-disabled');
        removeBtn.classList.add('remove-selected-active');
        removeBtn.innerHTML = `
            <span class="btn-icon">🗑️</span>
            <span class="btn-text">Remove ${selectedCards.length} Selected</span>
        `;
    } else {
        removeBtn.disabled = true;
        removeBtn.classList.add('remove-selected-disabled');
        removeBtn.classList.remove('remove-selected-active');
        removeBtn.innerHTML = `
            <span class="btn-icon">-</span>
            <span class="btn-text">Remove Selected</span>
        `;
    }
}

// ==============================================
//    DRAG & DROP FUNCTIONALITY
// ==============================================

document.addEventListener('DOMContentLoaded', function() {
    setupDragAndDrop();
});

function setupDragAndDrop() {
    const dragDropZone = document.getElementById('drag-drop-zone');
    const fileInput = document.getElementById('file-input');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const uploadSuccess = document.getElementById('upload-success');
    const imagePathInput = document.getElementById('new-painting-image');
    const imagePreview = document.getElementById('new-painting-preview');
    
    if (!dragDropZone || !fileInput) return;
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dragDropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dragDropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dragDropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dragDropZone.classList.add('dragover');
    }
    
    function unhighlight() {
        dragDropZone.classList.remove('dragover');
    }
    
    // Handle dropped files
    dragDropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }
    
    // Handle browse button selection
    fileInput.addEventListener('change', function(e) {
        handleFiles(this.files);
    });
    
    // Main file handling function
    function handleFiles(files) {
        if (files.length === 0) return;
        
        const file = files[0];
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('❌ Please select an image file (JPG, PNG, GIF, WebP)');
            return;
        }
        
        // Validate file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            alert('❌ File size must be less than 10MB');
            return;
        }
        
        // Show upload progress
        uploadProgress.style.display = 'block';
        uploadSuccess.style.display = 'none';
        
        // Simulate upload progress (in real world, this would be actual upload)
        simulateUpload(file);
    }
    
    function simulateUpload(file) {
        let progress = 0;
        
        const uploadInterval = setInterval(() => {
            progress += Math.random() * 25 + 5; // Random progress between 5-30%
            if (progress > 100) progress = 100;
            
            progressBar.style.width = progress + '%';
            progressText.textContent = Math.round(progress) + '%';
            
            if (progress >= 100) {
                clearInterval(uploadInterval);
                completeUpload(file);
            }
        }, 150);
    }
    
    function completeUpload(file) {
    // Create FormData for actual file upload
    const formData = new FormData();
    formData.append('uploaded_image', file);
    
    // Upload to server
    fetch(window.location.pathname, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide progress, show success
            uploadProgress.style.display = 'none';
            uploadSuccess.style.display = 'block';
            
            // Update the path input with real server path
            imagePathInput.value = data.path;
            
            // Show image preview
            imagePreview.src = data.path;
            imagePreview.style.display = 'block';
            
            // Hide success message after 3 seconds
            setTimeout(() => {
                uploadSuccess.style.display = 'none';
            }, 3000);
        } else {
            alert('Upload failed: ' + data.message);
            uploadProgress.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        alert('Upload failed. Please try again.');
        uploadProgress.style.display = 'none';
    });
}

// Add enhanced drag & drop CSS
const dragDropCSS = `
<style>
.drag-drop-zone {
    border: 3px dashed #ccc;
    border-radius: 15px;
    padding: 40px 20px;
    text-align: center;
    background: #f8f9fa;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-top: 10px;
}

.drag-drop-zone:hover {
    border-color: #667eea;
    background: #f0f2ff;
}

.drag-drop-zone.dragover {
    border-color: #667eea;
    background: #e8ebff;
    transform: scale(1.02);
    border-style: solid;
}

.drag-drop-content {
    pointer-events: none;
}

.upload-icon {
    font-size: 3rem;
    color: #667eea;
    margin-bottom: 15px;
    display: block;
}

.upload-text {
    font-size: 1.2rem;
    color: #333;
    margin: 10px 0;
    font-weight: 500;
}

.upload-or {
    color: #666;
    margin: 15px 0;
    font-size: 1rem;
}

.browse-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    pointer-events: auto;
}

.browse-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.upload-progress {
    background: #f0f0f0;
    border-radius: 10px;
    padding: 15px;
    margin-top: 15px;
}

.progress-bar {
    height: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    width: 0%;
    transition: width 0.3s ease;
    position: relative;
}

.progress-text {
    display: block;
    text-align: center;
    font-weight: bold;
    color: #333;
    margin-top: 10px;
}

.upload-success {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    color: white;
    padding: 15px;
    border-radius: 10px;
    margin-top: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: center;
}

.success-icon {
    font-size: 1.5rem;
}

.success-text {
    font-weight: 500;
}
</style>`;

// Inject the CSS
if (!document.querySelector('.drag-drop-styles')) {
    const styleTag = document.createElement('style');
    styleTag.className = 'drag-drop-styles';
    styleTag.innerHTML = dragDropCSS.replace('<style>', '').replace('</style>', '');
    document.head.appendChild(styleTag);
}

}

</script>

</body>
</html>