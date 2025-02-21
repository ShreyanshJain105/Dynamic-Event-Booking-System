<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$stmt = $conn->query("SELECT * FROM events");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
include '../includes/header.php';
?>
<h2>Admin Dashboard</h2>
<a href="add_event.php">Add New Event</a>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Seats</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?php echo htmlspecialchars($event['title']); ?></td>
                <td><?php echo $event['date']; ?></td>
                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                <td><?php echo $event['available_seats']; ?></td>
                <td>
                    <a href="edit_event.php?id=<?php echo $event['id']; ?>">Edit</a>
                    <a href="delete_event.php?id=<?php echo $event['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                    <a href="view_bookings.php?event_id=<?php echo $event['id']; ?>">View Bookings</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>