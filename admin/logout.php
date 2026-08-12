<?php
session_start();
require_once '../config.php';

unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

if (!isset($_SESSION['student_id'])) {
    session_unset();
    session_destroy();
}

header("Location: /index.php?logged_out=1");
exit();
?>