<?php
require_once 'config.php';

// Get instructors from database
$sql = "SELECT u.id, u.name, u.image, u.specialty, u.facebook, u.twitter, u.linkedin 
        FROM users u 
        WHERE u.role = 'instructor' AND u.status = 'active' 
        ORDER BY u.name ASC";

$result = mysqli_query($conn, $sql);

$instructors = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $instructors[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($instructors);
?>