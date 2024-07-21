<?php
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "test";

    // إنشاء اتصال بقاعدة البيانات
    $connection = new mysqli($servername, $username, $password, $database);

    // التحقق من نجاح الاتصال
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    // تأمين المتغيرات ضد هجمات SQL Injection
    $id = $connection->real_escape_string($id);

    // استعلام لحذف السجل
    $sql = "DELETE FROM clients WHERE id = $id";
    if ($connection->query($sql) === TRUE) {
        // سجل تم حذفه بنجاح
    } else {
        echo "Error deleting record: " . $connection->error;
    }

    // إغلاق الاتصال بقاعدة البيانات
    $connection->close();
}

// التوجيه إلى الصفحة المطلوبة بعد تنفيذ العملية
header("Location: /21-7/create.php");
exit;
?>
