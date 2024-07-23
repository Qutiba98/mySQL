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

// استرجاع أدوار المستخدمين من قاعدة البيانات
$roles = [];
$role_query = "SELECT id, role_name FROM roles"; // افترض أن لديك جدولًا يسمى 'roles'
$role_result = $conn->query($role_query);

if ($role_result && $role_result->num_rows > 0) {
    while ($row = $role_result->fetch_assoc()) {
        $roles[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من وجود البيانات
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
    $role_id = isset($_POST['role_id']) ? $_POST['role_id'] : '';
    $Phone_number = isset($_POST['Phone_number']) ? $_POST['Phone_number'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';

    // التحقق من صحة البيانات
    if (!empty($email) && !empty($password) && !empty($role_id) && !empty($Phone_number) && !empty($name)) {
        // استخدام تعبير SQL للتحقق من البيانات
        $stmt = $conn->prepare("INSERT INTO users (email, password, role_id, Phone_number, name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $email, $password, $role_id, $Phone_number, $name);

        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <style>
        * {
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
            width: 400px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
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
            color: #555;
        }

        input, select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
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
            font-size: 16px;
            background-color: #007bff;
        }

        .button:hover {
            opacity: 0.9;
        }

        .footer {
            margin-top: 20px;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Account admin</h1>
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <br>
            <label for="role_id">Role ID:</label>
            <select name="role_id" required>
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo htmlspecialchars($role['id']); ?>">
                        <?php echo htmlspecialchars($role['role_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>
            <label for="Phone_number">Phone Number:</label>
            <input type="text" name="Phone_number" required>
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" required>
            <br>
            <input type="submit" value="Create Account" class="button">
        </form>
    </div>
</body>
</html>
