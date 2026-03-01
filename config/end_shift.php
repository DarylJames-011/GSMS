<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("UPDATE shifts SET shift_end = NOW(), status = 'ended' WHERE user_id = ? AND status = 'active'");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['message' => 'Shift ended']);
    } 
} else {
    echo json_encode(['error' => 'Database error: ' . $stmt->error]);
}
