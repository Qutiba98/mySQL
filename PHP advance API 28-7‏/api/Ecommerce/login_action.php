<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM customers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['pass'])) {
        $_SESSION['email'] = $email;
        if ($email == 'sabbaghzaid88@gmail.com') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header("Location: login.php");
    }

    $stmt->close();
    $conn->close();
}
?>
