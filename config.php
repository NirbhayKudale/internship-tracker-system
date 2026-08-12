<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// =====================================================
// INTERNSHIP TRACKER - CONFIGURATION FILE
// =====================================================

// Application Settings
define('APP_NAME', 'Internship Tracker');
define('APP_URL', 'https://internshiptracker.infinityfree.me');
define('APP_DESCRIPTION', 'Professional Internship & Placement Portal');
define('APP_VERSION', '1.0.0');

// Branding
define('BRAND_COLOR', '#007bff');
define('BRAND_COLOR_DARK', '#0056b3');
define('BRAND_COLOR_LIGHT', '#e7f1ff');

// Meta Tags
define('DEFAULT_TITLE', 'Internship Tracker - Find & Manage Internships');
define('DEFAULT_DESCRIPTION', 'Connect with top companies, apply for internships, and track your applications in real-time.');
define('DEFAULT_KEYWORDS', 'internship, placement, jobs, careers, applications, tracking');

// Security
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_UPLOAD_SIZE', 10485760); // 10MB
define('ALLOWED_UPLOAD_EXTENSIONS', ['pdf', 'docx', 'jpg', 'jpeg', 'png']);

// SEO
define('SITE_AUTHOR', 'Internship Tracker Team');
define('SITE_AUTHOR_URL', 'https://internshiptracker.infinityfree.me');
define('SOCIAL_TWITTER', '@InternshipTracker');
define('SOCIAL_FACEBOOK', 'internshiptracker');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Error Reporting (Production Mode - turn off public error display)
error_reporting(0);
ini_set('display_errors', 0);
?>