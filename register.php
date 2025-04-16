<?php
require_once 'includes/config.php';

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

// Process registration form
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'student'; // Default role for new registrations
    
    // Validate name
    if(empty($name)) {
        $error = "Please enter your name.";
    }
    
    // Validate email
    if(empty($email)) {
        $error = "Please enter your email.";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) > 0) {
                    $error = "This email is already registered.";
                }
            } else {
                $error = "Oops! Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty($password)) {
        $error = "Please enter a password.";
    } else if(strlen($password) < 6) {
        $error = "Password must have at least 6 characters.";
    }
    
    // Validate confirm password
    if(empty($confirm_password)) {
        $error = "Please confirm your password.";
    } else if($password != $confirm_password) {
        $error = "Passwords do not match.";
    }
    
    // If no errors, register user
    if(empty($error)) {
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);
            
            if(mysqli_stmt_execute($stmt)) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Oops! Something went wrong. Please try again later.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Scholar</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .register-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .register-logo {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2rem;
        }
        .register-form .form-control {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .register-btn {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            background-color: var(--primary-color);
            border: none;
            color: #fff;
            font-weight: 600;
            margin-top: 10px;
        }
        .register-btn:hover {
            background-color: var(--secondary-color);
        }
        .register-links {
            text-align: center;
            margin-top: 20px;
        }
        .register-links a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .register-links a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }
        .success-message {
            color: #28a745;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-logo">SCHOLAR</div>
            
            <?php if(!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if(!empty($success)): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form class="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="register-btn">Create Account</button>
            </form>
            
            <div class="register-links">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
            
            <div class="mt-4 text-center">
                <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>