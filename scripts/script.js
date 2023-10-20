
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

    // Variables nécessaires pour la requête AJAX et le chargement de photos
    
    // On utilise les listes déroulantes comme filtres photos
    $('.filter-select').on('change', function () {
        var category = $('#category_selector').val();
        var format = $('#format_selector').val();
        var date = $('#date_order').val();
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
                $('.photo-content').replaceWith(response);
            },
            error: function (error) {
                console.log('Erreur AJAX:', error);
            }
        });
    });
    var loading = false; // Variable pour empêcher le chargement multiple

  $('#load-more').on('click', function () {
    if (loading) return; // Si le chargement est déjà en cours, n'exécutez pas de nouveau

    console.log('Load more button clicked');
    loading = true; // Marquer le chargement en cours

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
          $('.photo-content').last().after(response); // Ajoutez la réponse après la dernière div .photo-content
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


    // Lorsque la page est chargée
    $('#show-all-photos').on('click', function () {
        console.log('Toutes les photos button clicked');
        
        var currentCategory = $related_photos();
        if (currentCategory && currentCategory.term_id) {
            // Utiliser l'identifiant de la catégorie actuelle
            var currentCategoryId = currentCategory.term_id;

            var data = {
                'action': 'get_related_photos',
                'current_category': currentCategoryId,
            };
            $.ajax({
                url: ajax_object.ajax_url,
                data: data,
                type: 'POST',
                success: function (response) {
                    console.log('AJAX success:', response);
                    var container = $('.affiche');
                    console.log(container);
                    container.append(response);
                    currentSlide = 0;
                    showSlide(currentSlide);
                    offset += photosToLoad;
                },
                error: function (error) {
                    console.log('AJAX error:', error);
                }
            });
        };

    });
});

let currentSlide = 0;
    const totalSlides = document.querySelectorAll('.carousel-item').length;

    function showSlide(index) {
        // Masquer toutes les diapositives
        const slides = document.querySelectorAll('.carousel-item');
        slides.forEach(slide => {
            slide.classList.remove('active');
        });

        // Afficher la diapositive spécifiée
        slides[index].classList.add('active');
    }

    function get_previous_post() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
        console.log('gauche ok');
    }

    function get_next_post() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
        console.log('droite ok');
    }