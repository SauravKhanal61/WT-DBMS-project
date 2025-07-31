<?php
// admin_logout.php - Admin Logout for a very basic college project

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Unset specific admin session variables
unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_username']);

// Optionally, destroy the entire session (if no user session needs to persist)
// session_destroy();

// Set a logout message
$_SESSION['message'] = "You have been logged out from admin panel.";
$_SESSION['message_type'] = "info";

// Redirect to the admin login page
header("Location: admin_login.php");
exit();
?>
