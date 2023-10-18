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
        <div>
            <button class="myBtn">Contact</button>

        </div>
        <?php
        // ... (votre code PHP existant) ...

        // Ouvrez la boucle WordPress pour afficher le contenu de la photo
        if (have_posts()) :
            while (have_posts()) : the_post();
        ?>
                <div class="single-photo-content">
                    <?php the_content(); ?>
                    <div class="carousel-wrapper">
                        <div class="carousel">
                            <!-- <?php
                                    // Récupérer la catégorie de la photo actuelle
                                    $current_photo_category = wp_get_post_terms(get_the_ID(), 'categorie');

                                    // Récupérer les autres photos de la même catégorie
                                    $related_photos = new WP_Query(array(
                                        'post_type' => 'photo',
                                        'posts_per_page' => 1,
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => 'categorie',
                                                'field' => 'id',
                                                'terms' => !empty($current_photo_category) ? $current_photo_category[0]->term_id : 0,
                                            ),
                                        ),
                                    ));

                                    while ($related_photos->have_posts()) : $related_photos->the_post();
                                    ?> -->
                            <div class="carousel-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php echo get_the_post_thumbnail('thumbnail', 'smaller'); ?>
                                </a>
                            </div>
                        <?php endwhile;
                                    wp_reset_postdata();
                        ?>

                        <div class="carousel-arrow carousel-arrow-left" onclick="prevSlide()">&#8249;</div>
                        <div class="carousel-arrow carousel-arrow-right" onclick="nextSlide()">&#8250;</div>
                        </div>
                    </div>
                </div>
                <div class="single-photo-description">

                </div>
        <?php
            endwhile;
        endif;
        ?>


    </div>
    <div>
        <div id="line">
            <hr>
        </div>

        <div>
            <p class="p">VOUS AIMEREZ AUSSI</p>
        </div>
        <div class='affiche'>
<button id="show-all-photos" >Toutes les photos
</button>

        </div>
    </div>
</body>
<?php get_footer(); ?>