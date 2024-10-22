<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safar!</title>
    <link href="https://fonts.googleapis.com/css2?family=TT+Chocolates&display=swap" rel="stylesheet"> <!-- Import the font -->
    <link rel="stylesheet" href="home.css">
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
                <li><a href="home.php">Home</a></li>
                <li><a href="#">Holiday Packages</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contactus.html">Contact Us</a></li>
                <li><a href="#">Account Settings</a></li>
                <li><a href="#">Manage Booking</a></li>
                
                <!-- Check if the user is logged in -->
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="logout.php" class="btn-signin">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-signin">Sign In</a></li>
                    <li><a href="register.php" class="btn-register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="hero-section">
        <div class="overlay"></div>
        <div class="content">
            <div class="text-content">
                <!-- Greet the user if logged in -->
                <?php if (isset($_SESSION['username'])): ?>
                    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!<br>Where are you going?</h1>
                <?php else: ?>
                    <h1>Hey there!<br>Where are you going?</h1>
                <?php endif; ?>
            </div>
            <div class="form-container">
                <form action="searchtrains.php" method="POST">
                    <div class="form-row">
                        <label for="from">From</label>
                        <input type="text" id="from" name="from" placeholder="City or Station" required>
                    </div>

                    <div class="form-row">
                        <label for="to">To</label>
                        <input type="text" id="to" name="to" placeholder="Destination" required>
                    </div>

                    <div class="form-row">
                        <label for="departure-date">Departure</label>
                        <input type="date" id="departure-date" name="departure-date" required>
                    </div>

                    <div class="form-row full-width">
                        <button type="submit" class="btn">Search Trains</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
