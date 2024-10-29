// Toggle Dark Mode
document.getElementById('dark-mode-toggle').addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    document.querySelector('header').classList.toggle('dark-mode');
});

// Search functionality
document.getElementById('search-button').addEventListener('click', function() {
    performSearch();
});

function performSearch() {
    const searchInput = document.getElementById('search-input').value;
    if (searchInput) {
        alert('Searching for: ' + searchInput); // Replace with actual search logic
    } else {
        alert('Please enter a search term.');
    }
}

// Additional profile-related functions can go here
