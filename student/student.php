<?php
session_start();
require '../connect.php'; // Update path as per your directory structure

// Redirect if user is not logged in or not a student
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'student') {
    header("Location: /student-management-system/index.php");
    exit;
}

// Fetch logged-in student information
$username = $_SESSION['username'];
$query = "SELECT name, username, email FROM user_tbl WHERE username = '$username' AND user_type = 'student'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $studentInfo = mysqli_fetch_assoc($result);
    // Store email in session for password reset verification
    $_SESSION['email'] = $studentInfo['email'];
} else {
    echo "<script>alert('Failed to fetch student information.');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="style.css">

    <title>Students Page</title>
    <style>
        .card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }
        .card h2 {
            margin: 0;
            color: #333;
        }
        .card p {
            color: #666;
        }
        .card .change-password-btn {
            margin-top: 15px;
            display: inline-block;
            background: transparent;
            color: #007bff;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-decoration: underline;
        }
        .card .change-password-btn:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <span class="text">Student Management System</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
                <a href="/student-management-system/student/student.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">My Information</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Personal Information</h1>
                </div>
                <li>
                    <a href="/student-management-system/logout.php" class="logout">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
                    </a>
                </li>
            </div>

            <div class="card">
                <h2><?php echo htmlspecialchars($studentInfo['name']); ?></h2>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($studentInfo['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($studentInfo['email']); ?></p>
                <button class="change-password-btn" 
                        onclick="window.location.href='/student-management-system/forgot-pass/reset.php?email=<?php echo urlencode($studentInfo['email']); ?>&from=student';">
                    Change Password
                </button>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="script.js"></script>
</body>
</html>
