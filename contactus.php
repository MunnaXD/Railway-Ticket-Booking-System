<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="contactus.css">
    <script defer src="validation.js"></script>
    <!-- Corrected FontAwesome Link for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
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
                    <li><a href="logout.php" class="btn-signin">Sign Out</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-signin">Sign In</a></li>
                    <li><a href="register.php" class="btn-register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>

    </header>

    <!-- Section 1: Contact Header with Image -->
    <section class="contact-header-section">
        <div class="image">
            <div class="header-content">
                <h1>Contact Us</h1>
                <p>We're here to help. Connect with us anytime regarding your inquiries.</p>
            </div>
        </div>
    </section>

    <!-- Section 2: Contact Form and Details -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-content">
                <!-- Contact Form -->
                <div class="contact-form">
                    <h2>Get In Touch</h2>
                    <form id="contactForm" action="process_contact.php" method="POST">
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

                <!-- Contact Information -->
                <div class="contact-info">
                    <p><strong>Contact Us:</strong></p>
                    <p><i class="fa fa-phone"></i> +91 12345 67890</p>
                    <p><i class="fa fa-envelope"></i> support@trainreserve.com</p>
                    <p><i class="fa fa-map-marker"></i> 1234, Railway Street, Mumbai, India</p>
                    <!-- Embed Google Map -->
                    <iframe src="https://www.google.com/maps/embed?..." width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Explore Section -->
    <section class="explore-section">
        <div class="explore-content">
            <h2>Let’s Explore With Us</h2>
            <p>Discover new adventures and travel experiences with Train Reserve. Join us today!</p>
            <a href="register.php">
                <button class="join-btn">Join Us Now</button>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-bottom">
            <p>© 2024 Safar | All rights reserved</p>
        </div>
    </footer>

</body>
</html>
