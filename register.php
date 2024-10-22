<?php
session_start();
include 'db.php'; // Include your database connection code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $countryCode = mysqli_real_escape_string($conn, $_POST['country-code']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);

    // Check if the username already exists
    $checkUser = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $checkUser);

    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists. Please try a different one.";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user details into the database
        $query = "INSERT INTO users (username, fullname, password, email, country_code, mobile) 
                  VALUES ('$username', '$fullname', '$hashedPassword', '$email', '$countryCode', '$mobile')";

        if (mysqli_query($conn, $query)) {
            echo "Registration successful!";
            header("Location: login.html"); // Redirect to login page
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
