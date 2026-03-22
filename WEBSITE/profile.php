<?php
require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
$user = currentUser();

$stmt = $pdo->prepare('SELECT is_admin FROM users WHERE id = ?');
$stmt->execute([$user['id']]);
$dbUser = $stmt->fetch();
$isAdmin = $dbUser['is_admin'] ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - LibraryHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="featured" style="max-width: 600px; margin: 4rem auto; padding: 2rem; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h2 class="section-title" style="font-size: 2rem;">Profile</h2>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        
        <div style="margin-top: 2rem; display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="favorites.php" class="cta-button" style="padding: 12px 24px; text-decoration: none;">❤️ My Favorites</a>
            <a href="settings.php" class="cta-button" style="padding: 12px 24px; text-decoration: none;">⚙️ Settings</a>
            <?php if ($isAdmin): ?>
                <a href="admin.php" class="cta-button" style="padding: 12px 24px; text-decoration: none; background: linear-gradient(135deg, #114847 0%, #0B3B42 100%);">📊 Admin Dashboard</a>
            <?php endif; ?>
        </div>

        <div style="margin-top: 2rem;">
            <a href="index.php" style="color:#114847; text-decoration:underline;">← Back to browse</a> |
            <a href="logout.php" style="color:#900c0f; font-weight:700; text-decoration:underline;">Log out</a>
        </div>
    </section>
</body>
</html>
