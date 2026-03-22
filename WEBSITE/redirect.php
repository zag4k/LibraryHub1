<?php
require_once 'config.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo 'Invalid book id.';
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT read_url FROM books WHERE id = ?');
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    http_response_code(404);
    echo 'Book not found.';
    exit;
}

$target = $book['read_url'];
header('Location: ' . filter_var($target, FILTER_SANITIZE_URL));
exit;
