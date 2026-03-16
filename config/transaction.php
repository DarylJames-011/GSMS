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

if($action === 'getsummary') {
$user_id = $_SESSION['user_id'] ?? null;    

    // Get the most recent ended shift
    $shiftQuery = $conn->prepare("
        SELECT shift_start, shift_end
        FROM shifts
        WHERE user_id = ?
        AND shift_end IS NOT NULL
        ORDER BY shift_end DESC
        LIMIT 2
    ");
    $shiftQuery->bind_param("i", $user_id);
    $shiftQuery->execute();
    $result = $shiftQuery->get_result();
    $shifts = $result->fetch_all(MYSQLI_ASSOC);
    $previousTotal = 0;
    $lastShift = $shifts[0] ?? null;
    $prevShift = $shifts[1] ?? null;

    if (!$lastShift) {
        echo json_encode(['summary' => null]);
        exit;
    }

    if ($prevShift) {

    $prevStart = $prevShift['shift_start'];
    $prevEnd = $prevShift['shift_end'];

    $prevQuery = $conn->prepare("
        SELECT SUM(total_amt) AS totalSales
        FROM transaction_tbl
        WHERE user_id = ?
        AND status != 'Void'
        AND date_created BETWEEN ? AND ?
    ");

    $prevQuery->bind_param("iss", $user_id, $prevStart, $prevEnd);
    $prevQuery->execute();
    $prevResult = $prevQuery->get_result()->fetch_assoc();

    $previousTotal = $prevResult['totalSales'] ?? 0;
    }

    $shiftStart = $lastShift['shift_start'];
    $shiftEnd = $lastShift['shift_end'];

    // Get summary stats
    $summaryQuery = $conn->prepare("
        SELECT
        COUNT(DISTINCT t.transaction_id) AS transactions,
        SUM(CASE WHEN ti.product_type='fuel' THEN ti.quantity ELSE 0 END) AS totalLiters,
        SUM(CASE WHEN ti.product_type='product' THEN ti.quantity ELSE 0 END) AS totalProducts,
        SUM(t.total_amt) AS totalSales,
        SUM(CASE WHEN t.payment_method='Cash' THEN t.total_amt ELSE 0 END) AS cashTotal,
        SUM(CASE WHEN t.payment_method='Credit' THEN t.total_amt ELSE 0 END) AS creditTotal,
        SUM(CASE WHEN t.payment_method='Online' THEN t.total_amt ELSE 0 END) AS onlineTotal,
        (SELECT COUNT(*)
        FROM transaction_tbl t2
        WHERE t2.user_id = ?
        AND t2.status='Void'
        AND t2.date_created BETWEEN ? AND ?) AS voidedTransactions
    FROM transaction_tbl t
    LEFT JOIN transaction_items ti
        ON t.transaction_id = ti.transaction_id
    WHERE t.user_id = ?
    AND t.date_created BETWEEN ? AND ?
    AND t.status != 'Void';
    ");

    $summaryQuery->bind_param(
        "ississ",
        $user_id, $shiftStart, $shiftEnd,
        $user_id, $shiftStart, $shiftEnd
    );

    $summaryQuery->execute();
    $summary = $summaryQuery->get_result()->fetch_assoc();
    $summary['totalLiters'] = (float)$summary['totalLiters'];
    $summary['totalProducts'] = (int)$summary['totalProducts'];
    $summary['cashTotal'] = (float)$summary['cashTotal'];
    $summary['creditTotal'] = (float)$summary['creditTotal'];
    $summary['onlineTotal'] = (float)$summary['onlineTotal'];


    echo json_encode([
        'summary' => $summary,
        'previousTotal' => (float)$previousTotal,
        'shiftStart' => $shiftStart,
        'shiftEnd' => $shiftEnd
    ]);
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
    $reference_num = $postData['reference_num'] ?? null;  // new field
    $amt_received = isset($postData['amt_received']) ? floatval($postData['amt_received']) : 0; $amt_received = $postData['amt_received'] ?? 0;       // new field
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
        $stmt = $conn->prepare("
    INSERT INTO transaction_tbl 
        (user_id, date_created, total_amt, payment_method, reference_num, amt_received) 
    VALUES 
        (?, NOW(), ?, ?, ?, ?)
");

    $stmt->bind_param("idssd", $user_id, $total_amount, $payment_method, $reference_num, $amt_received);
        
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
    
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT transaction_no, payment_method, total_amt, date_created, status 
            FROM transaction_tbl 
            WHERE user_id = '$user_id'
            ORDER BY date_created DESC";

    $result = $conn->query($sql);

    $transactions = [];

    while ($row = $result->fetch_assoc()) {

        // convert YYYY-MM-DD HH:MM:SS → MM-DD-YYYY
        $row['date_created'] = date("m-d-Y", strtotime($row['date_created']));

        // NEW: numeric part of transaction_no
        $row['transaction_id'] = str_replace('TRNS-', '', $row['transaction_no']);

        $transactions[] = $row;
    }

    echo json_encode($transactions);
}

if ($action === 'viewTransaction') {
$id = $_GET['id'];

    $stmt = $conn->prepare("SELECT 
    t.transaction_id,
    t.transaction_no,
    t.payment_method,
    t.status,
    t.total_amt,
    t.reference_num,
    t.amt_received,
    t.date_created,
    ti.transaction_id2,
    ti.product_type,
    ti.product_id,
    ti.quantity,
    ti.price,
    ti.subtotal,
    CASE 
        WHEN ti.product_type = 'product' THEN p.product_name
        WHEN ti.product_type = 'fuel' THEN f.fuel_type
        ELSE 'Unknown'
    END AS item_name
FROM transaction_tbl t
JOIN transaction_items ti ON t.transaction_id = ti.transaction_id
LEFT JOIN product_tbl p ON ti.product_type = 'product' AND ti.product_id = p.product_id
LEFT JOIN fuel_tbl f ON ti.product_type = 'fuel' AND ti.product_id = f.fuel_id
WHERE t.transaction_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    $transactions = [];

    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

    echo json_encode($transactions);
}


if($action === 'voidTransaction') {

    // Read JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    $transaction_id = $data['transaction_id'] ?? 0;

    if(!$transaction_id){
        echo json_encode(['success' => false, 'message' => 'No transaction ID provided']);
        exit;
    }


    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE transaction_tbl SET status = 'Void' WHERE transaction_id = ? AND status = 'Confirmed'");
    $stmt->bind_param("i", $transaction_id);

    if($stmt->execute()){
        // If rows affected is 0, either ID is wrong or already voided
        if($stmt->affected_rows > 0){
            echo json_encode(['success' => true, 'message' => 'Transaction voided successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Transaction already voided or invalid ID']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to void transaction']);
    }

    $stmt->close();
    $conn->close();
    exit;
}

if($action === 'verifypass') { 
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);
        $password = $data['password'] ?? '';

        // Fetch admin user(s) - usually only one
        $stmt = $conn->prepare("SELECT password FROM user_table WHERE role='Administrator' LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();

        if($result && $result->num_rows > 0){
            $admin = $result->fetch_assoc();

            // Verify password securely
            if($password === $admin['password']){
                echo json_encode(['success' => true]);
                exit;
            }
        }

        // If we reach here, password is wrong
        echo json_encode(['success' => false]);
        exit;
    }

if ($action === 'getReceipt') {

    $transaction_id = $_GET['transaction_id'];

    // Transaction info
   $transaction = $conn->query("
    SELECT 
        t.transaction_no,
        t.total_amt,
        t.payment_method,
        t.reference_num,
        t.date_created,
        t.amt_received,
        u.username
    FROM transaction_tbl t
    JOIN user_table u 
        ON t.user_id = u.user_id
    WHERE t.transaction_id = $transaction_id
")->fetch_assoc();


    // Items
    $items = [];

    $result = $conn->query("
        SELECT 
            product_type,
            product_id,
            quantity,
            price,
            subtotal
        FROM transaction_items
        WHERE transaction_id = $transaction_id
    ");

    while($row = $result->fetch_assoc()) {

        if($row['product_type'] === 'product'){

            $name = $conn->query("
                SELECT product_name 
                FROM product_tbl 
                WHERE product_id = {$row['product_id']}
            ")->fetch_assoc()['product_name'];

        } else {

            $name = $conn->query("
                SELECT fuel_type
                FROM fuel_tbl 
                WHERE fuel_id = {$row['product_id']}
            ")->fetch_assoc()['fuel_type'];

        }

        $items[] = [
            "name" => $name,
            "qty" => $row['quantity'],
            "price" => $row['price'],
            "subtotal" => $row['subtotal']
        ];
    }

    echo json_encode([
        "transaction" => $transaction,
        "items" => $items
    ]);
}

?>