<?php
include 'db.php'; // Include your database connection script

// SQL query to create the database (if not already created)
$sql = "CREATE DATABASE IF NOT EXISTS Safar";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully!<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

// Select the database
mysqli_select_db($conn, 'Safar');

// SQL query to create the users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    country_code VARCHAR(5),
    mobile VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo "Users table created successfully!<br>";
} else {
    echo "Error creating users table: " . mysqli_error($conn);
}

// SQL query to create the trains table
$sql = "CREATE TABLE IF NOT EXISTS trains (
    TrainID INT AUTO_INCREMENT PRIMARY KEY,
    TrainNumber VARCHAR(10) NOT NULL UNIQUE,
    TrainName VARCHAR(100) NOT NULL,
    RouteID INT NOT NULL,
    ScheduleID INT NOT NULL
)";
if (mysqli_query($conn, $sql)) {
    echo "Train table created successfully!<br>";
} else {
    echo "Error creating train table: " . mysqli_error($conn);
}

// SQL query to create the route table
$sql = "CREATE TABLE IF NOT EXISTS route (
    RouteID INT AUTO_INCREMENT PRIMARY KEY,
    SourceStation INT NOT NULL,
    DestinationStation INT NOT NULL,
    Distance DECIMAL(10, 2) NOT NULL,
    Time DECIMAL(5, 2) NOT NULL
)";
if (mysqli_query($conn, $sql)) {
    echo "Route table created successfully!<br>";
} else {
    echo "Error creating route table: " . mysqli_error($conn);
}

// SQL query to create the schedule table
$sql = "CREATE TABLE IF NOT EXISTS schedule (
    ScheduleID INT AUTO_INCREMENT PRIMARY KEY,
    TrainID INT NOT NULL,
    DepartureTime TIME NOT NULL,
    ArrivalTime TIME NOT NULL,
    FOREIGN KEY (TrainID) REFERENCES trains(TrainID)
)";
if (mysqli_query($conn, $sql)) {
    echo "Schedule table created successfully!<br>";
} else {
    echo "Error creating schedule table: " . mysqli_error($conn);
}

// SQL query to modify the booking table
$sql = "CREATE TABLE IF NOT EXISTS booking (
    BookingID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    TrainID INT NOT NULL,
    ScheduleID INT NOT NULL,
    BookingStatus VARCHAR(50) NOT NULL,
    BookingDate DATE NOT NULL,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES users(id),
    FOREIGN KEY (TrainID) REFERENCES trains(TrainID),
    FOREIGN KEY (ScheduleID) REFERENCES schedule(ScheduleID)
)";
if (mysqli_query($conn, $sql)) {
    echo "Booking table modified successfully!<br>";
} else {
    echo "Error modifying booking table: " . mysqli_error($conn);
}

// SQL query to create the seat table
$sql = "CREATE TABLE IF NOT EXISTS seat (
    SeatID INT AUTO_INCREMENT PRIMARY KEY,
    TrainID INT NOT NULL,
    SeatNumber VARCHAR(10) NOT NULL,
    ClassType VARCHAR(50) NOT NULL,
    AvailabilityStatus VARCHAR(50) NOT NULL,
    FOREIGN KEY (TrainID) REFERENCES trains(TrainID)
)";
if (mysqli_query($conn, $sql)) {
    echo "Seat table created successfully!<br>";
} else {
    echo "Error creating seat table: " . mysqli_error($conn);
}

// SQL query to create the cost table
$sql = "CREATE TABLE IF NOT EXISTS cost (
    CostID INT AUTO_INCREMENT PRIMARY KEY,
    SeatID INT NOT NULL,
    BaseFare DECIMAL(10, 2) NOT NULL,
    DynamicFare DECIMAL(10, 2) NOT NULL,
    Tax DECIMAL(10, 2) NOT NULL,
    ReservationCharge DECIMAL(10, 2) NOT NULL,
    SuperfastCharge DECIMAL(10, 2) NOT NULL,
    TotalPrice DECIMAL(10, 2) NOT NULL,
    ClassType VARCHAR(50) NOT NULL,
    FOREIGN KEY (SeatID) REFERENCES seat(SeatID)
)";
if (mysqli_query($conn, $sql)) {
    echo "Cost table created successfully!<br>";
} else {
    echo "Error creating cost table: " . mysqli_error($conn);
}

// SQL query to create the travellers table
$sql = "CREATE TABLE IF NOT EXISTS travellers (
    TravellerID INT AUTO_INCREMENT PRIMARY KEY,
    BookingID INT NOT NULL,
    TravellerName VARCHAR(100) NOT NULL,
    Age INT NOT NULL,
    Gender VARCHAR(10) NOT NULL,
    BerthPreference VARCHAR(50),
    FOREIGN KEY (BookingID) REFERENCES booking(BookingID)
)";
if (mysqli_query($conn, $sql)) {
    echo "Travellers table created successfully!<br>";
} else {
    echo "Error creating travellers table: " . mysqli_error($conn);
}

// Close the connection
mysqli_close($conn);
?>
