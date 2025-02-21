<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Booking System</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
    <header>
        <h1>Event Booking System</h1>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/index.php">Home</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/admin/dashboard.php">Admin Dashboard</a>
                <?php else: ?>
                    <a href="/user/events.php">Events</a>
                <?php endif; ?>
                <a href="/auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="/auth/login.php">Login</a>
                <a href="/auth/register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>