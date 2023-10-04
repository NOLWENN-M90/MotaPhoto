<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>
<div class="contenu">
<div class="formulaire">
  <form action="<?php echo esc_url(home_url('/')); ?>" method="get">
    <label for="category"></label>
    <?php
    // Afficher la liste déroulante pour la taxonomie "catégorie"
    wp_dropdown_categories(array(
      'taxonomy' => 'categorie', 
      'name' => 'category',
      'show_option_all' => 'CATÉGORIES',
      'orderby' => 'name',
      'echo' => 1,
    ));
    ?>

    <label for="format"></label>
    <?php
    // Afficher la liste déroulante pour la taxonomie "format"
    $terms = get_terms(array(
      'taxonomy' => 'format', 
      'hide_empty' => false,
    ));

    if (!empty($terms) && !is_wp_error($terms)) {
      echo '<select name="format" id="format">';
      echo '<option value="">FORMATS</option>';
      foreach ($terms as $term) {
        echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
      }
      echo '</select>';
    }
    ?>
  
    <label for="sort_by"></label>
    <select name="sort_by" id="sort_by">
        <option value="default">TRIER PAR</option>
        <option value="type">TYPES</option>
        <option value="reference">RÉFÉRENCES</option>
    </select>
  
    
</form>

</div>

  <?php
  // Utilisez WP_Query pour récupérer les photos du type de contenu personnalisé "photo"
  $args = array(
    'post_type' => 'photo',  
    'posts_per_page' => 8,  // Récupérer tous les articles
    'orderby' => 'meta_value', // Tri par la valeur du champ personnalisé
    'meta_key' => 'référence',  // Clé du champ personnalisé "référence"
    'order' => 'ASC', 
  );
  // Ajoutez des conditions pour les paramètres de recherche
  if (isset($_GET['category']) && !empty($_GET['category'])) {
    $args['tax_query'][] = array(
      'taxonomy' => 'categorie',
      'field' => 'id',
      'terms' => intval($_GET['category']),
    );
  }

  if (isset($_GET['format']) && !empty($_GET['format'])) {
    $args['tax_query'][] = array(
      'taxonomy' => 'format',
      'field' => 'slug',
      'terms' => sanitize_text_field($_GET['format']),
    );
  }
  $query = new WP_Query($args);

  // Vérifiez si des photos sont trouvées
  if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
  ?>
      <div class="photo-container">
        <div class="photo-content">
          <?php the_content(); ?>
        </div>
      </div>
  <?php
    endwhile;
    wp_reset_postdata();
  else :
    echo 'Aucune photo trouvée.';
  endif;
  ?>

</div>

<div class="btn__wrapper">
  <a href="#!" class="btn btn__primary" id="load-more">Charger plus</a>
</div>