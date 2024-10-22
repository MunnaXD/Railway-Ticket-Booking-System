<?php
session_start();
include 'db.php'; // Include your database connection

// Get the form data
$from = $_POST['from']; // Source station name
$to = $_POST['to']; // Destination station name
$departure_date = $_POST['departure_date']; // Departure date

// Prepare a query to fetch relevant trains
$sql = "SELECT t.TrainID, t.TrainNumber, t.TrainName, s.DepartureTime, s.ArrivalTime, s.DaysOfOperation, r.SourceStation, r.DestinationStation 
        FROM trains t 
        JOIN schedule s ON t.ScheduleID = s.ScheduleID
        JOIN route r ON t.RouteID = r.RouteID
        JOIN stations st1 ON r.SourceStation = st1.StationID
        JOIN stations st2 ON r.DestinationStation = st2.StationID
        WHERE st1.StationName = ? 
        AND st2.StationName = ? 
        AND s.DaysOfOperation LIKE CONCAT('%', DAYNAME(?), '%')";

// Prepare the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $from, $to, $departure_date);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Trains</title>
    <link rel="stylesheet" href="searchtrain.css">
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <h1>Train Booking</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="#">Train Schedule</a></li>
                    <li><a href="#">Offers</a></li>
                    <li><a href="#">Account</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li><a href="logout.php" class="btn-signin">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn-signin">Sign In</a></li>
                        <li><a href="register.php" class="btn-register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <section class="train-search-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="train-card">
                    <div class="train-info">
                        <h2><?php echo htmlspecialchars($row['TrainName']) . " (#" . htmlspecialchars($row['TrainNumber']) . ")"; ?></h2>
                        <p>Departure: <?php echo htmlspecialchars($row['DepartureTime']) . ", " . htmlspecialchars($departure_date); ?></p>
                        <p>Arrival: <?php echo htmlspecialchars($row['ArrivalTime']); ?></p>
                        <p>Days of Operation: <?php echo htmlspecialchars($row['DaysOfOperation']); ?></p>
                    </div>
                    <div class="booking-options">
                        <div class="class-option">
                            <p>2A</p>
                            <span>₹2045</span>
                        </div>
                        <div class="class-option">
                            <p>3E</p>
                            <span>₹1450</span>
                        </div>
                        <div class="class-option">
                            <p>3A</p>
                            <span>₹1145</span>
                        </div>
                        <div class="class-option">
                            <p>2S</p>
                            <span>₹275</span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No trains available for the selected route and date.</p>
        <?php endif; ?>
    </section>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
