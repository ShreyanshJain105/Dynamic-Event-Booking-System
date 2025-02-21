<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$event_id = $_GET['event_id'];
$stmt = $conn->prepare("SELECT b.*, u.name FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.event_id = ?");
$stmt->execute([$event_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$event_stmt = $conn->prepare("SELECT title FROM events WHERE id = ?");
$event_stmt->execute([$event_id]);
$event = $event_stmt->fetch(PDO::FETCH_ASSOC);
include '../includes/header.php';
?>
<h2>Bookings for <?php echo htmlspecialchars($event['title']); ?></h2>
<table>
    <thead>
        <tr>
            <th>User Name</th>
            <th>Booking Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['name']); ?></td>
                <td><?php echo $booking['booking_date']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>