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

$error_messages = [
    'name' => '',
    'email' => '',
    'password' => '',
    'Phone_number' => '',
    'User_image' => ''
];

// التحقق من وجود البيانات في POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['Phone_number'])) {
    // الحصول على البيانات من النموذج
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // تشفير كلمة المرور
    $Phone_number = $_POST['Phone_number'];
    
    // معالجة الصورة
    $User_image = '';
    if (isset($_FILES['User_image']) && $_FILES['User_image']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['User_image']['tmp_name'];
        $file_name = $_FILES['User_image']['name'];
        $file_type = $_FILES['User_image']['type'];

        // تحقق من نوع الملف
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file_type, $allowed_types)) {
            // تحديد المسار الذي سيتم حفظ الصورة فيه
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_path = $upload_dir . basename($file_name);

            // نقل الصورة إلى المسار المحدد
            if (move_uploaded_file($file_tmp, $file_path)) {
                $User_image = $file_name; // حفظ اسم الصورة فقط في قاعدة البيانات
            } else {
                $error_messages['User_image'] = "Failed to upload image.";
            }
        } else {
            $error_messages['User_image'] = "Invalid file type.";
        }
    }

    if (empty(array_filter($error_messages))) {
        // إعداد الاستعلام لإدخال البيانات
        $sql = "INSERT INTO users (name, email, password, Phone_number, User_image, role_id) VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $password, $Phone_number, $User_image);

        if ($stmt->execute()) {
            // إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
            header("Location: login.php");
            exit(); // تأكد من إيقاف تنفيذ السكربت بعد إعادة التوجيه
        } else {
            $error_messages['database'] = "Error: " . $stmt->error;
        }

        // إغلاق الاتصال
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
            margin-bottom: 5px;
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

        .signup {
            background-color: #dc3545;
        }

        .login {
            background-color: #007bff;
            color: #fff;

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

        .error-message {
            color: #dc3545;
            margin-top: -10px;
            margin-bottom: 10px;
            font-size: 0.875em;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Create Account</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">
        <p id="name-error" class="error-message"><?php echo htmlspecialchars($error_messages['name']); ?></p>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email">
        <p id="email-error" class="error-message"><?php echo htmlspecialchars($error_messages['email']); ?></p>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <p id="password-error" class="error-message"><?php echo htmlspecialchars($error_messages['password']); ?></p>

        <label for="Phone_number">Phone:</label>
        <input type="text" id="Phone_number" name="Phone_number">
        <p id="Phone_number-error" class="error-message"><?php echo htmlspecialchars($error_messages['Phone_number']); ?></p>

        <label for="User_image">Profile Image:</label>
        <input type="file" id="User_image" name="User_image" accept="image/*">
        <p id="User_image-error" class="error-message"><?php echo htmlspecialchars($error_messages['User_image']); ?></p>

        <button type="submit" class="button signup">Sign Up</button>
    </form>
    <div class="footer">
        <p>Already have an account? <a style="color:white" href="login.php" class="button login">Login</a></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const errorMessageElems = {
        'name': document.getElementById('name-error'),
        'email': document.getElementById('email-error'),
        'password': document.getElementById('password-error'),
        'Phone_number': document.getElementById('Phone_number-error'),
        'User_image': document.getElementById('User_image-error')
    };
    
    form.addEventListener('submit', function(event) {
        let errors = {
            'name': '',
            'email': '',
            'password': '',
            'Phone_number': '',
            'User_image': ''
        };
        
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const phoneNumber = document.getElementById('Phone_number').value.trim();
        const fileInput = document.getElementById('User_image');
        const file = fileInput.files[0];
        
        if (!name) {
            errors['name'] = 'Name is required.';
        }
        
        if (!email || !validateEmail(email)) {
            errors['email'] = 'A valid email is required.';
        }
        
        if (!password || password.length < 6) {
            errors['password'] = 'Password must be at least 6 characters long.';
        }
        
        if (!phoneNumber || !/^\d{10}$/.test(phoneNumber)) {
            errors['Phone_number'] = 'Phone number must be a 10-digit number.';
        }
        
        if (file && !['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
            errors['User_image'] = 'Invalid file type. Only JPEG, PNG, and GIF are allowed.';
        }
        
        let hasErrors = false;
        for (const [key, value] of Object.entries(errors)) {
            if (value) {
                errorMessageElems[key].textContent = value;
                hasErrors = true;
            } else {
                errorMessageElems[key].textContent = '';
            }
        }
        
        if (hasErrors) {
            event.preventDefault();
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
</script>

</body>
</html>
