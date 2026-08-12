<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include required files in order
require_once dirname(__FILE__) . '/../config.php';
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/functions.php';
require_once dirname(__FILE__) . '/seo.php';

// Set default SEO if not already set
if (!isset($page_seo)) {
    $page_seo = generateSEO();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO TAGS -->
    <?php echo renderSEOTags($page_seo); ?>
    
    <!-- FAVICON -->
<link rel="icon" type="image/x-icon" href="/assets/favicon.ico">
<link rel="apple-touch-icon" href="/assets/favicon.ico">
    
    <!-- THEME COLOR -->
    <meta name="theme-color" content="<?php echo BRAND_COLOR; ?>">
    
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- TOASTR CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
<link rel="stylesheet" href="/css/style.css">
    
    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body data-app="<?php echo APP_NAME; ?>">