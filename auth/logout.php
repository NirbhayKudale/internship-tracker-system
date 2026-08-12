<?php
session_start();
require_once '../config.php';

unset($_SESSION['student_id']);
unset($_SESSION['student_name']);

if (!isset($_SESSION['admin_id'])) {
    session_unset();
    session_destroy();
}

header("Location: /index.php?logged_out=1");
exit();
?>