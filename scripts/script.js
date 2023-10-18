

// Fonction principale qui sera exécutée lorsque le document est prêt
jQuery(document).ready(function ($) {
    console.log("js ok");

    // Get the modal
    var modal = document.getElementById('myModal');
    var overlay = document.getElementById("backgroundOverlay");

    // Sélectionnez tous les éléments avec la classe .myBtn
    var btns = document.querySelectorAll('.myBtn');

    // Attachez un gestionnaire d'événement à chaque bouton
    btns.forEach(function (button) {
        button.addEventListener('click', function () {
            // Affichez la modal et l'overlay lorsque le bouton est cliqué
            overlay.style.display = "block";
            modal.style.display = "block";
        });
    });

    // Fermez la modal lorsqu'on clique en dehors de celle-ci
    window.addEventListener('click', function (event) {
        if (event.target === overlay) {
            // Masquez la modal et l'overlay lorsqu'on clique en dehors de la modal
            modal.style.display = "none";
            overlay.style.display = "none";
        }
    });

    // Variables nécessaires pour la requête AJAX et le chargement de photos
    var offset = 0; // Déclarez et initialisez offset
    var photosToLoad = 10; // Déclarez et initialisez photosToLoad

    // On utilise les listes déroulantes comme filtres photos
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

    $('#load-more').on('click', function () {
        console.log('Load more button clicked');
        var data = {
            'action': 'load_more_photos',
            'offset': offset,
            'photos_to_load': photosToLoad,
            // Remplacez les parties PHP par des valeurs appropriées
            'category_selector': 'category',
            'format_selector': 'format',
            'annee': 'annee',
        };

        // Utiliser une requête AJAX pour charger plus de photos
        $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            type: 'POST',
            success: function (response) {
                console.log('AJAX success:', response);

                // Assurez-vous que container est défini avant de l'utiliser
                var container = $('.photo-content');

                // Append la réponse à .photo-content
                container.append(response);

                // Incrémentez offset pour la prochaine requête
                offset += photosToLoad;
            },
            error: function (error) {
                console.log('AJAX error:', error);
            }
        });
    });

    // Lorsque la page est chargée
    $('#show-all-photos').on('click', function () {
        console.log('Toutes les photos button clicked');
        var data = {
            'action': 'load_more_photos',
            'offset': offset,
            'photos_to_load': photosToLoad,
            // Remplacez les parties PHP par des valeurs appropriées
            'category_selector': 'category',
        };
        $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            type: 'POST',
            success: function (response) {
                console.log('AJAX success:', response);

                // Assurez-vous que container est défini avant de l'utiliser
                var container = $('.affiche');
                

                // Append la réponse à .affiche
                container.append(response);

                // Incrémentez offset pour la prochaine requête
                offset += photosToLoad;
            },
            error: function (error) {
                console.log('AJAX error:', error);
            }
        });
        // ...
    });
});
