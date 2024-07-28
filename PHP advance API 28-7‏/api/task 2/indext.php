<?php
header("Content-Type: application/json");
include 'db.php';

$request_method = $_SERVER["REQUEST_METHOD"];
$path_info = isset($_SERVER["PATH_INFO"]) ? explode('/', trim($_SERVER["PATH_INFO"], '/')) : [];

error_log("Request method: $request_method"); // إضافة سجل للطلب
error_log("Path info: " . print_r($path_info, true)); // إضافة سجل للمسار

switch($request_method) {
    case 'POST':
        if (isset($path_info[0]) && $path_info[0] == 'students') {
            create_student();
        } else {
            error_log("POST endpoint not found"); // إضافة سجل
        }
        break;
    case 'PUT':
        if (isset($path_info[0]) && $path_info[0] == 'students' && isset($path_info[1])) {
            $student_id = intval($path_info[1]);
            update_student($student_id);
        } else {
            error_log("PUT endpoint not found"); // إضافة سجل
        }
        break;
    case 'DELETE':
        if (isset($path_info[0]) && $path_info[0] == 'students' && isset($path_info[1])) {
            $student_id = intval($path_info[1]);
            delete_student($student_id);
        } else {
            error_log("DELETE endpoint not found"); // إضافة سجل
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(array('message' => 'الطريقة غير مسموحة'));
        error_log("Method not allowed: $request_method"); // إضافة سجل
        break;
}

function create_student() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    error_log("Received data: " . print_r($data, true)); // إضافة سجل للبيانات المستلمة

    $name = $data["name"];
    $class = $data["class"];
    $date_of_birth = $data["date_of_birth"];
    $address = $data["address"];
    $contact_information = $data["contact_information"];

    $stmt = $conn->prepare("INSERT INTO students (name, class, date_of_birth, address, contact_information) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $class, $date_of_birth, $address, $contact_information);

    if ($stmt->execute()) {
        $response = array(
            'student_id' => $conn->insert_id,
            'name' => $name,
            'class' => $class,
            'date_of_birth' => $date_of_birth,
            'address' => $address,
            'contact_information' => $contact_information
        );
        echo json_encode($response);
    } else {
        echo json_encode(array('message' => 'خطأ: ' . $conn->error));
        error_log("Error: " . $conn->error); // إضافة سجل للخطأ
    }
}

function update_student($student_id) {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);

    $fields = [];
    $params = [];
    $types = '';

    if (!empty($data["name"])) {
        $fields[] = "name=?";
        $params[] = $data["name"];
        $types .= 's';
    }
    if (!empty($data["class"])) {
        $fields[] = "class=?";
        $params[] = $data["class"];
        $types .= 's';
    }
    if (!empty($data["date_of_birth"])) {
        $fields[] = "date_of_birth=?";
        $params[] = $data["date_of_birth"];
        $types .= 's';
    }
    if (!empty($data["address"])) {
        $fields[] = "address=?";
        $params[] = $data["address"];
        $types .= 's';
    }
    if (!empty($data["contact_information"])) {
        $fields[] = "contact_information=?";
        $params[] = $data["contact_information"];
        $types .= 's';
    }

    $params[] = $student_id;
    $types .= 'i';

    $stmt = $conn->prepare("UPDATE students SET " . implode(', ', $fields) . " WHERE student_id=?");
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $response = array(
            'student_id' => $student_id,
            'name' => $data["name"] ?? null,
            'class' => $data["class"] ?? null,
            'date_of_birth' => $data["date_of_birth"] ?? null,
            'address' => $data["address"] ?? null,
            'contact_information' => $data["contact_information"] ?? null
        );
        echo json_encode($response);
    } else {
        echo json_encode(array('message' => 'خطأ: ' . $conn->error));
        error_log("Error: " . $conn->error); // إضافة سجل للخطأ
    }
}

function delete_student($student_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id=?");
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        echo json_encode(array('message' => 'تم حذف الطالب بنجاح.'));
    } else {
        echo json_encode(array('message' => 'خطأ: ' . $conn->error));
        error_log("Error: " . $conn->error); // إضافة سجل للخطأ
    }
}
?>
