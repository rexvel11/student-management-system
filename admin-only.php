<?php
// admin-only.php
session_start();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to login page if not an admin
    header("Location: /student-management-system/index.php");
    exit;
}
?>
