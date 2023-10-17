<?php get_header(); ?>
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
        <p class="font">Cette photo vous intéresse ?</p>
    </div>
    <div>
        <button class="myBtn">Contact</button>

    </div>
    <div class="carousel">
        <?php
        // Récupérer les autres photos de la même catégorie
        $related_photos = new WP_Query(array(
            'post_type' => 'photos',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'categorie',
                    'field' => 'id',
                    'terms' => $terms_category[0], // Utiliser le terme de la catégorie actuelle
                ),
            ),
        ));

        while ($related_photos->have_posts()) : $related_photos->the_post();
            // Afficher les miniatures des photos
        ?>
            <div>
                <a href="<?php the_permalink(); ?>">
                    <?php echo get_the_post_thumbnail('thumbnail'); ?>
                </a>
            </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    </div>
</div>
<div id="line">
    <hr>
</div>

<div>
    <p class="p">VOUS AIMEREZ AUSSI</p>
</div>


</body>
<?php get_footer(); ?>