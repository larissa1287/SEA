document.getElementById('usuario-icon').addEventListener('click', function() {
    var popup = document.getElementById('login-popup');
    popup.style.display = (popup.style.display === 'none' || popup.style.display === '') ? 'block' : 'none';
});

document.addEventListener('click', function(event) {
    var popup = document.getElementById('login-popup');
    var icon = document.getElementById('usuario-icon');
    if (!icon.contains(event.target) && !popup.contains(event.target)) {
        popup.style.display = 'none';
    }
});
