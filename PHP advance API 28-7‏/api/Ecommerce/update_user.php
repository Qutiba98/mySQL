<?php
session_start();
include 'config.php';

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM customers WHERE id='$id'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Error: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
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
            var name = document.forms["updateForm"]["name"].value;
            var password = document.forms["updateForm"]["password"].value;
            var confirmPassword = document.forms["updateForm"]["confirmPassword"].value;
            var email = document.forms["updateForm"]["email"].value;
            var phone = document.forms["updateForm"]["phone"].value;

            var namePattern = /^[A-Za-z]{2,}(\s[A-Za-z]{2,})*$/;
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var phonePattern = /^\d{10}$/;
            var passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}$/;

            var errors = {};

            if (!name.match(namePattern)) {
                errors.name = "Name must have at least two letters.";
            }
            if (password && !password.match(passwordPattern)) {
                errors.password = "Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters.";
            }
            if (password && password !== confirmPassword) {
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
            <h2>Update User</h2>
            <form name="updateForm" onsubmit="return validateForm()" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                    <span id="nameError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="password">Password (leave blank to keep current password):</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <span id="passwordError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                    <span id="confirmPasswordError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    <span id="emailError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    <span id="phoneError" class="error"></span>
                </div>
                <button type="submit" class="btn btn-warning btn-custom">Update User</button>
                <a href="admin.php" class="btn btn-primary btn-custom">Back to Admin</a>
            </form>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        $errors = [];

        if (!preg_match('/^[A-Za-z]{2,}(\s[A-Za-z]{2,})*$/', $name)) {
            $errors['name'] = "Name must have at least two letters.";
        }
        if ($password && !preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}$/', $password)) {
            $errors['password'] = "Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters.";
        }
        if ($password && $password !== $confirmPassword) {
            $errors['confirmPassword'] = "Passwords do not match.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }
        if (!preg_match('/^\d{10}$/', $phone)) {
            $errors['phone'] = "Phone number must be exactly 10 digits.";
        }

        if (empty($errors)) {
            // Prepare the data to be sent to the API
            $data = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
            ];

            // Add the password only if it is provided
            if ($password) {
                $data['password'] = $password;
            }

            // Send the data to the update API
            $apiUrl = "update.php?id=$id";
            $options = [
                'http' => [
                    'header'  => "Content-type: application/json\r\n",
                    'method'  => 'PUT',
                    'content' => json_encode($data),
                ],
            ];
            $context  = stream_context_create($options);
            $result = file_get_contents($apiUrl, false, $context);
            $response = json_decode($result, true);

            if ($response['status'] == 200) {
                header("Location: admin.php");
                exit();
            } else {
                echo "<script>alert('Error: " . $response['message'] . "');</script>";
            }
        } else {
            foreach ($errors as $key => $error) {
                echo "<script>document.getElementById('{$key}Error').innerText = '{$error}';</script>";
            }
        }
    }
    ?>
</body>
</html>
