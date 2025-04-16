<?php
require_once 'config.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = sanitize_input($_POST['email']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../index.php?newsletter=invalid");
        exit;
    }
    
    // Check if email already exists
    $sql = "SELECT id FROM newsletter_subscribers WHERE email = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) > 0) {
                // Email already subscribed
                header("Location: ../index.php?newsletter=exists");
                exit;
            }
        }
        
        mysqli_stmt_close($stmt);
    }
    
    // Insert new subscriber
    $sql = "INSERT INTO newsletter_subscribers (email) VALUES (?)";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        
        if(mysqli_stmt_execute($stmt)) {
            header("Location: ../index.php?newsletter=success");
        } else {
            header("Location: ../index.php?newsletter=error");
        }
        
        mysqli_stmt_close($stmt);
    } else {
        header("Location: ../index.php?newsletter=error");
    }
} else {
    // Redirect if accessed directly
    header("Location: ../index.php");
}
exit;
?>