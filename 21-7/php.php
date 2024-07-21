<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5"> <!-- تصحيح خطأ في علامة الاقتباس -->
        <h2>Employees Details</h2>
        <a class="btn btn-primary" href="/21-7/create.php" role="button">New Client</a> <!-- تصحيح خطأ في علامة الاقتباس -->
        <br>

        <table class="table">
            <thead> 
                <tr> 
                    <th>ID</th> <!-- تصحيح الخطأ في تسمية العلامة -->
                    <th>Name</th>
                    <th>Address</th> 
                    <th>Salary</th>
                </tr> 
            </thead> 
            <tbody> 

                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "test";
                $connection = new mysqli($servername, $username, $password, $database);
                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error); // تصحيح خطأ في نص الرسالة
                }

                $sql = "SELECT * FROM clients"; // إضافة مسافة بين SELECT و *
                $result = $connection->query($sql);

                if (!$result) {
                    die("Invalid query: " . $connection->error); // تصحيح خطأ في استخدام connection بدلاً من $connection
                }

                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr> 
                        <td>$row[id]</td> 
                        <td>$row[name]</td> 
                        <td>$row[address]</td> 
                        <td>$row[salary]</td> 
                        <td> 
                            <a class='btn btn-primary btn-sm' href='/21-7/Edit.php?id=$row[id]'>Edit</a> <!-- استخدام علامات الاقتباس المفردة داخل النص -->
                            <a class='btn btn-danger btn-sm' href='/21-7/Delete.php?id=$row[id]'>Delete</a>
                        </td> 
                    </tr>";
                }

                // إغلاق الاتصال بقاعدة البيانات
                $connection->close();
                ?>
            </tbody> 
        </table> 
    </div>
</body>
</html>
