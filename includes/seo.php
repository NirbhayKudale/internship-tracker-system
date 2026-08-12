<?php
// =====================================================
// SEO HELPER FUNCTIONS
// =====================================================

function generateSEO($data = []) {
    $defaults = [
        'title' => DEFAULT_TITLE,
        'description' => DEFAULT_DESCRIPTION,
        'keywords' => DEFAULT_KEYWORDS,
        'image' => APP_URL . '/assets/images/og-image.png',
        'url' => APP_URL . $_SERVER['REQUEST_URI'],
        'type' => 'website',
        'author' => SITE_AUTHOR,
        'robots' => 'index, follow',
        'canonical' => APP_URL . $_SERVER['REQUEST_URI'],
    ];
    
    $seo = array_merge($defaults, $data);
    
    return $seo;
}

function renderSEOTags($seo) {
    $output = '';
    
    // Title
    $output .= '<title>' . htmlspecialchars($seo['title']) . '</title>' . "\n";
    
    // Meta Description
    $output .= '<meta name="description" content="' . htmlspecialchars($seo['description']) . '">' . "\n";
    
    // Keywords
    $output .= '<meta name="keywords" content="' . htmlspecialchars($seo['keywords']) . '">' . "\n";
    
    // Author
    $output .= '<meta name="author" content="' . htmlspecialchars($seo['author']) . '">' . "\n";
    
    // Robots
    $output .= '<meta name="robots" content="' . $seo['robots'] . '">' . "\n";
    
    // Canonical URL
    $output .= '<link rel="canonical" href="' . htmlspecialchars($seo['canonical']) . '">' . "\n";
    
    // Open Graph Tags
    $output .= '<meta property="og:title" content="' . htmlspecialchars($seo['title']) . '">' . "\n";
    $output .= '<meta property="og:description" content="' . htmlspecialchars($seo['description']) . '">' . "\n";
    $output .= '<meta property="og:image" content="' . htmlspecialchars($seo['image']) . '">' . "\n";
    $output .= '<meta property="og:url" content="' . htmlspecialchars($seo['url']) . '">' . "\n";
    $output .= '<meta property="og:type" content="' . $seo['type'] . '">' . "\n";
    $output .= '<meta property="og:site_name" content="' . APP_NAME . '">' . "\n";
    
    // Twitter Card Tags
    $output .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $output .= '<meta name="twitter:title" content="' . htmlspecialchars($seo['title']) . '">' . "\n";
    $output .= '<meta name="twitter:description" content="' . htmlspecialchars($seo['description']) . '">' . "\n";
    $output .= '<meta name="twitter:image" content="' . htmlspecialchars($seo['image']) . '">' . "\n";
    $output .= '<meta name="twitter:site" content="' . SOCIAL_TWITTER . '">' . "\n";
    
    return $output;
}

function renderStructuredData($data) {
    $json_ld = json_encode($data, JSON_UNESCAPED_SLASHES);
    return '<script type="application/ld+json">' . $json_ld . '</script>';
}

function getOrganizationSchema() {
    return [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => APP_NAME,
        'description' => APP_DESCRIPTION,
        'url' => APP_URL,
        'logo' => APP_URL . '/assets/images/logo.png',
        'sameAs' => [
            'https://twitter.com/' . str_replace('@', '', SOCIAL_TWITTER),
            'https://facebook.com/' . SOCIAL_FACEBOOK,
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'contactType' => 'Customer Support',
            'email' => 'support@internshiptracker.com'
        ]
    ];
}

function getWebsiteSchema() {
    return [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => APP_NAME,
        'url' => APP_URL,
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => APP_URL . '/student/jobs.php?search={search_term_string}'
            ],
            'query-input' => 'required name=search_term_string'
        ]
    ];
}

function getJobSchema($job) {
    return [
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        'title' => $job['title'],
        'description' => $job['description'],
        'hiringOrganization' => [
            '@type' => 'Organization',
            'name' => $job['company_name'],
            'sameAs' => APP_URL
        ],
        'jobLocation' => [
            '@type' => 'Place',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'IN'
            ]
        ],
        'validThrough' => $job['last_date_to_apply'],
        'applicantLocationRequirements' => [
            '@type' => 'Country',
            'name' => 'India'
        ]
    ];
}
?>