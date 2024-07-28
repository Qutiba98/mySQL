<?php
header("Content-Type: application/json");
include 'db.php';

$request_method = $_SERVER["REQUEST_METHOD"];

switch($request_method) {
    case 'GET':
        // استرجاع الطلاب
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            get_students($id);
        } else {
            get_students();
        }
        break;
    case 'POST':
        // إدراج طالب
        insert_student();
        break;
    case 'PUT':
        // تحديث طالب
        $id = intval($_GET["id"]);
        update_student($id);
        break;
    case 'DELETE':
        // حذف طالب
        $id = intval($_GET["id"]);
        delete_student($id);
        break;
    default:
        // طريقة طلب غير صالحة
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
function get_students($id = 0) {
    global $conn;
    $query = "SELECT * FROM students";
    if ($id != 0) {
        $query .= " WHERE id=" . $id . " LIMIT 1";
    }
    $response = array();
    $result = $conn->query($query);
    while($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
    echo json_encode($response);
}

function insert_student() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $first_name = $data["first_name"];
    $last_name = $data["last_name"];
    $email = $data["email"];
    $phone_number = $data["phone_number"];
    $date_of_birth = $data["date_of_birth"];
    $enrollment_date = $data["enrollment_date"];

    $query = "INSERT INTO students(first_name, last_name, email, phone_number, date_of_birth, enrollment_date) VALUES('".$first_name."', '".$last_name."', '".$email."', '".$phone_number."', '".$date_of_birth."', '".$enrollment_date."')";
    
    if($conn->query($query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'تم إضافة الطالب بنجاح.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'فشل في إضافة الطالب.'
        );
    }
    echo json_encode($response);
}

function update_student($id) {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $first_name = $data["first_name"];
    $last_name = $data["last_name"];
    $email = $data["email"];
    $phone_number = $data["phone_number"];
    $date_of_birth = $data["date_of_birth"];
    $enrollment_date = $data["enrollment_date"];

    $query = "UPDATE students SET first_name='".$first_name."', last_name='".$last_name."', email='".$email."', phone_number='".$phone_number."', date_of_birth='".$date_of_birth."', enrollment_date='".$enrollment_date."' WHERE id=".$id;

    if($conn->query($query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'تم تحديث الطالب بنجاح.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'فشل في تحديث الطالب.'
        );
    }
    echo json_encode($response);
}

function delete_student($id) {
    global $conn;
    $query = "DELETE FROM students WHERE id=".$id;
    if($conn->query($query)) {
        $response = array(
            'status' => 1,
            'status_message' => 'تم حذف الطالب بنجاح.'
        );
    } else {
        $response = array(
            'status' => 0,
            'status_message' => 'فشل في حذف الطالب.'
        );
    }
    echo json_encode($response);
}
?>
