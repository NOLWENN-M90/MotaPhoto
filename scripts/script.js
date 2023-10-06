// Get the modal
console.log("js ok")


var modal = document.getElementById('myModal');
var overlay = document.getElementById("backgroundOverlay");

const btn = document.querySelectorAll('.myBtn')
btn.forEach(function (button) {
    button.addEventListener('click', function () {
        overlay.style.display = "block";
        modal.style.display = "block";
    });

})

window.addEventListener('click', function (event) {
    if (event.target === overlay) {
        modal.style.display = "none"; // Masquez la modal
        overlay.style.display = "none"; // Masquez l'overlay
    }
});

// Filtrer les photos par leur taxonomies sans bouton //
document.getElementById('category_selector').addEventListener('change', function () {
    document.getElementById('filter-form').submit();
});

document.getElementById('format_selector').addEventListener('change', function () {
    document.getElementById('filter-form').submit();
});

document.getElementById('date_order').addEventListener('change', function () {
    document.getElementById('filter-form').submit();
});

