<?php
// ===== INCLUDE FILES IN CORRECT ORDER =====
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';




$error = '';

// Handle admin login form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if(empty($username)) {
        $error = "Username is required!";
    } elseif(empty($password)) {
        $error = "Password is required!";
    } else {
        // Get admin user
        $query = "SELECT * FROM admin WHERE username = '$username'";
        $result = $conn->query($query);
        
        if($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            
            // VERIFY HASHED PASSWORD

            if(password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                redirect('/admin/dashboard.php');
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Invalid username or password!";
        }
    }
}

// NOW we can use generateSEO()
$page_seo = generateSEO([
    'title' => 'Admin Login | Internship Tracker',
    'description' => 'Admin login for Internship Tracker management portal.',
    'keywords' => 'admin login, internship tracker admin',
    'robots' => 'noindex, nofollow',
]);

// ONLY NOW load HTML
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<style>
    .admin-wrapper {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
        min-height: 80vh;
        padding: 40px 20px;
        display: flex;
        align-items: center;
    }
    
    .admin-wrapper h1, 
    .admin-wrapper .lead {
        color: white;
    }
    
    .admin-wrapper .admin-feature p {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .admin-wrapper .admin-feature h5 {
        color: white;
    }
</style>

<main>
    <div class="admin-wrapper">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                
                <!-- LEFT SIDE - Admin Portal Info -->
                <div class="col-lg-5 d-none d-lg-block mb-5 mb-lg-0">
                    <h1 class="display-5 fw-bold mb-4">Admin Portal</h1>
                    <p class="lead mb-5">Manage internship jobs, applications, and student data.</p>
                    
                    <div class="admin-features mb-5">
                        <div class="admin-feature d-flex align-items-start mb-4">
                            <i class="fas fa-briefcase me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Manage Jobs</h5>
                                <p class="text-muted">Post and manage internship job listings.</p>
                            </div>
                        </div>
                        
                        <div class="admin-feature d-flex align-items-start mb-4">
                            <i class="fas fa-file-alt me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Review Applications</h5>
                                <p class="text-muted">Review and manage student applications.</p>
                            </div>
                        </div>
                        
                        <div class="admin-feature d-flex align-items-start mb-4">
                            <i class="fas fa-users me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">View Students</h5>
                                <p class="text-muted">View and manage student profiles and data.</p>
                            </div>
                        </div>
                        
                        <div class="admin-feature d-flex align-items-start mb-4">
                            <i class="fas fa-chart-bar me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Analytics</h5>
                                <p class="text-muted">View detailed analytics and statistics.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- RIGHT SIDE - Admin Login Form Panel -->
                <div class="col-lg-5 col-md-8 col-sm-10 mx-auto mx-lg-0">
                    <div class="card p-4" style="border: 1px solid #ddd; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                        
                        <!-- Header -->
                        <h3 class="text-center mb-2 fw-bold">
                            <i class="fas fa-shield-alt me-2"></i>Admin Login
                        </h3>
                        <p class="text-center text-muted mb-4">Authorized access only</p>
                        
                        <!-- Error Message -->
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Login Form -->
                        <form method="POST" action="">
                            
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label fw-bold">Username</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="username" 
                                    name="username" 
                                    placeholder="Enter username"
                                    required
                                    autocomplete="off"
                                >
                            </div>
                            
                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Enter password"
                                    required
                                    autocomplete="off"
                                >
                            </div>
                            
                            <!-- Remember Me -->
                            <div class="mb-3 form-check">
                                <input 
                                    type="checkbox" 
                                    class="form-check-input" 
                                    id="remember" 
                                    name="remember"
                                >
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            
                            <!-- Login Button -->
                            <button 
                                type="submit" 
                                class="btn w-100 fw-bold"
                                style="background-color: black; color: white; border: 2px solid black; transition: all 0.3s; padding: 10px;"
                                onmouseover="this.style.backgroundColor='white'; this.style.color='black';"
                                onmouseout="this.style.backgroundColor='black'; this.style.color='white';"
                            >
                                Admin Login
                            </button>
                        </form>
                        
                        <!-- Footer Links -->
                        <p class="text-center mt-3 mb-0">
                            <a href="/" class="text-decoration-none">← Back to Home</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>