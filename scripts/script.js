(function ($) {
    // Variables globales
    var loading = false;
    var currentIndex = 0;
    var allPhotosData = [];

    // Fonctions qui doivent être accessibles dans tout le script
    function updateAllPhotosData() {
        allPhotosData = $('.photo-linka').map(function () {
            return {
                src: $(this).data('src'),
                category: $(this).data('category'),
                reference: $(this).data('reference')
            };
        }).get();
    }
    function showPreviousImage() {
        currentIndex = currentIndex > 0 ? currentIndex - 1 : allPhotosData.length - 1;
        updateLightboxImage(currentIndex);
    }

    // Afficher l'image suivante dans la lightbox
    function showNextImage() {
        currentIndex = currentIndex < allPhotosData.length - 1 ? currentIndex + 1 : 0;
        updateLightboxImage(currentIndex);
    }
    // Mettre à jour l'image affichée dans la lightbox
    function updateLightboxImage(index) {
        if (index >= 0 && index < allPhotosData.length) {
            var photoData = allPhotosData[index];
            var lightboxImage = $('#lightbox-image');
            var lightboxDetails = $('#lightbox-details');

            if (lightboxImage.length) {
                lightboxImage.attr('src', photoData.src);
                lightboxDetails.find('#lightbox-category').text(photoData.category);
                lightboxDetails.find('#lightbox-reference').text(photoData.reference);
                currentIndex = index; // Assurez-vous que cet index est correctement mis à jour
            }
        }
    }
    // Ouvrir la lightbox avec l'image, la catégorie et la référence
    function openLightbox(image, category, reference) {
        $('#lightbox-overlay').remove();
        
        // Création de l'overlay de la lightbox
        var overlay = $('<div id="lightbox-overlay"></div>').css({
            position: 'fixed',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            backgroundColor: 'rgba(0,0,0,0.7)',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            zIndex: 1000,
        }).appendTo('body');

        // Création de l'image en plein écran
        var lightboxImage = $('<img id="lightbox-image">').attr('src', image).css({
            maxWidth: '80%',
            maxHeight: '80%',
            margin: 'auto'
        }).appendTo(overlay);

        // Ajout des détails de l'image
        var imageDetails = $('<div></div>').attr('id', 'lightbox-details').css({
            position: 'absolute',
            'text-transform': 'uppercase',
            'justify-content': 'space-around',
            'bottom': '5%',
            'width': '100%',
            'display': 'flex',
            'color': 'rgb(255, 255, 255)',
            'font-family': 'Poppins',
            'text-align': 'center'
        }).html('<p id="lightbox-reference">' + reference + '</p>' + '<p id="lightbox-category">' + category + '</p>').appendTo(overlay);

        // Ajout des boutons de navigation
        var prevButton =

            $('<button>←</button>').css({
                position: 'absolute',
                top: '50%',
                left: '10%',
                transform: 'translateY(-50%)',
                zIndex: 1001,
                'background-color': 'transparent',
                'color': 'white',
                'border': 'none',
                'font-size': '20px',
                'cursor': 'pointer'
            }).html('<span class="arrow" style="font-size: 24px;">&larr;</span> <span class="text" style="font-size: 16px;">Précédente</span>')
                .on('click', showPreviousImage).appendTo(overlay);

        var nextButton =

            $('<button>→</button>').css({
                position: 'absolute',
                top: '50%',
                right: '10%',
                transform: 'translateY(-50%)',
                zIndex: 1001,
                'background-color': 'transparent',
                'color': 'white',
                'border': 'none',
                'font-size': '20px',
                'cursor': 'pointer'
            }).html('<span class="text" style="font-size: 16px;">Suivante</span> <span class="arrow" style="font-size: 24px;">&rarr;</span>')
                .on('click', showNextImage).appendTo(overlay);
                

        // Fermeture de la lightbox en cliquant sur l'overlay
        overlay.on('click', function (e) {
            if (e.target !== this) return;
            overlay.remove();
        });
    }
    function reinitSelect2ForNewElements() {
        $('select').not('.select2-hidden-accessible').select2();
    }
    function reattachEventHandlersForNewElements() {
        $('.fullscreen-icon i').off('click').on('click', function () {
            var $this = $(this);
            var imageSrc = $this.closest('.photo-content').find('img').attr('src');
            var category = $this.closest('.photo-linka').data('category');
            var reference = $this.closest('.photo-linka').data('reference');
            openLightbox(imageSrc, category, reference);
        });
    }
    function attachPreviewArrowEvents() {
        $('.preview-arrow').off('mouseover').on('mouseover', function () {
            var $arrow = $(this);
            var imageSrc = $arrow.attr('href');
            var category = $arrow.data('category');
            var reference = $arrow.data('reference');
            openLightbox(imageSrc, category, reference);
        });
    }
    function openCurrentLightbox() {
        var photoData = allPhotosData[currentIndex];
        openLightbox(photoData.src, photoData.category, photoData.reference);
    }

    // Réattacher les gestionnaires d'événements pour les icônes de plein écran après le chargement AJAX
    function attachFullScreenIconEvents() {
        $('.fullscreen-icon i').off('click').on('click', function () {
            var $icon = $(this);
            var imageSrc = $icon.closest('.photo-content').find('img').attr('src');
            var category = $icon.closest('.photo-linka').data('category');
            var reference = $icon.closest('.photo-linka').data('reference');
            openLightbox(imageSrc, category, reference);
        });
    }
    jQuery(document).ready(function ($) {
        console.log("js ok");
        updateAllPhotosData();
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
        var mobileToggle = document.getElementById('mobile-menu-toggle');
        var mobileMenu = document.getElementById('mobile-menu');

        // Gérez le clic sur le bouton "burger"
        mobileToggle.addEventListener('click', function () {
            mobileMenu.classList.toggle('is-active');
        });
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
                    category: $('#category_selector').val(),
                    format: $('#format_selector').val(),
                    date_order: $('#date_order').val(),
                    nonce: customScriptData.nonce,
                },
                success: function (response) {
                    $('#filtered-photos').html(response).fadeIn();
                    updateAllPhotosData(); // Mise à jour des données pour la navigation dans la lightbox
                    reattachEventHandlersForNewElements();
                    reinitSelect2ForNewElements();
                    attachPreviewArrowEvents();
                    attachFullScreenIconEvents();
                    loading = false;
                },
            });
        });
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
                    if (response.trim() !== '') {

                        $('#loaded-photos').append(response).fadeIn();;
                        updateAllPhotosData(); // Mise à jour des données pour la navigation dans la lightbox
                        reattachEventHandlersForNewElements();
                        reinitSelect2ForNewElements();
                        attachPreviewArrowEvents();
                        attachFullScreenIconEvents();
                        // Active les éléments de l'overlay sur les nouvelles photos
                        $('.photo-content .already-displayed .overlay').each(function () {
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

        // Réattacher les gestionnaires d'événements pour les icônes de plein écran après le chargement AJAX
        jQuery(document).on('click', '.fullscreen-icon i', function () {
            var $icon = $(this);
            var imageSrc = $icon.closest('.photo-content').find('img').attr('src');
            var category = $icon.closest('.photo-linka').data('category');
            var reference = $icon.closest('.photo-linka').data('reference');
            openLightbox(imageSrc, category, reference);
        });
        jQuery(document).on('mouseover', '.preview-arrow', function () {
            var $arrow = $(this);
            var imageSrc = $arrow.attr('href');
            var category = $arrow.data('category');
            var reference = $arrow.data('reference');
            var index = $arrow.data('index');
            var currentIndex = index ;
            if ($(this).hasClass('arrow-left')) {
                showPreviousImage();
            } else if ($(this).hasClass('arrow-right')) {
                showNextImage();
            }
            openLightbox(imageSrc, category, reference);
        });
    });
    updateAllPhotosData();
})(jQuery);
