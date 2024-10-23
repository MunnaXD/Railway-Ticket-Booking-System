<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="aboutus.css">
    <!-- Corrected FontAwesome Link for Social Media Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
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

    <!-- About Us Section -->
    <section class="about-us-section">
        <div class="container">
            <h1>About Us</h1>
            <p>Welcome to Safar! We provide a seamless online railway ticket booking experience for travelers across India. Our platform is user-friendly and designed to offer quick, hassle-free train bookings. Whether you're looking for holiday packages, train schedules, or managing your account, we have it all under one roof.</p>
        </div>
    </section>

    <!-- Meet Our Team Section -->
    <section class="team-section">
        <div class="container">
            <h2>Meet Our Team</h2>
            <div class="team-container">
                <!-- Team Member 1 -->
                <div class="team-box">
                    <div class="team-image">
                        <img src="aman.jpg" alt="Aman Babbar">
                    </div>
                    <h3>Aman Babbar</h3>
                    <p>MBA Tech IT - I003</p>
                    <div class="social-icons">
                        <a href="https://www.linkedin.com/in/aman-babbar-b60020250" target="_blank" class="social-icon">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="mailto:amanbabbar2004@gmail.com" target="_blank" class="social-icon">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <a href="https://www.instagram.com/aman_babbar04/" target="_blank" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                <!-- Team Member 2 -->
                <div class="team-box">
                    <div class="team-image">
                        <img src="charvi.jpg" alt="Charvi Jain">
                    </div>
                    <h3>Charvi Jain</h3>
                    <p>MBA Tech IT - I013</p>
                    <div class="social-icons">
                        <a href="https://linkedin.com/in/charvijain" target="_blank" class="social-icon">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="mailto:charvi@example.com" target="_blank" class="social-icon">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <a href="https://instagram.com/charvijain" target="_blank" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                <!-- Team Member 3 -->
                <div class="team-box">
                    <div class="team-image">
                        <img src="harini.jpg" alt="Harini Suryanarayanan">
                    </div>
                    <h3>Harini Suryanarayanan</h3>
                    <p>MBA Tech IT - I019</p>
                    <div class="social-icons">
                        <a href="https://linkedin.com/in/harinisuryanarayanan" target="_blank" class="social-icon">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="mailto:harini@example.com" target="_blank" class="social-icon">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <a href="https://instagram.com/harinisuryanarayanan" target="_blank" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <p>Let’s Explore With Us</p>
            <a href="home.php">
                <button class="join-btn">Join Us Now</button>
            </a>
        </div>
        <div class="footer-bottom">
            <p>© 2024 Safar | All rights reserved</p>
        </div>
    </footer>
</body>
</html>
