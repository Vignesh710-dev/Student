<?php
require_once 'config.php';

// Get user ID if logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

// If user is logged in, get personalized recommendations
if ($user_id > 0) {
    // Get user's viewed courses
    $sql = "SELECT course_id FROM user_course_views WHERE user_id = $user_id ORDER BY view_count DESC LIMIT 3";
    $result = mysqli_query($conn, $sql);
    
    $viewed_courses = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $viewed_courses[] = $row['course_id'];
    }
    
    if (count($viewed_courses) > 0) {
        // Get courses in the same categories as viewed courses
        $viewed_ids = implode(',', $viewed_courses);
        $sql = "SELECT c.* FROM courses c
                JOIN courses v ON c.category_id = v.category_id
                WHERE v.id IN ($viewed_ids) AND c.id NOT IN ($viewed_ids) AND c.status = 'active'
                GROUP BY c.id
                ORDER BY RAND()
                LIMIT 3";
    } else {
        // No view history, get popular courses
        $sql = "SELECT * FROM courses WHERE status = 'active' ORDER BY enrollment_count DESC LIMIT 3";
    }
} else {
    // Not logged in, get popular courses
    $sql = "SELECT * FROM courses WHERE status = 'active' ORDER BY enrollment_count DESC LIMIT 3";
}

$result = mysqli_query($conn, $sql);

$courses = array();
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($courses);
?>