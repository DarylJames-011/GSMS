<?php
include 'db.php';
session_start();
header('Content-Type: application/json'); 

$action = $_GET['action'] ?? '';


if ($action === 'getproducts') {
header('Content-Type: application/json');

    // Run the query
    $sql = "SELECT *
FROM product_tbl
ORDER BY status = 'unavailable', date_created;";
    $result = $conn->query($sql);

    $orders = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    // Return JSON
    echo json_encode($orders);
    exit;


}

elseif ($action === 'getfuel') {
header('Content-Type: application/json');

    // Run the query
    $sql = "SELECT * FROM `fuel_tbl` WHERE 1";
    $result = $conn->query($sql);

    $orders = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    // Return JSON
    echo json_encode($orders);
    exit;


}
?>