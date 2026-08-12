<?php
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';

// NOW we can use generateSEO()


$error = '';

// Handle login form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if(empty($email)) {
        $error = "Email is required!";
    } elseif(empty($password)) {
        $error = "Password is required!";
    } else {
        $query = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($query);
        
        if($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            
            // Use verifyPassword() function to check hashed password
            if(verifyPassword($password, $student['password'])) {
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['student_name'] = $student['name'];
                $_SESSION['student_email'] = $student['email'];
                redirect('/student/dashboard.php');
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Email not found!";
        }
    }
}

$page_seo = generateSEO([
    'title' => 'Student Login | Internship Tracker',
    'description' => 'Login to your account and browse internships.',
    'keywords' => 'login, internship tracker',
    'robots' => 'noindex, follow',
]);

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<style>
    .login-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 80vh;
        padding: 40px 20px;
        display: flex;
        align-items: center;
    }
    
    .login-wrapper h1, 
    .login-wrapper .lead {
        color: white;
    }
    
    .login-wrapper .feature-item p {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .login-wrapper .feature-item h5 {
        color: white;
    }
</style>

<main>
    <div class="login-wrapper">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                
                <!-- LEFT SIDE - Welcome Content -->
                <div class="col-lg-5 d-none d-lg-block mb-5 mb-lg-0">
                    <h1 class="display-5 fw-bold mb-4">Welcome Back!</h1>
                    <p class="lead mb-5">Login to your account and start applying for internships.</p>
                    
                    <div class="features mb-5">
                        <div class="feature-item d-flex align-items-start mb-4">
                            <i class="fas fa-check-circle me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Browse Thousands of Jobs</h5>
                                <p class="text-muted">Explore internship opportunities from top companies.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item d-flex align-items-start mb-4">
                            <i class="fas fa-check-circle me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Apply with One Click</h5>
                                <p class="text-muted">Apply to jobs instantly once your profile is ready.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item d-flex align-items-start mb-4">
                            <i class="fas fa-check-circle me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Track Your Applications</h5>
                                <p class="text-muted">Monitor the status of all your applications in real-time.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- RIGHT SIDE - Login Form Panel -->
                <div class="col-lg-5 col-md-8 col-sm-10 mx-auto mx-lg-0">
                    <div class="card p-4" style="border: 1px solid #ddd; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                        
                        <!-- Header -->
                        <h3 class="text-center mb-4 fw-bold">Student Login</h3>
                        
                        <!-- Error Message -->
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Login Form -->
                        <form method="POST" action="">
                            
                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    placeholder="Enter your email"
                                    required
                                    autocomplete="email"
                                >
                            </div>
                            
                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Enter your password"
                                    required
                                    autocomplete="current-password"
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
                                Login
                            </button>
                        </form>
                        
                        <!-- Footer Links -->
                        <p class="text-center mt-3 mb-0">
                            Don't have an account? <a href="register.php" class="text-decoration-none fw-bold">Register here</a>
                        </p>
                        <p class="text-center small text-muted mt-2">
                            <a href="#" class="text-muted text-decoration-none">Forgot password?</a>
                        </p>
                        
                        <!-- Demo Credentials -->
                        <hr class="my-3">
                        <p class="text-center small text-muted mb-2">Demo Credentials:</p>
                        <div class="text-center" style="cursor: pointer; padding: 10px; background-color: #f8f9fa; border-radius: 5px;" onclick="document.getElementById('email').value='demo@example.com'; document.getElementById('password').value='123456';">
                            <code style="font-size: 0.9rem;">demo@example.com / 123456</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>