<?php
include 'db.php';
session_start();
header('Content-Type: application/json'); 

$action = $_GET['action'] ?? '';


if ($action === 'getproducts') {

$query = "SELECT product_id, product_name, price, stock FROM product_tbl ";
$result = $conn->query($query);

$products = [];

while ($row = $result->fetch_assoc()) {
    // Determine status
    if ($row['stock'] > 10) {
        $status = "Available";
        $color = "#48BA6B"; // green
    } elseif ($row['stock'] > 0) {
        $status = "Low Stock";
        $color = "#F0AD4E"; // orange
    } else {
        $status = "Out of Stock";
        $color = "#D9534F"; // red
    }

    $products[] = [
        "product_id" => $row['product_id'],
        "name" => $row['product_name'],
        "price" => $row['price'],
        "stock" => $row['stock'],
        "status" => $status,
        "color" => $color
    ];
}

echo json_encode($products);

}

if($action === 'getfuel') {
    $query = "SELECT fuel_type, price_per_ltr, stock_ltrs FROM fuel_tbl";
$result = $conn->query($query);

$fuels = [];
$max_capacity = 20000; // fixed

while ($row = $result->fetch_assoc()) {

    $percentage = ($row['stock_ltrs'] / $max_capacity) * 100;

    if ($percentage > 50) {
        $status = "Sufficient Stock";
        $color = "bg-green-500";
    } elseif ($percentage > 20) {
        $status = "Low";
        $color = "bg-yellow-400";
    } else {
        $status = "Critical";
        $color = "bg-red-600";
    }

    $fuels[] = [
        "name" => $row['fuel_type'],
        "price" => $row['price_per_ltr'],
        "stock" => $row['stock_ltrs'],
        "capacity" => $max_capacity,
        "percentage" => $percentage,
        "status" => $status,
        "color" => $color
    ];
}

echo json_encode($fuels);
}

if ($action === 'getproductinfo') {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM product_tbl WHERE product_id = $id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    echo json_encode($product);
} else {
    echo json_encode([]);
}
}


?>