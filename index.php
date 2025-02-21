<?php
session_start();
include 'config/db_connect.php';

// Debugging: Check session data
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

// Redirect logged-in users
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/events.php");
    }
    exit(); // Ensure no further code is executed after the redirect
}

// Include header
include 'includes/header.php';
?>

<h2>Welcome to the Event Booking System</h2>
<p>Please <a href="auth/login.php">login</a> or <a href="auth/register.php">register</a> to continue.</p>

<?php
// Include footer
include 'includes/footer.php';
?>