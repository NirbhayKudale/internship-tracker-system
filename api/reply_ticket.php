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

$ticket_id = intval($_POST['ticket_id'] ?? 0);
$reply = trim($_POST['reply'] ?? '');

if (!$ticket_id || empty($reply)) {
    echo json_encode(['success' => false, 'message' => 'Ticket ID and reply are required']);
    exit();
}

$reply = $conn->real_escape_string($reply);

$result = $conn->query("UPDATE support_tickets SET admin_reply = '$reply', status = 'Closed' WHERE ticket_id = $ticket_id");

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Reply sent and ticket closed']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send reply']);
}
?>