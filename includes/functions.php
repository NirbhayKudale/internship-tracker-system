<?php
// =====================================================
// HELPER FUNCTIONS
// =====================================================

// Sanitize input to prevent SQL injection
function sanitize($input) {
    global $conn;
    return $conn->real_escape_string(trim(htmlspecialchars($input)));
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Check if student is logged in
function isLoggedIn() {
    return isset($_SESSION['student_id']);
}

// Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Redirect to another page
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Get student by ID
function getStudentById($id) {
    global $conn;
    $id = sanitize($id);
    $query = "SELECT * FROM students WHERE id = '$id'";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

// Get all jobs
function getAllJobs() {
    global $conn;
    $query = "SELECT * FROM jobs ORDER BY last_date_to_apply ASC";
    return $conn->query($query);
}

// Get job by ID
function getJobById($id) {
    global $conn;
    $id = sanitize($id);
    $query = "SELECT * FROM jobs WHERE id = '$id'";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

// Get all students
function getAllStudents() {
    global $conn;
    $query = "SELECT id, name, email, course FROM students ORDER BY id DESC";
    return $conn->query($query);
}

// Get applications by student
function getApplicationsByStudent($student_id) {
    global $conn;
    $student_id = sanitize($student_id);
    $query = "SELECT a.*, j.title, j.company_name, j.last_date_to_apply 
              FROM applications a 
              JOIN jobs j ON a.job_id = j.id 
              WHERE a.student_id = '$student_id'
              ORDER BY a.apply_date DESC";
    return $conn->query($query);
}

// Get applications by job
function getApplicationsByJob($job_id) {
    global $conn;
    $job_id = sanitize($job_id);
    $query = "SELECT a.id, a.student_id, a.status, a.apply_date, s.name, s.email, s.technical_skills 
              FROM applications a 
              JOIN students s ON a.student_id = s.id 
              WHERE a.job_id = '$job_id'
              ORDER BY a.apply_date DESC";
    return $conn->query($query);
}

// Get support tickets
function getSupportTickets($student_id = null) {
    global $conn;
    if ($student_id) {
        $student_id = sanitize($student_id);
        $query = "SELECT * FROM support_tickets WHERE student_id = '$student_id' ORDER BY created_at DESC";
    } else {
        $query = "SELECT t.*, s.name, s.email FROM support_tickets t 
                  JOIN students s ON t.student_id = s.id 
                  ORDER BY CASE WHEN t.status = 'Open' THEN 1 ELSE 2 END, t.created_at DESC";
    }
    return $conn->query($query);
}

// Show alert message
function showAlert($message, $type = 'success') {
    $bgColor = ($type === 'success') ? '#d4edda' : '#f8d7da';
    $textColor = ($type === 'success') ? '#155724' : '#721c24';
    $borderColor = ($type === 'success') ? '#c3e6cb' : '#f5c6cb';
    
    return "<div style='background-color: $bgColor; color: $textColor; border: 1px solid $borderColor; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>
                $message
            </div>";
}

// ===== VALIDATION FUNCTIONS =====

// Validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate phone number
function validatePhone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return strlen($phone) >= 10;
}

// Validate password strength
function validatePassword($password) {
    return strlen($password) >= 6;
}

// Check password strength
function checkPasswordStrength($password) {
    $strength = 0;
    
    if (strlen($password) >= 8) $strength++;
    if (preg_match('/[a-z]/', $password)) $strength++;
    if (preg_match('/[A-Z]/', $password)) $strength++;
    if (preg_match('/[0-9]/', $password)) $strength++;
    if (preg_match('/[^a-zA-Z0-9]/', $password)) $strength++;
    
    return $strength;
}

// Get password strength text
function getPasswordStrengthText($strength) {
    switch($strength) {
        case 0:
        case 1:
            return 'Weak';
        case 2:
            return 'Fair';
        case 3:
            return 'Good';
        case 4:
            return 'Strong';
        case 5:
            return 'Very Strong';
        default:
            return 'Unknown';
    }
}

// Validate file upload
function validateFileUpload($file, $allowed_extensions = ['pdf', 'docx', 'jpg', 'jpeg', 'png'], $max_size = 10485760) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload error'];
    }
    
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed_extensions)) {
        return ['success' => false, 'message' => 'File type not allowed'];
    }
    
    if ($file['size'] > $max_size) {
        return ['success' => false, 'message' => 'File size too large'];
    }
    
    return ['success' => true, 'message' => 'File valid'];
}

?>