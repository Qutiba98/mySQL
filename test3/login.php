<?php
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

// إضافة مستخدم أدمن إذا لم يكن موجودًا بالفعل
$email = 'admin@example.com';
$plain_password = '123456'; // كلمة المرور غير المشفرة
$role_id = 2; // معرّف الدور كأدمن

// التحقق من وجود مستخدم أدمن بالفعل
$sql_check = "SELECT COUNT(*) FROM users WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
if ($stmt_check === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->bind_result($count);
$stmt_check->fetch();
$stmt_check->close();

if ($count == 0) {
    // إعداد الاستعلام لإدخال بيانات المستخدم
    $sql_insert = "INSERT INTO users (email, password, role_id) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    if ($stmt_insert === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt_insert->bind_param("ssi", $email, $plain_password, $role_id);
    if ($stmt_insert->execute()) {
        echo "Admin user added successfully.";
    } else {
        echo "Error: " . $stmt_insert->error;
    }

    $stmt_insert->close();
}

// التحقق من وجود البيانات في POST
if (isset($_POST['email'], $_POST['password'])) {
    // الحصول على البيانات من النموذج
    $email = $_POST['email'];
    $password = $_POST['password'];

    // إعداد الاستعلام للبحث عن المستخدم بناءً على البريد الإلكتروني
    $sql = "SELECT id, password, role_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // التحقق مما إذا كانت هناك نتائج
    if ($stmt->num_rows === 1) {
        // ربط النتائج
        $stmt->bind_result($id, $password_from_db, $role_id);
        $stmt->fetch();

        // التحقق من صحة كلمة المرور
        if ($password === $password_from_db) {
            // بدء الجلسة وتخزين بيانات المستخدم
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['role_id'] = $role_id;

            // إعادة توجيه المستخدم بناءً على نوع الدور
            if ($role_id == 2) { // أدمن
                header("Location: admin.php");
            } else { // مستخدم عادي
                header("Location: home.php");
            }
            exit(); // تأكد من إيقاف تنفيذ السكربت بعد إعادة التوجيه
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that email.";
    }

    // إغلاق الاتصال
    $stmt->close();
} else {
    echo "";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            width: 300px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
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
            margin-top: 10px;
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

        .footer {
            margin-top: 20px;
        }

        .footer a {
            color: #000;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="button login">Login</button>
        </form>
        <div class="footer">
            <p>Don't have an account? <a href="signup.php" class="button signup">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
