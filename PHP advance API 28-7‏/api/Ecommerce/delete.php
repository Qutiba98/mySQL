<?php
error_reporting(0);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With");

include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $query = "DELETE FROM customers WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $response = [
                'status' => 200,
                'message' => 'Customer deleted successfully'
            ];
            header("Location: admin.php"); // Redirect back to admin page after deletion
        } else {
            $response = [
                'status' => 500,
                'message' => 'Failed to delete customer'
            ];
        }

        $stmt->close();
    } else {
        $response = [
            'status' => 400,
            'message' => 'Invalid customer ID'
        ];
    }
    echo json_encode($response);
} else {
    $response = [
        'status' => 405,
        'message' => $_SERVER["REQUEST_METHOD"] . ' Method Not Allowed'
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($response);
}
?>
