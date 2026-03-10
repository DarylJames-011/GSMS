<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Only check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Alpha Stage/Login.php"); // remove space from folder
    exit();
}

// Optional: fetch role if not already in session
if (!isset($_SESSION['role'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT role FROM user_table WHERE user_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if (!$user_data) {
        session_destroy();
        header("Location: /AlphaStage/Login.php");
        exit();
    }

    $_SESSION['role'] = $user_data['role'];
}
?>