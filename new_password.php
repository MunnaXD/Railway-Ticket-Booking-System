<?php
session_start();
include 'db.php'; // Include your DB connection here

// Define variables for error messages and form visibility
$username_error = $newpass_error = $confirmpass_error = "";
$show_username_form = true;
$show_password_form = false;
$password_updated = false; // Track if the password was successfully updated

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'])) {
        // Step 1: Check if username exists
        $username = htmlspecialchars($_POST['username']);
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['reset_username'] = $username;
            $show_username_form = false;
            $show_password_form = true;
        } else {
            $username_error = "Username not found.";
        }
        $stmt->close();
    } elseif (isset($_POST['new_password']) && isset($_SESSION['reset_username'])) {
        // Step 2: Handle new password submission
        $new_password = htmlspecialchars($_POST['new_password']);
        $confirm_password = htmlspecialchars($_POST['confirm_password']);
        $hasUpperCase = preg_match('/[A-Z]/', $new_password);
        $hasSpecialChar = preg_match('/[!@#$%^&*()_+{}\[\]:;<>,.?~\\/\-|=]/', $new_password);
        $hasNumber = preg_match('/[0-9]/', $new_password);

        // Validation
        if (!$hasUpperCase || !$hasSpecialChar || !$hasNumber) {
            $newpass_error = "Password must contain 1 uppercase letter, 1 special character, and 1 numeric value.";
        } elseif ($new_password !== $confirm_password) {
            $confirmpass_error = "New Password and confirm password do not match.";
        } else {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $username = $_SESSION['reset_username'];
            $sql = "UPDATE users SET password = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hashed_password, $username);

            if ($stmt->execute()) {
                // Password updated successfully
                $password_updated = true;
                session_unset();
                session_destroy();
            } else {
                echo "<script>alert('Failed to update password. Please try again later.');</script>";
            }
            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Password</title>
    <style>
        body {
            background-color: #001219; /* Background color */
            color: #fff; /* Text color */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('new passoword.jpg'); /* Add your background image URL here */
            background-size: cover; /* Make background cover the entire page */
            filter: blur(0px); /* Optional: Add slight blur to the background */
        }

        .container {
            text-align: center;
            position: relative; /* Relative positioning to overlay the box */
            z-index: 1; /* Ensure the box appears above the background */
        }

        .box {
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px); /* Apply a blur effect to the container */
            padding: 35px;
            border-radius: 10px;
            max-width: 300px;
            margin: auto;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 30px;
        }

        .input-field {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            background-color: #333;
            color: #fff;
            border-radius: 5px;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
            background-color: #0A9396; /* Changed to your color palette */
            border: none;
            color: #fff;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #94D2BD; /* Lighter shade for hover effect */
        }

        .error {
            color: red; /* Error message color */
            margin-top: 5px; /* Space above error messages */
        }
    </style>
    <script>
        function validateForm() {
            var new_password = document.querySelector("[name='new_password']").value;
            var confirm_password = document.querySelector("[name='confirm_password']").value;
            var hasUpperCase = /[A-Z]/.test(new_password);
            var hasSpecialChar = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/\-|=]/.test(new_password);
            var hasNumber = /[0-9]/.test(new_password);

            var isValid = true;
            var newpassError = "";
            var confirmpassError = "";

            if (!hasUpperCase || !hasSpecialChar || !hasNumber) {
                newpassError = "Password must contain 1 uppercase letter, 1 special character, and 1 numeric value.";
                isValid = false;
            }
            if (new_password !== confirm_password) {
                confirmpassError = "New Password and confirm password do not match.";
                isValid = false;
            }

            document.querySelector(".newpass-error").innerText = newpassError;
            document.querySelector(".confirmpass-error").innerText = confirmpassError;

            return isValid;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="box">
            <?php if ($password_updated): ?>
                <h2>Password updated successfully!</h2>
                <p>You will be redirected to the login page shortly...</p>
                <script>
                    setTimeout(function() {
                        window.location.href = 'login.html';
                    }, 3000); // Redirect after 3 seconds
                </script>
            <?php else: ?>
                <?php if ($show_username_form): ?>
                    <h1>Reset Password</h1>
                    <form method="POST" action="">
                        <input type="text" name="username" placeholder="Enter your username" class="input-field" required>
                        <p class="error"><?php echo $username_error; ?></p>
                        <input type="submit" value="Next" class="submit-button">
                    </form>
                <?php elseif ($show_password_form): ?>
                    <h1>Create New Password</h1>
                    <form method="POST" action="" onsubmit="return validateForm()">
                        <input type="password" name="new_password" placeholder="New Password" class="input-field" required>
                        <p class="error newpass-error"><?php echo $newpass_error; ?></p>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" class="input-field" required>
                        <p class="error confirmpass-error"><?php echo $confirmpass_error; ?></p>
                        <input type="submit" value="Submit" class="submit-button">
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
