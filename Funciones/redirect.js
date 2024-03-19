document.addEventListener('DOMContentLoaded', function() {
    var imageContainers = document.querySelectorAll('.image-container');

    imageContainers.forEach(function(container) {
        container.addEventListener('click', function() {
            window.location.href = 'redirect_to_bitcoin.php';
        });
    });
});
