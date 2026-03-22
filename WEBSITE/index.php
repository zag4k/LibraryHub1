<?php
require_once 'config.php';
require_once 'auth.php';

$stmt = $pdo->query('SELECT * FROM books ORDER BY created_at DESC');
$books = $stmt->fetchAll();
$loggedIn = isLoggedIn();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Library Management System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="#home" class="logo">📚 LibraryHub</a>
            <div class="search-box">
                <i class="search-icon">🔍</i>
                <input type="text" placeholder="Search books, authors..." id="searchInput">
            </div>
            <div style="margin-left: 1rem; color: #FFFFFF; font-weight: 700;">
                <?php if ($loggedIn): ?>
                    Welcome, <?= htmlspecialchars($user['username']) ?> |
                    <a href="profile.php" style="color:#fff; text-decoration:underline;">Profile</a> |
                    <a href="logout.php" style="color:#fff; text-decoration:underline;">Logout</a>
                <?php else: ?>
                    <a href="login.php" style="color:#fff; text-decoration:underline;">Login</a> |
                    <a href="register.php" style="color:#fff; text-decoration:underline;">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#featured">Featured</a></li>
            <li><a href="#arrivals">New Arrivals</a></li>
            <li><a href="#reviews">Reviews</a></li>
            <li><a href="#blogs">Blogs</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-text">
            <h1>Discover Your Next<br>Favorite Book</h1>
            <p>Explore our curated collection of bestsellers, new releases, and timeless classics. Unlimited access to wisdom awaits.</p>
            <button class="cta-button" onclick="scrollToFeatured()">Browse Collection</button>
        </div>
        <img src="https://i.pinimg.com/1200x/ab/b4/03/abb4034b8dadc06cb33a2398c0b5c557.jpg" alt="Reading" loading="lazy">
    </section>

    <!-- Featured Books -->
    <section class="featured" id="featured">
        <h2 class="section-title">Featured Collection</h2>
        <div class="book-container" id="bookContainer">
            <?php foreach ($books as $book): ?>
                <div class="book" data-link="<?= htmlspecialchars($book['read_url']) ?>">
                    <a href="redirect.php?id=<?= htmlspecialchars($book['id']) ?>" class="card-link" target="_blank" rel="noopener noreferrer">
                        <img src="<?= htmlspecialchars($book['cover_url']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" loading="lazy">
                        <h4><?= htmlspecialchars($book['title']) ?></h4>
                        <?php if (!empty($book['author'])): ?>
                            <p class="author"><?= htmlspecialchars($book['author']) ?></p>
                        <?php endif; ?>
                    </a>
                    <?php if ($loggedIn): ?>
                        <button class="book-btn" onclick="toggleFavorite(<?= $book['id'] ?>, this)" style="background: linear-gradient(135deg, #F8D7DA 0%, #F5C6CB 100%); color: #900C0F; font-weight: 600;">❤️ Add to Favorites</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- (Remaining page structure copied from current static HTML - unchanged) -->

    <section class="featured" id="arrivals" style="background: linear-gradient(135deg, #AA594E 0%, #D39389 100%); padding: 4rem 2rem; margin: 4rem auto;">
        <h2 class="section-title" style="color: #FFFFFF; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">🆕 New Arrivals</h2>
        <div class="book-container">
            <?php foreach ($books as $book): ?>
                <div class="book" style="border-color: #FFFFFF;">
                    <a href="redirect.php?id=<?= htmlspecialchars($book['id']) ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?= htmlspecialchars($book['cover_url']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" loading="lazy">
                        <h4><?= htmlspecialchars($book['title']) ?></h4>
                        <?php if (!empty($book['author'])): ?>
                            <p class="author"><?= htmlspecialchars($book['author']) ?></p>
                        <?php endif; ?>
                    </a>
                    <?php if ($loggedIn): ?>
                        <button class="book-btn" onclick="toggleFavorite(<?= $book['id'] ?>, this)" style="background: #FFFFFF; color: #AA594E; font-weight: 600;">❤️ Add to Favorites</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Reviews -->
    <section class="blog" id="reviews">
        <h2 class="section-title">⭐ Customer Reviews</h2>
        <div class="blog-container">
            <div class="blog-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                <h3>⭐⭐⭐⭐⭐</h3>
                <p>"Best library website ever! Found my new favorite book."</p>
                <small>- Sarah K.</small>
            </div>
            <div class="blog-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <h3>⭐⭐⭐⭐⭐</h3>
                <p>"Amazing collection and super easy to use!"</p>
                <small>- Michael R.</small>
            </div>
            <div class="blog-card" style="background: linear-gradient(135deg, #d299c2 0%, #fef9d7 100%);">
                <h3>⭐⭐⭐⭐⭐</h3>
                <p>"Love the recommendations and fast checkout!"</p>
                <small>- Emily T.</small>
            </div>
        </div>
    </section>

    <!-- Team / About / Teachers sections (copy your current content) -->

    <footer>
        <p>© 2025 LibraryHub - Your Gateway to Knowledge ✨</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
