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
    <!-- Afficher les sélecteurs pour "catégories", "formats" et "date"  -->
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>" id="filter-form">
    <label for="category_selector"></label>
      <select name="category_selector" id="category_selector">
        <option value="">CATÉGORIES</option>
        <?php
        $categories = get_terms(array('taxonomy' => 'categories', 'hide_empty' => false));
        foreach ($categories as $category) : ?>
          <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
        <?php endforeach; ?>
      </select>

      <label for="format_selector"></label>
      <select name="format_selector" id="format_selector">
        <option value="">FORMATS</option>
        <?php
        $formats = get_terms(array('taxonomy' => 'formats', 'hide_empty' => false));
        foreach ($formats as $format) : ?>
          <option value="<?php echo esc_attr($format->slug); ?>"><?php echo esc_html($format->name); ?></option>
        <?php endforeach; ?>
      </select>

      <label for="date_order"></label>
      <select name="date_order" id="date_order">
      <option value="">TRIER PAR</option>
        <?php 
        $date_values = get_field('date', 'option', false);
        foreach ($date_values as $date_values) : ?>
        <option value="DESC" <?php echo selected($date_order, 'DESC'); ?>>Du plus récent au plus ancien</option>
        <option value="ASC" <?php echo selected($date_order, 'ASC'); ?>>Du plus ancien au plus récent</option>
        <?php endforeach; ?>
      </select>


    </form>
  </div>
  <?php
  // Récupérer les valeurs des filtres
  $category_filter = isset($_GET['category_selector']) ? sanitize_text_field($_GET['category_selector']) : '';
  $format_filter = isset($_GET['format_selector']) ? sanitize_text_field($_GET['format_selector']) : '';
  $date_order = isset($_GET['date_order']) ? sanitize_text_field($_GET['date_order']) : '';
  $args_all_photos = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : '';

  

  // Afficher toutes les photos sur la page d'accueil
  $args_all_photos = array(
    'post_type' => 'photo',
    'posts_per_page' => 8,  // Récupérer tous les articles
    'orderby' => 'meta_value', // Tri par la valeur du champ personnalisé
    'meta_key' => 'date',  // Clé du champ personnalisé "date"
    'order' => $date_order,
  );
  if (!empty($args_all_photos)) {
    $args['post_type'] = $args_all_photos;
  }

  $query_all_photos = new WP_Query($args_all_photos);

  if ($query_all_photos->have_posts()) {
    while ($query_all_photos->have_posts()) {
      $query_all_photos->the_post();
  ?>
      <div class="photo-content">
        <div class="thumbnail"><?php the_post_thumbnail(); ?></div>
      </div>
  <?php
    }
    wp_reset_postdata();
  }
  ?>


  <?php
  // Construire les arguments de la requête WP_Query en fonction des filtres
  $args = array(
    'post_type' => 'photos',
    'posts_per_page' => 8,
    'order' => $date_order,

  );

  // Ajouter le filtre de taxonomie "catégories" si sélectionné
  if (!empty($category_filter)) {
    $args['tax_query'][] = array(
      'taxonomy' => 'categories',
      'field' => 'slug',
      'terms' => $category_filter,
    );
  }

  // Ajouter le filtre de taxonomie "formats" si sélectionné
  if (!empty($format_filter)) {
    $args['tax_query'][] = array(
      'taxonomy' => 'formats',
      'field' => 'slug',
      'terms' => $format_filter,
    );
  }

  

  // Exécuter la requête WP_Query
  $query = new WP_Query($args);

  // Afficher les photos
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
  ?>
      <div class="photo">
        <div class="thumbnail"><?php the_post_thumbnail(); ?></div>
      </div>
  <?php
    }
    wp_reset_postdata();
  } 
  ?>
</div>
<div class="btn__wrapper">
  <a href="#!" class="btn btn__primary" id="load-more">Charger plus</a>
</div>
</div>