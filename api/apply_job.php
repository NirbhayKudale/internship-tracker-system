<?php
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$student_id = $_SESSION['student_id'];
$job_id = intval($_POST['job_id'] ?? 0);

if (!$job_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid job']);
    exit();
}

// Check if job exists and not expired
$job = getJobById($job_id);
if (!$job) {
    echo json_encode(['success' => false, 'message' => 'Job not found']);
    exit();
}

if (strtotime($job['last_date_to_apply']) < strtotime(date('Y-m-d'))) {
    echo json_encode(['success' => false, 'message' => 'Application deadline has passed']);
    exit();
}

// Check if already applied
$check = $conn->query("SELECT id FROM applications WHERE student_id = $student_id AND job_id = $job_id");
if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'You have already applied for this job']);
    exit();
}

// Insert application
$today = date('Y-m-d');
$stmt = $conn->prepare("INSERT INTO applications (student_id, job_id, apply_date, status) VALUES (?, ?, ?, 'Under Review')");
$stmt->bind_param("iis", $student_id, $job_id, $today);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to apply. Please try again.']);
}
?>