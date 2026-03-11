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

if ($action === 'getfuel') {
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

if ($action === 'saveTransaction') {
    $postData = json_decode(file_get_contents('php://input'), true);

    $cart = $postData['cart'] ?? [];
    $payment_method = $postData['payment_method'] ?? 'Cash';
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(['status'=>'error','message'=>'User not logged in']);
        exit;
    }
    
    // calculate total amount
    $total_amount = 0;
    foreach ($cart as $id => $item) {
        if (is_array($item)) {
            // fuel
            $total_amount += $item['pesos'];
        } else {
            // product
            $row = $conn->query("SELECT price FROM product_tbl WHERE product_id = $id")->fetch_assoc();
            if (!$row) continue; // skip invalid product
            $total_amount += $item * $row['price'];
        }
    }

    $conn->begin_transaction();

    try {
        // 1️ Insert transaction
        $stmt = $conn->prepare("INSERT INTO transaction_tbl (user_id, date_created, total_amt, payment_method) VALUES (?, NOW(), ?, ?)");
        $stmt->bind_param("ids", $user_id, $total_amount, $payment_method);
        $stmt->execute();
        $transaction_id = $stmt->insert_id;
        $stmt->close();

        //  Generate transaction_no
        $transaction_no = 'TRNS-' . str_pad($transaction_id, 3, '0', STR_PAD_LEFT);
        $update = $conn->prepare("UPDATE transaction_tbl SET transaction_no = ? WHERE transaction_id = ?");
        $update->bind_param("si", $transaction_no, $transaction_id);
        $update->execute();
        $update->close();

        // Insert each cart item
        $stmt = $conn->prepare("INSERT INTO transaction_items (transaction_id, product_type, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($cart as $id => $item) {
            if (is_array($item)) {
                // Fuel
                $product_type = 'fuel';
                $product_id = $id;                 // fuel_id
                $quantity = $item['liters'];
                $subtotal = $item['pesos'];
                $price = $subtotal / $quantity;
            } else {
                // Product
                $product_type = 'product';
                $product_id = $id;                 // product_id
                $row = $conn->query("SELECT price FROM product_tbl WHERE product_id = $id")->fetch_assoc();
                if (!$row) continue;
                $price = $row['price'];
                $quantity = $item;
                $subtotal = $quantity * $price;
            }

            $stmt->bind_param("isiddd", $transaction_id, $product_type, $product_id, $quantity, $price, $subtotal);
            $stmt->execute();
        }

        $stmt->close();
        $conn->commit();

        echo json_encode([
            'status' => 'success',
            'transaction_id' => $transaction_id,
            'transaction_no' => $transaction_no
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
    }
}

if ($action === 'getTransaction') {

    $sql = "SELECT transaction_no, payment_method, total_amt, date_created FROM transaction_tbl";
    $result = $conn->query($sql);

    $transactions = [];

    while ($row = $result->fetch_assoc()) {

        // convert YYYY-MM-DD HH:MM:SS → DD/MM/YYYY
        $row['date_created'] = date("d/m/Y", strtotime($row['date_created']));

        $transactions[] = $row;
    }

    echo json_encode($transactions);
}
?>