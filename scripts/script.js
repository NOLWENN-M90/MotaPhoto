
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
    var mobileToggle = document.getElementById('mobile-menu-toggle');
    var mobileMenu = document.getElementById('mobile-menu');

    // Gérez le clic sur le bouton "burger"
    mobileToggle.addEventListener('click', function () {
        mobileMenu.classList.toggle('is-active');
    });
    // Variables nécessaires pour la requête AJAX et le chargement de photos
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
                    $('.ajax-photos').html(response);
                    $('.photo-content').last().after(response);

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
    var loading = false; // Variable pour empêcher le chargement multiple
    var currentIndex = 0; // Variable pour garder une trace de l'index de l'image actuelle

    function updateLightboxImage(index) {
        // Assurer que l'index reste dans la plage des images disponibles
        if (index >= btns.length) index = 0;
        if (index < 0) index = btns.length - 1;
    
        // Mettre à jour l'image dans la lightbox directement
        var newImageSrc = btns[index].getAttribute('data-image-src'); // Supposant que chaque .myBtn a un attribut data-image-src
        $('#lightbox-image').attr('src', newImageSrc);
        currentIndex = index; // Mettre à jour l'index courant
    }
    
    // Fonction pour configurer la navigation de la lightbox une seule fois
    function setupLightboxNavigation() {
        $('#lightbox-overlay .prev-button').on('click', function (e) {
            e.stopPropagation();
            updateLightboxImage(currentIndex - 1);
        });
    
        $('#lightbox-overlay .next-button').on('click', function (e) {
            e.stopPropagation();
            updateLightboxImage(currentIndex + 1);
        });
    }
    function openLightbox(image, category, reference) {
        var that = this;
        
        setupLightboxNavigation();
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
        var lightboxImage = $('<img>').attr('src', image.attr('src')).css({
            maxWidth: '80%',
            maxHeight: '80%',
            margin: 'auto'
        }).appendTo(overlay);

        // Ajout des détails de l'image
        var imageDetails = $('<div></div>').css({
            position: 'absolute',
            'text-transform': 'uppercase',
            'justify-content': 'space-around',
            'bottom': '5%',
            'width': '100%',
            'display': 'flex',
            'color': 'rgb(255, 255, 255)',
            'font-family': 'Poppins',
            'text-align': 'center'
        }).html('<p>' + reference + '</p>' +
            '<p>' + category + '</p>').appendTo(overlay);

        // Ajout des boutons de navigation
        var prevButton = $('<button>←</button>').css({
            position: 'absolute',
            top: '50%',
            left: '10%',
            transform: 'translateY(-50%)',
            zIndex: 1001,
            'background-color': 'transparent',
            'color':'white',
            'border':'none',
            'font-size':'20px',
            'cursor':'pointer'
        }).html('<span class="arrow" style="font-size: 24px;">&larr;</span> <span class="text" style="font-size: 16px;">Précédente</span>')
        .on('click', function () {
            that.showImage(that.currentIndex - 1);
        }).appendTo(overlay);

        var nextButton = $('<button>→</button>').css({
            position: 'absolute',
            top: '50%',
            right: '10%',
            transform: 'translateY(-50%)',
            zIndex: 1001,
            'background-color': 'transparent',
            'color':'white',
            'border':'none',
            'font-size':'20px',
            'cursor':'pointer'
        }).html('<span class="text" style="font-size: 16px;">Suivante</span> <span class="arrow" style="font-size: 24px;">&rarr;</span>')
        .on('click', function () {
            that.showImage(that.currentIndex + 1);
        }).appendTo(overlay);

        // Fermeture de la lightbox en cliquant sur l'overlay
        overlay.on('click', function (e) {
            if (e.target !== this) return;
            overlay.remove();
        });
       
    }

    // Gestion du clic sur l'icône d'agrandissement
    $('.fullscreen-icon i').on('click', function () {
        var $icon = $(this);
        var image = $icon.closest('.photo-content').find('img');
        
        // Récupération des données de l'élément cliqué
        var category = $icon.closest('.photo-linka').data('category');
        var reference = $icon.closest('.photo-linka').data('reference');
        
        // Ouverture de la lightbox avec les données récupérées
        openLightbox(image, category, reference);
        
    });
   

});
