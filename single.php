<?php
get_header();

/* Start the Loop */
while (have_posts()) :
    the_post();

    if (is_singular('photo')) {
        // Utilisez le modèle single-photo.php pour les messages de type 'photo'
        get_template_part('single-photo');
    } else {
        // Utilisez le modèle par défaut pour les autres types de messages
        get_template_part('template-parts/content/content-single');
    }

    // Reste du contenu...

endwhile; // End of the loop.

get_footer();
