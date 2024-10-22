<?php
session_start();
include 'db.php';

// Check if the necessary POST variables are set
if (!isset($_POST['train_id']) || !isset($_POST['seat_class']) || !isset($_POST['base_fare'])) {
    header("Location: searchtrains.php");
    exit();
}

$_SESSION['selected_train_id'] = $_POST['train_id'];
$_SESSION['selected_seat_class'] = $_POST['seat_class'];

$train_id = $_SESSION['selected_train_id'];
$seat_class = $_SESSION['selected_seat_class'];

$sql_train = "SELECT t.TrainName AS train_name, t.TrainNumber AS train_number, 
              s.DepartureTime AS departure_time, s.ArrivalTime AS arrival_time, 
              r.SourceStation AS departure_station, r.DestinationStation AS arrival_station
              FROM trains t 
              JOIN schedule s ON t.ScheduleID = s.ScheduleID
              JOIN route r ON t.RouteID = r.RouteID
              WHERE t.TrainID = ?";
$stmt_train = $conn->prepare($sql_train);
$stmt_train->bind_param("i", $train_id);
$stmt_train->execute();
$train_result = $stmt_train->get_result();
$train_data = $train_result->fetch_assoc();

if (!$train_data) {
    echo "Train details not found.";
    exit();
}

$sql_seat = "SELECT SeatID FROM seat WHERE TrainID = ? AND ClassType = ?";
$stmt_seat = $conn->prepare($sql_seat);
$stmt_seat->bind_param("is", $train_id, $seat_class);
$stmt_seat->execute();
$seat_result = $stmt_seat->get_result();
$seat_data = $seat_result->fetch_assoc();

if (!$seat_data) {
    echo "No available seats found for the selected train and class.";
    exit();
}

$sql_cost = "SELECT BaseFare, DynamicFare, Tax, ReservationCharge, SuperfastCharge 
             FROM cost WHERE ClassType = ?";
$stmt_cost = $conn->prepare($sql_cost);
$stmt_cost->bind_param("s", $seat_class);
$stmt_cost->execute();
$cost_result = $stmt_cost->get_result();
$cost_data = $cost_result->fetch_assoc();

if (!$cost_data) {
    echo "Cost details not found for the selected seat class.";
    exit();
}

$total_price = $cost_data['BaseFare'] + $cost_data['DynamicFare'] + $cost_data['Tax'] + 
               $cost_data['ReservationCharge'] + $cost_data['SuperfastCharge'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Review Your Booking</title>
    <link rel="stylesheet" href="bookticket.css">
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

    <div class="booking-details-section">
        <div class="booking-container">
            <h2 class="centered-heading">Review Your Booking</h2>
            <div class="details-section">
                <div class="train-info-box">
                    <h2 class="centered-heading">Train Details</h2>
                    <div class="train-details">
                        <p><strong>Train:</strong> <?php echo htmlspecialchars($train_data['train_name']); ?> (#<?php echo htmlspecialchars($train_data['train_number']); ?>)</p>
                        <p><strong>Departure:</strong> <?php echo htmlspecialchars($train_data['departure_time']); ?> (<?php echo htmlspecialchars($train_data['departure_station']); ?>)</p>
                        <p><strong>Arrival:</strong> <?php echo htmlspecialchars($train_data['arrival_time']); ?> (<?php echo htmlspecialchars($train_data['arrival_station']); ?>)</p>
                        <p><strong>Seat Class:</strong> <?php echo htmlspecialchars($seat_class); ?></p>
                    </div>
                </div>
                
                <div class="cost-details-box">
                    <h2 class="centered-heading">Cost Details</h2>
                    <p><strong>Base fare:</strong> ₹<?php echo htmlspecialchars($cost_data['BaseFare']); ?></p>
                    <p><strong>Dynamic fare:</strong> ₹<?php echo htmlspecialchars($cost_data['DynamicFare']); ?></p>
                    <p><strong>Tax:</strong> ₹<?php echo htmlspecialchars($cost_data['Tax']); ?></p>
                    <p><strong>Reservation charge:</strong> ₹<?php echo htmlspecialchars($cost_data['ReservationCharge']); ?></p>
                    <p><strong>Superfast charge:</strong> ₹<?php echo htmlspecialchars($cost_data['SuperfastCharge']); ?></p>
                    <h3>Total Price per adult: ₹<?php echo htmlspecialchars($total_price); ?></h3>
                </div>
            </div>

            <div class="traveller-info-box">
                <h2>Add Travellers</h2>
                <div class="traveller-form">
                    <div class="form-group">
                        <label for="travellerName">Name</label>
                        <input type="text" id="travellerName" placeholder="Enter Traveller Name" required>
                    </div>
                    <div class="form-group">
                        <label for="travellerAge">Age</label>
                        <input type="number" id="travellerAge" placeholder="Enter Age" required>
                    </div>
                    <div class="form-group">
                        <label for="travellerGender">Gender</label>
                        <select id="travellerGender">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="travellerBerth">Berth Preference</label>
                        <select id="travellerBerth">
                            <option value="No Berth Preference">No Berth Preference</option>
                            <option value="Lower">Lower</option>
                            <option value="Middle">Middle</option>
                            <option value="Upper">Upper</option>
                            <option value="Side Lower">Side Lower</option>
                            <option value="Side Upper">Side Upper</option>
                        </select>
                    </div>
                    <button class="btn-add" type="button" onclick="addTraveller()">Add Traveller</button>
                </div>

                <div class="traveller-list">
                    <h3>Travellers List:</h3>
                    <table id="travellerTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Berth Preference</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="contact-info-box">
                <h2>Contact Information</h2>
                <form action="process_booking.php" method="POST" id="bookingForm">
                    <input type="hidden" name="train_id" value="<?php echo htmlspecialchars($train_id); ?>">
                    <input type="hidden" name="seat_class" value="<?php echo htmlspecialchars($seat_class); ?>">
                    <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($total_price); ?>">
                    <input type="hidden" name="travellers" id="travellers">
                    
                    <div class="form-group">
                        <label for="contactEmail">Email ID</label>
                        <input type="email" name="contactEmail" id="contactEmail" placeholder="Enter Email ID" required>
                    </div>
                    <div class="form-group">
                        <label for="contactMobile">Mobile Number</label>
                        <input type="text" name="contactMobile" id="contactMobile" placeholder="Enter Mobile Number" required>
                    </div>
                    <button type="submit" class="btn-pay">Pay & Book Now</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let travellers = [];

        function addTraveller() {
            const name = document.getElementById('travellerName').value;
            const age = document.getElementById('travellerAge').value;
            const gender = document.getElementById('travellerGender').value;
            const berth = document.getElementById('travellerBerth').value;

            if (!name || !age || !gender) {
                alert('Please fill in all fields.');
                return;
            }

            const traveller = {
                name,
                age,
                gender,
                berth
            };
            travellers.push(traveller);

            const table = document.getElementById('travellerTable').getElementsByTagName('tbody')[0];
            const row = table.insertRow();
            row.innerHTML = `<td>${name}</td><td>${age}</td><td>${gender}</td><td>${berth}</td>`;

            document.getElementById('travellerName').value = '';
            document.getElementById('travellerAge').value = '';
            document.getElementById('travellerGender').selectedIndex = 0;
            document.getElementById('travellerBerth').selectedIndex = 0;

            document.getElementById('travellers').value = JSON.stringify(travellers);
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
