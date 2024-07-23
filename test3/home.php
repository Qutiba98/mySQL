<?php
session_start();

// التحقق من الجلسة
if (!isset($_SESSION['user_id'])) {
    // إذا لم يكن هناك جلسة نشطة، إعادة التوجيه إلى صفحة تسجيل الدخول
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
</head>
<body>
    <h1>Welcome to the Home Page</h1>
    <!-- محتوى صفحة المستخدم هنا -->
</body>
</html>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Welcome</title>
</head>
<style>

{
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
}

body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f4f4f4;
}

.container {
    background: #fff;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    text-align: center;
    width: 300px;
}

h1, h2 {
    margin-bottom: 20px;
    font-size: 24px;
}

p {
    margin-bottom: 20px;
    color: #666;
}

form {
    display: flex;
    flex-direction: column;
    align-items: stretch;
}

label {
    text-align: left;
    margin-bottom: 5px;
    font-weight: bold;
}

input {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.button {
    display: block;
    width: 100%;
    padding: 10px;
    text-align: center;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
}

.login {
    background-color: #007bff;
}

.signup {
    background-color: #dc3545;
}

.button:hover {
    opacity: 0.9;
}

.buttons {
    display: flex;
    justify-content: space-between;
}

.buttons .button {
    width: 48%;
}

</style>
<body>
    <div class="container">
        <h1>Hello There!</h1>
        <p>Automatic identity verification which enables you to verify your identity</p>
        <div class="buttons">
            <a href="login.php" class="button login">Login</a>
            <a href="signup.php" class="button signup">Sign Up</a>
        </div>
    </div>
</body>
</html>

