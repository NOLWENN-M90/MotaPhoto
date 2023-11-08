<?php get_header(); ?>


<?php

while (have_posts()) : the_post();
?>
    <div class="container" style="height: 844px; padding-top: 30px;
    text-transform: uppercase; margin-top:0px;">
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
        <div class="thumbnail open-lightbox">
            <?php
            // Lien caché vers l'image principale pour la lightbox
            $main_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            echo '<a href="' . esc_url($main_image_url) . '" style="display: none;"></a>';
            // Affichage de l'image principale
            echo get_the_post_thumbnail(get_the_ID(), 'full');
            ?>
        </div>
    </div>
<?php endwhile; ?>

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
            $prev_post = get_adjacent_post(false, '', true);
            $next_post = get_adjacent_post(false, '', true);

            // Préparation des URL et des données pour la lightbox
            $prev_image_url = $prev_post ? esc_url(get_the_post_thumbnail_url($prev_post->ID)) : '';
            $next_image_url = $next_post ? esc_url(get_the_post_thumbnail_url($next_post->ID)) : '';
            ?>
            <div class="single-photo-content " data-prev-image="<?php echo $prev_image_url; ?>" data-next-image="<?php echo $next_image_url; ?>" data-prev-reference="<?php echo esc_attr($prev_reference); ?>" data-next-reference="<?php echo esc_attr($next_reference); ?>" data-prev-category="<?php echo esc_attr($prev_category); ?>" data-next-category="<?php echo esc_attr($next_category); ?>">
                <?php the_content(); ?>
                <div class="arrows">
                    <?php if ($prev_post) : ?>
                        <a href="<?php echo $prev_image_url; ?>" class="preview-arrow thumbnail" data-reference="<?php echo esc_attr(get_field('reference', $prev_post->ID)); ?>" data-category="<?php echo esc_attr(wp_get_post_terms($prev_post->ID, 'categorie')[0]->name); ?>">
                            <span class="carousel-arrow-left left-arrow">←</span>
                        </a>
                    <?php endif; ?>
                    <!-- Flèche droite pour l'image suivante -->
                    <?php if ($next_post) : ?>
                        <a href="<?php echo $next_image_url; ?>" class="preview-arrow thumbnail" data-reference="<?php echo esc_attr(get_field('reference', $next_post->ID)); ?>" data-category="<?php echo esc_attr(wp_get_post_terms($next_post->ID, 'categorie')[0]->name); ?>">
                            <span class="carousel-arrow-right right-arrow">→</span>
                        </a>
                    <?php endif; ?>
                </div>
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
                    echo '<div class="fullscreen-icon thumbnail">';
                    echo '<i class="fa fa-expand" data-tooltip="Plein écran"></i>';
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
            wp_reset_postdata();
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


<?php get_footer(); ?>