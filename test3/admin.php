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

// استعلام لعرض بيانات المستخدمين
$sql = "SELECT id, email, role_id, Phone_number, name, created_at, User_image FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 80%;
            margin: 0 auto;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        .button {
            display: inline-block;
            padding: 10px;
            color: #fff;
            background-color: #dc3545;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover {
            opacity: 0.9;
        }

        .button.delete {
            background-color: #dc3545;
        }

        img.User_image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }



        
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, Admin!</h1>
        <a href="home.php" class="button">Logout</a>

        <h2>Users List</h2>
        <a href="create_account.php" class="button" style="background-color: #28a745;">Create Account</a>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role ID</th>
                        <th>Phone</th>
                        <th>Created At</th>
                        <th>Profile Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['role_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['Phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <?php if (!empty($row['User_image'])): ?>
                                    <img  src="uploads/<?php echo htmlspecialchars($row['User_image']); ?>" alt="User_image" class="User_image">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_account.php?id=<?php echo $row['id']; ?>" class="button" style="background-color: #ffc107;">Edit</a>
                                <a href="delete_account.php?id=<?php echo $row['id']; ?>" class="button delete" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>

        <?php
        // إغلاق الاتصال
        $conn->close();
        ?>
    </div>
</body>
</html>
