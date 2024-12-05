<?php
session_start(); // Start the session

// Destroy the session
session_unset(); // Unset session variables
session_destroy(); // Destroy the session

// Redirect to the login page or index
header("Location: /student-management-system/index.php");
exit;
?>
