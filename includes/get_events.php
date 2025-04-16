<?php
require_once 'config.php';

// Get upcoming events from database
$current_date = date('Y-m-d');

$sql = "SELECT * FROM events 
        WHERE event_date >= '$current_date' 
        ORDER BY event_date ASC 
        LIMIT 3";

$result = mysqli_query($conn, $sql);

$events = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Format date for display
        $date_obj = new DateTime($row['event_date']);
        $row['date'] = $date_obj->format('M d, Y');
        
        $events[] = $row;
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($events);
?>