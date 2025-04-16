<?php
require_once 'includes/config.php';

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

// Process login form
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    // Validate email
    if(empty($email)) {
        $error = "Please enter your email.";
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }
    
    // Validate password
    if(empty($password)) {
        $error = "Please enter your password.";
    }
    
    // If no errors, attempt login
    if(empty($error)) {
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = ? AND status = 'active'";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                // Check if user exists
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $name, $email, $hashed_password, $role);
                    
                    if(mysqli_stmt_fetch($stmt)) {
                        // Verify password
                        if(password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION['user_id'] = $id;
                            $_SESSION['name'] = $name;
                            $_SESSION['email'] = $email;
                            $_SESSION['role'] = $role;
                            
                            // Log activity
                            log_activity($id, 'login', 'User logged in');
                            
                            // Redirect based on role
                            if($role == 'admin') {
                                header("Location: admin/index.php");
                            } else if($role == 'instructor') {
                                header("Location: instructor/index.php");
                            } else {
                                header("Location: dashboard.php");
                            }
                            exit;
                        } else {
                            $error = "Invalid email or password.";
                        }
                    }
                } else {
                    $error = "Invalid email or password.";
                }
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
    <title>Login - Scholar</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container {
            max-width: 450px;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2rem;
        }
        .login-form .form-control {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            background-color: var(--primary-color);
            border: none;
            color: #fff;
            font-weight: 600;
            margin-top: 10px;
        }
        .login-btn:hover {
            background-color: var(--secondary-color);
        }
        .login-links {
            text-align: center;
            margin-top: 20px;
        }
        .login-links a {
            color: var(--primary-color);
            text-decoration: none;
        }
        .login-links a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-logo">SCHOLAR</div>
            
            <?php if(!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="login-links">
                <a href="forgot-password.php">Forgot Password?</a> | <a href="register.php">Create Account</a>
            </div>
            
            <div class="mt-4 text-center">
                <a href="index.php" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>