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

    <section class="hero-section">
        <div class="overlay"></div>
        <div class="content">
            <div class="text-content">
                <!-- Greet the user if logged in -->
                <?php if (isset($_SESSION['username'])): ?>
                    <h1>Hey, <?php echo htmlspecialchars($_SESSION['username']); ?>!<br>Where are you going?</h1>
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
                        <input type="date" id="departure-date" name="departure_date" required>
                    </div>

                    <div class="form-row full-width">
                        <button type="submit" class="btn">Search Trains</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="destinations-section">
        <h2>Popular Destinations</h2>
        <div class="destinations-container">
            <div class="destination">
                <img src="kashmir.jpg" alt="Destination 1">
                <div class="overlay">
                    <h3>Kashmir</h3>
                </div>
            </div>
            <div class="destination">
                <img src="delhi.jpg" alt="Destination 2">
                <div class="overlay">
                    <h3>Delhi</h3>
                </div>
            </div>
            <div class="destination">
                <img src="darjeeling.jpg" alt="Destination 3">
                <div class="overlay">
                    <h3>Darjeeling</h3>
                </div>
            </div>
            <div class="destination">
                <img src="assam.jpg" alt="Destination 4">
                <div class="overlay">
                    <h3>Assam</h3>
                </div>
            </div>
            <div class="destination">
                <img src="goa.jpg" alt="Destination 4">
                <div class="overlay">
                    <h3>Goa</h3>
                </div>
            </div>
            
        </div>
    </section>
</body>
</html>
