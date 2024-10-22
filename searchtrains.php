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
$sql = "SELECT DISTINCT t.TrainID, t.TrainName, t.TrainNumber, s.DepartureTime, s.ArrivalTime, 
               r.SourceStation, r.DestinationStation, c.BaseFare, c.ClassType 
        FROM trains t 
        JOIN schedule s ON t.ScheduleID = s.ScheduleID
        JOIN route r ON t.RouteID = r.RouteID
        JOIN seat seat ON t.TrainID = seat.TrainID
        JOIN cost c ON seat.SeatID = c.SeatID
        WHERE r.SourceStation = ? 
        AND r.DestinationStation = ?
        AND seat.AvailabilityStatus = 'Available'";  // Check for available seats

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
                <li><a href="home.html">Home</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contactus.html">Contact Us</a></li>
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
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="train-card">
                        <div class="train-info">
                            <h2><?php echo htmlspecialchars($row['TrainName']); ?></h2>
                            <p>#<?php echo htmlspecialchars($row['TrainNumber']); ?> | Departs on: <span>S M T W T F S</span></p>
                            <div class="train-details">
                                <div class="time">
                                    <p><?php echo htmlspecialchars($row['DepartureTime']); ?>, <?php echo htmlspecialchars($departure_date); ?></p>
                                    <p><?php echo htmlspecialchars($row['SourceStation']); ?></p>
                                </div>
                                <div class="duration">
                                    <p>Duration: Approx. 5 hrs 50 mins</p>
                                    <a href="#" class="view-route">View route</a>
                                </div>
                                <div class="time">
                                    <p><?php echo htmlspecialchars($row['ArrivalTime']); ?>, <?php echo htmlspecialchars($departure_date); ?></p>
                                    <p><?php echo htmlspecialchars($row['DestinationStation']); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="booking-options">
                            <div class="class-option">
                                <p>Class: <?php echo htmlspecialchars($row['ClassType']); ?></p>
                                <p>Fare: ₹ <?php echo htmlspecialchars($row['BaseFare']); ?></p>
                                <button onclick="redirectToBooking('<?php echo htmlspecialchars($row['TrainID']); ?>', '<?php echo htmlspecialchars($row['ClassType']); ?>', '<?php echo htmlspecialchars($row['BaseFare']); ?>')">Book Now</button>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No trains available for this route on the selected date.</p>
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
