<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer\PHPMailer-master\src\Exception.php';
require 'phpmailer\PHPMailer-master\src\PHPMailer.php';
require 'phpmailer\PHPMailer-master\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration for Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ramukaha100@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'jidifgcrwgjkhibc'; // Replace with your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
        $mail->Port = 587; // TLS port

        // Email setup
        $mail->setFrom('your-email@gmail.com', 'Safar'); // Replace with your "from" email and name
        $mail->addAddress('ramukaha100@gmail.com'); // Recipient email address
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "<h4>From: $name</h4>
                       <h4>Email: $email</h4>
                       <p>$message</p>";

        // Attempt to send the email
        $mail->send();
        echo 'Message has been sent successfully!';
    } catch (Exception $e) {
        echo 'There was an error sending your message. Please try again.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="contactus.css">
    <script defer src="validation.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <img src="train.png" alt="Safar Logo" class="logo-img"> 
            <h2>SAFAR</h2>
        </div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php" class="btn-signin">Log Out</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-signin">Sign In</a></li>
                    <li><a href="register.php" class="btn-register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="contact-header-section">
        <div class="image">
            <div class="header-content">
                <h1>Contact Us</h1>
                <p>We're here to help. Connect with us anytime regarding your inquiries.</p>
            </div>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <div class="contact-content">
                <!-- Display Success/Error Messages -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert">
                        <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']); // Clear the message after displaying it
                        ?>
                    </div>
                <?php endif; ?>

                <div class="contact-form">
                    <h2>Get In Touch</h2>
                    <form id="contactForm" action="" method="POST">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" placeholder="Your Name" required>
                        
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="example@yourmail.com" required>

                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="Title" required>
                        
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="Type Here..." required></textarea>

                        <button type="submit" class="btn">Send Now</button>
                    </form>
                </div>

                <div class="contact-info">
                    <p><strong>Contact Us:</strong></p>
                    <p><i class="fa fa-phone"></i> +91 12345 67890</p>
                    <p><i class="fa fa-envelope"></i> support@safar.com</p>
                    <p><i class="fa fa-map-marker"></i> 1234, Juhu Beach, Mumbai, India</p>
                    <!-- Embed Google Map -->
                    <iframe src="https://www.google.com/maps/embed?..." width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>

    <section class="explore-section">
        <div class="explore-content">
            <h2>Let’s Explore With Us</h2>
            <p>Discover new adventures and travel experiences with Train Reserve. Join us today!</p>
            <a href="register.php">
                <button class="join-btn">Join Us Now</button>
            </a>
        </div>
    </section>

    <footer>
        <div class="footer-bottom">
            <p>© 2024 Safar | All rights reserved</p>
        </div>
    </footer>

    <style>
        .alert {
            padding: 10px;
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>

</body>
</html>
