<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'scholar_db');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}

// Set session parameters
session_start();

// Function to sanitize input data
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Function to log user activity
function log_activity($user_id, $activity_type, $description) {
    global $conn;
    
    $user_id = (int)$user_id;
    $activity_type = sanitize_input($activity_type);
    $description = sanitize_input($description);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $sql = "INSERT INTO activity_logs (user_id, activity_type, description, ip_address) 
            VALUES ($user_id, '$activity_type', '$description', '$ip_address')";
    
    mysqli_query($conn, $sql);
}

// Function to track active users
function track_active_user() {
    global $conn;
    
    $session_id = session_id();
    $time = time();
    $timeout = 15 * 60; // 15 minutes
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    // Remove expired sessions
    $sql = "DELETE FROM active_users WHERE last_activity < ($time - $timeout)";
    mysqli_query($conn, $sql);
    
    // Check if session exists
    $sql = "SELECT * FROM active_users WHERE session_id = '$session_id'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) == 0) {
        // New session, insert
        $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
        $sql = "INSERT INTO active_users (session_id, user_id, ip_address, last_activity) 
                VALUES ('$session_id', $user_id, '$ip_address', $time)";
    } else {
        // Update existing session
        $sql = "UPDATE active_users SET last_activity = $time WHERE session_id = '$session_id'";
    }
    
    mysqli_query($conn, $sql);
}

// Track active user on every page load
track_active_user();
?>