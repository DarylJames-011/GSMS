<?php
session_start();
ini_set('display_errors', 1);  // show errors for debugging
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';
date_default_timezone_set('Asia/Manila'); 
$now = date('Y-m-d H:i:s');

try {

    if ($action === 'lowStock') {
        $query = "
            SELECT 'product' AS item_type, product_id AS item_id, product_name AS item_name, stock AS stock
            FROM product_tbl
            WHERE stock <= 10
            UNION ALL
            SELECT 'fuel' AS item_type, fuel_id AS item_id, fuel_type AS item_name, stock_ltrs AS stock
            FROM fuel_tbl
            WHERE stock_ltrs <= 5000
        ";
        $result = $conn->query($query);

        $low_stocks = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $low_stocks[] = $row;
            }
        }

        echo json_encode($low_stocks);
        exit();
    }

    if ($action === 'getTransaction') {
        $stmt = $conn->prepare("
            SELECT transaction_no, payment_method, total_amt, date_created, status 
            FROM transaction_tbl 
            WHERE user_id = ? 
            ORDER BY date_created DESC LIMIT 3
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $row['date_created'] = date("m-d-Y", strtotime($row['date_created']));
            $row['transaction_id'] = str_replace('TRNS-', '', $row['transaction_no']);
            $transactions[] = $row;
        }

        echo json_encode($transactions);
        exit();
    }

    if ($action === 'startShift') {
        // Check for active shift
        $stmt = $conn->prepare("SELECT * FROM shifts WHERE user_id = ? AND status='active'");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $insert = $conn->prepare("INSERT INTO shifts (user_id, shift_start) VALUES (?, ?)");
            $insert->bind_param("is", $user_id, $now);
            $insert->execute();
        }

        echo json_encode(['shift_start' => $now]);
        exit();
    }

   if ($action === 'updatecard') {
    $userId = $_SESSION['user_id']; // logged-in user

    // 1️⃣ Current shift (active)
    $currentShiftQuery = $conn->prepare("
        SELECT shift_id, shift_start
        FROM shifts
        WHERE user_id = ?
          AND shift_end IS NULL
        LIMIT 1
    ");
    $currentShiftQuery->bind_param("i", $userId);
    $currentShiftQuery->execute();
    $currentShiftResult = $currentShiftQuery->get_result();
    $currentShift = $currentShiftResult->fetch_assoc() ?? null;

    // 2️⃣ Previous shift (most recent ended)
    $prevShiftQuery = $conn->prepare("
        SELECT shift_id, shift_start, shift_end
        FROM shifts
        WHERE user_id = ?
          AND shift_end IS NOT NULL
        ORDER BY shift_end DESC
        LIMIT 1
    ");
    $prevShiftQuery->bind_param("i", $userId);
    $prevShiftQuery->execute();
    $prevShiftResult = $prevShiftQuery->get_result();
    $prevShift = $prevShiftResult->fetch_assoc() ?? null;

    function getShiftStats($conn, $userId, $shiftStart, $shiftEnd) {
        $query = $conn->prepare("
            SELECT 
                COUNT(DISTINCT t.transaction_id) AS transactions,
                SUM(t.total_amt) AS totalSales,
                SUM(CASE WHEN ti.product_type='fuel' THEN ti.quantity ELSE 0 END) AS fuelSold
            FROM transaction_tbl t
            LEFT JOIN transaction_items ti
              ON t.transaction_id = ti.transaction_id
            WHERE t.user_id = ?
              AND t.date_created BETWEEN ? AND ?
              AND t.status = 'Confirmed'
        ");
        $query->bind_param("iss", $userId, $shiftStart, $shiftEnd);
        $query->execute();
        return $query->get_result()->fetch_assoc();
    }

    $currentStats = $currentShift ? getShiftStats($conn, $userId, $currentShift['shift_start'], date('Y-m-d H:i:s')) : null;
    $prevStats = $prevShift ? getShiftStats($conn, $userId, $prevShift['shift_start'], $prevShift['shift_end']) : null;


    echo json_encode([
        'currentShift' => $currentShift ? ['stats' => $currentStats] : null,
        'prevShift' => $prevShift ? ['stats' => $prevStats] : null
    ]);
    exit;
    }

    // Unknown action
    echo json_encode(['error' => 'Invalid action']);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}