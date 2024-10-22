<?php
session_start();
include 'db.php'; // Include your database connection

// Get the form data
$from = $_POST['from'];  // Source station
$to = $_POST['to'];      // Destination station
$departure_date = $_POST['departure_date'];  // Date of travel

// Store the departure date in the session for later use (in ticket creation, etc.)
$_SESSION['departure_date'] = $departure_date;

// Prepare a query to fetch relevant trains
$sql = "SELECT t.TrainID, t.TrainNumber, t.TrainName, s.DepartureTime, s.ArrivalTime, r.SourceStation, r.DestinationStation 
        FROM trains t 
        JOIN schedule s ON t.ScheduleID = s.ScheduleID
        JOIN route r ON t.RouteID = r.RouteID
        WHERE r.SourceStation = ? 
        AND r.DestinationStation = ? ";

// Prepare the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $from, $to);

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
    <link rel="stylesheet" href="search_train_styles.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <img src="train.png" alt="Safar Logo" class="logo-img"> <!-- Add your logo image here -->
            <br>
            <h2>SAFAR</h2>
        </div>
        <nav>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="#">Holiday Packages</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contactus.html">Contact Us</a></li>
                <li><a href="#">Account Settings</a></li>
                <li><a href="#">Manage Booking</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- If user is logged in, show the logout button -->
                    <li><a href="logout.php" class="btn-signin">Logout</a></li>
                <?php else: ?>
                    <!-- If user is not logged in, show Sign In and Register buttons -->
                    <li><a href="login.html" class="btn-signin">Sign In</a></li>
                    <li><a href="register.html" class="btn-register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <div class="content-container">
        <div class="train-search-container">
            <!-- Train Card 1 -->
            <div class="train-card">
                <div class="train-info">
                    <h2>Hapa Duronto Express</h2>
                    <p>#12267 | Departs on: <span>S M T W T F S</span></p>
                    <div class="train-details">
                        <div class="time">
                            <p>11:00 PM, Wed</p>
                            <p>Mumbai Central (MMCT)</p>
                        </div>
                        <div class="duration">
                            <p>5 hrs 50 mins</p>
                            <a href="#" class="view-route">View route</a>
                        </div>
                        <div class="time">
                            <p>4:50 AM, Thu</p>
                            <p>Ahmedabad Jn (ADI)</p>
                        </div>
                    </div>
                    <p class="info-message">Your ticket will be booked on a longer route. Board at <strong>Mumbai Central</strong> and get down at <strong>Ahmedabad</strong></p>
                </div>
                <div class="booking-options">
                    <div class="class-option" onclick="redirectToBooking('2A')">
                        <p>2A</p>
                        <p>₹ 2045</p>
                        <span>AVAILABLE 23</span>
                        <small>Updated 7 hrs ago</small>
                    </div>
                    <div class="class-option" onclick="redirectToBooking('3E')">
                        <p>3E</p>
                        <p>₹ 1450</p>
                        <span>AVAILABLE 100</span>
                        <small>Updated 7 hrs ago</small>
                    </div>
                    <div class="class-option" onclick="redirectToBooking('3A')">
                        <p>3A</p>
                        <p>₹ 1145</p>
                        <span>AVAILABLE 107</span>
                        <small>Updated 2 hrs ago</small>
                    </div>
                    <div class="class-option" onclick="redirectToBooking('2S')">
                        <p>2S</p>
                        <p>₹ 275</p>
                        <span>AVAILABLE 10</span>
                        <small>Updated 7 hrs ago</small>
                    </div>
                </div>
                
            </div>
            <div class="train-card">
                <div class="train-info">
                    <h2>Adi Shatabdi Express</h2>
                    <p>#12009 | Departs on: <span>S M T W T F S</span></p>
                    <div class="train-details">
                        <div class="time">
                            <p>6:20 AM, Wed</p>
                            <p>Mumbai Central (MMCT)</p>
                        </div>
                        <div class="duration">
                            <p>6 hrs 20 mins</p>
                            <a href="#" class="view-route">View route</a>
                        </div>
                        <div class="time">
                            <p>12:40 PM, Wed</p>
                            <p>Ahmedabad Jn (ADI)</p>
                        </div>
                    </div>
                </div>
                <div class="booking-options">
                    <div class="class-option" onclick="bookNow('CC')">
                        <p>CC</p>
                        <p>₹ 1370</p>
                        <span>AVAILABLE 326</span>
                        <small>Free Cancellation</small>
                    </div>
                    <div class="class-option" onclick="bookNow('EA')">
                        <p>EA</p>
                        <p>₹ 2385</p>
                        <span>AVAILABLE 13</span>
                        <small>Free Cancellation</small>
                    </div>
                    <div class="class-option" onclick="bookNow('EC')">
                        <p>EC</p>
                        <p>₹ 2085</p>
                        <span>AVAILABLE 39</span>
                        <small>Free Cancellation</small>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <script src="search_train_script.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
