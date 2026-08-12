<?php
// ===== INCLUDE FILES IN CORRECT ORDER =====
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/seo.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// NOW we can use generateSEO()
$page_seo = generateSEO([
    'title' => 'Register | Internship Tracker - Create Your Account',
    'description' => 'Create a free account and start exploring internship opportunities.',
    'keywords' => 'register, sign up, internship tracker registration',
    'robots' => 'index, follow',
]);

$error = '';
$success = '';

// Handle registration form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';
    $roll_no = sanitize($_POST['roll_no'] ?? '');
    $course = sanitize($_POST['course'] ?? '');
    
    // Validation
    if(empty($name) || empty($email) || empty($password) || empty($confirm_pass) || empty($roll_no) || empty($course)) {
        $error = "All fields are required!";
    } elseif(!validateEmail($email)) {
        $error = "Invalid email format!";
    } elseif(!validatePassword($password)) {
        $error = "Password must be at least 6 characters!";
    } elseif($password !== $confirm_pass) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $check_query = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($check_query);
        
        if($result->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // HASH PASSWORD USING password_hash()
            $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
            
            // INSERT WITH HASHED PASSWORD
            $insert_query = "INSERT INTO students (name, email, password, enrollment_no, course) 
                            VALUES ('$name', '$email', '$hashed_pass', '$roll_no', '$course')";
            
            if($conn->query($insert_query)) {
                $success = "Registration successful! Redirecting to login...";
                echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 2000);</script>";
            } else {
                $error = "Registration failed! Try again.";
            }
        }
    }
}
?>

<style>
    .register-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 80vh;
        padding: 40px 20px;
        display: flex;
        align-items: center;
    }
    
    .register-wrapper h1, 
    .register-wrapper .lead {
        color: white;
    }
    
    .register-wrapper .benefit-item p {
        color: rgba(255, 255, 255, 0.9);
    }
    
    .register-wrapper .benefit-item h5 {
        color: white;
    }
</style>

<main>
    <div class="register-wrapper">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                
                <!-- LEFT SIDE - Get Started Content -->
                <div class="col-lg-5 d-none d-lg-block mb-5 mb-lg-0">
                    <h1 class="display-5 fw-bold mb-4">Get Started Today!</h1>
                    <p class="lead mb-5">Join thousands of students finding their perfect internship.</p>
                    
                    <div class="benefits mb-5">
                        <div class="benefit-item d-flex align-items-start mb-4">
                            <i class="fas fa-briefcase me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Browse Jobs</h5>
                                <p class="text-muted">Access hundreds of internship opportunities.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item d-flex align-items-start mb-4">
                            <i class="fas fa-paper-plane me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Apply Instantly</h5>
                                <p class="text-muted">Apply to jobs with just one click.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item d-flex align-items-start mb-4">
                            <i class="fas fa-chart-line me-3 mt-1" style="font-size: 1.5rem; color: white;"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Track Progress</h5>
                                <p class="text-muted">Monitor your applications in real-time.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- RIGHT SIDE - Registration Form Panel -->
                <div class="col-lg-5 col-md-8 col-sm-10 mx-auto mx-lg-0">
                    <div class="card p-4" style="border: 1px solid #ddd; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                        
                        <!-- Header -->
                        <h3 class="text-center mb-2 fw-bold">Create Account</h3>
                        <p class="text-center text-muted mb-4">Join us in a few easy steps</p>
                        
                        <!-- Success Message -->
                        <?php if(!empty($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Error Message -->
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Registration Form -->
                        <form method="POST" action="">
                            
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Full Name</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="name" 
                                    name="name" 
                                    placeholder="John Doe"
                                    required
                                >
                            </div>
                            
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    placeholder="your@email.com"
                                    required
                                >
                            </div>
                            
                            <!-- Roll No -->
                            <div class="mb-3">
                                <label for="roll_no" class="form-label fw-bold">Roll No</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="roll_no" 
                                    name="roll_no" 
                                    placeholder="2024001"
                                    required
                                >
                            </div>
                            
                            <!-- Course -->
                            <div class="mb-3">
                                <label for="course" class="form-label fw-bold">Course</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="course" 
                                    name="course" 
                                    placeholder="B.Tech CSE"
                                    required
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
                                    placeholder="Min 6 characters"
                                    required
                                >
                            </div>
                            
                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label fw-bold">Confirm Password</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="confirm_password" 
                                    name="confirm_password" 
                                    placeholder="Re-enter password"
                                    required
                                >
                            </div>
                            
                            <!-- Terms & Conditions -->
                            <div class="mb-3 form-check">
                                <input 
                                    type="checkbox" 
                                    class="form-check-input" 
                                    id="terms" 
                                    name="terms"
                                    required
                                >
                                <label class="form-check-label small" for="terms">
                                    I agree to the Terms & Conditions
                                </label>
                            </div>
                            
                            <!-- Register Button -->
                            <button 
                                type="submit" 
                                class="btn w-100 fw-bold"
                                style="background-color: black; color: white; border: 2px solid black; transition: all 0.3s; padding: 10px;"
                                onmouseover="this.style.backgroundColor='white'; this.style.color='black';"
                                onmouseout="this.style.backgroundColor='black'; this.style.color='white';"
                            >
                                Create Account
                            </button>
                        </form>
                        
                        <!-- Footer Links -->
                        <p class="text-center mt-3 mb-0">
                            Already have an account? <a href="login.php" class="text-decoration-none fw-bold">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>