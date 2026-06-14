<?php
// Start session
session_start();

// Clear session variables
$_SESSION = array();

// Destroy session data
session_destroy();

// Redirect back to login screen
header("Location: login.php");
exit;
?>