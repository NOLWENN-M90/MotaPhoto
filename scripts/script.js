
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

        if (loading) return;

        loading = true; // Marquer le chargement en cours

        $.ajax({
            url: customScriptData.ajax_url,
            type: 'POST',
            async: true,
            data: {
                action: 'get_filtered_photos',
                category: category,
                format: format,
                date: date,
                nonce: customScriptData.nonce,
            },
            success: function (response) {
                $('.photo-content').remove();
                $('#ajax-photos').empty().html(response).fadeIn();
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
        if (loading) return; // Si le chargement est déjà en cours, n'exécute pas de nouveau

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
            url: customScriptData.ajax_url,
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
    $('.open-lightbox').on('click', function (e) {
        e.preventDefault();

        console.log('Lightbox opened');  // Pour vérifier que l'événement est déclenché

        console.log($(this).data('src')); // Vérifiez le chemin de l'image
        console.log($(this).data('reference')); // Vérifiez la référence
        console.log($(this).data('category')); // Vérifiez la catégorie

        $.fancybox.open({
            src: $(this).data('src'),
            type: 'image',
            opts: {
                buttons: [
                    'slideShow',
                    'fullScreen',
                    'thumbs',
                    'close'
                ],
                afterLoad: function (instance, current) {
                    console.log('After Load triggered');  // Pour vérifier que l'événement afterLoad est déclenché
                    var ref = $(e.target).data('reference') || '';  // Pour éviter undefined
                    var cat = $(e.target).data('category') || '';   // Pour éviter undefined
                    var caption = '<div class="lightbox-caption"><p class="photo-reference">' + ref + '</p><p class="photo-category">' + cat + '</p></div>';
                    $(current.$content[0]).parent().append(caption);
                }
            }
        });
    });



    (function ($) {
        $(document).ready(function () {

            function showLightboxWithImage(imageUrl) {
                if (imageUrl) {
                    $.fancybox.open({
                        src: imageUrl,
                        type: 'image',
                        opts: {
                            afterLoad: function (instance, current) {
                                console.log('Lightbox avec image chargée');
                            }
                        }
                    });
                }
            }

            function getThumbnailUrl(isPrevious) {
                var contentDiv = $('.single-photo-content');
                return isPrevious ? contentDiv.data('prev-image') : contentDiv.data('next-image');
            }

            $('.carousel-arrow-left').on('mouseover', function () {
                var thumbnailUrl = getThumbnailUrl(true);
                showLightboxWithImage(thumbnailUrl);
            });

            $('.carousel-arrow-right').on('mouseover', function () {
                var thumbnailUrl = getThumbnailUrl(false);
                showLightboxWithImage(thumbnailUrl);
            });

        });
    })(jQuery);


});
