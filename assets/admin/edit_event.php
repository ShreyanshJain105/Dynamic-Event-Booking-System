<?php
session_start();
include '../config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Retrieve event ID from query string
$event_id = $_GET['id'] ?? null;

if (!$event_id) {
    // Redirect or show an error if no event ID is provided
    header("Location: dashboard.php");
    exit();
}

// Fetch the event details from the database
$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    // Redirect or show an error if the event doesn't exist
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input validation and sanitization
    $title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
    $date = htmlspecialchars(trim($_POST['date']), ENT_QUOTES, 'UTF-8'); // Sanitize date as string
    $venue = htmlspecialchars(trim($_POST['venue']), ENT_QUOTES, 'UTF-8');
    $seats = filter_var($_POST['seats'], FILTER_VALIDATE_INT); // Validate integer for seats

    // Check if all required fields are present and valid
    if (empty($title) || empty($date) || empty($venue) || $seats === false || $seats < 0) {
        $error = "All required fields must be filled and valid.";
    } else {
        try {
            // Update the event in the database
            $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, date = ?, venue = ?, available_seats = ? WHERE id = ?");
            $stmt->execute([$title, $description, $date, $venue, $seats, $event_id]);
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error updating event: " . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>
<h2>Edit Event</h2>
<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
<form method="POST">
    <input type="text" name="title" value="<?php echo htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
    <textarea name="description"><?php echo htmlspecialchars($event['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
    <input type="datetime-local" name="date" value="<?php echo str_replace(' ', 'T', $event['date']); ?>" required>
    <input type="text" name="venue" value="<?php echo htmlspecialchars($event['venue'], ENT_QUOTES, 'UTF-8'); ?>" required>
    <input type="number" name="seats" value="<?php echo htmlspecialchars($event['available_seats'], ENT_QUOTES, 'UTF-8'); ?>" min="0" required>
    <button type="submit">Update Event</button>
</form>
<?php include '../includes/footer.php'; ?>