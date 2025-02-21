<?php
session_start();
include '../config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = $_POST['event_id'];

// Cancel booking and increase seats
$stmt = $conn->prepare("DELETE FROM bookings WHERE user_id = ? AND event_id = ?");
$stmt->execute([$user_id, $event_id]);

if ($stmt->rowCount() > 0) {
    $stmt = $conn->prepare("UPDATE events SET available_seats = available_seats + 1 WHERE id = ?");
    $stmt->execute([$event_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
}