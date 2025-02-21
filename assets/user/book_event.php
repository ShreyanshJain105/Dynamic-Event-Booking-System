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

// Prevent duplicate bookings
$stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? AND event_id = ?");
$stmt->execute([$user_id, $event_id]);
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => false, 'message' => 'You already booked this event']);
    exit();
}

// Book event and reduce seats
$stmt = $conn->prepare("UPDATE events SET available_seats = available_seats - 1 WHERE id = ? AND available_seats > 0");
$stmt->execute([$event_id]);

if ($stmt->rowCount() > 0) {
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, event_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $event_id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Event is sold out']);
}