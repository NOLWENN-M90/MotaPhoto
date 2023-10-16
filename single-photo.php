<?php get_header(); ?>
<?php

// Récupérer les paramètres de l'URL
$photo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$reference = get_field('reference', $photo_id);
$terms_category = wp_get_object_terms($photo_id, 'categorie', array('fields' => 'names'));
$terms_format = wp_get_object_terms($photo_id, 'format', array('fields' => 'names'));
$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';
$annee = isset($_GET['annee']) ? sanitize_text_field($_GET['annee']) : '';


// Vérifier que l'ID est valide
if ($photo_id > 0) {
    // Utilisez l'ID et les autres paramètres pour personnaliser le contenu
    $photo = get_post($photo_id);
    if ($photo) {
        // Récupérer l'URL de l'image à sa taille complète
        $image_data = wp_get_attachment_image_src($photo_id, 'full');
        $image_url = $image_data ? $image_data[0] : '';

?>
        <div class="photo-details">
            <div class="titre">
                <h2><?php echo esc_html($photo->post_title); ?></h2>
                <div class="list">
                    <ul>
                        <li>Référence: <?php echo esc_html($reference); ?></li>
                        <li>Catégorie: <?php echo esc_html(implode(', ', $terms_category)); ?></li>
                        <li>Format: <?php echo esc_html(implode(', ', $terms_format)); ?></li>
                        <li>Type: <?php echo esc_html($type); ?></li>
                        <li>Année: <?php echo esc_html($annee); ?></li>

                    </ul>
                    <div id="line-down">
                        <hr>
                    </div>
                </div>

            </div>
            <div class="thumbnail"><?php echo get_the_post_thumbnail($image_data, 'full'); ?> </div>



        </div>


<?php
    } else {
        echo '<p>Photo non trouvée</p>';
    }
} else {
    echo '<p>Identifiant de photo invalide</p>';
}
?>
<div class="bp">
    <div>
        <p class="font">Cette photo vous intéresse ?</p>
    </div>
    <div>
        <button class="myBtn2">Contact</button>
    </div>
    <div class="carousel">
        <?php
        // Récupérer les autres photos de la même catégorie
        $related_photos = new WP_Query(array(
            'post_type' => 'photo',
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