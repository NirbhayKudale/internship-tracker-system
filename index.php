<?php
// ===== INCLUDE REQUIRED FILES FIRST =====
require_once 'config.php';         // Configuration file
require_once 'includes/db.php';     // Database connection
require_once 'includes/functions.php'; // Helper functions
require_once 'includes/seo.php';    // SEO functions (MUST BE HERE!)
?>

<?php
// NOW we can use generateSEO() because seo.php is already loaded
$page_seo = generateSEO([
    'title' => 'Internship Tracker - Find & Manage Internships | Apply Today',
    'description' => 'Connect with top companies, apply for internships, and track your applications in real-time. Join thousands of students finding their perfect internship.',
    'keywords' => 'internship, placement, jobs, internship opportunities, career, applications, internship portal',
    'robots' => 'index, follow',
    'type' => 'website',
]);

// Now include header (which needs all above files)
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<?php if (isset($_GET['logged_out'])): ?>
<div class="container mt-3">
    <div class="alert alert-info alert-dismissible fade show">
        <i class="fas fa-info-circle"></i> You have been logged out successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<!-- ===== HERO SECTION ===== -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Find Your Dream Internship</h1>
                <p class="lead mb-4">Connect with top companies, apply for internships, and track your applications in real-time.</p>
                
                <?php if(!isLoggedIn() && !isAdminLoggedIn()): ?>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="auth/login.php" class="btn btn-primary btn-lg" title="Student Login">
                            <i class="fas fa-sign-in-alt"></i> Student Login
                        </a>
                        <a href="auth/register.php" class="btn btn-outline-light btn-lg" title="Register as Student">
                            <i class="fas fa-user-plus"></i> Register Now
                        </a>
                    </div>
                <?php elseif(isLoggedIn()): ?>
                    <a href="student/dashboard.php" class="btn btn-light btn-lg" title="Go to Dashboard">
                        <i class="fas fa-home"></i> Go to Dashboard
                    </a>
                <?php elseif(isAdminLoggedIn()): ?>
                    <a href="admin/dashboard.php" class="btn btn-light btn-lg" title="Go to Admin Dashboard">
                        <i class="fas fa-chart-bar"></i> Admin Dashboard
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <i class="fas fa-briefcase fa-10x text-white opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== FEATURES SECTION ===== -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose <?php echo APP_NAME; ?>?</h2>
        
        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>Find Jobs</h4>
                    <p>Browse hundreds of internship opportunities from top companies across different industries.</p>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h4>Apply Easily</h4>
                    <p>Apply to jobs with just one click. Complete your profile once and start applying to your favorite jobs.</p>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Track Progress</h4>
                    <p>Monitor your application status from submission to selection in real-time with our tracking system.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATS SECTION ===== -->
<section class="stats-section py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-5">Our Impact</h2>
        
        <div class="row text-center">
            <!-- Jobs Available -->
            <div class="col-md-3 mb-4">
                <div class="stat-box">
                    <h3 class="text-primary">
                        <?php 
                        try {
                            $result = $conn->query("SELECT COUNT(*) as count FROM jobs");
                            $row = $result->fetch_assoc();
                            echo $row['count'] ?? 0;
                        } catch(Exception $e) {
                            echo "0";
                        }
                        ?>+
                    </h3>
                    <p>Jobs Available</p>
                </div>
            </div>
            
            <!-- Students Registered -->
            <div class="col-md-3 mb-4">
                <div class="stat-box">
                    <h3 class="text-success">
                        <?php 
                        try {
                            $result = $conn->query("SELECT COUNT(*) as count FROM students");
                            $row = $result->fetch_assoc();
                            echo $row['count'] ?? 0;
                        } catch(Exception $e) {
                            echo "0";
                        }
                        ?>+
                    </h3>
                    <p>Students Registered</p>
                </div>
            </div>
            
            <!-- Applications Received -->
            <div class="col-md-3 mb-4">
                <div class="stat-box">
                    <h3 class="text-info">
                        <?php 
                        try {
                            $result = $conn->query("SELECT COUNT(*) as count FROM applications");
                            $row = $result->fetch_assoc();
                            echo $row['count'] ?? 0;
                        } catch(Exception $e) {
                            echo "0";
                        }
                        ?>+
                    </h3>
                    <p>Applications Received</p>
                </div>
            </div>
            
            <!-- Students Selected -->
            <div class="col-md-3 mb-4">
                <div class="stat-box">
                    <h3 class="text-warning">
                        <?php 
                        try {
                            $result = $conn->query("SELECT COUNT(*) as count FROM applications WHERE status = 'Selected'");
                            $row = $result->fetch_assoc();
                            echo $row['count'] ?? 0;
                        } catch(Exception $e) {
                            echo "0";
                        }
                        ?>+
                    </h3>
                    <p>Students Selected</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section class="cta-section py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-4">Ready to Start Your Internship Journey?</h2>
        <p class="lead mb-4">Join thousands of students who have found their perfect internship opportunity</p>
        
        <?php if(!isLoggedIn() && !isAdminLoggedIn()): ?>
            <a href="auth/register.php" class="btn btn-light btn-lg" title="Register Now">
                <i class="fas fa-rocket"></i> Get Started Now
            </a>
        <?php endif; ?>
    </div>
</section>

<!-- ===== STRUCTURED DATA (For Google Rich Snippets) ===== -->
<script type="application/ld+json">
<?php
echo json_encode(getOrganizationSchema());
?>
</script>

<script type="application/ld+json">
<?php
echo json_encode(getWebsiteSchema());
?>
</script>

<?php require_once 'includes/footer.php'; ?>