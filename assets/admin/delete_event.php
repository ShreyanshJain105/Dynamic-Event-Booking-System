<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$event_id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
$stmt->execute([$event_id]);
header("Location: dashboard.php");
exit();
?>