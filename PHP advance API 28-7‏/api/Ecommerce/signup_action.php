<?php
session_start();
include 'config.php';

$errors = [];

function validateName($name) {
    return preg_match('/^[A-Za-z]{2,}$/', $name);
}

function validatePhoneNumber($phone) {
    $phone = preg_replace('/\D/', '', $phone); // Remove non-numeric characters
    return preg_match('/^\d{10}$/', $phone);
}

function validatePassword($password) {
    return preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}$/', $password);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (!validateName($name)) {
        $errors['name'] = 'Name must have at least two letters.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }
    if (!validatePhoneNumber($phone)) {
        $errors['phone'] = 'Phone number must be exactly 10 digits.';
    }
    if (!validatePassword($password)) {
        $errors['password'] = 'Password must be at least 8 characters long and include uppercase, lowercase, numbers, and special characters.';
    }
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        // Check if email already exists
        $sql = "SELECT COUNT(*) FROM customers WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($emailExists);
        $stmt->fetch();
        $stmt->close();

        if ($emailExists) {
            $_SESSION['email_exists'] = true;
            $_SESSION['inputs'] = $_POST;
            header("Location: signup.php");
            exit();
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO customers (name, pass, email, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $passwordHash, $email, $phone);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Store errors and input values in the session and redirect back to the signup page
    $_SESSION['errors'] = $errors;
    $_SESSION['inputs'] = $_POST;
    header("Location: signup.php");
    exit();
}
?>
