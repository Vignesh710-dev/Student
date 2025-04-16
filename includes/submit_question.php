<?php
require_once 'config.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $question = sanitize_input($_POST['question']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email format";
        exit;
    }
    
    // Insert into database
    $sql = "INSERT INTO faq_questions (name, email, question) 
            VALUES ('$name', '$email', '$question')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Question submitted successfully";
    } else {
        http_response_code(500);
        echo "Error: " . mysqli_error($conn);
    }
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?>