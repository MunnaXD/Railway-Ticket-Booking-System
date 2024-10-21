<?php
include 'db.php'; // Include your database connection script

// SQL query to create the database (if not already created)
$sql = "CREATE DATABASE IF NOT EXISTS Safar";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully!<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

// Select the database
mysqli_select_db($conn, 'Safar');

// SQL query to create the users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Users table created successfully!<br>";
} else {
    echo "Error creating users table: " . mysqli_error($conn);
}

// Close the connection
mysqli_close($conn);
?>
