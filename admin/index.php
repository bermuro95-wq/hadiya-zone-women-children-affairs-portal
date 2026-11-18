<?php
session_start();
require_once 'db.php';

// Handle Language Switcher Session & Cookie Configuration
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] == 'am' ? 'am' : 'en';
    $_SESSION['lang'] = $lang;
    setcookie('lang', $lang, time() + (86400 * 30), "/"); // Cookie lives for 30 days
} else {
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'en');
}

// Language Translations Dictionary Array
$translations = [
    'en' => [
        'home'=>'Home', 'about'=>'About Us', 'news'=>'News', 'contact'=>'Contact Us',
        'mission'=>'Mission', 'vision'=>'Vision', 'values'=>'Values', 'goals'=>'Goals',
        'services'=>'Services', 'resources'=>'Resources', 'history'=>'History', 'audit'=>'Audit Logs',
        'messages'=>'Messages',
        'admin_panel'=>'Admin Dashboard Controls', 'logout'=>'Logout', 'login'=>'Login',
        'edit'=>'Update Content', 'delete'=>'Delete', 'save'=>'Save Changes', 'add_news'=>'Post News Item',
        'user_mode'=>'Viewer Access Only Mode',
        'bureau_title'=>'HADIYA ZONE BUREAU OF WOMENS <br> AND CHILDREN AFFAIRS',
        'read_more'=>'Read More'
    ],
    'am' => [
        'home'=>'ዋና ገጽ', 'about'=>'ስለ እኛ', 'news'=>'ዜና', 'contact'=>'አግኙን',
        'mission'=>'ተልዕኮ', 'vision'=>'ራዕይ', 'values'=>'እሴቶች', 'goals'=>'ግቦች',
        'services'=>'አገልግሎቶች', 'resources'=>'ግብዓቶች', 'history'=>'ታሪክ', 'audit'=>'የኦዲት መዝገብ',
        'messages'=>'መልእክቶች',
        'admin_panel'=>'የአስተዳዳሪ መቆጣጠሪያ', 'logout'=>'ውጣ', 'login'=>'ግባ',
        'edit'=>'ይዘት ያሻሽሉ', 'delete'=>'ሰርዝ', 'save'=>'ለውጦችን አስቀምጥ', 'add_news'=>'ዜና ይለጥፉ',
        'user_mode'=>'የዕይታ ብቻ መዳረሻ ሁኔታ',
        'bureau_title'=>'ሀዲያ ዞን ሴቶችና ህጻናት ጉዳይ ቢሮ',
        'read_more'=>'ተጨማሪ ያንብቡ'
    ]
];

// Helper function to split text into lists by punctuation marks dynamically
function render_as_sentence_list($text, $current_lang) {
    if (empty(trim($text))) return '';
    
    // Select delimiter based on active language settings (. for English, ። for Amharic)
    $delimiter = ($current_lang === 'am') ? '።' : '.';
    
    // Explode the string using the targeted punctuation mark
    $sentences = explode($delimiter, $text);
    $output = '';
    
    foreach ($sentences as $sentence) {
        $trimmed = trim($sentence);
        if (!empty($trimmed)) {
            // Re-append the matching punctuation mark at the end of each statement element block
            $output .= htmlspecialchars($trimmed . $delimiter) . '<br>';
        }
    }
    return $output;
}

// Fetch Main Website Structured Contents (Retrieve both language mutations)
$content_stmt = $pdo->query("SELECT * FROM site_content");
$site_data = [];
while($row = $content_stmt->fetch()){
    $site_data[$row['content_key']] = [
        'en' => $row['content_en'],
        'am' => $row['content_am']
    ];
}

// Form Handlers: Delete Individual Audit Log Entry
if (isset($_GET['delete_audit_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $audit_id = $_GET['delete_audit_id'];
    
    // Permanently remove the record from the database
    $del_stmt = $pdo->prepare("DELETE FROM audit_logs WHERE id = ?");
    $del_stmt->execute([$audit_id]);
    
    // Redirect back to the audit log section
    header("Location: index.php#audit-section");
    exit();
}

// Form Handlers: Delete Individual Contact Message Entry
if (isset($_GET['delete_msg_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $msg_id = $_GET['delete_msg_id'];
    
    $del_stmt = $pdo->prepare("DELETE FROM w_c_issues.contact_messages WHERE id = ?");
    $del_stmt->execute([$msg_id]);
    
    $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
    $audit->execute([$_SESSION['username'], "Deleted message ID: " . $msg_id]);
    
    header("Location: index.php#messages-section");
    exit();
}

// Form Handlers: Upload New Gallery Image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_upload_gallery']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    if (isset($_FILES['gallery_img']) && $_FILES['gallery_img']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "photos/";
        
        // Ensure directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES["gallery_img"]["name"]);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["gallery_img"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO gallery_images (image_path) VALUES (?)");
            $stmt->execute([$target_file]);
            
            $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
            $audit->execute([$_SESSION['username'], "Uploaded a new image to the gallery: " . $file_name]);
            
            header("Location: index.php#gallery-section");
            exit();
        }
    }
}


// Form Handlers: Delete Gallery Image
if (isset($_GET['delete_gallery_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $id = $_GET['delete_gallery_id'];
    
    // Fetch file path to delete from physical disk space
    $stmt = $pdo->prepare("SELECT image_path FROM gallery_images WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetch();
    
    if ($img) {
        if (file_exists($img['image_path'])) {
            unlink($img['image_path']); // Remove file from server storage
        }
        
        $del_stmt = $pdo->prepare("DELETE FROM gallery_images WHERE id = ?");
        $del_stmt->execute([$id]);
        
        $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
        $audit->execute([$_SESSION['username'], "Deleted gallery image ID: " . $id]);
    }
    header("Location: index.php#gallery-section");
    exit();
}

// Form Handlers: Update Main Content (Simultaneous English & Amharic updates)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_update']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $key = $_POST['content_key'];
    $text_en = $_POST['updated_text_en'];
    $text_am = $_POST['updated_text_am'];
    
    $stmt = $pdo->prepare("UPDATE site_content SET content_en = ?, content_am = ? WHERE content_key = ?");
    $stmt->execute([$text_en, $text_am, $key]);
    
    // Log Activity to Audit Table
    $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
    $audit->execute([$_SESSION['username'], "Updated dual-language content for key: " . $key]);
    header("Location: index.php");
    exit();
}

// Form Handlers: Post New News Stories
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_news']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $title_en = $_POST['title_en']; $title_am = $_POST['title_am'];
    $body_en = $_POST['body_en']; $body_am = $_POST['body_am'];
    
    $stmt = $pdo->prepare("INSERT INTO news (title_en, title_am, body_en, body_am) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title_en, $title_am, $body_en, $body_am]);
    
    $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
    $audit->execute([$_SESSION['username'], "Posted new news article tracking profile."]);
    header("Location: index.php");
    exit();
}

// Form Handlers: Delete News Stories
if (isset($_GET['delete_news_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $id = $_GET['delete_news_id'];
    $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
    $stmt->execute([$id]);
    
    $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
    $audit->execute([$_SESSION['username'], "Deleted structural news item ID: " . $id]);
    header("Location: index.php");
    exit();
}

// Form Handlers: Store Contact Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_contact'])) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        // Specify database explicitly in table query
        $stmt = $pdo->prepare("INSERT INTO w_c_issues.contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);

        // Optional activity logging
        if (isset($_SESSION['username'])) {
            $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
            $audit->execute([$_SESSION['username'], "Submitted contact form message."]);
        }

        header("Location: index.php?status=success#contact");
        exit();
    }
}



// Form Handler: Update Social Media Links (Admin Only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_update_socials']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $platforms = ['facebook', 'twitter', 'youtube', 'telegram', 'whatsapp', 'instagram', 'linkedin'];
    
    $stmt = $pdo->prepare("INSERT INTO w_c_issues.social_links (platform, url) VALUES (?, ?) ON DUPLICATE KEY UPDATE url = VALUES(url)");
    
    foreach ($platforms as $platform) {
        $url = trim($_POST[$platform] ?? '');
        if (!empty($url)) {
            $stmt->execute([$platform, $url]);
        }
    }

    // Audit Log
    if (isset($_SESSION['username'])) {
        $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
        $audit->execute([$_SESSION['username'], "Updated social media links."]);
    }

    header("Location: index.php#footer");
    exit();
}

// Fetch Social Links from Database
$social_links = [
    'facebook'  => 'https://facebook.com',
    'twitter'   => 'https://twitter.com',
    'youtube'   => 'https://youtube.com',
    'telegram'  => 'https://t.me/+DTVJJJ96gwk3YTg0',
    'whatsapp' => 'https://wa.me/251118723297',
    'instagram' => 'https://instagram.com',
    'linkedin'  => 'https://linkedin.com'
];

$social_stmt = $pdo->query("SELECT * FROM w_c_issues.social_links");
while ($row = $social_stmt->fetch()) {
    $social_links[$row['platform']] = $row['url'];
}

// Form Handler: Update Office Contact Info (Admin Only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_update_hq']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $hq_address = trim($_POST['hq_address']);
    $hq_phone   = trim($_POST['hq_phone']);
    $hq_email   = trim($_POST['hq_email']);

    $stmt = $pdo->prepare("INSERT INTO w_c_issues.office_settings (setting_key, setting_value) 
                           VALUES ('hq_address', ?), ('hq_phone', ?), ('hq_email', ?) 
                           ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    $stmt->execute([$hq_address, $hq_phone, $hq_email]);

    // Log Activity to Audit Table
    if (isset($_SESSION['username'])) {
        $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
        $audit->execute([$_SESSION['username'], "Updated Office HQ contact details."]);
    }

    header("Location: index.php#contact");
    exit();
}

// Fetch HQ Contact Details from Database
$hq_data = [];
$hq_stmt = $pdo->query("SELECT * FROM w_c_issues.office_settings");
while ($row = $hq_stmt->fetch()) {
    $hq_data[$row['setting_key']] = $row['setting_value'];
}

$hq_address = $hq_data['hq_address'] ?? 'Soro-Bara, Hossana, Ethiopia';
$hq_phone   = $hq_data['hq_phone'] ?? '046-555-23-25';
$hq_email   = $hq_data['hq_email'] ?? 'Hadiyazonewomen&ChildrenAffairs@gmail.com';

// Form Handlers: Add New Hero Slider Background Photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_add_hero_slide']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "photos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = time() . '_hero_' . basename($_FILES["hero_img"]["name"]);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["hero_img"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO w_c_issues.hero_slides (image_path) VALUES (?)");
            $stmt->execute([$target_file]);
            
            $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
            $audit->execute([$_SESSION['username'], "Added a new background photo to the Hero Slider: " . $file_name]);
            
            header("Location: index.php#home");
            exit();
        }
    }
}

// Form Handlers: Remove Hero Slider Background Photo
if (isset($_GET['delete_hero_slide_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $slide_id = $_GET['delete_hero_slide_id'];
    
    $stmt = $pdo->prepare("SELECT image_path FROM w_c_issues.hero_slides WHERE id = ?");
    $stmt->execute([$slide_id]);
    $slide = $stmt->fetch();
    
    if ($slide) {
        if (file_exists($slide['image_path'])) {
            unlink($slide['image_path']); // Remove file from storage
        }
        
        $del_stmt = $pdo->prepare("DELETE FROM w_c_issues.hero_slides WHERE id = ?");
        $del_stmt->execute([$slide_id]);
        
        $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
        $audit->execute([$_SESSION['username'], "Deleted hero slider background photo ID: " . $slide_id]);
    }
    header("Location: index.php#home");
    exit();
}

// Form Handlers: Add New Hero Slider Background Photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_add_hero_slide']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "photos/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = time() . '_hero_' . basename($_FILES["hero_img"]["name"]);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["hero_img"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO w_c_issues.hero_slides (image_path) VALUES (?)");
            $stmt->execute([$target_file]);
            
            $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
            $audit->execute([$_SESSION['username'], "Added a new background photo to the Hero Slider: " . $file_name]);
            
            header("Location: index.php#home");
            exit();
        }
    }
}

// Form Handlers: Remove Hero Slider Background Photo
if (isset($_GET['delete_hero_slide_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $slide_id = $_GET['delete_hero_slide_id'];
    
    $stmt = $pdo->prepare("SELECT image_path FROM w_c_issues.hero_slides WHERE id = ?");
    $stmt->execute([$slide_id]);
    $slide = $stmt->fetch();
    
    if ($slide) {
        if (file_exists($slide['image_path'])) {
            unlink($slide['image_path']); // Remove file from storage
        }
        
        $del_stmt = $pdo->prepare("DELETE FROM w_c_issues.hero_slides WHERE id = ?");
        $del_stmt->execute([$slide_id]);
        
        $audit = $pdo->prepare("INSERT INTO audit_logs (username, action) VALUES (?, ?)");
        $audit->execute([$_SESSION['username'], "Deleted hero slider background photo ID: " . $slide_id]);
    }
    header("Location: index.php#home");
    exit();
}

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $translations[$lang]['bureau_title']; ?></title>
    <!-- Bootstrap and Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { --primary-color: #2c3e50; --accent-color: #3498db; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #e1e1e198; }
        .navbar-custom { background-color: var(--primary-color); transition: background-color 0.4s ease, box-shadow 0.4s ease; }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand { color: #fff !important; transition: color 0.3s ease, transform 0.3s ease; }
        .navbar-custom .nav-link:hover { transform: translateY(-2px); color: var(--accent-color) !important; }
        
        /* 🔵 HERO SLIDER STRUCTURE STYLES WITH ANIMATIONS */
        .slider { 
            position: relative; 
            width: 100%; 
            height: 550px; 
            overflow: hidden; 
            background-color: #000;
        }
        .slides {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 1.2s cubic-bezier(0.77, 0, 0.175, 1);
        }
        .slide {
            min-width: 100%;
            height: 100%;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55));
            background-blend-mode: overlay;
            flex-shrink: 0;
            transition: transform 6s ease-out;
        }
        .slider .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            z-index: 10;
            border-radius: 50px;
            transition: background 0.4s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }
        .slider .arrow:hover { 
            background: var(--accent-color); 
            transform: translateY(-50%) scale(1.15);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        }
        .slider .arrow.left { left: 20px; }
        .slider .arrow.right { right: 20px; }
        
        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 5;
            width: 80%;
            animation: heroTextFadeIn 1.5s ease-out forwards;
        }
        .hero-text h1 { 
            font-size: 3.2rem; 
            font-weight: 800; 
            letter-spacing: 1.5px; 
            text-shadow: 2px 2px 10px rgba(0,0,0,0.8);
            animation: slideInDown 1.2s ease-out forwards;
        }
        .hero-text p { 
            font-size: 1.4rem; 
            text-shadow: 1px 1px 5px rgba(0,0,0,0.8);
            animation: slideInUp 1.4s ease-out forwards;
        }
        .admin-badge-container { 
            margin-top: 15px; 
            animation: fadeIn 1.8s ease-out forwards;
        }

        /* CARD ANIMATIONS */
        .feature-card { 
            border: none; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
            margin-bottom: 30px; 
            border-radius: 12px;
            overflow: hidden;
        }
        .feature-card:hover { 
            transform: translateY(-8px) scale(1.01); 
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }
        .lang-btn { font-weight: bold; }

        /* The Moving Track Gallery Styles */
        .slider-wrapper {
            width: 100%;
            height: 450px;
            margin: 20px 0;
            overflow: hidden;
            position: relative;
            background: #05f9a4;
            display: flex;
            align-items: center;
            border-top: 2px solid #0d6efd;
            border-bottom: 2px solid #0d6efd;
        }
        .slide-track {
            display: flex;
            width: calc(500px * 32); 
            animation: scroll-infinite 40s linear infinite;
        }
        .slide-track:hover { animation-play-state: paused; }
        .slide-item { width: 500px; flex-shrink: 0; padding: 10px; display: flex; align-items: center; justify-content: center; }
        .slide-item img { 
            width: 100%; 
            height: 430px; 
            object-fit: cover; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.15); 
            filter: grayscale(100%); 
            transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1); 
        }
        .slide-item img:hover { filter: grayscale(0%); transform: scale(1.08); }

        @keyframes scroll-infinite {
            0% { transform: translateX(0); }
            100% { transform: translateX(-8000px); }
        }

        /* SMOOTH ANIMATIONS KEYFRAMES */
        @keyframes heroTextFadeIn {
            0% { opacity: 0; transform: translate(-50%, -45%); }
            100% { opacity: 1; transform: translate(-50%, -50%); }
        }
        @keyframes slideInDown {
            0% { opacity: 0; transform: translateY(-30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @media (max-width: 768px) {
            .slider { height: 400px; }
            .hero-text h1 { font-size: 2rem; }
            .hero-text p { font-size: 1rem; }
            .slider-wrapper { height: 450px; }
            .slide-item { width: 350px; }
            .slide-item img { height: 400px; }
            @keyframes scroll-infinite {
                0% { transform: translateX(0); }
                100% { transform: translateX(calc(-350px * 16)); }
            }
        }

        /* 20-line clamp for collapsed state */
        .expandable-content {
            transition: all 0.5s ease-in-out;
        }
        .expandable-content.collapsed {
            display: -webkit-box;
            -webkit-line-clamp: 20; /* Clamps content to exactly 20 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .expandable-content.expanded {
            display: block;
            overflow: visible;
        }

        /* Smooth Button & Form Animations */
        .btn { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }
    </style>
</head>
<body>

<!-- Dynamic Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top shadow-sm">
    <div class="container-fluid px-lg-4 px-3"> 
        <a class="navbar-brand d-flex align-items-center" href="#home">
            <!-- Updated Image with inline CSS sizing -->
            <img src="Photos/logoo.jpg" 
                 alt="Logo" 
                 class="navbar-logo mr-3 flex-shrink-0" 
                 style="height: 45px; width: 45px; object-fit: contain;">
                 
            <h5 class="m-0 font-weight-bold bureau-brand-title"><?php echo $translations[$lang]['bureau_title']; ?></h5>
        </a>
        
        <button class="navbar-toggler text-white border-0" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars fa-lg"></i>
        </button>

        <style>
            .navbar-logo {
    height: 80px !important;
    width: 80px !important;
    object-fit: contain;
    flex-shrink: 0;
}
            </style>
        
 <div class="collapse navbar-collapse" id="mainNav">
    <!-- Centered Navigation Links -->
    <ul class="navbar-nav mx-auto nav-links-wrapper">
        <li class="nav-item"><a class="nav-link custom-nav-link" href="#home"><?php echo $translations[$lang]['home']; ?></a></li>
        <li class="nav-item"><a class="nav-link custom-nav-link" href="#about"><?php echo $translations[$lang]['about']; ?></a></li>
        <li class="nav-item"><a class="nav-link custom-nav-link" href="#news"><?php echo $translations[$lang]['news']; ?></a></li>
        <li class="nav-item"><a class="nav-link custom-nav-link" href="#contact"><?php echo $translations[$lang]['contact']; ?></a></li>

        <!-- Language-Aware Message Button for Logged-In Admins -->
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <?php
                $msg_count_query = $pdo->query("SELECT COUNT(*) FROM contact_messages");
                $total_msgs = $msg_count_query ? $msg_count_query->fetchColumn() : 0;
            ?>
            <li class="nav-item">
                <a class="nav-link custom-nav-link text-info font-weight-bold d-flex align-items-center" 
                   href="#messages-section" 
                   onclick="toggleMessagesSection()">
                    
                    <span><?php echo $translations[$lang]['messages']; ?></span>
                    <?php if($total_msgs > 0): ?>
                        <span class="badge badge-pill badge-danger ml-1 font-weight-bold"><?php echo $total_msgs; ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link custom-nav-link text-warning font-weight-bold" href="#audit-section"><?php echo $translations[$lang]['audit']; ?></a>
            </li>
        <?php endif; ?>
    </ul>

    <!-- Dynamic Language Matrix & Auth Controls -->
    <div class="d-flex align-items-center nav-controls-wrapper">
        <div class="btn-group mr-3" role="group">
            <a href="?lang=en" class="btn btn-outline-light lang-btn <?php echo $lang=='en'?'active':''; ?>">EN</a>
            <a href="?lang=am" class="btn btn-outline-light lang-btn <?php echo $lang=='am'?'active':''; ?>">አማ</a>
        </div>

        <?php if(isset($_SESSION['username'])): ?>
            <a href="logout.php" class="btn btn-danger nav-action-btn"><?php echo $translations[$lang]['logout']; ?></a>
        <?php else: ?>
            <a href="login.php" class="btn btn-success nav-action-btn"><i class="fa-solid fa-right-to-bracket mr-1"></i><?php echo $translations[$lang]['login']; ?></a>
        <?php endif; ?>
    </div>
</div>
    
    
</div>
    </div>
</nav>

<!-- 🔵 HERO SLIDER WITH DYNAMIC BACKGROUND PHOTOS -->
<div id="home" class="slider position-relative">
    
    <div class="slides" id="heroSlides">
        <?php
        // Retrieve dynamic background photos from w_c_issues database
        $slides_stmt = $pdo->query("SELECT * FROM w_c_issues.hero_slides ORDER BY id DESC");
        $slides = $slides_stmt ? $slides_stmt->fetchAll() : [];

        // Fallback default slides if table is empty
        if (empty($slides)) {
            $default_photos = [
                'Photos/web1-1536x1024.jpg', 
                'photos/img-1.webp', 
                'Photos/img-3.avif', 
                'Photos/img-2.avif', 
                'Photos/img-4.avif'
            ];
            foreach ($default_photos as $index => $photo_path) {
                $active_class = ($index === 0) ? 'active' : '';
                echo '<div class="slide ' . $active_class . '" style="background-image: url(\'' . htmlspecialchars($photo_path) . '\');"></div>';
            }
        } else {
            foreach ($slides as $index => $slide) {
                $active_class = ($index === 0) ? 'active' : '';
                echo '<div class="slide ' . $active_class . '" style="background-image: url(\'' . htmlspecialchars($slide['image_path']) . '\');"></div>';
            }
        }
        ?>
    </div>
    
    <button class="arrow left" onclick="prevSlide()">
        <i class="fa fa-chevron-left"></i>
    </button>
    
    <button class="arrow right" onclick="nextSlide()">
        <i class="fa fa-chevron-right"></i>
    </button>
    
    <!-- TEXT ON HERO IMAGE -->
    <div class="hero-text" id="heroTextContainer">
       <h1 class="display-4 font-weight-bold"><?php echo $translations[$lang]['bureau_title']; ?></h1>
        <p class="lead">Providing reliable structural data tracking and legal management access metrics.</p>
        
        <div class="admin-badge-container">
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <div class="alert alert-warning d-inline-block py-1 px-3 m-0 shadow-sm"><?php echo $translations[$lang]['admin_panel']; ?> Activated</div>
                
                <!-- Admin Slider Management Trigger -->
                <div class="mt-3">
                    <button class="btn btn-sm btn-light shadow-sm text-dark font-weight-bold" data-toggle="collapse" data-target="#heroManagementPanel">
                        <i class="fa-solid fa-images mr-1"></i> Manage Hero Backgrounds
                    </button>
                </div>
            <?php else: ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- 🛠️ ADMIN HERO BACKGROUND MANAGEMENT CONTROL PANEL -->
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div class="collapse container my-3" id="heroManagementPanel">
        <div class="card card-body bg-light shadow-sm">
            <h5 class="font-weight-bold mb-3"><i class="fa-solid fa-sliders text-primary mr-2"></i>Hero Background Photo Management</h5>
            
            <!-- Upload New Photo Form -->
            <form action="index.php" method="POST" enctype="multipart/form-data" class="form-inline mb-4">
                <div class="form-group mr-2">
                    <label for="hero_img" class="sr-only">Upload Photo</label>
                    <input type="file" name="hero_img" id="hero_img" class="form-control-file border p-1 bg-white rounded" required>
                </div>
                <button type="submit" name="action_add_hero_slide" class="btn btn-success">
                    <i class="fa-solid fa-upload mr-1"></i> Add Background Photo
                </button>
            </form>

            <!-- Delete Existing Photos Grid -->
            <h6 class="font-weight-bold text-secondary mb-2">Active Database Photos:</h6>
            <div class="d-flex flex-wrap gap-2">
                <?php if (!empty($slides)): ?>
                    <?php foreach ($slides as $slide): ?>
                        <div class="position-relative m-1 border rounded p-1 bg-white shadow-sm" style="width: 110px;">
                            <img src="<?php echo htmlspecialchars($slide['image_path']); ?>" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                            <a href="?delete_hero_slide_id=<?php echo $slide['id']; ?>" 
                               class="btn btn-danger btn-sm btn-block mt-1 py-0 px-1 font-weight-bold"
                               onclick="return confirm('Are you sure you want to remove this background photo?');"
                               title="Delete Photo">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted small font-italic mb-0">Using static fallback images. Upload a photo above to switch to dynamic slides.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
// Handle POST actions for News Management
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Helper function for safe redirection whether headers are sent or not
    function safe_redirect($url) {
        if (!headers_sent()) {
            header("Location: " . $url);
        } else {
            echo "<script>window.location.href='" . $url . "';</script>";
            echo "<noscript><meta http-equiv='refresh' content='0;url=" . $url . "' /></noscript>";
        }
        exit();
    }

    // 1. Insert New News Item
    if (isset($_POST['action_create_news']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        $title_en = trim($_POST['news_title_en'] ?? '');
        $title_am = trim($_POST['news_title_am'] ?? '');
        $body_en  = trim($_POST['news_body_en'] ?? '');
        $body_am  = trim($_POST['news_body_am'] ?? '');

        if (!empty($title_en) && !empty($title_am) && !empty($body_en) && !empty($body_am)) {
            $insert_stmt = $pdo->prepare("INSERT INTO news (title_en, title_am, body_en, body_am) VALUES (:t_en, :t_am, :b_en, :b_am)");
            $insert_stmt->execute([
                ':t_en' => $title_en,
                ':t_am' => $title_am,
                ':b_en' => $body_en,
                ':b_am' => $body_am
            ]);
            safe_redirect($_SERVER['REQUEST_URI']);
        }
    }

    // 2. Delete News Item
    if (isset($_POST['action_delete_news']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        $news_id = intval($_POST['news_id'] ?? 0);
        if ($news_id > 0) {
            $delete_stmt = $pdo->prepare("DELETE FROM news WHERE id = :id");
            $delete_stmt->execute([':id' => $news_id]);
            safe_redirect($_SERVER['REQUEST_URI']);
        }
    }
}
?>

<main class="container my-5">
    <!-- About Section: Vision, Mission, Values, Goals, Services, Resources, History -->
    <section id="about" class="mb-5">
        <h2 class="text-left mb-4 border-bottom pb-2 font-weight-bold text-dark"><?php echo $translations[$lang]['about']; ?></h2>
        <div class="row">
            <?php foreach(['vision', 'mission', 'values', 'goals', 'services', 'resources', 'history'] as $key): ?>
                <div class="col-md-6 col-lg-6 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body d-flex flex-column text-left">
                            <h4 class="card-title text-capitalize text-primary font-weight-bold text-left"><?php echo $translations[$lang][$key]; ?></h4>
                            <div class="card-text expandable-content collapsed text-left w-100" id="text-<?php echo $key; ?>" style="text-align: left !important; padding-left: 0; margin-left: 0;">
                                <?php 
                                    $raw_content = $site_data[$key][$lang] ?? '';
                                    echo render_as_sentence_list($raw_content, $lang); 
                                ?>
                            </div>
                            <div class="mt-auto pt-2 text-left">
                                <button style="background-color:yellow; font-weight: bold; border-radius: 4px; padding: 2px 8px;" class="btn btn-sm btn-link text-dark p-0 read-more-btn d-none" onclick="toggleReadMore('text-<?php echo $key; ?>', this)">
                                    <?php echo $translations[$lang]['read_more']; ?>
                                </button>
                            </div>

                            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <button class="btn btn-sm btn-outline-secondary mt-2 text-left" onclick="enableContentEdit('<?php echo $key; ?>')">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i><?php echo $translations[$lang]['edit']; ?>
                                </button>
                                
                                <form method="POST" class="mt-2 d-none text-left" id="form-<?php echo $key; ?>">
                                    <input type="hidden" name="content_key" value="<?php echo $key; ?>">
                                    
                                    <div class="form-group mb-2 text-left">
                                        <small class="text-muted font-weight-bold d-block text-left">English Version Content</small>
                                        <textarea name="updated_text_en" class="form-control form-control-sm text-left" rows="3" required><?php echo htmlspecialchars($site_data[$key]['en'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group mb-2 text-left">
                                        <small class="text-muted font-weight-bold d-block text-left">የአማርኛ ትርጉም (Amharic)</small>
                                        <textarea name="updated_text_am" class="form-control form-control-sm text-left" rows="3" required><?php echo htmlspecialchars($site_data[$key]['am'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <button type="submit" name="action_update" value="1" class="btn btn-sm btn-success"><?php echo $translations[$lang]['save']; ?></button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- News & Operational Updates Display & Upload Section -->
    <section id="news" class="my-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h2 class="font-weight-bold text-dark text-left m-0"><?php echo $translations[$lang]['news']; ?></h2>
            
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <button class="btn btn-primary btn-sm font-weight-bold" type="button" data-toggle="collapse" data-target="#uploadNewsForm" aria-expanded="false" aria-controls="uploadNewsForm">
                    <i class="fa-solid fa-plus mr-1"></i>Post New Announcement
                </button>
            <?php endif; ?>
        </div>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <!-- Admin Upload/Create News Form Panel -->
            <div class="collapse mb-4" id="uploadNewsForm">
                <div class="card card-body bg-light border text-left">
                    <h5 class="font-weight-bold text-dark mb-3">Publish New Announcement / News</h5>
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Title (English)</label>
                                <input type="text" name="news_title_en" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">ርዕስ (አማርኛ)</label>
                                <input type="text" name="news_title_am" class="form-control form-control-sm" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">Body Content (English)</label>
                                <textarea name="news_body_en" class="form-control form-control-sm" rows="3" required></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold">ዝርዝር መረጃ (አማርኛ)</label>
                                <textarea name="news_body_am" class="form-control form-control-sm" rows="3" required></textarea>
                            </div>
                        </div>
                        <button type="submit" name="action_create_news" value="1" class="btn btn-sm btn-success font-weight-bold">
                            <i class="fa-solid fa-paper-plane mr-1"></i>Publish Announcement
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Display Uploaded News Feed for Both Admins and Viewers -->
        <div class="row text-left">
            <?php
            try {
                $news_stmt = $pdo->query("SELECT id, title_en, title_am, body_en, body_am, created_at FROM news ORDER BY created_at DESC");
                $news_items = $news_stmt ? $news_stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            } catch (PDOException $e) {
                $news_items = [];
            }

            if (!empty($news_items)):
                foreach ($news_items as $item):
                    $title = ($lang === 'am' && !empty($item['title_am'])) ? $item['title_am'] : $item['title_en'];
                    $body  = ($lang === 'am' && !empty($item['body_am']))  ? $item['body_am']  : $item['body_en'];
            ?>
                <div class="col-md-6 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body d-flex flex-column text-left">
                            <h5 class="card-title text-primary font-weight-bold text-left"><?php echo htmlspecialchars($title); ?></h5>
                            <p class="card-text text-secondary font-weight-bold
                            text-left mb-3"><?php echo nl2br(htmlspecialchars($body)); ?></p>
                            
                            <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fa-regular fa-clock mr-1"></i><?php echo date('M d, Y - H:i', strtotime($item['created_at'])); ?>
                                </small>
                                
                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                        <input type="hidden" name="news_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" name="action_delete_news" value="1" class="btn btn-sm btn-outline-danger py-0 px-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach;
            else: 
            ?>
                <div class="col-12 text-left text-muted py-4 font-italic">No news items posted yet.</div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
// ==========================================
// 1. GALLERY BACKEND MANAGEMENT (ADMIN ONLY)
// ==========================================
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    
    // Handle Gallery Image Upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_upload_gallery'])) {
        if (isset($_FILES['gallery_img']) && $_FILES['gallery_img']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'Photos/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['gallery_img']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = 'gallery_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
                $target_filepath = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['gallery_img']['tmp_name'], $target_filepath)) {
                    $insert_stmt = $pdo->prepare("INSERT INTO gallery_images (image_path, created_at) VALUES (?, NOW())");
                    $insert_stmt->execute([$target_filepath]);
                    echo "<script>alert('Gallery image uploaded successfully!'); window.location.href=window.location.pathname;</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Invalid file format. Please upload JPG, PNG, WEBP, or AVIF images.');</script>";
            }
        }
    }

    // Handle Gallery Image Deletion
    if (isset($_GET['delete_gallery_id'])) {
        $del_id = (int)$_GET['delete_gallery_id'];
        $fetch_stmt = $pdo->prepare("SELECT image_path FROM gallery_images WHERE id = ?");
        $fetch_stmt->execute([$del_id]);
        $img_data = $fetch_stmt->fetch();
        
        if ($img_data) {
            if (file_exists($img_data['image_path'])) {
                unlink($img_data['image_path']); // Delete physical file from Photos/ folder
            }
            $delete_stmt = $pdo->prepare("DELETE FROM gallery_images WHERE id = ?");
            $delete_stmt->execute([$del_id]);
            echo "<script>alert('Gallery image deleted successfully!'); window.location.href=window.location.pathname;</script>";
            exit;
        }
    }
}
?>
<?php
// ==========================================
// 1. HEROES BACKEND MANAGEMENT (ADMIN ONLY)
// ==========================================
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    
    // Handle Hero Upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_upload_hero'])) {
        if (isset($_FILES['hero_img']) && $_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'Photos/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['hero_img']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif'];
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_filename = 'hero_' . time() . '_' . rand(1000, 9999) . '.' . $file_extension;
                $target_filepath = $upload_dir . $new_filename;
                
                $name = trim($_POST['hero_name'] ?? '');
                $profession = trim($_POST['hero_profession'] ?? '');

                if (move_uploaded_file($_FILES['hero_img']['tmp_name'], $target_filepath)) {
                    $insert_stmt = $pdo->prepare("INSERT INTO heroes (name, profession, image_path, created_at) VALUES (?, ?, ?, NOW())");
                    $insert_stmt->execute([$name, $profession, $target_filepath]);
                    echo "<script>alert('Hero added successfully!'); window.location.href=window.location.pathname;</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Invalid file format. Please upload an image file.');</script>";
            }
        }
    }

    // Handle Hero Deletion
    if (isset($_GET['delete_hero_id'])) {
        $del_id = (int)$_GET['delete_hero_id'];
        $fetch_stmt = $pdo->prepare("SELECT image_path FROM heroes WHERE id = ?");
        $fetch_stmt->execute([$del_id]);
        $hero_data = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($hero_data) {
            if (file_exists($hero_data['image_path'])) {
                unlink($hero_data['image_path']);
            }
            $delete_stmt = $pdo->prepare("DELETE FROM heroes WHERE id = ?");
            $delete_stmt->execute([$del_id]);
            echo "<script>alert('Hero record removed successfully!'); window.location.href=window.location.pathname;</script>";
            exit;
        }
    }
}
?>

<!-- ========================================== -->
<!-- 2. HEROES SECTION COMPONENT                -->
<!-- ========================================== -->
<section class="heroes-section my-5">
    <style>
        .heroes-section {
            padding: 20px 15px;
            background: transparent;
            box-sizing: border-box;
        }

        .section-title {
            text-align: center;
            font-weight: 800;
            letter-spacing: 1px;
            color: #1a252f;
            margin-bottom: 5px;
        }

        .title-underline {
            width: 80px;
            height: 4px;
            background-color: #007bff;
            margin: 0 auto 30px auto;
            border-radius: 2px;
        }

        .carousel-wrap {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .viewport {
            overflow: hidden;
            width: 100%;
            padding: 10px 0;
        }

        .track {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 100%;
        }

        /* 
         * Exact 3-column grid layout per slide:
         * Uses calc() to guarantee all 3 columns fit within 100% width cleanly.
        */
        .page {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
            width: 100%;
            min-width: 100%;
            flex-shrink: 0;
            box-sizing: border-box;
        }

        /* Responsive adjustments for mobile/tablet screens */
        @media (max-width: 768px) {
            .page {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 480px) {
            .page {
                grid-template-columns: 1fr;
            }
        }

        /* Card Hover and Animation Styles */
        .card {
            position: relative;
            background: #ffffff;
            border-radius: 12px;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .card img {
            width: 100%;
            height: 240px; /* Reduced height for smaller, proportional cards */
            object-fit: cover;
            object-position: center;
            transition: transform 0.5s ease;
        }

        .card:hover img {
            transform: scale(1.05);
        }

        .card .name {
            padding: 10px 8px;
            font-weight: 700;
            color: #2c3e50;
            width: 100%;
            font-size: 0.95rem;
            box-sizing: border-box;
        }

        .card .name p {
            font-size: 0.82rem;
            color: #6c757d;
            margin: 4px 0 0 0;
            font-weight: 400;
        }

        .card hr {
            margin: 6px 0;
            border-top: 1px solid #e9ecef;
        }

        .dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ccc;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .dot.active {
            background: #007bff;
            width: 24px;
            border-radius: 5px;
        }
    </style>

    <h2 class="section-title">ORGANIZATIONAL GALLERY</h2>
    <div class="title-underline"></div>

    <!-- Admin Upload Deck -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="container mb-4 p-3 bg-white shadow-sm rounded border">
            <h5 class="text-primary font-weight-bold text-center mb-3">Add New Hero</h5>
            <form method="POST" enctype="multipart/form-data" class="form-row align-items-center justify-content-center">
                <div class="col-md-3 mb-2">
                    <input type="text" name="hero_name" class="form-control" placeholder="Hero Name" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="text" name="hero_profession" class="form-control" placeholder="Profession / Role" required>
                </div>
                <div class="col-md-4 mb-2">
                    <input type="file" name="hero_img" class="form-control-file border p-1 rounded" accept="image/*" required>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" name="action_upload_hero" class="btn btn-primary w-100">Upload</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <div class="carousel-wrap">
        <div class="viewport">
            <div class="track" id="track">
                <?php
                // Fetch heroes from database
                $hero_stmt = $pdo->query("SELECT * FROM heroes ORDER BY id DESC");
                $heroes = $hero_stmt ? $hero_stmt->fetchAll(PDO::FETCH_ASSOC) : [];
                
                // Grouping by 6 items per slide page (creates exactly 2 rows with 3 images each)
                $chunks = array_chunk($heroes, 6);
                ?>

                <?php if (!empty($chunks)): ?>
                    <?php foreach ($chunks as $pageIndex => $pageHeroes): ?>
                        <div class="page">
                            <?php foreach ($pageHeroes as $hero): ?>
                                <div class="card">
                                    <img src="<?php echo htmlspecialchars($hero['image_path']); ?>" alt="<?php echo htmlspecialchars($hero['name']); ?>">
                                    <div class="name">
                                        <?php echo htmlspecialchars($hero['name']); ?>
                                        <hr>
                                        <p><?php echo htmlspecialchars($hero['profession']); ?></p>
                                        <hr>
                                    </div>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <a href="?delete_hero_id=<?php echo $hero['id']; ?>" 
                                           class="btn btn-danger btn-sm position-absolute" 
                                           style="top: 8px; right: 8px; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;"
                                           onclick="return confirm('Remove this hero?')">&times;</a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center w-100 py-4 text-muted">No heroes listed yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="dots" id="dots">
        <?php for ($i = 0; $i < count($chunks); $i++): ?>
            <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" data-page="<?php echo $i; ?>"></span>
        <?php endfor; ?>
    </div>
</section>

<!-- Smooth Carousel Navigation Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('track');
    const prevBtn = document.getElementById('prevBtnHeroes');
    const nextBtn = document.getElementById('nextBtnHeroes');
    const dots = document.querySelectorAll('.dot');
    
    let currentPage = 0;
    const totalPages = dots.length;

    function updateCarousel(page) {
        if (totalPages === 0) return;
        currentPage = page;
        track.style.transform = `translateX(-${currentPage * 100}%)`;
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentPage);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const next = (currentPage + 1) % totalPages;
            updateCarousel(next);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            const prev = (currentPage - 1 + totalPages) % totalPages;
            updateCarousel(prev);
        });
    }

    dots.forEach(dot => {
        dot.addEventListener('click', (e) => {
            const page = parseInt(e.target.dataset.page);
            updateCarousel(page);
        });
    });
});
</script>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <!-- Sent Messages Display Block -->
    <section id="messages-section" class="my-5 p-4 bg-white rounded shadow-sm feature-card">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4">
            <h4 class="font-weight-bold text-dark mb-0">
                <i class="fa-solid fa-inbox mr-2 text-primary"></i><?php echo $translations[$lang]['messages']; ?>
            </h4>
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleMessagesSection()">Hide / Show</button>
        </div>

        <div id="messages-content-wrapper" class="table-responsive">
            <table class="table table-hover table-striped border align-middle">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 25%;">Sender Info</th>
                        <th style="width: 45%;">Message Content</th>
                        <th style="width: 15%;">Date Sent</th>
                        <th style="width: 10%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $msg_stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
                    $all_messages = $msg_stmt ? $msg_stmt->fetchAll() : [];

                    if (!empty($all_messages)):
                        foreach ($all_messages as $msg):
                    ?>
                        <tr>
                            <td class="font-weight-bold text-muted"><?php echo htmlspecialchars($msg['id']); ?></td>
                            <td>
                                <strong class="d-block text-dark"><?php echo htmlspecialchars($msg['name']); ?></strong>
                                <small class="text-muted"><a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>"><?php echo htmlspecialchars($msg['email']); ?></a></small>
                            </td>
                            <td>
                                <div class="p-2 bg-light rounded text-dark" style="max-height: 100px; overflow-y: auto;">
                                    <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                </div>
                            </td>
                            <td><small class="text-muted"><i class="fa-regular fa-clock mr-1"></i><?php echo htmlspecialchars($msg['created_at']); ?></small></td>
                            <td class="text-center align-middle">
                                <a href="?delete_msg_id=<?php echo $msg['id']; ?>" 
                                   class="btn btn-outline-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this message?');"
                                   title="Delete Message">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else: 
                    ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4 font-italic">No messages found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>

   <!-- Contact Us Section Component -->
<section id="contact" class="my-5 bg-white p-4 rounded shadow-sm feature-card mx-auto" style="max-width: 850px;">
    <h2 class="font-weight-bold text-dark border-bottom pb-2 mb-4"><?php echo $translations[$lang]['contact']; ?></h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                <div class="alert alert-success">Thank you! Your message has been stored in our system.</div>
            <?php endif; ?>
            
            <form method="POST" action="index.php">
                <input type="hidden" name="action_contact" value="1">
                
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Message</label>
                    <textarea name="message" class="form-control" rows="4" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary px-4 font-weight-bold">Send Message</button>
            </form>
        </div>

        <div class="col-md-6 mt-4 mt-md-0">
            <h5 class="font-weight-bold text-primary">Our Office HQ</h5>
            
            <!-- Dynamic Display Mode -->
            <div id="hq-display-view">
                <p class="mb-2"><i class="fa-solid fa-location-dot mr-2 text-danger"></i> <?php echo htmlspecialchars($hq_address); ?></p>
                <p class="mb-2"><i class="fa-solid fa-phone mr-2 text-success"></i> <?php echo htmlspecialchars($hq_phone); ?></p>
                <p class="mb-2"><i class="fa-solid fa-envelope mr-2 text-info"></i> <?php echo htmlspecialchars($hq_email); ?></p>
                
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <button class="btn btn-sm btn-outline-secondary mt-2" onclick="enableHqEdit()">
                        <i class="fa-solid fa-pen-to-square mr-1"></i><?php echo $translations[$lang]['edit']; ?>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Admin Update Form (Hidden by default) -->
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <form method="POST" action="index.php" id="hq-edit-form" class="d-none mt-2">
                    <input type="hidden" name="action_update_hq" value="1">
                    
                    <div class="form-group mb-2">
                        <small class="text-muted font-weight-bold">Address / Location</small>
                        <input type="text" name="hq_address" class="form-control form-control-sm" value="<?php echo htmlspecialchars($hq_address); ?>" required>
                    </div>
                    
                    <div class="form-group mb-2">
                        <small class="text-muted font-weight-bold">Phone Number</small>
                        <input type="text" name="hq_phone" class="form-control form-control-sm" value="<?php echo htmlspecialchars($hq_phone); ?>" required>
                    </div>

                    <div class="form-group mb-2">
                        <small class="text-muted font-weight-bold">Email Address</small>
                        <input type="email" name="hq_email" class="form-control form-control-sm" value="<?php echo htmlspecialchars($hq_email); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-sm btn-success mt-1"><?php echo $translations[$lang]['save']; ?></button>
                    <button type="button" class="btn btn-sm btn-secondary mt-1" onclick="disableHqEdit()">Cancel</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

   <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <section id="audit-section" class="my-5 p-4 bg-dark text-light rounded shadow-lg">
        <h3><i class="fa-solid fa-clock-rotate-left mr-2 text-warning"></i>System Audit Logging Engine</h3>
        <div class="table-responsive mt-3">
            <table class="table table-striped table-dark table-sm align-middle">
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>User</th>
                        <th>Action Performed</th>
                        <th>Timestamp</th>
                        <th class="text-center">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $audits = $pdo->query("SELECT * FROM audit_logs ORDER BY timestamp DESC LIMIT 10");
                    while($log = $audits->fetch()):
                    ?>
                        <tr>
                            <td class="text-muted"><?php echo htmlspecialchars($log['id']); ?></td>
                            <td><strong><?php echo htmlspecialchars($log['username']); ?></strong></td>
                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                            <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
                            <td class="text-center">
                                <a href="?delete_audit_id=<?php echo $log['id']; ?>" 
                                   class="btn btn-outline-danger btn-sm py-0 px-2" 
                                   title="Delete this entry"
                                   onclick="return confirm('Security Warning: Are you sure you want to delete this log entry? This operation will be audited.')">
                                    <i class="fa-solid fa-trash-can font-size-sm"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php endif; ?>

</main>

<!-- Modal Component To Add News Entries Dynamically -->
<div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="POST" class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Post New Operational Update</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <h6 class="font-weight-bold text-primary">English Standard Content</h6>
                        <div class="form-group"><input type="text" name="title_en" class="form-control mb-2" placeholder="Title" required></div>
                        <div class="form-group"><textarea name="body_en" class="form-control" rows="5" placeholder="Body Text Content..." required></textarea></div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-primary">የአማርኛ ይዘት መግለጫ (Amharic)</h6>
                        <div class="form-group"><input type="text" name="title_am" class="form-control mb-2" placeholder="ርዕስ" required></div>
                        <div class="form-group"><textarea name="body_am" class="form-control" rows="5" placeholder="ዝርዝር መግለጫ..." required></textarea></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="action_news" value="1" class="btn btn-primary font-weight-bold">Publish Post</button>
            </div>
        </form>
    </div>
</div>



<footer style="text-align: center; color: white; padding: 30px;" class="navbar navbar-custom mt-5">
    
    
<!-- Enhanced Professional & Interactive Footer -->
<footer style="padding-left:30px;" class="footer-custom mt-5">
    <div class="container py-5">
        <div class="row text-white">
            
            <!-- Column 1: Organization Overview -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <img src="Photos/logoo.jpg" alt="Logo" style="height: 55px; width: auto; border-radius: 4px;" class="mr-2">
                    <h6 class="font-weight-bold m-0 text-uppercase text-light" style="letter-spacing: 0.5px; line-height: 1.3;">
                        <?php echo $translations[$lang]['bureau_title']; ?>
                    </h6>
                </div>
                <p class="small text-white-50" style="line-height: 1.6;">
                    Dedicated to promoting gender equality, safeguarding children's welfare, and empowering women and communities through sustainable social programs across the region.
                </p>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h6 class="font-weight-bold text-uppercase mb-3 text-warning border-bottom pb-2" style="border-color: rgba(255,255,255,0.1) !important;">
                    Quick Links
                </h6>
                <ul class="list-unstyled footer-links small">
                    <li class="mb-2"><a href="#home"><i class="fa-solid fa-angle-right mr-1 text-primary"></i><?php echo $translations[$lang]['home']; ?></a></li>
                    <li class="mb-2"><a href="#about"><i class="fa-solid fa-angle-right mr-1 text-primary"></i><?php echo $translations[$lang]['about']; ?></a></li>
                    <li class="mb-2"><a href="#news"><i class="fa-solid fa-angle-right mr-1 text-primary"></i><?php echo $translations[$lang]['news']; ?></a></li>
                    <li class="mb-2"><a href="#contact"><i class="fa-solid fa-angle-right mr-1 text-primary"></i><?php echo $translations[$lang]['contact']; ?></a></li>
                </ul>
            </div>

            <!-- Column 3: Contact Info -->
           <div class="col-md-3 mt-4 mt-md-0">
    <h5 class="font-weight-bold text-primary">Our Office HQ</h5>
    
    <!-- Dynamic Display Mode -->
    <div id="hq-display-view">
        <p class="mb-2"><i class="fa-solid fa-location-dot mr-2 text-danger"></i> <?php echo htmlspecialchars($hq_address); ?></p>
        <p class="mb-2"><i class="fa-solid fa-phone mr-2 text-success"></i> <?php echo htmlspecialchars($hq_phone); ?></p>
        <p class="mb-2"><i class="fa-solid fa-envelope mr-2 text-info"></i> <?php echo htmlspecialchars($hq_email); ?></p>
        
     
    </div>

    <!-- Admin Update Form (Hidden by default) -->
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <form method="POST" action="index.php" id="hq-edit-form" class="d-none mt-1">
            <input type="hidden" name="action_update_hq" value="1">
            
            <div class="form-group mb-2">
                <small class="text-muted font-weight-bold">Address / Location</small>
                <input type="text" name="hq_address" class="form-control form-control-sm" value="<?php echo htmlspecialchars($hq_address); ?>" required>
            </div>
            
            <div class="form-group mb-2">
                <small class="text-muted font-weight-bold">Phone Number</small>
                <input type="text" name="hq_phone" class="form-control form-control-sm" value="<?php echo htmlspecialchars($hq_phone); ?>" required>
            </div>

            <div class="form-group mb-2">
                <small class="text-muted font-weight-bold">Email Address</small>
                <input type="email" name="hq_email" class="form-control form-control-sm" value="<?php echo htmlspecialchars($hq_email); ?>" required>
            </div>

            <button type="submit" class="btn btn-sm btn-success mt-1"><?php echo $translations[$lang]['save']; ?></button>
            <button type="button" class="btn btn-sm btn-secondary mt-1" onclick="disableHqEdit()">Cancel</button>
        </form>
    <?php endif; ?>
</div>

          <!-- Column 4: Social Media Networks -->
<div class="col-lg-3 col-md-6" id="footer-social-block">
    <h6 class="font-weight-bold text-uppercase mb-3 text-warning border-bottom pb-2" style="border-color: rgba(255,255,255,0.1) !important;">
        Connect With Us
    </h6>
    <p class="small text-white-50 mb-3">Follow our social channels to get real-time operational updates.</p>
    
    <!-- Dynamic Social Links Grid -->
    <div id="social-display-view">
        <div class="social-links-grid d-flex flex-wrap" style="gap: 15px;">
            <a href="<?php echo htmlspecialchars($social_links['facebook']); ?>" target="_blank" rel="noopener" class="social-icon facebook" title="Facebook">
                <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="<?php echo htmlspecialchars($social_links['twitter']); ?>" target="_blank" rel="noopener" class="social-icon twitter" title="Twitter / X">
                <i class="fa-brands fa-x-twitter"></i>
            </a>
            <a href="<?php echo htmlspecialchars($social_links['youtube']); ?>" target="_blank" rel="noopener" class="social-icon youtube" title="YouTube">
                <i class="fa-brands fa-youtube"></i>
            </a>
            <a href="<?php echo htmlspecialchars($social_links['telegram']); ?>" target="_blank" rel="noopener" class="social-icon telegram" title="Telegram">
                <i class="fa-brands fa-telegram"></i>
            </a>
            <a href="<?php echo htmlspecialchars($social_links['whatsapp']); ?>" target="_blank" rel="noopener" class="social-icon whatsapp text-success" title="WhatsApp">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
            <a href="<?php echo htmlspecialchars($social_links['instagram']); ?>" target="_blank" rel="noopener" class="social-icon instagram" title="Instagram">
                <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="<?php echo htmlspecialchars($social_links['linkedin']); ?>" target="_blank" rel="noopener" class="social-icon linkedin" title="LinkedIn">
                <i class="fa-brands fa-linkedin-in"></i>
            </a>
        </div>

        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <button class="btn btn-sm btn-outline-warning mt-3" onclick="enableSocialEdit()">
                <i class="fa-solid fa-pen-to-square mr-1"></i>Edit Links
            </button>
        <?php endif; ?>
    </div>

    <!-- Admin Update Modal / Form -->
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <form method="POST" action="index.php" id="social-edit-form" class="d-none mt-2 p-3 bg-dark rounded border border-secondary text-left">
            <input type="hidden" name="action_update_socials" value="1">
            <h6 class="text-warning mb-2 font-weight-bold">Update Social URLs</h6>

            <div class="form-group mb-1">
                <small class="text-light">Facebook</small>
                <input type="url" name="facebook" class="form-control form-control-sm" value="<?php echo htmlspecialchars($social_links['facebook']); ?>" required>
            </div>
            <div class="form-group mb-1">
                <small class="text-light">Twitter / X</small>
                <input type="url" name="twitter" class="form-control form-control-sm" value="<?php echo htmlspecialchars($social_links['twitter']); ?>" required>
            </div>
            <div class="form-group mb-1">
                <small class="text-light">YouTube</small>
                <input type="url" name="youtube" class="form-control form-control-sm" value="<?php echo htmlspecialchars($social_links['youtube']); ?>" required>
            </div>
            <div class="form-group mb-1">
                <small class="text-light">Telegram</small>
                <input type="url" name="telegram" class="form-control form-control-sm" value="<?php echo htmlspecialchars($social_links['telegram']); ?>" required>
            </div>
            <div class="form-group mb-1">
                <small class="text-light">WhatsApp</small>
                <input type="url" name="whatsapp" class="form-control form-control-sm" value="<?php echo htmlspecialchars($social_links['whatsapp']); ?>" required>
            </div>
            <div class="form-group mb-1">
                <small class="text-light">Instagram</small>
                <input type="url" name="instagram" class="form-control form-control-sm" value="<?php echo htmlspecialchars($social_links['instagram']); ?>" required>
            </div>
            <div class="form-group mb-2">
                <small class="text-light">LinkedIn</small>
                <input type="url" name="linkedin" class="form-control form-control-sm" value="<?php echo htmlspecialchars($social_links['linkedin']); ?>" required>
            </div>

            <button type="submit" class="btn btn-sm btn-success font-weight-bold"><?php echo $translations[$lang]['save']; ?></button>
            <button type="button" class="btn btn-sm btn-secondary" onclick="disableSocialEdit()">Cancel</button>
        </form>
    <?php endif; ?>
</div>
        </div>

<br><br><br><br>

        <p class="m-0 w-100"><?php echo $translations[$lang]['bureau_title']; ?> - &copy; 2026. All Rights Reserved</p>
    </div>

    <!-- Copyright Sub-Footer -->
   
</footer>

<!-- Scripts Integration Matrix -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // 1-MINUTE HERO SLIDER AUTOMATION ENGINE WITH ANIMATIONS
    let currentSlideIndex = 0;
    const slidesContainer = document.getElementById('heroSlides');
    const totalSlides = slidesContainer.querySelectorAll('.slide').length;
    let autoSlideInterval;

    function triggerTextAnimation() {
        const textContainer = document.getElementById('heroTextContainer');
        if(textContainer) {
            textContainer.style.animation = 'none';
            textContainer.offsetHeight; // Trigger reflow
            textContainer.style.animation = 'heroTextFadeIn 1.2s ease-out forwards';
        }
    }

    function updateSlidePosition() {
        slidesContainer.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
        triggerTextAnimation();
    }

    function nextSlide() {
        currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
        updateSlidePosition();
        resetTimer();
    }

    function prevSlide() {
        currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
        updateSlidePosition();
        resetTimer();
    }

    // Auto-advance slides every 60,000ms (1 minute)
    function startTimer() {
        autoSlideInterval = setInterval(() => {
            currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
            updateSlidePosition();
        }, 60000); // 1 minute interval
    }

    // Reset timer when user manually interacts with controls
    function resetTimer() {
        clearInterval(autoSlideInterval);
        startTimer();
    }

    function enableContentEdit(key) {
        document.getElementById('text-' + key).classList.add('d-none');
        document.getElementById('form-' + key).classList.remove('d-none');
    }
    
    function handleContactSubmit(e) {
        e.preventDefault();
        alert('Thank you for contacting us! Your inquiry has been saved.');
        e.target.reset();
    }

    // Dynamic Read More Calculation Engine
    function toggleReadMore(targetId, btn) {
        const content = document.getElementById(targetId);
        
        if (content.classList.contains('collapsed')) {
            content.classList.remove('collapsed');
            content.classList.add('expanded');
            btn.textContent = '<?php echo ($lang == "am") ? "ያነሱ" : "Read Less"; ?>';
        } else {
            content.classList.remove('expanded');
            content.classList.add('collapsed');
            btn.textContent = '<?php echo ($lang == "am") ? "ተጨማሪ ያንብቡ" : "Read More"; ?>';
        }
    }

    // Auto-detects 20-line height overflow and displays button
    document.addEventListener("DOMContentLoaded", function() {
        startTimer();

        const items = document.querySelectorAll('.expandable-content');
        items.forEach(item => {
            if (item.scrollHeight > item.clientHeight) {
                const btn = item.parentElement.querySelector('.read-more-btn');
                if (btn) {
                    btn.classList.remove('d-none');
                }
            } else {
                item.classList.remove('collapsed');
            }
        });
    });

    function enableHqEdit() {
    document.getElementById('hq-display-view').classList.add('d-none');
    document.getElementById('hq-edit-form').classList.remove('d-none');
}

function disableHqEdit() {
    document.getElementById('hq-display-view').classList.remove('d-none');
    document.getElementById('hq-edit-form').classList.add('d-none');
}

function enableSocialEdit() {
    document.getElementById('social-display-view').classList.add('d-none');
    document.getElementById('social-edit-form').classList.remove('d-none');
}

function disableSocialEdit() {
    document.getElementById('social-display-view').classList.remove('d-none');
    document.getElementById('social-edit-form').classList.add('d-none');
}

function toggleMessagesSection() {
    const section = document.getElementById('messages-content-wrapper');
    if (section) {
        if (section.style.display === 'none') {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    }
}
</script>
</body>
</html>