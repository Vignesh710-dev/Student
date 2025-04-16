<?php
require_once 'config.php';

// Get count of active users in the last 15 minutes
$time = time();
$timeout = 15 * 60; // 15 minutes

$sql = "SELECT COUNT(*) as count FROM active_users WHERE last_activity > ($time - $timeout)";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo $row['count'];
?>