<?php
require_once 'config.php';

// Check if question was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['question'])) {
    $question = sanitize_input($_POST['question']);
    
    // Log the question for analysis
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $sql = "INSERT INTO ai_questions (user_id, question) VALUES ($user_id, '$question')";
    mysqli_query($conn, $sql);
    
    // Simple keyword-based AI response system
    // In a real application, you might use an actual AI API like OpenAI
    $response = get_ai_response($question);
    
    echo $response;
} else {
    http_response_code(400);
    echo "No question provided";
}

// Function to generate AI responses based on keywords
function get_ai_response($question) {
    global $conn;
    
    // Convert question to lowercase for easier matching
    $question_lower = strtolower($question);
    
    // Check for course-related questions
    if (strpos($question_lower, "course") !== false || 
        strpos($question_lower, "class") !== false || 
        strpos($question_lower, "program") !== false) {
        
        // Get popular courses
        $sql = "SELECT title FROM courses WHERE status = 'active' ORDER BY enrollment_count DESC LIMIT 3";
        $result = mysqli_query($conn, $sql);
        
        $courses = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = $row['title'];
        }
        
        if (count($courses) > 0) {
            return "We offer many courses including our most popular ones: " . implode(", ", $courses) . 
                   ". You can browse all courses on our website or filter by category. Would you like more information about a specific course?";
        } else {
            return "We offer a variety of courses across different subjects. You can browse all courses on our website or filter by category. Would you like more information about a specific subject?";
        }
    }
    
    // Check for instructor-related questions
    else if (strpos($question_lower, "instructor") !== false || 
             strpos($question_lower, "teacher") !== false || 
             strpos($question_lower, "professor") !== false) {
        
        return "Our instructors are industry professionals and academic experts with years of experience in their fields. Each instructor undergoes a rigorous selection process to ensure they provide the highest quality education. You can view instructor profiles on our website to learn more about their backgrounds and expertise.";
    }
    
    // Check for pricing-related questions
    else if (strpos($question_lower, "price") !== false || 
             strpos($question_lower, "cost") !== false || 
             strpos($question_lower, "fee") !== false ||
             strpos($question_lower, "payment") !== false) {
        
        return "Course prices vary depending on the program, duration, and level. We offer flexible payment options including installment plans and scholarships for eligible students. You can see the specific price for each course on its details page. We also occasionally offer discounts and promotions, so be sure to check our website regularly!";
    }
    
    // Check for certification-related questions
    else if (strpos($question_lower, "certificate") !== false || 
             strpos($question_lower, "certification") !== false || 
             strpos($question_lower, "diploma") !== false) {
        
        return "Yes, upon successful completion of your course, you will receive a digital certificate that you can add to your resume or LinkedIn profile. Our certificates are recognized by many employers and can help enhance your career prospects. Some of our professional certification programs may require passing an exam to receive the certificate.";
    }
    
    // Default response for other questions
    else {
        return "Thank you for your question! I'm an AI assistant designed to help with basic inquiries. For this specific question, I recommend contacting our support team at support@scholar.com or calling us at (555) 123-4567 for more detailed information. Our team is available Monday through Friday, 9 AM to 5 PM.";
    }
}
?>