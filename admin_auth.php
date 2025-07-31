<?php
// admin_auth.php - Checks if an admin is logged in 

// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the 'admin_logged_in' session variable is set and true
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // If not logged in, set a message and redirect to the admin login page
    $_SESSION['message'] = "Please log in to access the admin panel.";
    $_SESSION['message_type'] = "error";
    header("Location: admin_login.php");
    exit(); // Stop script execution after redirect
}
?>
