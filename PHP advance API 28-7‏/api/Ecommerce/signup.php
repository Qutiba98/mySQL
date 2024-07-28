<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
        }
        .btn-custom {
            width: 100%;
            margin: 10px 0;
        }
        .error {
            color: red;
        }
    </style>
    <script>
        function validateForm() {
            var name = document.forms["signupForm"]["name"].value;
            var email = document.forms["signupForm"]["email"].value;
            var phone = document.forms["signupForm"]["phone"].value;
            var password = document.forms["signupForm"]["password"].value;
            var confirmPassword = document.forms["signupForm"]["confirmPassword"].value;

            var namePattern = /^[A-Za-z\s]{2,}$/;
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var phonePattern = /^\d{10}$/;
            var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}$/;

            var errors = {};

            if (!name.match(namePattern)) {
                errors.name = "Name must have at least two letters.";
            }
            if (!password.match(passwordPattern)) {
                errors.password = "Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters.";
            }
            if (password !== confirmPassword) {
                errors.confirmPassword = "Passwords do not match.";
            }
            if (!email.match(emailPattern)) {
                errors.email = "Invalid email format.";
            }
            if (!phone.match(phonePattern)) {
                errors.phone = "Phone number must be exactly 10 digits.";
            }

            var errorKeys = Object.keys(errors);
            if (errorKeys.length > 0) {
                errorKeys.forEach(function(key) {
                    document.getElementById(key + "Error").innerText = errors[key];
                });
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Sign Up</h2>
            <p>Create an Account, It's free</p>
            <form name="signupForm" action="signup_action.php" method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name">
                    <span id="nameError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <span id="emailError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                    <span id="phoneError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <span id="passwordError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                    <span id="confirmPasswordError" class="error"></span>
                </div>
                <button type="submit" class="btn btn-danger btn-custom">Sign Up</button>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
    <?php
    session_start();
    if (isset($_SESSION['email_exists'])) {
        echo '<script>alert("This email is already registered.");</script>';
        unset($_SESSION['email_exists']);
    }
    ?>
</body>
</html>
