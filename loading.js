// loading.js
document.addEventListener("DOMContentLoaded", function() {
    var loader = document.getElementById('loader');
    var content = document.getElementById('content');
    var loginForm = document.getElementById('loginForm');
    
    window.addEventListener('load', function() {
        loader.style.display = 'none';
        content.style.display = 'block';
    });

    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            loader.style.display = 'flex';
            content.style.display = 'none';
        });
    }

    // Show loader on page navigation
    document.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function(event) {
            loader.style.display = 'flex';
            content.style.display = 'none';
        });
    });
});
