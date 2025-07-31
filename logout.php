<?php
session_start();
session_destroy();
echo "<script>alert('You have been logged out successfully');</script>";
// Redirect to the Sign Up page
header("Location: http://localhost/WT&DBMSproject/SignUP.php");
?>