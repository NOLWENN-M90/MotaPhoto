console.log("custom script ok");

jQuery(document).ready(function ($) {
    $('.carousel').slick({
        // Les options de configuration de Slick Carousel
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        prevArrow: '<button type="button" class="slick-prev">Previous</button>',
        nextArrow: '<button type="button" class="slick-next">Next</button>',
    });

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
