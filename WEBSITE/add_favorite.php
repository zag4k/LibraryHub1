<?php
require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo 'Unauthorized';
    exit;
}

$user = currentUser();
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;
$action = $_GET['action'] ?? 'add';

if ($book_id <= 0) {
    http_response_code(400);
    echo 'Invalid book id';
    exit;
}

if ($action === 'add') {
    $stmt = $pdo->prepare('INSERT IGNORE INTO favorites (user_id, book_id) VALUES (?, ?)');
    $stmt->execute([$user['id'], $book_id]);
    echo 'added';
} elseif ($action === 'remove') {
    $stmt = $pdo->prepare('DELETE FROM favorites WHERE user_id = ? AND book_id = ?');
    $stmt->execute([$user['id'], $book_id]);
    echo 'removed';
} else {
    $stmt = $pdo->prepare('SELECT COUNT(*) as cnt FROM favorites WHERE user_id = ? AND book_id = ?');
    $stmt->execute([$user['id'], $book_id]);
    $result = $stmt->fetch();
    echo $result['cnt'] > 0 ? 'liked' : 'notliked';
}
