
document.getElementById('logoutButton').addEventListener('click', function() {
    var confirmLogout = confirm('Are you sure you want to logout?');
    if (confirmLogout) {
        // Redirect to another page
        window.location.href = "main.html"; // Replace 'logout.php' with the actual URL you want to redirect to
    }
});
