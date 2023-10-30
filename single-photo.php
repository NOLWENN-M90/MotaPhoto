<?php get_header(); ?>

<body>
    <?php

    while (have_posts()) : the_post();
    ?>
        <div class="photo-details">
            <div class="titre">
                <h2><?php the_title() ?></h2>
                <div class="list">
                    <ul>
                        <?php

                        $reference = get_field('reference');
                        if ($reference) {
                            echo '<li>Référence: ' . esc_html($reference) . '</li>';
                        }


                        $terms_category = wp_get_post_terms(get_the_ID(), 'categorie');
                        if (!empty($terms_category)) {
                            echo '<li>Catégorie: ';
                            foreach ($terms_category as $term) {
                                echo $term->name . ' ';
                            }
                            echo '</li>';
                        }

                        $terms_format = wp_get_post_terms(get_the_ID(), 'format');
                        if (!empty($terms_format)) {
                            echo '<li>Format: ';
                            foreach ($terms_format as $term) {
                                echo $term->name . ' ';
                            }
                            echo '</li>';
                        }

                        $type = get_field('type');
                        echo '<li>Type: ' . esc_html($type) . '</li>';

                        $annee = get_field('annee');
                        echo '<li>Année: ' . esc_html($annee) . '</li>';
                        ?>
                    </ul>
                    <div id="line-down">
                        <hr>
                    </div>
                </div>
            </div>
            <div class="thumbnail"><?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?> </div>
        </div>
    <?php
    endwhile;
    ?>

    <div id="bp">
        <div>
            <p class="tx">Cette photo vous intéresse ?</p>
        </div>
        <div class="cont">
            <button class="myBtn">Contact</button>

        </div>
        <?php

        if (have_posts()) :
            while (have_posts()) : the_post();
        ?>
                <?php
                $prev_image_url = esc_url(get_the_post_thumbnail_url(get_adjacent_post(false, '', true)));
                $next_image_url = esc_url(get_the_post_thumbnail_url(get_adjacent_post(false, '', false)));
                ?>
                <div class="single-photo-content" data-prev-image="<?php echo $prev_image_url; ?>" data-next-image="<?php echo $next_image_url; ?>">
                    <?php the_content(); ?>
                    <div class="arrows">
                        <a href="<?php echo esc_url(get_permalink(get_adjacent_post(false, '', true))); ?>">
                            <img class="carousel-arrow-left" src="\wp-content\themes\mota-theme\assets\Line6.png" alt="Flèche gauche">
                        </a>
                        <a href="<?php echo esc_url(get_permalink(get_adjacent_post(false, '', false))); ?>">
                            <img class="carousel-arrow-right" src="\wp-content\themes\mota-theme\assets\Line7.png" alt="Flèche droite">
                        </a>
                    </div>
                </div>
                <div class="thumbnail-preview" style="display: none;">
                    <img src="<?php echo $prev_image_url; ?>" alt="Thumbnail">
                </div>
            <?php endwhile; ?>
    </div>
    <div>
        <div id="line">
            <hr>
        </div>

        <div>
            <p class="p">VOUS AIMEREZ AUSSI</p>
        </div>
        <div class="affiche">
        <?php
            $current_photo_id = get_the_ID();

            // Obtenir les termes de la taxonomie 'categorie' de la photo actuelle
            $terms_category = wp_get_post_terms($current_photo_id, 'categorie');

            if (!empty($terms_category)) {
                $current_category_id = $terms_category[0]->term_id;

                // Effectue une requête pour récupérer les photos du CPT avec la même catégorie
                $related_photos = new WP_Query(array(
                    'post_type' => 'photo',
                    'posts_per_page' => 3, // Récupère 3 posts (2 supplémentaires)
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'categorie',
                            'field' => 'id',
                            'terms' => $current_category_id,
                        ),
                    ),
                ));

                $count = 0;

                // Affiche les photos
                while ($related_photos->have_posts()) : $related_photos->the_post();

                    if ($count < 2) { // Affiche seulement 2 photos supplémentaires
                        echo '<div class="photo-content">';
                        echo '<a href="' . get_permalink() . '">';
                        echo '<div class="overlay">';
                        echo get_the_post_thumbnail();
                        echo '<div class="info-icon">';
                        echo '<i class="fa fa-eye"></i>';
                        echo '</div>';
                        echo '<div class="fullscreen-icon">';
                        echo '<i class="fa fa-expand"></i>';
                        echo '</div>';
                        echo '<div class="overlay-content">';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                        echo '</div>';
                        $count++;
                    }

                endwhile;

                // Réinitialise les données de la requête principale de WordPress
                wp_reset_query();
            };
        endif;
        ?>
        </div>
        <div>
            <button id="show-all-photos">
                <a href="<?php echo esc_url(home_url()); ?>">Toutes les photos</a>
            </button>
        </div>
    </div>

</body>
<?php get_footer(); ?>