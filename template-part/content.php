<div class="container">
  <div class="formulaire">
    <form method="get" action="<?php echo esc_url(home_url('/')); ?>" id="filter-form" class="forma">
<!-- Catégorie -->
      <label for="category_selector"></label>
      <select name="category_selector" id="category_selector" class="filter-select" data-type="category">
        <option value="">CATÉGORIES</option>
        <?php
        $categories = get_terms(array('taxonomy' => 'categorie', 'hide_empty' => false));
        foreach ($categories as $category) : ?>
          <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
        <?php endforeach; ?>
      </select>
<!-- Format -->
      <label for="format_selector"></label>
      <select name="format_selector" id="format_selector" class="filter-select" data-type="format">
        <option value="">FORMATS</option>
        <?php
        $formats = get_terms(array('taxonomy' => 'format', 'hide_empty' => false));
        foreach ($formats as $format) : ?>
          <option value="<?php echo esc_attr($format->slug); ?>"><?php echo esc_html($format->name); ?></option>
        <?php endforeach; ?>
      </select>
<!-- Tri par date -->
      <div class="order_date">
        <label for="date_order"></label>
        <select name="date_order" id="date_order" class="filter-select" data-type="date">
          <option value="">TRIER PAR</option>
          <option value="DESC">Du plus récent au plus ancien</option>
          <option value="ASC">Du plus ancien au plus récent</option>
        </select>
      </div>
    </form>
  </div>

  <div id="filtered-photos">
    <?php
    // Récupérer les valeurs des filtres
    $category_filter = isset($_GET['category_selector']) ? sanitize_text_field($_GET['category_selector']) : '';
    $format_filter = isset($_GET['format_selector']) ? sanitize_text_field($_GET['format_selector']) : '';
    $date_order = isset($_POST['date_order']) ? sanitize_text_field($_POST['date_order']) : 'DESC'; // Par défaut, trier par ordre décroissant
    // Afficher toutes les photos sur la page d'accueil
    $args_all_photos = array(
      'post_type' => 'photo',
      'posts_per_page' => 8,
      'orderby' => 'meta_value_num', // Tri par la valeur du champ personnalisé
      'meta_key' => 'annee',  // Clé du champ personnalisé "annee"
      'order' => $date_order,
    );
    // Si un ordre est défini, ajoutez le tri par année
    if ($date_order) {
      $args_all_photos['orderby'] = 'meta_value_num';
      $args_all_photos['meta_key'] = 'annee';
      $args_all_photos['order'] = $date_order;
    }
    $query_all_photos = new WP_Query($args_all_photos);

    if ($query_all_photos->have_posts()) :

      while ($query_all_photos->have_posts()) :
        $query_all_photos->the_post();

        $photo_id = get_the_ID();

        $category = !empty(get_the_category()) ? esc_attr(get_the_category()[0]->name) : '';
        $type = esc_attr(get_post_meta($photo_id, 'type', true));
        $date_order= isset($_GET['date_order']) ? sanitize_text_field($_GET['date_order']) : '';
        $format = esc_attr(get_post_meta($photo_id, 'format', true));

        $link_url = add_query_arg(
          array(
            'id' => $photo_id,
            'category' => $category,
            'type' => $type,
            'date' => $date_order,
            'format' => $format,
          ),

          get_permalink(),


        );


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
      <?php endwhile; ?>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>

  </div>
  <div id="loaded-photos"></div>

  <div>
    <button type="button" id="load-more">Charger plus</button>
  </div>
</div>
