<?php
session_start();
include '../config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
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
            $stmt = $conn->prepare("INSERT INTO events (title, description, date, venue, available_seats) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $date, $venue, $seats]);
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $error = "Error creating event: " . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>
<h2>Add New Event</h2>
<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
<form method="POST">
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="description" placeholder="Description"></textarea>
    <input type="datetime-local" name="date" required>
    <input type="text" name="venue" placeholder="Venue" required>
    <input type="number" name="seats" placeholder="Available Seats" min="0" required>
    <button type="submit">Add Event</button>
</form>
<?php include '../includes/footer.php'; ?>