<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['fuel'])) {

    header('Content-Type: application/json');

    $fuel = $_GET['fuel'];

    $stmt = $conn->prepare("
       SELECT stock_ltrs
        FROM fuel_tbl WHERE
        fuel_type = ?
    ");

    $stmt->bind_param("s", $fuel);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        $liters = $row['stock_ltrs'];
        $capacity = 20000;

        $percentage = ($capacity > 0)
            ? round(($liters / $capacity) * 100)
            : 0;

        if ($percentage > 50) {
            $status = "Sufficient Stock"; // Green
        } elseif ($percentage > 20) {
            $status = "Monitoring";       // Yellow
        } else {
            $status = "Critical Low";     // Red
        }

        echo json_encode([
            "currentLiters" => $liters,
            "capacity" => $capacity,
            "percentage" => $percentage,
            "status" => $status
        ]);
    } else {
        echo json_encode(["error" => "Fuel not found"]);
    }

    $stmt->close();
    $conn->close();
    exit; 
}



if (isset($_GET['action']) && $_GET['action'] === 'fuel') {
    
    header('Content-Type: application/json');

    $sql = "SELECT fuel_type, stock_ltrs FROM fuel_tbl ORDER BY fuel_type ASC;";
    $result = mysqli_query($conn, $sql);

    $tanks = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $tanks[] = [
            "type" => $row['fuel_type'],
            "current" => (int)$row['stock_ltrs']
        ];
    }

    echo json_encode($tanks);
    exit;
}




if ($_SERVER["REQUEST_METHOD"] === "POST") {

      if (isset($_POST['fuel_typ'])) {
           $invoice = $_POST['invoice'];
    $supplier = $_POST['supplier'];
    $date_o = $_POST['date_o'];
    $date_r = $_POST['date_r'];
    $note = $_POST['note'];
    $qty = (int)$_POST['qty'];
    $fuel_typ = $_POST['fuel_typ'];

    $conn->begin_transaction();

    try {

    $check = $conn->prepare("SELECT invoice_number FROM received_order WHERE invoice_number = ?");
    $check->bind_param("s", $invoice);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        echo json_encode([
            'status' => 'error',
            'field' => 'invoice',
            'message' => 'Invoice number already exists!'
        ]);
        $conn->close();
        exit;
    }
    $check->close();


    $stmt = $conn->prepare("SELECT stock_ltrs FROM fuel_tbl WHERE fuel_type = ?");
    $stmt->bind_param("s", $fuel_typ);
    $stmt->execute();
    $stmt->bind_result($current_stock);
    $stmt->fetch();
    $stmt->close();

    $max_stock = 20000;
    $new_total = $current_stock + $qty;
    if ($new_total > $max_stock) {
        echo json_encode([
            'status' => 'error',
            'field' => 'qty',
            'message' => 'Quantity exceeds max stock. Available: ' . ($max_stock - $current_stock) . ' liters.'
        ]);
        $conn->close();
        exit;
    }

            $stmt1 = $conn->prepare("INSERT INTO received_order (invoice_number, supplier_name, date_ordered, date_received, notes) VALUES (?, ?, ?, ?, ?)");
            $stmt1->bind_param("sssss", $invoice, $supplier, $date_o, $date_r, $note);
            $stmt1->execute();
            $stmt1->close();

        $fuel_stmt = $conn->prepare("SELECT fuel_id FROM fuel_tbl WHERE fuel_type = ?");
        $fuel_stmt->bind_param("s", $fuel_typ);
        $fuel_stmt->execute();
        $fuel_stmt->bind_result($fuel_id);
        $fuel_stmt->fetch();
        $fuel_stmt->close();

        $stmt2 = $conn->prepare("INSERT INTO received_order_fuel (invoice_number, fuel_id, liters) VALUES (?, ?, ?)");
        $stmt2->bind_param("sii", $invoice, $fuel_id, $qty );
        $stmt2->execute();
        $stmt2->close();

        $conn->commit();
        echo json_encode([
        'status' => 'success',
        'message' => 'Saved successfully!',
        'invoice' => $invoice
    ]);
    } catch (Exception $e) {
        $conn->rollback();
       echo json_encode([
        'status' => 'error',
        'field' => null, // always present
        'message' => $e->getMessage()
    ]);
    }

    $conn->close();
    }

    if (isset($_POST['prod_name'])) {
        // NEW POST block for saving products
        $productName = $_POST['prod_name'];
        $price = (float)$_POST['price_pu'];
        $description = $_POST['desc'];

        // Duplicate check example
        $check = $conn->prepare("SELECT product_id FROM product_tbl  WHERE product_name = ?");
        $check->bind_param("s", $productName);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            echo json_encode([
                'status' => 'error',
                'field' => 'product_name',
                'message' => 'Product already exists!'
            ]);
            $check->close();
            exit;
        }
        $check->close();

        $filename = null;
        if (isset($_FILES['prod_img']) && $_FILES['prod_img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/products/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

            $tmpName = $_FILES['prod_img']['tmp_name'];
            $filename = basename($_FILES['prod_img']['name']);

            // Optional: rename file to avoid collisions
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $filename = uniqid('prod_') . '.' . $ext;

            $targetFile = $uploadDir . $filename;

            if (!move_uploaded_file($tmpName, $targetFile)) {
                echo json_encode([
                    'status' => 'error',
                    'field' => 'product_image',
                    'message' => 'Failed to upload image.'
                ]);
                exit;
            }
        }

        $stmt = $conn->prepare("INSERT INTO product_tbl (product_name, price, description, image) VALUES (?, ?, ?,?)");
        $stmt->bind_param("sdss", $productName, $price, $description, $filename);
        $stmt->execute();
        $stmt->close();

        echo json_encode([
            'status' => 'success',
            'message' => 'Product saved successfully'
        ]);
        exit;
    }

}

$action = $_GET['action'] ?? '';

if ($action === 'recent_orders') {
    
    header('Content-Type: application/json');
    $sql = "SELECT * FROM received_order ORDER BY date_created DESC LIMIT 3";
    $result = $conn->query($sql);

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($orders);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'get_order' && isset($_GET['id'])) {
    $orderId = intval($_GET['id']); 

    $stmt = $conn->prepare("
     SELECT o.order_id, o.supplier_name, o.invoice_number, o.date_ordered, o.date_received, o.notes, o.date_created,
      i.fuel_id, i.liters
        FROM received_order o
        LEFT JOIN received_order_fuel i ON o.order_id = i.id
        WHERE o.order_id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();

    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if ($order) {
        echo json_encode($order);
    } else {
        echo json_encode(["error" => "Order not found"]);
    }

    exit;
}

if ($action === 'get_product') {
$result = $conn->query("SELECT product_id, product_name, price, stock, description, status, restock_date FROM product_tbl");

$products = [];
while ($row = $result->fetch_assoc()) {
    
    if ($row['restock_date'] === null) {
        $row['restock_date'] = "Not restocked yet";
    }
    $products[] = $row;
}

// Check what we got
echo json_encode($products, JSON_PRETTY_PRINT);

$conn->close();  
}

if ($action === 'fetchitem') {
   $product_id = intval($_GET['fetchitem']);


    $stmt = $conn->prepare("SELECT * FROM product_tbl WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();


    header('Content-Type: application/json');

    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(["error" => "Product not found"]);
    }

    $stmt->close();
    $conn->close();
    exit; 
}

