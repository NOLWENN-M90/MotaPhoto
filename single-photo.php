<?php get_header(); ?>

<?php while (have_posts()) : the_post();
    $current_photo_id = get_the_ID(); // ID de la photo actuelle pour l'exclusion
?>
    <div class="container" style="height: 844px; padding-top: 30px; text-transform: uppercase; margin-top:0px;">
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
            $main_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            echo '<a href="' . esc_url($main_image_url) . '" style="display: none;"></a>'; // Ajoute l'image principale à la galerie mais la cache
            echo get_the_post_thumbnail(get_the_ID(), 'full');
            ?>
        </div>
    </div>

    <div id="bp">
        <div>
            <p class="tx">Cette photo vous intéresse ?</p>
        </div>
        <div class="cont">
            <button class="myBtn">Contact</button>
        </div>


        <div class="single-photo-content">
            <?php the_content(); ?>
            <div class="arrows">
                <?php
                $prev_post = get_adjacent_post(false, '', true);
                $next_post = get_adjacent_post(false, '', true);

                if ($prev_post) {
                    $prev_image_url = esc_url(get_the_post_thumbnail_url($prev_post->ID));
                    $prev_reference = get_field('reference', $prev_post->ID);
                    $prev_terms_category = wp_get_post_terms($prev_post->ID, 'categorie');
                    $prev_category = !empty($prev_terms_category) ? $prev_terms_category[0]->name : '';
                    echo '<a href="#" class="preview-arrow thumbnail" data-direction="prev" data-postid="' . $prev_post->ID . '" data-imageurl="' . $prev_image_url . '" data-reference="' . esc_attr($prev_reference) . '" data-category="' . esc_attr($prev_category) . '">';
                    echo '<span class="carousel-arrow-left left-arrow">←</span>';
                    echo '</a>';
                }


                if ($next_post) {
                    $next_image_url = esc_url(get_the_post_thumbnail_url($next_post->ID));
                    $next_reference = get_field('reference', $next_post->ID);
                    $next_terms_category = wp_get_post_terms($next_post->ID, 'categorie');
                    $next_category = !empty($next_terms_category) ? $next_terms_category[0]->name : '';
                    echo '<a href="#" class="preview-arrow thumbnail" data-direction="next" data-postid="' . $next_post->ID . '" data-imageurl="' . $next_image_url . '" data-reference="' . esc_attr($next_reference) . '" data-category="' . esc_attr($next_category) . '">';
                    echo '<span class="carousel-arrow-right right-arrow">→</span>';
                    echo '</a>';
                }
                ?>
                <!-- <a href="<?php echo esc_url($prev_image_url); ?>" class="preview-arrow thumbnail" data-reference="<?php echo esc_attr($prev_reference); ?>" data-category="<?php echo esc_attr($prev_category); ?>">
                    <span class="carousel-arrow-left left-arrow">←</span>
                </a>
                <a href="<?php echo esc_url($next_image_url); ?>" class="preview-arrow thumbnail" data-reference="<?php echo esc_attr($next_reference); ?>" data-category="<?php echo esc_attr($next_category); ?>">
                    <span class="carousel-arrow-right right-arrow">→</span>
                </a> -->
            </div>

        </div>
    </div>

    <div id="line">
        <hr>
    </div>

    <div>
        <p class="p">VOUS AIMEREZ AUSSI</p>
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
                    'posts_per_page' => 2, // Limite à 2 photos supplémentaires
                    'post__not_in' => array($current_photo_id), // Exclut la photo actuelle
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'categorie',
                            'field' => 'id',
                            'terms' => $current_category_id,
                        ),
                    ),
                ));

                if ($related_photos->have_posts()) :
                    while ($related_photos->have_posts()) : $related_photos->the_post();
            ?>
                        <div class="photo-content already-displayed">
                            <a href="<?php echo esc_url(get_permalink()) ?>" target="_blank" class="photo-link">
                                <div class="overlay">
                                    <?php the_post_thumbnail(); ?>
                                    <div class="info-icon">
                                        <i class="fa fa-eye"></i>
                                    </div>
                                    <div class="fullscreen-icon">
                                        <?php
                                        $terms_category = wp_get_post_terms(get_the_ID(), 'categorie');
                                        $category_name = !empty($terms_category) ? esc_attr($terms_category[0]->name) : '';
                                        $image_full = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                                        ?>
                                        <a data-src="<?php echo esc_url($image_full[0]); ?>" class="photo-linka" data-reference="<?php echo esc_attr(get_field('reference')); ?>" data-category="<?php echo esc_attr($category_name); ?>">
                                            <i class="fa fa-expand" data-tooltip="Plein écran"></i>
                                        </a>
                                    </div>
                                    <div class="overlay-content">
                                        <p class="photo-reference"><?php echo get_field('reference'); ?></p>
                                        <p class="photo-category">
                                            <?php
                                            $terms_category = wp_get_post_terms(get_the_ID(), 'categorie');
                                            if (!empty($terms_category)) {
                                                echo $terms_category[0]->name;
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>

                            </a>

                        </div>
            <?php
                    endwhile;
                endif;
                wp_reset_postdata();
            }
            ?>
        </div>
        <div>
            <button id="show-all-photos">
                <a href="<?php echo esc_url(home_url()); ?>">Toutes les photos</a>
            </button>
        </div>
    </div>
<?php endwhile; ?>

<?php get_footer(); ?>