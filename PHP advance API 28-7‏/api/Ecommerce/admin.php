<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] != 'sabbaghzaid88@gmail.com') {
    header("Location: login.php");
    exit();
}
include 'config.php';

$sql = "SELECT id, name, email, phone FROM customers";
$result = $conn->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            margin: 10px 0;
        }
        table tbody td {
            vertical-align: middle;
        }
        .btn-group-horizontal .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Page</h2>
        <div class="d-flex justify-content-between">
            <a href="create_user.php" class="btn btn-success btn-custom">Create New User</a>
            <a href="logout.php" class="btn btn-primary btn-custom">Logout</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td>
                            <div class="btn-group btn-group-horizontal" role="group">
                                <a href="view_user.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-info">View</a>
                                <a href="update_user.php?id=<?php echo htmlspecialchars($user['id']); ?>" class="btn btn-warning">Edit</a>
                                <form action="delete.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
