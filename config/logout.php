<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("UPDATE shifts SET shift_end = NOW(), status = 'ended' WHERE user_id = ? AND status = 'active'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

session_unset();
session_destroy();
header("Location: ../Login.php");
exit();
?>