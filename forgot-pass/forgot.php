<?php
@include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recover-submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists in the database
    $query = "SELECT * FROM user_tbl WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // If email exists, redirect to the reset password page with the email as a query parameter
        header("Location: /student-management-system/forgot-pass/reset.php?email=" . urlencode($email));
        exit();
    } else {
        // If email doesn't exist, show an alert
        echo "<script>alert('Email does not exist in the database.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="pass.css">
</head>
<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-center">
                    <h3><i class="fa fa-lock fa-4x"></i></h3>
                    <h2 class="text-center">Forgot Password?</h2>
                    <p>You can reset your password here.</p>
                    <div class="panel-body">
                        <form id="register-form" role="form" autocomplete="off" class="form" method="post">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                    <input id="email" name="email" placeholder="Email address" class="form-control" type="email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- Changed the submit input to a button -->
                                <button type="submit" name="recover-submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                            <input type="hidden" class="hide" name="token" id="token" value=""> 
                        </form>
                        <div class="back">
                            <label for="back">Go back to login? <a href="/student-management-system/index.php">Click here.</a></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
