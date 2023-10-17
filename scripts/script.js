console.log("js ok");

// Get the modal
var modal = document.getElementById('myModal');
var overlay = document.getElementById("backgroundOverlay");

const btn = document.querySelectorAll('.myBtn')
btn.forEach(function (button) {
    button.addEventListener('click', function () {
        overlay.style.display = "block";
        modal.style.display = "block";
    });
});

window.addEventListener('click', function (event) {
    if (event.target === overlay) {
        modal.style.display = "none"; // Masquer la modal
        overlay.style.display = "none"; // Masquer l'overlay
    }
});

// On utilise les listes déroulantes comme filtres photos
jQuery(document).ready(function ($) {
    $('.filter-select').on('change', function () {
        var category = $('#category_selector').val();
        var format = $('#format_selector').val();
        var date = $('#date_order').val();

        // Effectuez la requête AJAX pour récupérer les photos filtrées
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_filtered_photos',
                category: category,
                format: format,
                date: date
            },
            success: function (response) {
                // Remplacez le contenu de .photo-content avec la réponse AJAX
                $('.photo-content').replaceWith(response);
            },
            error: function (error) {
                console.log('Erreur AJAX:', error);
            }
        });
    });
});


$(document).ready(function () {
    $('#load-more').on('click', function () {
        console.log('Load more button clicked');
        var data = {
            'action': 'load_more_photos',
            'offset': offset,
            'photos_to_load': photosToLoad,
            'category_selector': '<?php echo esc_js($category_filter); ?>',
            'format_selector': '<?php echo esc_js($format_filter); ?>',
            'annee': '<?php echo esc_js($annee); ?>',
            // ... autres données à passer ...
        };

        // Utiliser une requête AJAX pour charger plus de photos
        $.ajax({
            url: ajax_object.ajax_url, // Assurez-vous que cette ligne est correcte
            data: data,
            type: 'POST',
            success: function (response) {
                console.log('AJAX success:', response); // Ajoutez cette ligne pour vérifier la réponse AJAX
                container.append(response);
                offset += photosToLoad;
            },
            error: function (error) {
                console.log('AJAX error:', error); // Ajoutez cette ligne pour vérifier les erreurs AJAX
            }
        });
    });
});
