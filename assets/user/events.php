<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$stmt = $conn->query("SELECT * FROM events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
include '../includes/header.php';
?>
<h2>Available Events</h2>
<div id="event-list">
    <?php foreach ($events as $event): ?>
        <div class="event">
            <h3><?php echo htmlspecialchars($event['title']); ?></h3>
            <p><?php echo htmlspecialchars($event['description']); ?></p>
            <p>Date: <?php echo $event['date']; ?></p>
            <p>Venue: <?php echo htmlspecialchars($event['venue']); ?></p>
            <p>Seats: <?php echo $event['available_seats']; ?></p>
            <?php if ($event['available_seats'] > 0): ?>
                <button onclick="bookEvent(<?php echo $event['id']; ?>)">Book Now</button>
            <?php else: ?>
                <p style="color: red;">Sold Out</p>
            <?php endif; ?>
            <?php
            $stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = ? AND event_id = ?");
            $stmt->execute([$_SESSION['user_id'], $event['id']]);
            if ($stmt->rowCount() > 0): ?>
                <button onclick="cancelBooking(<?php echo $event['id']; ?>)">Cancel Booking</button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<?php include '../includes/footer.php'; ?>