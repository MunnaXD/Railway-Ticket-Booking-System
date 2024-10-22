<?php
session_start();
include 'db.php'; // Include your database connection

// Check if user is logged in and BookingID is set
if (!isset($_SESSION['user_id'], $_POST['booking_id'])) {
    echo "Unauthorized access.";
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = $_POST['booking_id'];
$payment_method = $_POST['payment-method'];
$card_number = $_POST['card-number'];
$card_name = $_POST['card-name'];
$expiry_date = $_POST['expiry-date'];
$cvv = $_POST['cvv'];

// Process the payment here
$payment_success = true; // Assume payment was successful

if ($payment_success) {
    // Update booking status in the database if needed
    $sql = "UPDATE booking SET BookingStatus = 'Confirmed' WHERE BookingID = ? AND UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $user_id);
    
    if ($stmt->execute()) {
        // Redirect to confirmation page
        header("Location: confirmation.php?booking_id=$booking_id");
        exit();
    } else {
        echo "Error updating booking status. Please try again.";
    }
} else {
    echo "Payment failed. Please try again.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="payment_styles.css">
    <title>Payment Page</title>
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
                <li><a href="#">Holiday Packages</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contactus.html">Contact Us</a></li>
                <li><a href="#">Account Settings</a></li>
                <li><a href="#">Manage Booking</a></li>
                <li><a href="logout.php" class="btn-signout">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="payment-container-wrapper">
        <div class="payment-container">
            <h2>Payment Details</h2>
            <form class="payment-form" action="confirmation.php" method="POST" onsubmit="return validateForm()">
                <input type="hidden" name="booking_id" value="<?php echo $_GET['booking_id']; ?>"> <!-- Include Booking ID -->

                <label for="payment-method">Payment Method</label>
                <select id="payment-method" name="payment-method" required>
                    <option value="">Select a payment method</option>
                    <option value="credit-card">Credit Card</option>
                    <option value="debit-card">Debit Card</option>
                    <option value="net-banking">Net Banking</option>
                    <option value="upi">UPI</option>
                </select>

                <div class="card-details">
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" name="card-number" placeholder="Enter your card number" 
                           required pattern="\d{16}" title="Card number must be 16 digits">

                    <label for="card-name">Name on Card</label>
                    <input type="text" id="card-name" name="card-name" placeholder="Enter the name on card" 
                           required minlength="2" title="Name must be at least 2 characters long">

                    <div class="expiry-cvv">
                        <div>
                            <label for="expiry-date">Expiry Date</label>
                            <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" 
                                   required pattern="^(0[1-9]|1[0-2])\/?([0-9]{2})$" 
                                   title="Expiry date must be in MM/YY format">
                        </div>
                        <div>
                            <label for="cvv">CVV</label>
                            <input type="password" id="cvv" name="cvv" placeholder="CVV" 
                                   required pattern="\d{3}" title="CVV must be 3 digits">
                        </div>
                    </div>
                </div>
            
                <button type="submit" class="pay-btn">Pay Now</button>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            // Get card number
            const cardNumber = document.getElementById('card-number').value;
            if (!/^\d{16}$/.test(cardNumber)) {
                alert('Please enter a valid 16-digit card number.');
                return false; // Prevent form submission
            }

            // Get name on card
            const cardName = document.getElementById('card-name').value;
            if (cardName.trim().length < 2) {
                alert('Name on card must be at least 2 characters long.');
                return false; // Prevent form submission
            }

            // Get expiry date
            const expiryDate = document.getElementById('expiry-date').value;
            const regex = /^(0[1-9]|1[0-2])\/?([0-9]{2})$/;
            if (!regex.test(expiryDate)) {
                alert('Please enter a valid expiry date in MM/YY format.');
                return false; // Prevent form submission
            }

            // Get CVV
            const cvv = document.getElementById('cvv').value;
            if (!/^\d{3}$/.test(cvv)) {
                alert('CVV must be 3 digits.');
                return false; // Prevent form submission
            }

            // All validations passed
            return true;
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>