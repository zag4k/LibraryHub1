// Smooth scroll to featured
function scrollToFeatured() {
    document.getElementById('featured').scrollIntoView({ behavior: 'smooth' });
}

// Redirect to story
function redirectToStory(button) {
    const book = button.closest('.book');
    const link = book.dataset.link;
    if (link) {
        window.open(link, '_blank');
    }
}

// Toggle favorite
function toggleFavorite(bookId, button) {
    fetch('add_favorite.php?book_id=' + bookId + '&action=check')
        .then(r => r.text())
        .then(status => {
            const action = status === 'liked' ? 'remove' : 'add';
            return fetch('add_favorite.php?book_id=' + bookId + '&action=' + action);
        })
        .then(r => r.text())
        .then(result => {
            if (result === 'added') {
                button.textContent = '❤️ Remove from Favorites';
                button.style.background = 'linear-gradient(135deg, #FF6B6B 0%, #EE5A52 100%)';
                button.style.color = '#fff';
            } else {
                button.textContent = '❤️ Add to Favorites';
                button.style.background = 'linear-gradient(135deg, #F8D7DA 0%, #F5C6CB 100%)';
                button.style.color = '#900C0F';
            }
        });
}

// Read more blog
function readMore(topic) {
    alert(`📖 Opening: "${topic}"\n\nComing soon - full blog articles! ✨`);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const books = document.querySelectorAll('.book');
        
        books.forEach(book => {
            const title = book.querySelector('h4').textContent.toLowerCase();
            if (title.includes(searchTerm) || searchTerm === '') {
                book.style.display = 'block';
                book.style.opacity = '1';
            } else {
                book.style.opacity = '0.3';
                setTimeout(() => {
                    if (!title.includes(searchTerm)) book.style.display = 'none';
                }, 300);
            }
        });
    });
    
    // Smooth scrolling for navigation
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        // No modal to close
    }
});