<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
include('config.php');

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM products";
if ($search) {
    $query .= " WHERE name LIKE '%$search%'";
}

$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode($products);
?>
