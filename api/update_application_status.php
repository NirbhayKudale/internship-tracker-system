<?php
session_start();
require_once '../config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isAdminLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$application_id = intval($_POST['application_id'] ?? 0);
$status = trim($_POST['status'] ?? '');
$allowed_statuses = ['Under Review', 'Selected', 'Rejected'];

if (!$application_id || !in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

$stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $application_id);

if ($stmt->execute()) {
    // If selected, update student placed_status
    if ($status === 'Selected') {
        $app = $conn->query("SELECT student_id FROM applications WHERE id = $application_id")->fetch_assoc();
        if ($app) {
            $conn->query("UPDATE students SET placed_status = 'Placed' WHERE id = " . $app['student_id']);
        }
    }
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update status']);
}
?>