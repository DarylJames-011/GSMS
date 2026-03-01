<?php
session_start();
header('Content-Type: application/json'); // ensures browser sees JSON

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
date_default_timezone_set('Asia/Manila'); 
$now = date('Y-m-d H:i:s');

try {
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
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}
