<?php
require_once 'config.php';
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $message = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, email, password_hash FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            loginUser($user);
            header('Location: profile.php');
            exit;
        } else {
            $message = 'Invalid username/password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LibraryHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="featured" style="max-width: 500px; margin: 4rem auto; padding: 2rem; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h2 class="section-title" style="font-size: 2rem;">Login</h2>
        <?php if ($message): ?>
            <p style="color: #900C0F; text-align:center; margin-bottom:1rem;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div style="margin-bottom: 1rem;">
                <label>Username</label>
                <input type="text" name="username" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label>Password</label>
                <input type="password" name="password" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>
            <button class="cta-button" type="submit" style="width:100%;">Login</button>
        </form>
        <p style="margin-top:1rem; text-align:center;">No account? <a href="register.php">Register</a></p>
    </section>
</body>
</html>
