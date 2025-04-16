<?php
require_once 'config.php';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $message = sanitize_input($_POST['message']);
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email format";
        exit;
    }
    
    // Insert into database
    $sql = "INSERT INTO contact_messages (name, email, message) 
            VALUES ('$name', '$email', '$message')";
    
    if (mysqli_query($conn, $sql)) {
        // Send email notification to admin (optional)
        $to = "admin@scholar.com";
        $subject = "New Contact Form Submission";
        $email_message = "Name: $name\nEmail: $email\nMessage: $message";
        $headers = "From: noreply@scholar.com";
        
        // Uncomment to enable email sending
        // mail($to, $subject, $email_message, $headers);
        
        echo "Message submitted successfully";
    } else {
        http_response_code(500);
        echo "Error: " . mysqli_error($conn);
    }
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?>