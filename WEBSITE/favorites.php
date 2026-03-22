<?php
require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = currentUser();
$stmt = $pdo->prepare('
    SELECT b.* FROM books b
    INNER JOIN favorites f ON f.book_id = b.id
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
');
$stmt->execute([$user['id']]);
$favorites = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites - LibraryHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="index.php" class="logo">📚 LibraryHub</a>
            <div class="search-box">
                <i class="search-icon">🔍</i>
                <input type="text" placeholder="Search books, authors..." id="searchInput">
            </div>
            <div style="margin-left: 1rem; color: #FFFFFF; font-weight: 700;">
                Welcome, <?= htmlspecialchars($user['username']) ?> |
                <a href="favorites.php" style="color:#fff; text-decoration:underline;">Favorites</a> |
                <a href="settings.php" style="color:#fff; text-decoration:underline;">Settings</a> |
                <a href="logout.php" style="color:#fff; text-decoration:underline;">Logout</a>
            </div>
        </div>
    </header>

    <section class="featured" style="max-width: 1200px; margin: 4rem auto; padding: 0 2rem;">
        <h2 class="section-title">❤️ My Favorite Books</h2>
        <?php if (empty($favorites)): ?>
            <p style="text-align:center; color:#666; font-size:1.1rem;">You haven't added any favorites yet!</p>
        <?php else: ?>
            <div class="book-container">
                <?php foreach ($favorites as $book): ?>
                    <div class="book">
                        <a href="redirect.php?id=<?= htmlspecialchars($book['id']) ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?= htmlspecialchars($book['cover_url']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" loading="lazy">
                            <h4><?= htmlspecialchars($book['title']) ?></h4>
                        </a>
                        <button class="book-btn" onclick="removeFavorite(<?= $book['id'] ?>, this)" style="background: linear-gradient(135deg, #900C0F 0%, #AA594E 100%);">Remove ❤️</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <footer>
        <p>© 2025 LibraryHub - Your Gateway to Knowledge ✨</p>
    </footer>

    <script>
    function removeFavorite(bookId, button) {
        fetch('add_favorite.php?book_id=' + bookId + '&action=remove')
            .then(r => r.text())
            .then(data => {
                if (data === 'removed') {
                    button.closest('.book').style.opacity = '0';
                    setTimeout(() => location.reload(), 300);
                }
            });
    }
    </script>
</body>
</html>
