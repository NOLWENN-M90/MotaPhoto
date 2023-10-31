<div class="container">
  <div class="formulaire">
    <!-- Afficher les sélecteurs pour "catégories", "formats" et "date" -->
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>" id="filter-form">
      <label for="category_selector"></label>
      <select name="category_selector" id="category_selector" class="filter-select" data-type="category">
        <option value="">CATÉGORIES</option>
        <?php
        $categories = get_terms(array('taxonomy' => 'categorie', 'hide_empty' => false));
        foreach ($categories as $category) : ?>
          <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
        <?php endforeach; ?>
      </select>

      <label for="format_selector"></label>
      <select name="format_selector" id="format_selector" class="filter-select" data-type="format">
        <option value="">FORMATS</option>
        <?php
        $formats = get_terms(array('taxonomy' => 'format', 'hide_empty' => false));
        foreach ($formats as $format) : ?>
          <option value="<?php echo esc_attr($format->slug); ?>"><?php echo esc_html($format->name); ?></option>
        <?php endforeach; ?>
      </select>

      <label for="date_order"></label>
      <select name="date_order" id="date_order" class="filter-select" data-type="date">
        <option value="">TRIER PAR</option>
        <option value="DESC">Du plus récent au plus ancien</option>
        <option value="ASC">Du plus ancien au plus récent</option>
      </select>
    </form>
  </div>

  <?php
  // Récupérer les valeurs des filtres
  $category_filter = isset($_GET['category_selector']) ? sanitize_text_field($_GET['category_selector']) : '';
  $format_filter = isset($_GET['format_selector']) ? sanitize_text_field($_GET['format_selector']) : '';
  $annee = isset($_GET['date_order']) ? sanitize_text_field($_GET['date_order']) : 'ASC';

  // Afficher toutes les photos sur la page d'accueil
  $args_all_photos = array(
    'post_type' => 'photo',
    'posts_per_page' => 8,
    'orderby' => 'meta_value', // Tri par la valeur du champ personnalisé
    'meta_key' => 'date',  // Clé du champ personnalisé "date"
    'order' => $annee,
  );

  $query_all_photos = new WP_Query($args_all_photos);

  if ($query_all_photos->have_posts()) :
    while ($query_all_photos->have_posts()) :
      $query_all_photos->the_post();

      $photo_id = get_the_ID();

      $category = !empty(get_the_category()) ? esc_attr(get_the_category()[0]->name) : '';
      $type = esc_attr(get_post_meta($photo_id, 'type', true));
      $annee = isset($_GET['date_order']) ? sanitize_text_field($_GET['date_order']) : '';
      $format = esc_attr(get_post_meta($photo_id, 'format', true));

      $link_url = add_query_arg(
        array(
          'id' => $photo_id,
          'category' => $category,
          'type' => $type,
          'date' => $annee,
          'format' => $format,
        ),

        get_permalink(),
        

      );

  ?>
  <?php
    endwhile;
  endif;
  wp_reset_postdata();

  ?>
  <?php if ($query_all_photos->have_posts()) : ?>
    <?php while ($query_all_photos->have_posts()) : $query_all_photos->the_post(); ?>

      <div class="photo-content already-displayed">
        <a href="<?php echo esc_url(get_permalink()) ?>" target="_blank" class="photo-link">
          <div class="overlay">
            <?php the_post_thumbnail(); ?>
            <div class="info-icon">
              <i class="fa fa-eye"></i>
            </div>
            <div class="fullscreen-icon">
              <i class="fa fa-expand"></i>
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
    <?php endwhile; ?>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>
  <div>
    <button type="button" id="load-more">Charger plus</button>
  </div>

  </body>