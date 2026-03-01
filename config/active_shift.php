<?php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch active shift
$stmt = $conn->prepare("SELECT * FROM shifts WHERE user_id = ? AND status='active' ORDER BY shift_start DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($shift = $result->fetch_assoc()) {
    echo json_encode(['shift_start' => $shift['shift_start']]);
} else {
    echo json_encode(['shift_start' => null]);
}
exit();
?>
