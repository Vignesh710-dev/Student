<?php
require_once 'includes/config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user information
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$user_email = $_SESSION['email'];
$user_role = $_SESSION['role'];

// Get enrolled courses
$sql = "SELECT c.id, c.title, c.image, e.progress, e.status 
        FROM enrollments e 
        JOIN courses c ON e.course_id = c.id 
        WHERE e.user_id = $user_id 
        ORDER BY e.enrollment_date DESC";
$enrolled_courses = mysqli_query($conn, $sql);

// Get recommended courses
$sql = "SELECT c.* FROM courses c 
        WHERE c.id NOT IN (SELECT course_id FROM enrollments WHERE user_id = $user_id) 
        AND c.status = 'active' 
        ORDER BY c.enrollment_count DESC 
        LIMIT 3";
$recommended_courses = mysqli_query($conn, $sql);

// Get upcoming events
$current_date = date('Y-m-d');
$sql = "SELECT * FROM events 
        WHERE event_date >= '$current_date' 
        ORDER BY event_date ASC 
        LIMIT 3";
$upcoming_events = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Scholar</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-5 mb-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="assets/images/default-user.jpg" alt="Profile" class="rounded-circle img-fluid" style="width: 120px;">
                        <h5 class="my-3"><?php echo $user_name; ?></h5>
                        <p class="text-muted mb-1"><?php echo ucfirst($user_role); ?></p>
                        <div class="d-flex justify-content-center mb-2">
                            <a href="profile.php" class="btn btn-primary">Edit Profile</a>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="dashboard.php" class="text-decoration-none"><i class="fas fa-tachometer-alt fa-fw me-3"></i>Dashboard</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="my-courses.php" class="text-decoration-none"><i class="fas fa-graduation-cap fa-fw me-3"></i>My Courses</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="browse-courses.php" class="text-decoration-none"><i class="fas fa-book fa-fw me-3"></i>Browse Courses</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="events.php" class="text-decoration-none"><i class="fas fa-calendar-alt fa-fw me-3"></i>Events</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="certificates.php" class="text-decoration-none"><i class="fas fa-certificate fa-fw me-3"></i>Certificates</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="settings.php" class="text-decoration-none"><i class="fas fa-cog fa-fw me-3"></i>Settings</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <a href="logout.php" class="text-decoration-none text-danger"><i class="fas fa-sign-out-alt fa-fw me-3"></i>Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Welcome Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title">Welcome back, <?php echo $user_name; ?>!</h2>
                        <p class="card-text">Continue your learning journey today. You have access to all your enrolled courses below.</p>
                        <div class="progress">
                            <?php
                            // Calculate overall progress
                            $total_progress = 0;
                            $course_count = mysqli_num_rows($enrolled_courses);
                            
                            if($course_count > 0) {
                                mysqli_data_seek($enrolled_courses, 0);
                                while($course = mysqli_fetch_assoc($enrolled_courses)) {
                                    $total_progress += $course['progress'];
                                }
                                $average_progress = round($total_progress / $course_count);
                            } else {
                                $average_progress = 0;
                            }
                            ?>
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $average_progress; ?>%;" aria-valuenow="<?php echo $average_progress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $average_progress; ?>%</div>
                        </div>
                        <p class="mt-2 small text-muted">Overall progress across all courses</p>
                    </div>
                </div>
                
                <!-- My Courses Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Courses</h5>
                        <a href="my-courses.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            mysqli_data_seek($enrolled_courses, 0);
                            if(mysqli_num_rows($enrolled_courses) > 0) {
                                while($course = mysqli_fetch_assoc($enrolled_courses)) {
                            ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="<?php echo $course['image']; ?>" class="card-img-top" alt="<?php echo $course['title']; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $course['title']; ?></h5>
                                        <div class="progress mb-2">
                                            <div class="progress-bar" role="progressbar" style="width: <?php echo $course['progress']; ?>%;" aria-valuenow="<?php echo $course['progress']; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $course['progress']; ?>%</div>
                                        </div>
                                        <a href="course.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm">Continue Learning</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            } else {
                            ?>
                            <div class="col-12">
                                <div class="alert alert-info">
                                    You are not enrolled in any courses yet. <a href="browse-courses.php">Browse courses</a> to get started.
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Recommended Courses Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recommended For You</h5>
                        <a href="browse-courses.php" class="btn btn-sm btn-outline-primary">Browse All</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if(mysqli_num_rows($recommended_courses) > 0) {
                                while($course = mysqli_fetch_assoc($recommended_courses)) {
                            ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="<?php echo $course['image']; ?>" class="card-img-top" alt="<?php echo $course['title']; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $course['title']; ?></h5>
                                        <p class="card-text"><?php echo substr($course['description'], 0, 80); ?>...</p>
                                        <a href="course-details.php?id=<?php echo $course['id']; ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            } else {
                            ?>
                            <div class="col-12">
                                <div class="alert alert-info">
                                    No recommended courses available at the moment.
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Events Section -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Upcoming Events</h5>
                        <a href="events.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            if(mysqli_num_rows($upcoming_events) > 0) {
                                while($event = mysqli_fetch_assoc($upcoming_events)) {
                                    $event_date = new DateTime($event['event_date']);
                            ?>
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <img src="<?php echo $event['image']; ?>" class="card-img-top" alt="<?php echo $event['title']; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $event['title']; ?></h5>
                                        <p class="card-text">
                                            <i class="far fa-calendar-alt"></i> <?php echo $event_date->format('M d, Y'); ?><br>
                                            <i class="far fa-clock"></i> <?php echo $event['time']; ?><br>
                                            <i class="fas fa-map-marker-alt"></i> <?php echo $event['location']; ?>
                                        </p>
                                        <a href="event-details.php?id=<?php echo $event['id']; ?>" class="btn btn-outline-primary btn-sm">Event Details</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            } else {
                            ?>
                            <div class="col-12">
                                <div class="alert alert-info">
                                    No upcoming events at the moment.
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>