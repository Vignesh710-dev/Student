<?php
require_once 'config.php';

// Get courses from database
$sql = "SELECT c.*, u.name as instructor, 
        (SELECT AVG(rating) FROM course_ratings WHERE course_id = c.id) as rating 
        FROM courses c 
        LEFT JOIN users u ON c.instructor_id = u.id 
        WHERE c.status = 'active' 
        ORDER BY c.created_at DESC 
        LIMIT 6";

$result = mysqli_query($conn, $sql);

$courses = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Format rating to one decimal place
        $row['rating'] = number_format($row['rating'], 1);
        
        // If no rating, set default
        if ($row['rating'] == 0.0) {
            $row['rating'] = "New";
        }
        
        $courses[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($courses);
?>