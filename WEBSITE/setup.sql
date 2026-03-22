-- setup.sql

CREATE DATABASE IF NOT EXISTS libraryhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE libraryhub;

DROP TABLE IF EXISTS books;
CREATE TABLE books (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) DEFAULT NULL,
    cover_url VARCHAR(500) DEFAULT NULL,
    read_url VARCHAR(1000) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO books (title, author, cover_url, read_url) VALUES
('The Seven Husbands of Evelyn Hugo', 'Taylor Jenkins Reid', 'https://i.pinimg.com/1200x/03/32/64/0332640a2c719e6c69b796963411b165.jpg', 'https://www.goodreads.com/book/show/32620332-the-seven-husbands-of-evelyn-hugo'),
('The Song of Achilles', 'Madeline Miller', 'https://i.pinimg.com/736x/b6/92/1f/b6921f82255068d09e8e4f113c1ae3bc.jpg', 'https://www.goodreads.com/book/show/13623848-the-song-of-achilles'),
('The Housemaid', 'Nita Prose', 'https://i.pinimg.com/736x/8d/c1/e2/8dc1e2189a98cd0c9336c2c3b4722780.jpg', 'https://www.goodreads.com/book/show/60556912-the-housemaid'),
('Milk and Honey', 'Rupi Kaur', 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&h=600&q=80', 'https://www.goodreads.com/book/show/23513349-milk-and-honey'),
('Fourth Wing', 'Rebecca Yarros', 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&h=600&q=80', 'https://www.goodreads.com/book/show/61431922-fourth-wing'),
('Programming Basics', NULL, 'https://i.pinimg.com/736x/e9/fd/e7/e9fde7c2266a238fbd6fc9fe6018de4f.jpg', 'https://example.com/programming-basics'),
('Java Programming', NULL, 'https://i.pinimg.com/1200x/32/87/15/328715cbc6f3e2c820e20b19cb6ef5c2.jpg', 'https://example.com/java-programming');

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password_hash) VALUES
('testuser', 'testuser@example.com', '$2y$10$KixZzQTTOLCiwnMTg4R1GePzdkzVKRXa9U4Eo3cd2T7RDxOVcY4s4'),
('admin', 'admin@example.com', '$2y$10$KixZzQTTOLCiwnMTg4R1GePzdkzVKRXa9U4Eo3cd2T7RDxOVcY4s4');

DROP TABLE IF EXISTS favorites;
CREATE TABLE favorites (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    book_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    UNIQUE KEY unique_fav (user_id, book_id)
);

ALTER TABLE users ADD COLUMN is_admin BOOLEAN DEFAULT FALSE;
UPDATE users SET is_admin = TRUE WHERE username = 'admin';


