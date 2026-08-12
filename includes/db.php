<?php
// =====================================================
// DATABASE CONNECTION
// =====================================================

define('DB_HOST', 'sql212.infinityfree.com');
define('DB_USER', 'if0_42209989');
define('DB_PASS', 'oI3wqsBeaZlb11');
define('DB_NAME', 'if0_42209989_internship');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Error: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

?>