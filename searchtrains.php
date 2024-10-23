<?php
session_start();
include 'db.php'; // Include your database connection

// Get form data from search input (source, destination, date)
$from = $_POST['from'];  // Source station
$to = $_POST['to'];      // Destination station
$departure_date = $_POST['departure_date'];  // Date of travel

// Store the departure date in the session for later use
$_SESSION['departure_date'] = $departure_date;

// Prepare a query to fetch relevant trains with costs
$sql = "SELECT t.TrainID, t.TrainName, t.TrainNumber, s.DepartureTime, s.ArrivalTime, 
               r.SourceStation, r.DestinationStation, c.BaseFare, c.ClassType 
        FROM trains t 
        JOIN schedule s ON t.ScheduleID = s.ScheduleID
        JOIN route r ON t.RouteID = r.RouteID
        JOIN seat seat ON t.TrainID = seat.TrainID
        JOIN cost c ON seat.SeatID = c.SeatID
        WHERE r.SourceStation = ? 
        AND r.DestinationStation = ?
        AND seat.AvailabilityStatus = 'Available'
        ORDER BY t.TrainID, c.ClassType";  // Order by TrainID and ClassType

$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $from, $to);
$stmt->execute();
$result = $stmt->get_result();

// Check for errors in execution
if ($stmt->error) {
    echo "Database query error: " . $stmt->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Trains</title>
    <link rel="stylesheet" href="search_train_styles.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <img src="train.png" alt="Safar Logo" class="logo-img">
            <br>
            <h2>SAFAR</h2>
        </div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php" class="btn-signin">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.html" class="btn-signin">Sign In</a></li>
                    <li><a href="register.html" class="btn-register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <div class="content-container">
        <div class="train-search-container">
            <?php if ($result->num_rows > 0): ?>
                <?php 
                $currentTrainID = null;
                $currentTrainName = null;
                $currentTrainNumber = null;
                $trainClasses = []; // Array to hold classes for a train

                while($row = $result->fetch_assoc()): 
                    // Check if this is a new train or the same as the previous
                    if ($currentTrainID !== $row['TrainID']) {
                        // If it's a new train, display the previous train's information (if any)
                        if ($currentTrainID !== null) {
                            // Display classes and booking options for the last train
                            foreach ($trainClasses as $class) {
                                echo "<div class='class-option'>
                                    <p>Class: " . htmlspecialchars($class['ClassType']) . "</p>
                                    <p>Fare: ₹ " . htmlspecialchars($class['BaseFare']) . "</p>
                                    <button onclick=\"redirectToBooking('{$currentTrainID}', '" . htmlspecialchars($class['ClassType']) . "', '" . htmlspecialchars($class['BaseFare']) . "')\">Book Now</button>
                                </div>";
                            }
                            echo "</div>"; // Close the booking-options div
                        }

                        // Reset and start a new train's information
                        $currentTrainID = $row['TrainID'];
                        $currentTrainName = $row['TrainName'];
                        $currentTrainNumber = $row['TrainNumber'];
                        $trainClasses = []; // Clear the previous train's classes

                        // Display the new train card
                        echo "<div class='train-card'>
                            <div class='train-info'>
                                <h2>" . htmlspecialchars($currentTrainName) . "</h2>
                                <p>#" . htmlspecialchars($currentTrainNumber) . " | Departs on: <span>S M T W T F S</span></p>
                                <div class='train-details'>
                                    <div class='time'>
                                        <p>" . htmlspecialchars($row['DepartureTime']) . ", " . htmlspecialchars($departure_date) . "</p>
                                        <p>" . htmlspecialchars($row['SourceStation']) . "</p>
                                    </div>
                                    <div class='duration'>
                                        <p>Duration: Approx. 5 hrs 50 mins</p>
                                        
                                    </div>
                                    <div class='time'>
                                        <p>" . htmlspecialchars($row['ArrivalTime']) . ", " . htmlspecialchars($departure_date) . "</p>
                                        <p>" . htmlspecialchars($row['DestinationStation']) . "</p>
                                    </div>
                                </div>
                            </div>
                            <div class='booking-options'>";
                    }

                    // Add the current row's class information to the array
                    $trainClasses[] = [
                        'ClassType' => $row['ClassType'],
                        'BaseFare' => $row['BaseFare']
                    ];
                endwhile;

                // Display the last train's classes (if any)
                if ($currentTrainID !== null) {
                    foreach ($trainClasses as $class) {
                        echo "<div class='class-option'>
                            <p>Class: " . htmlspecialchars($class['ClassType']) . "</p>
                            <p>Fare: ₹ " . htmlspecialchars($class['BaseFare']) . "</p>
                            <button onclick=\"redirectToBooking('{$currentTrainID}', '" . htmlspecialchars($class['ClassType']) . "', '" . htmlspecialchars($class['BaseFare']) . "')\">Book Now</button>
                        </div>";
                    }
                    echo "</div>"; // Close the booking-options div
                }
                ?>
            <?php else: ?>
                <div class="no-trains-message">
                    <p>No trains available for this route on the selected date.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function redirectToBooking(trainID, seatClass, baseFare) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'bookticket.php';

            const trainIdField = document.createElement('input');
            trainIdField.type = 'hidden';
            trainIdField.name = 'train_id';
            trainIdField.value = trainID;
            form.appendChild(trainIdField);

            const seatClassField = document.createElement('input');
            seatClassField.type = 'hidden';
            seatClassField.name = 'seat_class';
            seatClassField.value = seatClass;
            form.appendChild(seatClassField);

            const baseFareField = document.createElement('input');
            baseFareField.type = 'hidden';
            baseFareField.name = 'base_fare';
            baseFareField.value = baseFare;
            form.appendChild(baseFareField);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
