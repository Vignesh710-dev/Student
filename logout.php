<?php
require_once 'includes/config.php';

// Check if user is logged in
if(isset($_SESSION['user_id'])) {
    // Log activity
    log_activity($_SESSION['user_id'], 'logout', 'User logged out');
    
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
}

// Redirect to login page
header("Location: login.php");
exit;
?>