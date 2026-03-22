<?php
require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = currentUser();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($current_password === '' || $new_password === '' || $confirm_password === '') {
            $error = 'All password fields are required.';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match.';
        } elseif (strlen($new_password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
            $stmt->execute([$user['id']]);
            $dbUser = $stmt->fetch();

            if (!$dbUser || !password_verify($current_password, $dbUser['password_hash'])) {
                $error = 'Current password is incorrect.';
            } else {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
                $stmt->execute([$new_hash, $user['id']]);
                $message = 'Password changed successfully!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - LibraryHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="index.php" class="logo">📚 LibraryHub</a>
            <div style="margin-left: 1rem; color: #FFFFFF; font-weight: 700;">
                Welcome, <?= htmlspecialchars($user['username']) ?> |
                <a href="profile.php" style="color:#fff; text-decoration:underline;">Profile</a> |
                <a href="favorites.php" style="color:#fff; text-decoration:underline;">Favorites</a> |
                <a href="logout.php" style="color:#fff; text-decoration:underline;">Logout</a>
            </div>
        </div>
    </header>

    <section class="featured" style="max-width: 500px; margin: 4rem auto; padding: 2rem; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h2 class="section-title" style="font-size: 2rem;">Account Settings</h2>
        
        <?php if ($message): ?>
            <p style="color: #00b894; text-align:center; margin-bottom:1rem; padding:0.5rem; border: 1px solid #00b894; border-radius: 8px;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p style="color: #900C0F; text-align:center; margin-bottom:1rem; padding:0.5rem; border: 1px solid #900C0F; border-radius: 8px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="settings.php">
            <input type="hidden" name="action" value="change_password">
            <h3 style="color: #114847; margin: 1.5rem 0 1rem;">Change Password</h3>
            
            <div style="margin-bottom: 1rem;">
                <label>Current Password</label>
                <input type="password" name="current_password" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label>New Password</label>
                <input type="password" name="new_password" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
            </div>
            
            <button class="cta-button" type="submit" style="width:100%;">Update Password</button>
        </form>
    </section>

    <footer>
        <p>© 2025 LibraryHub - Your Gateway to Knowledge ✨</p>
    </footer>
</body>
</html>
