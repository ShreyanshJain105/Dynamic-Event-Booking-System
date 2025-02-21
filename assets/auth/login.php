<?php
session_start();
include '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: ../index.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
include '../includes/header.php';
?>
<h2>Login</h2>
<?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<?php include '../includes/footer.php'; ?>