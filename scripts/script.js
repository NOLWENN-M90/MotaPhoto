
// Fonction principale qui sera exécutée lorsque le document est prêt
jQuery(document).ready(function ($) {
    console.log("js ok");

    // Get the modal
    var modal = document.getElementById('myModal');
    var overlay = document.getElementById("backgroundOverlay");

    // Sélectionne tous les éléments avec la classe .myBtn
    var btns = document.querySelectorAll('.myBtn');

    // Attache un gestionnaire d'événement à chaque bouton
    btns.forEach(function (button) {
        button.addEventListener('click', function () {
            // Affiche la modal et l'overlay lorsque le bouton est cliqué
            overlay.style.display = "block";
            modal.style.display = "block";
        });
    });

    // Ferme la modal lorsqu'on clique en dehors de celle-ci
    window.addEventListener('click', function (event) {
        if (event.target === overlay) {
            // Masque la modal et l'overlay lorsqu'on clique en dehors de la modal
            modal.style.display = "none";
            overlay.style.display = "none";
        }
    });

    // Sélectionnez le bouton "burger" et le menu
    var $toggleButton = $('#mobile-menu-toggle');
    var $mobileMenu = $('#mobile'); // Sélectionnez l'ID correct pour le menu mobile

    // Gérez le clic sur le bouton "burger"
    $toggleButton.on('click', function () {
        $mobileMenu.toggleClass('active');// Affichez ou masquez le menu
    });


    // Variables nécessaires pour la requête AJAX et le chargement de photos

    var loading = false; // Variable pour empêcher le chargement multiple

    // On utilise les listes déroulantes comme filtres photos
    $('.filter-select').on('change', function () {
        if (loading) return;
        var category = $('#category_selector').val();
        var format = $('#format_selector').val();
        var date = $('#date_order').val();
        if (
            category === customScriptData.currentCategory &&
            format === customScriptData.currentFormat &&
            date === customScriptData.currentDate
        ) {
            return; // Les options n'ont pas changé, aucune action requise
        }

        customScriptData.currentCategory = category;
        customScriptData.currentFormat = format;
        customScriptData.currentDate = date;

        loading = true; // Marquer le chargement en cours

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
                $('.photo-content').html(response).fadeIn();
                loading = false;
            },
            error: function (error) {
                console.log('Erreur AJAX:', error);
                loading = false;
            }
        });
    });
    var loading = false; // Variable pour empêcher le chargement multiple

    $('#load-more').on('click', function () {
        if (loading) return; // Si le chargement est déjà en cours, n'exécutez pas de nouveau

        console.log('Load more button clicked');
        loading = true; // Marque le chargement en cours

        var offset = $('.photo-content').not('.already-displayed').length;
        var newPhotosToLoad = 8; // Le nombre de photos à charger à chaque clic

        var data = {
            'action': 'load_more_photos',
            'offset': offset,
            'photos_to_load': newPhotosToLoad,
        };

        $.ajax({
            url: ajax_object.ajax_url,
            data: data,
            type: 'POST',
            success: function (response) {
                console.log('AJAX success:', response);
                if (response.trim() !== '') {
                    $('.photo-content').last().after(response);

                    // Active les éléments de l'overlay sur les nouvelles photos
                    $('.photo-content.already-displayed .overlay').each(function () {
                        var overlay = $(this);


                        overlay.find('.info-icon i').on('click', function () {
                            // Code pour gérer le clic sur l'icône d'info
                        });

                        overlay.find('.fullscreen-icon i').on('click', function () {
                            // Code pour gérer le clic sur l'icône de plein écran
                        });

                    });

                    $('.photo-content.already-displayed').removeClass('already-displayed');
                    loading = false; // Marquer le chargement comme terminé
                } else {
                    console.log('Aucune photo supplémentaire à charger.');
                    $('#load-more').prop('disabled', true);
                }
            },
            error: function (error) {
                console.log('AJAX error:', error);
                loading = false; // Marquer le chargement comme terminé en cas d'erreur
            }
        });
    });

    (function ($) {
        $(document).ready(function () {
            function showThumbnail(isPrevious) {
                var thumbnailUrl = isPrevious ? getThumbnailUrl(true) : getThumbnailUrl(false);
                var thumbnailPreview = $('.thumbnail-preview');

                if (thumbnailUrl) {
                    thumbnailPreview.html('<img src="' + thumbnailUrl + '" alt="Thumbnail">');
                    thumbnailPreview.show();
                } else {
                    thumbnailPreview.hide();
                }
            }

            function getThumbnailUrl(isPrevious) {
                var thumbnailUrl = '';
                if (isPrevious) {
                    // Logique pour obtenir l'URL de la miniature précédente
                    // Affectez l'URL à la variable thumbnailUrl
                } else {
                    // Logique pour obtenir l'URL de la miniature suivante
                    // Affectez l'URL à la variable thumbnailUrl
                }
                return thumbnailUrl;  // Renvoyez l'URL de la miniature
            }

            // Associez la fonction showThumbnail aux liens suivant/précédent
            $('.carousel-arrow-left').on('hover', function () {
                showThumbnail(true);
            });

            $('.carousel-arrow-right').on('hover', function () {
                showThumbnail(false);
            });
        });
    })(jQuery);


    document.addEventListener('DOMContentLoaded', function () {
        const arrowLeft = document.querySelector('.carousel-arrow-left');
        const arrowRight = document.querySelector('.carousel-arrow-right');
        const singleContent = document.querySelector('.single-photo-content');
        const thumbnailPreview = document.querySelector('.thumbnail-preview img');

        arrowLeft.addEventListener('mouseover', function () {
            const prevImageURL = singleContent.getAttribute('data-prev-image');
            singleContent.innerHTML = '';
            const prevImage = document.createElement('img');
            prevImage.src = prevImageURL;
            prevImage.alt = 'Photo précédente';
            singleContent.appendChild(prevImage);
        });

        arrowRight.addEventListener('mouseover', function () {
            const nextImageURL = singleContent.getAttribute('data-next-image');
            singleContent.innerHTML = '';
            const nextImage = document.createElement('img');
            nextImage.src = nextImageURL;
            nextImage.alt = 'Photo suivante';
            singleContent.appendChild(nextImage);
        });
    });


});
