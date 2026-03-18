<?php
header('Content-Type: application/json');
include '../db.php';
$action = $_GET['action'] ?? '';

try {

    if ($action === 'fetchrevenue') {


        $filter = $_GET['filter'] ?? 'Today';

        $condition = "DATE(date_created) = CURDATE()";

        if ($filter === 'Yesterday') {
            $condition = "DATE(date_created) = CURDATE() - INTERVAL 1 DAY";
        } elseif ($filter === 'Last Week') {
            $condition = "DATE(date_created) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()";
        } elseif ($filter === 'Past Month') {
            $condition = "DATE(date_created) BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE()";
        }

        $query = $conn->query("
            SELECT SUM(total_amt) AS total_revenue
            FROM transaction_tbl
            WHERE status = 'Confirmed'
            AND $condition
        ");

        $row = $query->fetch_assoc();

        echo json_encode([
            'success' => true,
            'revenue' => $row['total_revenue'] ?? 0
        ]);
        exit;
    }

    if ($action === 'fetchliters') {

    $filter = $_GET['filter'] ?? 'All';

    $sql = "
        SELECT SUM(ti.quantity) AS total_liters
        FROM transaction_items ti
        JOIN transaction_tbl t ON ti.transaction_id = t.transaction_id
        LEFT JOIN fuel_tbl f ON ti.product_id = f.fuel_id
        WHERE ti.product_type = 'fuel'
        AND t.status = 'Confirmed'
    ";

    // If not "All", filter by fuel name
    if ($filter !== 'All') {
        $sql .= " AND f.fuel_type = ?";
    }

    $query = $conn->prepare($sql);

    if ($filter !== 'All') {
        $query->bind_param("s", $filter);
    }

    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();

    echo json_encode([
        'success' => true,
        'liters' => $row['total_liters'] ?? 0
    ]);
    exit;
    }

    if ($action === 'fetchalltransactions') {

    $query = $conn->prepare("
        SELECT 
            t.transaction_id,
            t.transaction_no,
            t.date_created,
            t.payment_method,
            u.username AS cashier_name,
            t.total_amt,
            t.status
        FROM transaction_tbl t
        JOIN user_table u ON t.user_id = u.user_id
        ORDER BY t.date_created DESC
        LIMIT 20
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

  if ($_GET['action'] === 'fetchtransactiondetails') {
    if (!isset($_GET['id'])) {
        echo json_encode(['success' => false, 'message' => 'No transaction ID provided.']);
        exit;
    }

    $id = intval($_GET['id']); // force it to integer

    // Fetch transaction
    $tQuery = $conn->prepare("
        SELECT * 
        FROM transaction_tbl 
        WHERE transaction_id = ?
    ");

    if (!$tQuery) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $tQuery->bind_param("i", $id);
    $tQuery->execute();
    $transaction = $tQuery->get_result()->fetch_assoc();

    if (!$transaction) {
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
        exit;
    }

    // Fetch transaction items
    $iQuery = $conn->prepare("
        SELECT ti.*, 
               COALESCE(p.product_name, f.fuel_type) AS product_name,
               COALESCE(f.fuel_type, '') AS unit_type
        FROM transaction_items ti
        LEFT JOIN product_tbl p ON ti.product_id = p.product_id AND ti.product_type='product'
        LEFT JOIN fuel_tbl f ON ti.product_id = f.fuel_id AND ti.product_type='fuel'
        WHERE ti.transaction_id = ?
    ");

    if (!$iQuery) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $iQuery->bind_param("i", $id);
    $iQuery->execute();
    $items = $iQuery->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        'success' => true,
        'transaction' => $transaction,
        'items' => $items
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