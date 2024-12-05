<?php
@include 'connect.php';

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($conn, $_GET['token']); // Get the token from URL

    // Query to select the user with the provided token
    $query = "SELECT * FROM user_tbl WHERE token = '$token' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Check if the user is already verified
        if ($user['verified'] == 1) {
            echo "Your email is already verified. Please log in.";
        } else {
            // Update the 'verified' column for this specific user
            $updateQuery = "UPDATE user_tbl SET verified = 1 WHERE token = '$token'";

            if (mysqli_query($conn, $updateQuery)) {
                echo "Your email has been verified successfully. You can now log in.";
            } else {
                echo "There was an error verifying your email: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Invalid token. Verification failed.";
    }
} else {
    echo "No token provided.";
}
?>
