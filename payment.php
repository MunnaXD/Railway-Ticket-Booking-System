<?php
// db_connection.php
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    // Ticket details
    $train_id = $_SESSION['selected_train_id'];
    $seat_class = $_SESSION['selected_seat_class'];
    $user_id = $_SESSION['user_id']; // Assuming the user is logged in and user ID is stored in session

    // Save traveller information (multiple travellers)
    $traveller_names = $_POST['traveller_names']; // Array of names
    $traveller_ages = $_POST['traveller_ages']; // Array of ages
    $traveller_genders = $_POST['traveller_genders']; // Array of genders
    $traveller_berths = $_POST['traveller_berths']; // Array of berth preferences

    for ($i = 0; $i < count($traveller_names); $i++) {
        $sql_traveller = "INSERT INTO travellers (user_id, train_id, seat_class, traveller_name, age, gender, berth_preference) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_traveller = $conn->prepare($sql_traveller);
        $stmt_traveller->bind_param("iisssss", $user_id, $train_id, $seat_class, $traveller_names[$i], $traveller_ages[$i], $traveller_genders[$i], $traveller_berths[$i]);
        $stmt_traveller->execute();
    }

    // Save ticket information
    $sql_ticket = "INSERT INTO tickets (user_id, train_id, seat_class, total_price) VALUES (?, ?, ?, ?)";
    $total_price = $_POST['total_price'];
    $stmt_ticket = $conn->prepare($sql_ticket);
    $stmt_ticket->bind_param("iiss", $user_id, $train_id, $seat_class, $total_price);
    $stmt_ticket->execute();

    // Redirect to payment page
    header("Location: payment.php");
    exit();
}
?>


