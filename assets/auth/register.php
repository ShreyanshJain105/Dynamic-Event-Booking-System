<?php
session_start();
include '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input validation and sanitization
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL); // Validate email
    $password = $_POST['password'];

    // Check if inputs are valid
    if (empty($name) || !$email || empty($password)) {
        $error = "All fields are required and must be valid.";
    } else {
        try {
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert the user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password]);

            // Redirect to login page after successful registration
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            // Handle duplicate email or other database errors
            $error = "Email already exists or an error occurred.";
        }
    }
}

include '../includes/header.php';
?>
<h2>Register</h2>
<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
<form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Register</button>
</form>
<?php include '../includes/footer.php'; ?>