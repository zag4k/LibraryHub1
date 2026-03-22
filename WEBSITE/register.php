<?php
require_once 'config.php';
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: profile.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $message = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please provide a valid email address.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $message = 'Username or email already taken.';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $password_hash]);
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LibraryHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="featured" style="max-width: 500px; margin: 4rem auto; padding: 2rem; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h2 class="section-title" style="font-size: 2rem;">Create Account</h2>
        <?php if ($message): ?>
            <p style="color: #900C0F; text-align:center; margin-bottom:1rem;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <div style="margin-bottom: 1rem;">
                <label>Username</label>
                <input type="text" name="username" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label>Email</label>
                <input type="email" name="email" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label>Password</label>
                <input type="password" name="password" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>
            <button class="cta-button" type="submit" style="width:100%;">Register</button>
        </form>
        <p style="margin-top:1rem; text-align:center;">Have account? <a href="login.php">Login</a></p>
    </section>
</body>
</html>
