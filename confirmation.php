<?php
session_start();
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get Booking ID from the URL
if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);
} else {
    echo "Booking ID is missing.";
    exit();
}

// Retrieve booking and related details
$sql = "SELECT b.BookingID, b.TotalAmount, s.DepartureTime, s.ArrivalTime, 
        r.SourceStation, r.DestinationStation, t.TrainName, t.TrainNumber, 
        tr.TravellerName, tr.Gender, u.Email, se.SeatID, u.username
        FROM booking b
        JOIN seat se ON b.TrainID = se.TrainID
        JOIN schedule s ON b.ScheduleID = s.ScheduleID
        JOIN trains t ON b.TrainID = t.TrainID
        JOIN route r ON t.RouteID = r.RouteID
        JOIN travellers tr ON tr.BookingID = b.BookingID
        JOIN users u ON b.UserID = u.id
        WHERE b.BookingID = ? AND b.UserID = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booking_data = $result->fetch_assoc();
    // Fetch all required data from the booking
    $train_name = htmlspecialchars($booking_data['TrainName']);
    $train_number = htmlspecialchars($booking_data['TrainNumber']);
    $departure_time = htmlspecialchars($booking_data['DepartureTime']);
    $arrival_time = htmlspecialchars($booking_data['ArrivalTime']);
    $source_station = htmlspecialchars($booking_data['SourceStation']);
    $destination_station = htmlspecialchars($booking_data['DestinationStation']);
    $traveller_name = htmlspecialchars($booking_data['TravellerName']);
    $username=htmlspecialchars($booking_data['username']);
    $email = htmlspecialchars($booking_data['Email']);
    $seat = htmlspecialchars($booking_data['SeatID']);
    $total_amount = htmlspecialchars($booking_data['TotalAmount']); // Include total amount
} else {
    echo "Booking details not found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="confirmation.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
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
    
    <section class="confirmation-section">
        <div class="content">
            <h1>Online Booking Ticket Confirmation</h1>
            <p>Your ticket details are below. Safe travels!</p>

            <div class="ticket-container">
                <div class="ticket-header">
                    <div class="logo-container">
                        <img src="yup.png" alt="Vee Safar Logo" class="logo">
                    </div>
                    <h2><?php echo $train_name; ?> (<?php echo $train_number; ?>)</h2>
                    <p>Economy Class</p>
                </div>

                <div class="ticket-details">
                    <div class="departure-info">
                        <p><strong>Depart:</strong> <?php echo $departure_time; ?></p>
                        <p><strong>From:</strong> <?php echo $source_station; ?></p>
                    </div>

                    <div class="arrival-info">
                        <p><strong>Arrive:</strong> <?php echo $arrival_time; ?></p>
                        <p><strong>To:</strong> <?php echo $destination_station; ?></p>
                    </div>
                </div>

                <div class="qr-code-container">
                    <p>Scan the QR code for your ticket:</p>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=https://yourwebsite.com/confirmation.php?booking_id=<?php echo $booking_id; ?>&size=150x150" alt="QR Code" class="qr-code">
                </div>

                <div class="info-notes">
                    <p>Show e-tickets and passenger identities during check-in.</p>
                    <p>Please arrive at the boarding gate at least 30 minutes before departure time.</p>
                </div>

                <div class="passenger-info">
                    <div class="passenger-column">
                        <p><strong>Name:</strong> <?php echo $username; ?></p>
                        <p><strong>Email:</strong> <?php echo $email; ?></p>
                    </div>
                    <div class="passenger-column">
                        <p><strong>Booking Code:</strong> <?php echo $booking_id; ?></p>
                        <p><strong>Seat:</strong> <?php echo $seat; ?></p>
                    </div>
                </div>
            </div>

            <div class="download-share">
                <button id="btn-pdf" class="btn-download">Download Ticket</button>
                <button id="btn-share" class="btn-share">Share Ticket</button>
            </div>
        </div>
    </section>

    <script>
        // Function to download the ticket as PDF
        document.getElementById("btn-pdf").addEventListener("click", function () {
            const doc = new jsPDF();
    
            doc.setFontSize(22);
            doc.text("Booking Confirmation", 20, 20);
    
            doc.setFontSize(14);
            doc.text("<?php echo $train_name; ?> (<?php echo $train_number; ?>) - Economy Class", 20, 30);
            doc.text("Depart: <?php echo $departure_time; ?>", 20, 40);
            doc.text("From: <?php echo $source_station; ?>", 20, 50);
            doc.text("Arrive: <?php echo $arrival_time; ?>", 20, 60);
            doc.text("To: <?php echo $destination_station; ?>", 20, 70);
    
            doc.text("Passenger Name: <?php echo $traveller_name; ?>", 20, 80);
            doc.text("Email: <?php echo $email; ?>", 20, 90);
            doc.text("Booking Code: <?php echo $booking_id; ?>", 20, 100);
            doc.text("Seat: <?php echo $seat; ?>", 20, 110);
            doc.text("Total Amount: ₹<?php echo $total_amount; ?>", 20, 120);
    
            doc.text("Please show e-tickets and IDs during check-in.", 20, 130);
            doc.text("Arrive at least 30 minutes before departure.", 20, 140);
    
            const qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=https://yourwebsite.com/confirmation.php?booking_id=<?php echo $booking_id; ?>&size=150x150";
            toDataURL(qrCodeUrl, function(dataUrl) {
                doc.addImage(dataUrl, 'PNG', 20, 150, 40, 40);
                doc.save("ticket.pdf");
            });
        });

        function toDataURL(url, callback) {
            const xhr = new XMLHttpRequest();
            xhr.onload = function() {
                const reader = new FileReader();
                reader.onloadend = function() {
                    callback(reader.result);
                };
                reader.readAsDataURL(xhr.response);
            };
            xhr.open('GET', url);
            xhr.responseType = 'blob';
            xhr.send();
        }

        document.getElementById("btn-share").addEventListener("click", function () {
            const subject = "Your Ticket Confirmation";
            const body = "Please find attached your ticket confirmation. Booking ID: <?php echo $booking_id; ?>";
            const mailtoLink = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            window.location.href = mailtoLink;
        });
    </script>
</body>
</html>
