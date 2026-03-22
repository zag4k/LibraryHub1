<?php
require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = currentUser();

// Check if user is admin
$stmt = $pdo->prepare('SELECT is_admin FROM users WHERE id = ?');
$stmt->execute([$user['id']]);
$dbUser = $stmt->fetch();

if (!$dbUser || !$dbUser['is_admin']) {
    http_response_code(403);
    echo '<h1>Access Denied</h1><p>Only admins can access this page.</p>';
    exit;
}

$message = '';
$error = '';

// Handle add/update book
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $cover_url = trim($_POST['cover_url'] ?? '');
    $read_url = trim($_POST['read_url'] ?? '');

    if ($title === '' || $cover_url === '' || $read_url === '') {
        $error = 'Title, cover URL, and read URL are required.';
    } else {
        if ($action === 'add') {
            $stmt = $pdo->prepare('INSERT INTO books (title, author, cover_url, read_url) VALUES (?, ?, ?, ?)');
            $stmt->execute([$title, $author ?: null, $cover_url, $read_url]);
            $message = 'Book added successfully!';
        } elseif ($action === 'update') {
            $book_id = (int)($_POST['book_id'] ?? 0);
            if ($book_id > 0) {
                $stmt = $pdo->prepare('UPDATE books SET title = ?, author = ?, cover_url = ?, read_url = ? WHERE id = ?');
                $stmt->execute([$title, $author ?: null, $cover_url, $read_url, $book_id]);
                $message = 'Book updated successfully!';
            }
        } elseif ($action === 'delete') {
            $book_id = (int)($_POST['book_id'] ?? 0);
            if ($book_id > 0) {
                $stmt = $pdo->prepare('DELETE FROM books WHERE id = ?');
                $stmt->execute([$book_id]);
                $message = 'Book deleted successfully!';
            }
        }
    }
}

// Load all books
$stmt = $pdo->query('SELECT * FROM books ORDER BY created_at DESC');
$books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LibraryHub</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }
        .admin-table th, .admin-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .admin-table th {
            background: #114847;
            color: white;
        }
        .admin-table tr:hover {
            background: #f5f5f5;
        }
        .admin-table img {
            max-width: 50px;
        }
        .admin-btn {
            padding: 8px 12px;
            margin: 0 4px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
        }
        .edit-btn {
            background: #114847;
            color: white;
        }
        .delete-btn {
            background: #900C0F;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="index.php" class="logo">📚 LibraryHub Admin</a>
            <div style="margin-left: 1rem; color: #FFFFFF; font-weight: 700;">
                <a href="index.php" style="color:#fff; text-decoration:underline;">Home</a> |
                <a href="logout.php" style="color:#fff; text-decoration:underline;">Logout</a>
            </div>
        </div>
    </header>

    <section class="featured" style="max-width: 1200px; margin: 2rem auto; padding: 0 2rem;">
        <h2 class="section-title">📊 Book Management</h2>

        <?php if ($message): ?>
            <p style="color: #00b894; text-align:center; margin-bottom:1rem; padding:0.5rem; border: 1px solid #00b894; border-radius: 8px;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p style="color: #900C0F; text-align:center; margin-bottom:1rem; padding:0.5rem; border: 1px solid #900C0F; border-radius: 8px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Add New Book Form -->
        <div style="background: #fff; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <h3 style="color: #114847; margin-bottom: 1rem;">➕ Add New Book</h3>
            <form method="POST" action="admin.php">
                <input type="hidden" name="action" value="add">
                <div style="display: grid; gap: 1rem; grid-template-columns: 1fr 1fr;">
                    <div>
                        <label>Title *</label>
                        <input type="text" name="title" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
                    </div>
                    <div>
                        <label>Author</label>
                        <input type="text" name="author" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
                    </div>
                    <div>
                        <label>Cover URL *</label>
                        <input type="url" name="cover_url" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
                    </div>
                    <div>
                        <label>Read URL *</label>
                        <input type="url" name="read_url" required style="width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;">
                    </div>
                </div>
                <button class="cta-button" type="submit" style="margin-top:1rem;">Add Book</button>
            </form>
        </div>

        <!-- Books Table -->
        <div style="background: #fff; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <h3 style="color: #114847; margin-bottom: 1rem;">📚 Current Books</h3>
            <?php if (empty($books)): ?>
                <p style="text-align:center; color:#666;">No books found.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Read URL</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($book['cover_url']) ?>" alt="cover"></td>
                                <td><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author'] ?? 'N/A') ?></td>
                                <td><small><?= htmlspecialchars(substr($book['read_url'], 0, 40)) ?>...</small></td>
                                <td>
                                    <button class="admin-btn edit-btn" onclick="editBook(<?= $book['id'] ?>)">Edit</button>
                                    <form style="display:inline;" method="POST" action="admin.php" onsubmit="return confirm('Delete this book?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                        <button class="admin-btn delete-btn" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>© 2025 LibraryHub - Your Gateway to Knowledge ✨</p>
    </footer>

    <script>
    function editBook(bookId) {
        // Simple prompt for now; could be expanded to a modal
        const title = prompt('Enter new title:');
        if (title) {
            // This would require a more complex form; for now, redirect to edit page
            alert('Edit feature coming soon - contact admin');
        }
    }
    </script>
</body>
</html>
