<?php
session_start();
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure required data is available
    if (!isset($_SESSION['user_id'], $_POST['train_id'], $_POST['seat_class'], $_POST['total_price'], $_POST['travellers'])) {
        echo "Required information is missing.";
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $train_id = $_POST['train_id'];
    $seat_class = $_POST['seat_class'];
    $total_price = $_POST['total_price'];
    $travellers = json_decode($_POST['travellers'], true);
    $booking_date = date('Y-m-d');
    $booking_status = 'Confirmed'; // Example status, adjust as needed

    // Fetch ScheduleID based on TrainID
    $sql_schedule = "SELECT ScheduleID FROM schedule WHERE TrainID = ?";
    $stmt_schedule = $conn->prepare($sql_schedule);
    $stmt_schedule->bind_param("i", $train_id);
    $stmt_schedule->execute();
    $schedule_result = $stmt_schedule->get_result();
    $schedule_data = $schedule_result->fetch_assoc();
    
    if (!$schedule_data) {
        echo "Schedule information not found for the selected train.";
        exit();
    }

    $schedule_id = $schedule_data['ScheduleID'];

    // Create a new booking entry in the bookings table
    $sql_booking = "INSERT INTO booking (UserID, TrainID, ScheduleID, BookingStatus, BookingDate, TotalAmount)
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_booking = $conn->prepare($sql_booking);
    $stmt_booking->bind_param("iiisss", $user_id, $train_id, $schedule_id, $booking_status, $booking_date, $total_price);

    if ($stmt_booking->execute()) {
        // Get the newly created BookingID
        $booking_id = $stmt_booking->insert_id;

        // Insert each traveller into the travellers table
        foreach ($travellers as $traveller) {
            $traveller_name = $traveller['name'];
            $traveller_age = $traveller['age'];
            $traveller_gender = $traveller['gender'];
            $traveller_berth = $traveller['berth'];

            $sql_traveller = "INSERT INTO travellers (BookingID, TravellerName, Age, Gender, BerthPreference)
                              VALUES (?, ?, ?, ?, ?)";
            $stmt_traveller = $conn->prepare($sql_traveller);
            $stmt_traveller->bind_param("isiss", $booking_id, $traveller_name, $traveller_age, $traveller_gender, $traveller_berth);
            $stmt_traveller->execute();
        }

        // Redirect to a confirmation page or display a success message
        header("Location: payment.php?booking_id=" . $booking_id);
        exit();
    } else {
        echo "Failed to create a booking. Please try again.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
