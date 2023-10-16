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

document.addEventListener("DOMContentLoaded", function () {
    var categorySelector = document.getElementById('category_selector');
    var formatSelector = document.getElementById('format_selector');
    var dateOrder = document.getElementById('date_order');

    if (categorySelector && formatSelector && dateOrder) {
        categorySelector.addEventListener('change', function () {
            handlePhotoDetails();
        });

        formatSelector.addEventListener('change', function () {
            handlePhotoDetails();
        });

        dateOrder.addEventListener('change', function () {
            handlePhotoDetails();
        });
    }

    function handlePhotoDetails() {
        var photoLink = document.querySelector('.photo-link');
        if (photoLink) {
            console.log('Photo link found:', photoLink);

            var imageUrl = photoLink.getAttribute('href');
            console.log('Image URL:', imageUrl);

            // Requête Ajax pour récupérer les informations de la photo
            var xhr = new XMLHttpRequest();
            xhr.open('POST', ajax_object.ajax_url, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Ouvrir une nouvelle fenêtre et afficher les informations de la photo
                    var newWindow = window.open('single-photo.php', '_blank');
                    newWindow.document.write(xhr.responseText);
                }
            };

            // Construire les données à envoyer
            var data = 'action=get_photo_info&image_url=' + encodeURIComponent(imageUrl);
            console.log('Ajax data:', data);

            // Envoyer la requête Ajax
            xhr.send(data);
        } else {
            console.log('Photo link not found.');
        }
    }

    // Ajouter le code jQuery ici
    $('.photo-link').on('click', function (e) {
        e.preventDefault();

        var imageUrl = $(this).attr('href');

        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'get_photo_info',
                image_url: imageUrl
            },
            success: function (response) {
                var newWindow = window.open('single-photo.php', '_blank');
                newWindow.document.write(response);
            },
            error: function (error) {
                console.log('Erreur Ajax:', error);
            }
        });
    });
    
});

