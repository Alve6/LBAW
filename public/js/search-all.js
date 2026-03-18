document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const newsContainer = document.getElementById('news-container');
    let debounceTimer;

    if (searchInput && newsContainer) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            
            debounceTimer = setTimeout(() => {
                const searchTerm = searchInput.value;
                const url = new URL(window.location.origin + '/news');
                url.searchParams.set('search', searchTerm);
                
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newNewsContainer = doc.getElementById('news-container');
                    
                    if (newNewsContainer) {
                        newsContainer.innerHTML = newNewsContainer.innerHTML;
                    }
                })
                .catch(error => console.error('Search error:', error));
            }, 300);
        });
    }
});
