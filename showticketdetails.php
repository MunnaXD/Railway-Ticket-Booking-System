<?php
session_start();
include 'db_connection.php'; // Include your database connection file

// Fetch train details from the database based on selected train
$train_id = $_SESSION['selected_train_id']; // This should be set earlier in the process

$sql_train = "SELECT train_name, train_number, departure_time, departure_date, arrival_time, arrival_date, departure_station, arrival_station FROM trains WHERE train_id = ?";
$stmt_train = $conn->prepare($sql_train);
$stmt_train->bind_param("i", $train_id);
$stmt_train->execute();
$train_result = $stmt_train->get_result();
$train_data = $train_result->fetch_assoc();

// Fetch cost details from the database based on the selected train and seat class
$seat_class = $_SESSION['selected_seat_class']; // This should be set earlier in the process

$sql_cost = "SELECT base_fare, dynamic_fare, tax, reservation_charge, superfast_charge FROM costs WHERE train_id = ? AND seat_class = ?";
$stmt_cost = $conn->prepare($sql_cost);
$stmt_cost->bind_param("is", $train_id, $seat_class);
$stmt_cost->execute();
$cost_result = $stmt_cost->get_result();
$cost_data = $cost_result->fetch_assoc();
?>


