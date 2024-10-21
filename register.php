<?php
session_start();
include 'db.php'; // Include your database connection code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the username already exists
    $checkUser = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $checkUser);

    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists. Please try a different one.";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user details into the database
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";

        if (mysqli_query($conn, $query)) {
            echo "Registration successful!";
            header("Location: login.html"); // Redirect to login page
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
