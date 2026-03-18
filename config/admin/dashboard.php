<?php
header('Content-Type: application/json');
include '../db.php';
$action = $_GET['action'] ?? '';


try {
    if ($action === 'getAlltotal') {
    $query = "SELECT SUM(total_amt) AS revenue FROM transaction_tbl WHERE status = 'Confirmed'";
    $result = $conn->query($query);

    $data = $result->fetch_assoc();
    $revenue = $data['revenue'] ?? 0;

    echo json_encode([
        'success' => true,
        'revenue' => number_format($revenue, 2) // formatted
    ]);     
    }
    if ($action === 'getTodaytotal') {
            $query = "
        SELECT SUM(total_amt) AS revenue 
        FROM transaction_tbl
        WHERE DATE(date_created) = CURDATE()
    ";
    $result = $conn->query($query);

    $data = $result->fetch_assoc();
    $revenue = $data['revenue'] ?? 0;

    echo json_encode([
        'success' => true,
        'revenue' => number_format($revenue, 2)
    ]);
    }

    if ($action === 'getfuelcap') {
    $maxCapacity = 20000;
    $query = "SELECT fuel_type, stock_ltrs FROM fuel_tbl";
    $result = $conn->query($query);

    $fuels = [];
    while ($row = $result->fetch_assoc()) {
        $percent = ($row['stock_ltrs'] / $maxCapacity) * 100;
        $fuels[] = [
            'type' => $row['fuel_type'],
            'percent' => round($percent)
        ];
    }

    echo json_encode([
        'success' => true,
        'fuels' => $fuels
    ]);
    }
 
    if ($action === 'lowstock') {
    $lowThreshold = 10; // define what counts as "low stock"

    // Count products with quantity <= threshold
    $query = "SELECT COUNT(*) AS lowCount FROM product_tbl WHERE stock <= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $lowThreshold);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc(); 

    $lowCount = $data['lowCount'] ?? 0;

    echo json_encode([
        'success' => true,
        'lowCount' => $lowCount
    ]);
    }

    if ($action === 'getfuelprice') {
    $query = "SELECT fuel_type, price_per_ltr FROM fuel_tbl";
    $result = $conn->query($query);

    $fuelPrices = [];

    while ($row = $result->fetch_assoc()) {
        $fuelPrices[$row['fuel_type']] = $row['price_per_ltr'];
    }

    echo json_encode([
        "success" => true,
        "data" => $fuelPrices
    ]);
    }

    if ($action === 'changeprice') {
        
        $data = json_decode(file_get_contents("php://input"), true);

        $diesel = $data['diesel'];
        $premium = $data['premium'];
        $unleaded = $data['unleaded'];

        // update each fuel
        $stmt = $conn->prepare("UPDATE fuel_tbl SET price_per_ltr = ? WHERE fuel_type = ?");

        // Diesel
        $stmt->bind_param("ds", $diesel, $fuel);
        $fuel = "Diesel";
        $stmt->execute();

        // Premium
        $stmt->bind_param("ds", $premium, $fuel);
        $fuel = "Premium";
        $stmt->execute();

        // Unleaded
        $stmt->bind_param("ds", $unleaded, $fuel);
        $fuel = "Unleaded";
        $stmt->execute();

        echo json_encode([
            "success" => true
        ]);
    }

    if ($action === 'createannounce') {
        $data = json_decode(file_get_contents("php://input"), true);

        $type = $data['type'] ?? '';
        $title = $data['title'] ?? '';
        $body = $data['body'] ?? '';

        // basic validation
        if (!$type || !$title || !$body) {
            echo json_encode([
                "success" => false,
                "message" => "All fields are required"
            ]);
            exit;
        }

        // prepare insert
        $stmt = $conn->prepare("INSERT INTO announcement_tbl (anc_type, title, message, date_created) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $type, $title, $body);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => $stmt->error]);
        }
    }

    if ($action === 'fetchrevenue') {
 $filter = $_GET['filter'] ?? 'Today';

// determine date range
$start = $end = date('Y-m-d');

if ($filter === 'Yesterday') {
    $start = $end = date('Y-m-d', strtotime('-1 day'));
} elseif ($filter === 'Last Week') {
    $start = date('Y-m-d', strtotime('last sunday -6 days'));
    $end = date('Y-m-d', strtotime('last sunday'));
}

// --- Fuel sales ---
$fuelQuery = $conn->prepare("
    SELECT f.fuel_type AS product_name,
           SUM(ti.quantity) AS units_sold,
           SUM(ti.subtotal) AS revenue,
           'L' AS unit_type
    FROM transaction_items ti
    JOIN fuel_tbl f ON ti.product_id = f.fuel_id
    JOIN transaction_tbl t ON ti.transaction_id = t.transaction_id
    WHERE ti.product_type = 'fuel'
      AND DATE(t.date_created) BETWEEN ? AND ?
      AND t.status = 'Confirmed'
    GROUP BY ti.product_id
");

$fuelQuery->bind_param("ss", $start, $end);
$fuelQuery->execute();
$fuelResult = $fuelQuery->get_result();

$sales = [];
while ($row = $fuelResult->fetch_assoc()) {
    $sales[] = $row;
}

// --- Product sales ---
$productQuery = $conn->prepare("
    SELECT p.product_name AS product_name,
           SUM(ti.quantity) AS units_sold,
           SUM(ti.subtotal) AS revenue,
           'pcs' AS unit_type
    FROM transaction_items ti
    JOIN product_tbl p ON ti.product_id = p.product_id
    JOIN transaction_tbl t ON ti.transaction_id = t.transaction_id
    WHERE ti.product_type = 'product'
      AND DATE(t.date_created) BETWEEN ? AND ?
      AND t.status = 'Confirmed'
    GROUP BY ti.product_id
");

$productQuery->bind_param("ss", $start, $end);
$productQuery->execute();
$productResult = $productQuery->get_result();

while ($row = $productResult->fetch_assoc()) {
    $sales[] = $row;
}

// Sort by revenue descending
usort($sales, fn($a,$b) => $b['revenue'] <=> $a['revenue']);

echo json_encode(['success' => true, 'sales' => $sales]);
    }

    if ($action === 'fetchtransactions') {

    $query = $conn->prepare("
        SELECT 
            t.transasction_id,
            t.transaction_no,
            t.date_created,
            u.username AS cashier_name,
            t.total_amt
        FROM transaction_tbl t
        JOIN user_table u ON t.user_id = u.user_id
        WHERE t.status = 'Confirmed'
        ORDER BY t.date_created DESC
        LIMIT 5
    ");

    $query->execute();
    $result = $query->get_result();

    $transactions = [];

    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

    echo json_encode([
        'success' => true,
        'transactions' => $transactions
    ]);
    exit;
}

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>