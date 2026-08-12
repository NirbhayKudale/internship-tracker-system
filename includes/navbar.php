<?php
// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
$current_uri = $_SERVER['REQUEST_URI'];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top" role="navigation" aria-label="Main navigation">
    <div class="container-fluid">
        
        <!-- ===== BRAND/LOGO ===== -->
        <a class="navbar-brand fw-bold" href="/" title="<?php echo APP_NAME; ?> - Home">
            <i class="fas fa-graduation-cap me-2" aria-hidden="true"></i>
            <span><?php echo APP_NAME; ?></span>
        </a>
        
        <!-- ===== MOBILE TOGGLE BUTTON ===== -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- ===== NAVIGATION ITEMS ===== -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                
                <!-- ===== STUDENT LOGGED IN ===== -->
                <?php if(isLoggedIn()): ?>
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="/student/dashboard.php" 
                           title="Student Dashboard" aria-label="Go to Dashboard">
                            <i class="fas fa-home" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Browse Jobs -->
                    <li class="nav-item">
                        <a class="nav-link" href="/student/jobs.php" 
                           title="Browse Internship Jobs" aria-label="Browse Jobs">
                            <i class="fas fa-briefcase" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Jobs</span>
                        </a>
                    </li>
                    
                    <!-- My Applications -->
                    <li class="nav-item">
                        <a class="nav-link" href="/student/my_applications.php" 
                           title="View Your Applications" aria-label="View Applications">
                            <i class="fas fa-file-alt" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Applications</span>
                        </a>
                    </li>
                    
                    <!-- Profile -->
                    <li class="nav-item">
                        <a class="nav-link" href="/student/profile.php" 
                           title="Edit Your Profile" aria-label="Edit Profile">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Profile</span>
                        </a>
                    </li>
                    
                    <!-- Support Tickets -->
                    <li class="nav-item">
                        <a class="nav-link" href="/student/my_tickets.php" 
                           title="View Support Tickets" aria-label="Support Tickets">
                            <i class="fas fa-comments" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Tickets</span>
                        </a>
                    </li>
                    
                    <!-- Logout -->
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/auth/logout.php" 
                           title="Logout from your account" aria-label="Logout">
                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Logout</span>
                        </a>
                    </li>
                
                <!-- ===== ADMIN LOGGED IN ===== -->
                <?php elseif(isAdminLoggedIn()): ?>
                    <!-- Admin Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard.php" 
                           title="Admin Dashboard" aria-label="Admin Dashboard">
                            <i class="fas fa-chart-bar" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Post Job -->
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/post-job.php" 
                           title="Post a New Job" aria-label="Post Job">
                            <i class="fas fa-plus" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Post Job</span>
                        </a>
                    </li>
                    
                    <!-- Manage Jobs -->
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/manage-jobs.php" 
                           title="Manage Posted Jobs" aria-label="Manage Jobs">
                            <i class="fas fa-briefcase" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Jobs</span>
                        </a>
                    </li>
                    
                    <!-- Manage Students -->
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/manage-students.php" 
                           title="View Registered Students" aria-label="View Students">
                            <i class="fas fa-users" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Students</span>
                        </a>
                    </li>
                    
                    <!-- Support Tickets -->
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/support-tickets.php" 
                           title="View Support Tickets" aria-label="Support Tickets">
                            <i class="fas fa-comments" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Support</span>
                        </a>
                    </li>
                    
                    <!-- Logout -->
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/admin/logout.php" 
                           title="Logout from admin account" aria-label="Logout">
                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Logout</span>
                        </a>
                    </li>
                
                <!-- ===== NOT LOGGED IN ===== -->
                <?php else: ?>
                    <!-- Student Login -->
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/login.php" 
                           title="Login as Student" aria-label="Student Login">
                            <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Login</span>
                        </a>
                    </li>
                    
                    <!-- Register -->
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/register.php" 
                           title="Register as a Student" aria-label="Register">
                            <i class="fas fa-user-plus" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Register</span>
                        </a>
                    </li>
                    
                    <!-- Admin Login -->
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/admin_login.php" 
                           title="Login as Admin" aria-label="Admin Login">
                            <i class="fas fa-lock" aria-hidden="true"></i>
                            <span class="d-lg-none ms-2">Admin</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>