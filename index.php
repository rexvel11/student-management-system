<?php
session_start(); // Start the session

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

@include 'connect.php';

$errors = [];
$name = $username = $email = ""; // Initialize variables

// Handle session timeout (10 minutes = 600 seconds)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 600)) {
    session_unset();
    session_destroy();
    header("Location: /student-management-system/index.php");
    exit;
}
$_SESSION['last_activity'] = time(); // Update last activity time

// Registration Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['uName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['passsword']);
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password length
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (empty($errors)) {
        // Generate a unique token
        $token = bin2hex(random_bytes(16)); // Generates a secure 32-character token
        $verified = 0; // Default: not verified
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert data into the user_tbl
        $query = "INSERT INTO user_tbl (name, username, email, password, token, verified, user_type) 
                  VALUES ('$name', '$username', '$email', '$hashedPassword', '$token', '$verified', '$user_type')";

        if (mysqli_query($conn, $query)) {
            // Send verification email
            sendVerificationEmail($email, $token);

            echo "<script>alert('Registration successful. Please verify your email!');</script>";
        } else {
            echo "<script>alert('Error during registration: " . mysqli_error($conn) . "');</script>";
        }
    }
}

function sendVerificationEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hoshinayue@gmail.com';  // Your email
        $mail->Password   = 'erqq pupx mesc mevf';  // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('hoshinayue@gmail.com', 'Student Management System');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body    = "Click the link to verify your email: <a href='http://localhost/student-management-system/verify.php?token=$token'>Verify Email</a>";
        $mail->AltBody = "Click the link to verify your email: http://localhost/student-management-system/verify.php?token=$token";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Login Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['uName']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query the database for the user
    $query = "SELECT * FROM user_tbl WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if ($user['verified'] == 0) {
            echo "<script>alert('Please verify your account before logging in.');</script>";
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['last_activity'] = time(); // Set session start time

            switch ($user['user_type']) {
                case 'admin':
                    header('Location: /student-management-system/admin/index.php');
                    break;
                case 'student':
                    header('Location: /student-management-system/student/student.php');
                    break;
                case 'professor':
                    header('Location: /student-management-system/professor/professor.php');
                    break;
                default:
                    echo "<script>alert('Invalid user type.');</script>";
            }
            exit;
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('User does not exist.');</script>";
    }
}

// Restriction Logic
$currentPage = $_SERVER['REQUEST_URI'];

if (strpos($currentPage, '/admin/') !== false && (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin')) {
    header("Location: /student-management-system/index.php");
    exit;
}

if (strpos($currentPage, '/student/') !== false && (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student')) {
    header("Location: /student-management-system/index.php");
    exit;
}

if (strpos($currentPage, '/professor/') !== false && (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'professor')) {
    header("Location: /student-management-system/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="styles.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <style>
.input-box select {
    width: 100%;
    height: 100%;
    background: transparent;
    border: none;
    outline: none;
    border: 2px solid rgba(255, 255, 255, .2);
    border-radius: 40px;
    font-size: 16px;
    color: black;
    padding: 0 45px 0 20px; /* Adjust padding for the default arrow */
    text-align: left; /* Aligns text to the left */
    text-align-last: left; /* Ensures the selected option is left-aligned */
    appearance: auto; /* Enables default dropdown arrow */
    -webkit-appearance: menulist; /* For WebKit browsers like Chrome/Safari */
    -moz-appearance: menulist; /* For Firefox */
    cursor: pointer; /* Indicates interactiveness */
}
</style>


</head>
<body>
        <div class="container">
            <img src="/student-management-system/img/sorsu-removebg-preview.png" alt="sorsulogo" class="logo">
            <h1 class="system-title">Student Management <br>System</h1>
        </div>   

        <div class="wrapper">
            <form action="" method="POST" class="Login-form" id="login">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" name="uName" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox">Remember Me</label>
                    <a href="/student-management-system/forgot-pass/forgot.php">Forgot Password</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <div class="register-link">
                    <p>Don't have an account? <button class="gew" id="signUpButton">Sign Up</button></p>
                </div>
            </form>


            <form action="" method="POST" class="Register-form" id="register" style="display: none;">
                <h1>Sign Up</h1>
                <div class="input-box">
                    <input type="text" name="name" placeholder="Name" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="uName" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="passsword" placeholder="Password" id="password" required>
                    <i class='bx bxs-lock-alt'></i>
                    <p id="password-warning" style="color: red; display: none;">Password must be at least 8 characters long.</p>
                </div>
                <div class="input-box">
                    <select name="user_type" required>
                        <option value="student" <?= (isset($_POST['user_type']) && $_POST['user_type'] == 'student') ? 'selected' : '' ?>>Student</option>
                        <option value="professor" <?= (isset($_POST['user_type']) && $_POST['user_type'] == 'professor') ? 'selected' : '' ?>>Teacher</option>
                    </select>
                </div>
                <input type="hidden" name="register" value="1">
                <button type="submit" class="btn">Sign Up</button>
                <div class="register-link">
                    <p>Already have an account? <button class="gew" id="signInButton">Sign In</button></p>
                </div>
            </form>

        </div>
  <script src="script.js"></script>
</body>
</html>