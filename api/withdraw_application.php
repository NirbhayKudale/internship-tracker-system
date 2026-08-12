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
$application_id = intval($_POST['application_id'] ?? 0);

if (!$application_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid application']);
    exit();
}

// Make sure this application belongs to the student
$check = $conn->query("SELECT id, status FROM applications WHERE id = $application_id AND student_id = $student_id");
if ($check->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Application not found']);
    exit();
}

$app = $check->fetch_assoc();
if ($app['status'] === 'Selected') {
    echo json_encode(['success' => false, 'message' => 'Cannot withdraw a selected application']);
    exit();
}

$stmt = $conn->prepare("DELETE FROM applications WHERE id = ? AND student_id = ?");
$stmt->bind_param("ii", $application_id, $student_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Application withdrawn successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to withdraw application']);
}
?>