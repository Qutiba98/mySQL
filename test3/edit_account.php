<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test4";

// إنشاء اتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];

    $sql = "UPDATE users SET email='$email', role_id='$role_id' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $sql = "SELECT email, role_id FROM users WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $role_id = $row['role_id'];
    } else {
        echo "User not found.";
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
</head>
<body>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <br>
        <label for="role_id">Role ID:</label>
        <input type="number" name="role_id" value="<?php echo htmlspecialchars($role_id); ?>" required>
        <br>
        <input type="submit" value="Update Account">
    </form>
</body>
</html>
