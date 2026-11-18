<?php
session_start();
require_once 'db.php';

// Force strict viewer mode: remove any existing admin session variables
unset($_SESSION['role']);
unset($_SESSION['username']);

// Handle Language Switcher Session & Cookie Configuration
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'] == 'am' ? 'am' : 'en';
    $_SESSION['lang'] = $lang;
    setcookie('lang', $lang, time() + (86400 * 30), "/");
} else {
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'en');
}

// Language Translations Dictionary Array
$translations = [
    'en' => [
        'home'=>'Home', 'about'=>'About Us', 'news'=>'News', 'contact'=>'Contact Us',
        'mission'=>'Mission', 'vision'=>'Vision', 'values'=>'Values', 'goals'=>'Goals',
        'services'=>'Services', 'resources'=>'Resources', 'history'=>'History',
        'login'=>'Login', 'user_mode'=>'Viewer Access Only Mode',
        'bureau_title'=>'HADIYA ZONE BUREAU OF WOMENS<br>
         AND CHILDREN AFFAIRS',
        'read_more'=>'Read More'
    ],
    'am' => [
        'home'=>'ዋና ገጽ', 'about'=>'ስለ እኛ', 'news'=>'ዜና', 'contact'=>'አግኙን',
        'mission'=>'ተልዕኮ', 'vision'=>'ራዕይ', 'values'=>'እሴቶች', 'goals'=>'ግቦች',
        'services'=>'አገልግሎቶች', 'resources'=>'ግብዓቶች', 'history'=>'ታሪክ',
        'login'=>'ግባ', 'user_mode'=>'የዕይታ ብቻ መዳረሻ ሁኔታ',
        'bureau_title'=>'ሀዲያ ዞን ሴቶችና ህጻናት ጉዳይ ቢሮ',
        'read_more'=>'ተጨማሪ ያንብቡ'
    ]
];

// Helper function to resolve upload image paths from admin/photos or admin directories
function fix_image_path($path) {
    if (empty($path)) return '';
    
    // Clean up path separators and leading references
    $clean_filename = basename($path);
    $relative_path  = ltrim(str_replace('../', '', $path), '/');

    // Priority checks for admin directory paths
    $possible_paths = [
        $relative_path,
        'admin/photos/' . $clean_filename,
        'admin/uploads/' . $clean_filename,
        'photos/' . $clean_filename,
        'admin/' . $relative_path
    ];

    foreach ($possible_paths as $test_path) {
        if (file_exists($test_path)) {
            return $test_path;
        }
    }

    return $path;
}

// Helper function to split text into lists by punctuation marks
function render_as_sentence_list($text, $current_lang) {
    if (empty(trim($text))) return '';
    $delimiter = ($current_lang === 'am') ? '።' : '.';
    $sentences = explode($delimiter, $text);
    $output = '';
    
    foreach ($sentences as $sentence) {
        $trimmed = trim($sentence);
        if (!empty($trimmed)) {
            $output .= htmlspecialchars($trimmed . $delimiter) . '<br>';
        }
    }
    return $output;
}

// Fetch Main Website Structured Contents
$content_stmt = $pdo->query("SELECT * FROM site_content");
$site_data = [];
while($row = $content_stmt->fetch()){
    $site_data[$row['content_key']] = [
        'en' => $row['content_en'],
        'am' => $row['content_am']
    ];
}

// Form Handler: Public Contact Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_contact'])) {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO w_c_issues.contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
        header("Location: index.php?status=success#contact");
        exit();
    }
}

// Fetch Social Links from Database
$social_links = [
    'facebook'  => 'https://facebook.com',
    'twitter'   => 'https://twitter.com',
    'youtube'   => 'https://youtube.com',
    'telegram'  => 'https://t.me/+DTVJJJ96gwk3YTg0',
    'whatsapp'  => 'https://wa.me/251118723297',
    'instagram' => 'https://instagram.com',
    'linkedin'  => 'https://linkedin.com'
];

$social_stmt = $pdo->query("SELECT * FROM w_c_issues.social_links");
if ($social_stmt) {
    while ($row = $social_stmt->fetch()) {
        $social_links[$row['platform']] = $row['url'];
    }
}

// Fetch HQ Contact Details from Database
$hq_data = [];
$hq_stmt = $pdo->query("SELECT * FROM w_c_issues.office_settings");
if ($hq_stmt) {
    while ($row = $hq_stmt->fetch()) {
        $hq_data[$row['setting_key']] = $row['setting_value'];
    }
}

$hq_address = $hq_data['hq_address'] ?? 'Soro-Bara, Hossana, Ethiopia';
$hq_phone   = $hq_data['hq_phone'] ?? '046-555-23-25';
$hq_email   = $hq_data['hq_email'] ?? 'Hadiyazonewomen&ChildrenAffairs@gmail.com';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo strip_tags($translations[$lang]['bureau_title']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { --primary-color: #2c3e50; --accent-color: #3498db; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #e1e1e198; }
        .navbar-custom { background-color: var(--primary-color); transition: background-color 0.4s ease, box-shadow 0.4s ease; }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand { color: #fff !important; transition: color 0.3s ease, transform 0.3s ease; }
        .navbar-custom .nav-link:hover { transform: translateY(-2px); color: var(--accent-color) !important; }
        
        .navbar-logo {
            height: 80px !important;
            width: 80px !important;
            object-fit: contain;
            flex-shrink: 0;
        }

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

        .expandable-content {
            transition: all 0.5s ease-in-out;
        }
        .expandable-content.collapsed {
            display: -webkit-box;
            -webkit-line-clamp: 20;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .expandable-content.expanded {
            display: block;
            overflow: visible;
        }

        .heroes-section {
            padding: 30px 15px;
            background: transparent;
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
        }
        .viewport {
            overflow: hidden;
            width: 100%;
            padding: 10px 0;
        }
        .track {
            display: flex;
            transition: transform 0.5s ease-in-out;
            gap: 20px;
        }
        .page {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 10px;
            min-width: 100%;
            box-sizing: border-box;
        }
        .hero-card {
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
            
        }
        .hero-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.18);
        }
        .hero-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .hero-card:hover img {
            transform: scale(1.08);
        }
        .hero-card .name {
            padding: 12px 10px;
            font-weight: 700;
            color: #2c3e50;
            width: 100%;
            font-size: 1rem;
        }
        .hero-card .name p {
            font-size: 0.85rem;
            color: #6c757d;
            margin: 4px 0 0 0;
            font-weight: 400;
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


        

        .btn { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }

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
        }
    </style>
</head>
<body>

<!-- Dynamic Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top shadow-sm">
    <div class="container-fluid px-lg-4 px-3"> 
        <a class="navbar-brand d-flex align-items-center" href="#home">
            <img src="<?php echo fix_image_path('logoo.jpg'); ?>" alt="Logo" class="navbar-logo mr-3 flex-shrink-0">
            <h5 class="m-0 font-weight-bold bureau-brand-title"><?php echo $translations[$lang]['bureau_title']; ?></h5>
        </a>
        
        <button class="navbar-toggler text-white border-0" type="button" data-toggle="collapse" data-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars fa-lg"></i>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto nav-links-wrapper">
                <li class="nav-item"><a class="nav-link custom-nav-link" href="#home"><?php echo $translations[$lang]['home']; ?></a></li>
                <li class="nav-item"><a class="nav-link custom-nav-link" href="#about"><?php echo $translations[$lang]['about']; ?></a></li>
                <li class="nav-item"><a class="nav-link custom-nav-link" href="#news"><?php echo $translations[$lang]['news']; ?></a></li>
                <li class="nav-item"><a class="nav-link custom-nav-link" href="#contact"><?php echo $translations[$lang]['contact']; ?></a></li>
            </ul>

            <div class="d-flex align-items-center nav-controls-wrapper">
                <div class="btn-group mr-3" role="group">
                    <a href="?lang=en" class="btn btn-outline-light lang-btn <?php echo $lang=='en'?'active':''; ?>">EN</a>
                    <a href="?lang=am" class="btn btn-outline-light lang-btn <?php echo $lang=='am'?'active':''; ?>">አማ</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- HERO SLIDER SECTION -->
<div id="home" class="slider position-relative">
    <div class="slides" id="heroSlides">
        <?php
        // Fetch all admin-uploaded background hero images from database
        $slides_stmt = $pdo->query("SELECT * FROM w_c_issues.hero_slides ORDER BY id DESC");
        $slides = $slides_stmt ? $slides_stmt->fetchAll() : [];

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
                $image_url = fix_image_path($photo_path);
                echo '<div class="slide ' . $active_class . '" style="background-image: url(\'' . htmlspecialchars($image_url) . '\');"></div>';
            }
        } else {
            foreach ($slides as $index => $slide) {
                $active_class = ($index === 0) ? 'active' : '';
                $image_url = fix_image_path($slide['image_path']);
                echo '<div class="slide ' . $active_class . '" style="background-image: url(\'' . htmlspecialchars($image_url) . '\');"></div>';
            }
        }
        ?>
    </div>
    
    <button class="arrow left" onclick="prevSlide()"><i class="fa fa-chevron-left"></i></button>
    <button class="arrow right" onclick="nextSlide()"><i class="fa fa-chevron-right"></i></button>
    
    <div class="hero-text" id="heroTextContainer">
       <h1 class="display-4 font-weight-bold"><?php echo $translations[$lang]['bureau_title']; ?></h1>
        <p class="lead">Providing reliable structural data tracking and legal management access metrics.</p>
        
    </div>
</div>

<main class="container my-5">
    <!-- About Section: Vision, Mission, Values, Goals, Services, Resources, History -->
    <section id="about" class="mb-5">
        <h2 class="text-center mb-4 border-bottom pb-2 font-weight-bold text-dark"><?php echo $translations[$lang]['about']; ?></h2>
        <div class="row">
            <?php foreach(['vision', 'mission', 'values', 'goals', 'services', 'resources', 'history'] as $key): ?>
                <div class="col-md-6 col-lg-6 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title text-capitalize text-primary font-weight-bold"><?php echo $translations[$lang][$key]; ?></h4>
                            <div class="card-text expandable-content collapsed" id="text-<?php echo $key; ?>">
                                <?php 
                                    $raw_content = $site_data[$key][$lang] ?? '';
                                    echo render_as_sentence_list($raw_content, $lang); 
                                ?>
                            </div>
                            <div class="mt-auto pt-2">
                                <button style="background-color:yellow; font-weight: bold; border-radius: 4px; padding: 2px 8px;" class="btn btn-sm btn-link text-dark p-0 read-more-btn d-none" onclick="toggleReadMore('text-<?php echo $key; ?>', this)">
                                    <?php echo $translations[$lang]['read_more']; ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- News & Operational Updates Display Section -->
    <section id="news" class="my-5">
        <h2 class="text-center mb-4 border-bottom pb-2 font-weight-bold text-dark"><?php echo $translations[$lang]['news']; ?></h2>
        <div class="row">
            <?php
            $news_stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
            $news_items = $news_stmt ? $news_stmt->fetchAll() : [];

            if (!empty($news_items)):
                foreach ($news_items as $item):
                    $title = ($lang === 'am' && !empty($item['title_am'])) ? $item['title_am'] : $item['title_en'];
                    $body  = ($lang === 'am' && !empty($item['body_am']))  ? $item['body_am']  : $item['body_en'];
            ?>
                <div class="col-md-6 mb-4">
                    <div class="card feature-card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary font-weight-bold"><?php echo htmlspecialchars($title); ?></h5>
                            <p class="card-text text-secondary font-weight-bold"><?php echo nl2br(htmlspecialchars($body)); ?></p>
                            <div class="mt-auto pt-2 border-top">
                                <small class="text-muted"><i class="fa-regular fa-clock mr-1"></i><?php echo htmlspecialchars($item['created_at']); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach;
            else: 
            ?>
                <div class="col-12 text-center text-muted py-4 font-italic">No news items posted yet.</div>
            <?php endif; ?>
        </div>
    </section>

    <!-- HEROES DISPLAY SECTION -->
   <!-- Embedded CSS using CSS Grid / Flex-Wrap to drop extra images to a new vertical row -->
<style>
    .heroes-section {
        margin-top: 1rem !important;
        margin-bottom: 1rem !important;
    }
    .heroes-section .viewport {
        width: 100%;
        padding: 0 !important;
    }
    .heroes-section .page {
        display: flex;
        flex-wrap: wrap; /* Allows images to flow vertically to the next line when row fills up */
        justify-content: center;
        gap: 20px;
        width: 100%;
        padding: 0 !important;
    }
    .heroes-section .hero-card {
        flex: 1 1 280px; /* Base size; will wrap vertically if container width limit is reached */
        max-width: 380px;
        width: 100%;
        padding: 0 !important;
    }
    .heroes-section .hero-card img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        border-radius: 8px;
    }
    .heroes-section .name {
        padding: 4px 0 !important;
    }
</style>

<section class="heroes-section my-3">
    <h2 class="section-title mb-2">ORGANIZATIONAL GALLERY</h2>
    <div class="title-underline mb-3"></div>

    <div class="carousel-wrap p-0">
        <div class="viewport p-0">
            <div class="track" id="track">
                <?php
                // Fetch all admin-uploaded heroes/awardees images from database
                $hero_stmt = $pdo->query("SELECT * FROM heroes ORDER BY id DESC");
                $heroes = $hero_stmt ? $hero_stmt->fetchAll(PDO::FETCH_ASSOC) : [];
                
                // Set chunk size according to total items per page
                $chunks = array_chunk($heroes, 6);
                ?>

                <?php if (!empty($chunks)): ?>
                    <?php foreach ($chunks as $pageIndex => $pageHeroes): ?>
                        <div class="page p-0">
                            <?php foreach ($pageHeroes as $hero): 
                                $hero_img = fix_image_path($hero['image_path']);
                            ?>
                                <div class="hero-card p-0">
                                    <img src="<?php echo htmlspecialchars($hero_img); ?>" alt="<?php echo htmlspecialchars($hero['name']); ?>">
                                    <div class="name mt-2 text-center p-0">
                                        <h5 class="font-weight-bold mb-1"><?php echo htmlspecialchars($hero['name']); ?></h5>
                                        <hr class="my-1">
                                        <p class="mb-0 text-muted"><?php echo htmlspecialchars($hero['profession']); ?></p>
                                        <hr class="my-1">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center w-100 py-2 text-muted">No heroes listed yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="dots mt-2" id="dots">
        <?php for ($i = 0; $i < count($chunks); $i++): ?>
            <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" data-page="<?php echo $i; ?>"></span>
        <?php endfor; ?>
    </div>
</section>

    <!-- Public Contact Us Section -->
    <section id="contact" class="my-5 bg-white p-4 rounded shadow-sm feature-card">
        <h2 class="font-weight-bold text-dark border-bottom pb-2 mb-4"><?php echo $translations[$lang]['contact']; ?></h2>
        <div class="row">
            <div class="col-md-6">
                <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
                    <div class="alert alert-success">Thank you! Your message has been stored in our system.</div>
                <?php endif; ?>
                
                <form method="POST" action="index.php">
                    <input type="hidden" name="action_contact" value="1">
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="font-weight-bold">Message</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold">Send Message</button>
                </form>
            </div>

            <div class="col-md-6 mt-4 mt-md-0">
                <h5 class="font-weight-bold text-primary">Our Office HQ</h5>
                <div id="hq-display-view">
                    <p class="mb-2"><i class="fa-solid fa-location-dot mr-2 text-danger"></i> <?php echo htmlspecialchars($hq_address); ?></p>
                    <p class="mb-2"><i class="fa-solid fa-phone mr-2 text-success"></i> <?php echo htmlspecialchars($hq_phone); ?></p>
                    <p class="mb-2"><i class="fa-solid fa-envelope mr-2 text-info"></i> <?php echo htmlspecialchars($hq_email); ?></p>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Public Footer -->
<footer style="text-align: center; color: white; padding: 30px;" class="navbar navbar-custom mt-5">
    
    
<!-- Enhanced Professional & Interactive Footer -->
<footer style="padding-left:30px;" class="footer-custom mt-5">
    <div class="container py-5">
        <div class="row text-white">
           
            <!-- Column 1: Organization Overview -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                      <img src="<?php echo fix_image_path('logoo.jpg'); ?>" alt="Logo" class="navbar-logo mr-3 flex-shrink-0">
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


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentSlideIndex = 0;
    const slidesContainer = document.getElementById('heroSlides');
    const totalSlides = slidesContainer ? slidesContainer.querySelectorAll('.slide').length : 0;
    let autoSlideInterval;

    function triggerTextAnimation() {
        const textContainer = document.getElementById('heroTextContainer');
        if(textContainer) {
            textContainer.style.animation = 'none';
            textContainer.offsetHeight;
            textContainer.style.animation = 'heroTextFadeIn 1.2s ease-out forwards';
        }
    }

    function updateSlidePosition() {
        if(slidesContainer) {
            slidesContainer.style.transform = `translateX(-${currentSlideIndex * 100}%)`;
            triggerTextAnimation();
        }
    }

    function nextSlide() {
        if(totalSlides === 0) return;
        currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
        updateSlidePosition();
        resetTimer();
    }

    function prevSlide() {
        if(totalSlides === 0) return;
        currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
        updateSlidePosition();
        resetTimer();
    }

    function startTimer() {
        autoSlideInterval = setInterval(() => {
            if(totalSlides > 0) {
                currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
                updateSlidePosition();
            }
        }, 60000);
    }

    function resetTimer() {
        clearInterval(autoSlideInterval);
        startTimer();
    }

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

    document.addEventListener("DOMContentLoaded", function() {
        startTimer();

        const items = document.querySelectorAll('.expandable-content');
        items.forEach(item => {
            if (item.scrollHeight > item.clientHeight) {
                const btn = item.parentElement.querySelector('.read-more-btn');
                if (btn) btn.classList.remove('d-none');
            } else {
                item.classList.remove('collapsed');
            }
        });

        // Heroes Carousel Logic
        const track = document.getElementById('track');
        const dots = document.querySelectorAll('.dot');
        let currentPage = 0;
        const totalPages = dots.length;

        function updateCarousel(page) {
            if (totalPages === 0 || !track) return;
            currentPage = page;
            track.style.transform = `translateX(-${currentPage * 100}%)`;
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentPage);
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
</body>
</html>