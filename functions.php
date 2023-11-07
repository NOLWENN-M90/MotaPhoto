
<?php
function montheme_supports()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    register_nav_menu('header', 'En tête du menu');
    register_nav_menu('footer', 'Pied de page');
}
add_action('after_setup_theme', 'montheme_supports');

function theme_enqueue_style()
{
    wp_enqueue_style('mota-theme', get_template_directory_uri() . '/style.css');
   
}

function enqueue_custom_scripts()
{
    // Enregistre jQuery
    wp_enqueue_script('jquery');

    // Enqueue Select2 CSS
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');

    // Enqueue Select2 JS, dépendant de jQuery
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), null, true);
    
    // Enregistre le script script.js
    wp_enqueue_script('script', get_template_directory_uri() . '/scripts/script.js', array('jquery'), '1.0', true);

    $current_category = get_queried_object();

    // Initialise un tableau 
    $script_data = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('my_nonce'),
        'currentCategoryId' => $current_category ? $current_category->term_id : 0,
    );

    wp_localize_script('script', 'customScriptData', $script_data);
    enqueue_photo_data();
}
function enqueue_photo_data() {
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => -1, // Get all photos
        'fields' => 'ids', // Only get photo IDs to reduce load
    );
    $photo_query = new WP_Query($args);
    $photos = array();
    if ($photo_query->posts) {
        foreach ($photo_query->posts as $photo_id) {
            $photos[] = array(
                'id' => $photo_id,
                'src' => get_the_post_thumbnail_url($photo_id),
                'category' => wp_get_post_terms($photo_id, 'categorie')[0]->name ?? '',
                'reference' => get_field('reference', $photo_id),
            );
        }
    }
    // Localize photo data for use in JavaScript
    wp_localize_script('script', 'photosData', $photos);
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
add_action('wp_enqueue_scripts', 'theme_enqueue_style');
function select2_init()
{
    echo '
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("select").select2(); // Initialise Select2 sur tous les éléments "select"
        });
    </script>';
}
add_action('wp_footer', 'select2_init', 30);

// On intègre un bouton "Contact" au Menu en haut de page
function add_search_form2($items, $args)
{
    if ($args->theme_location == 'header') {
        $items .= '<button class="myBtn">CONTACT</button>';
    } else {
    }

    return $items;
}
add_filter('wp_nav_menu_items', 'add_search_form2', 10, 2);

// On affiche une image de façon aléatoire dans la banière à chaque actualisation
function get_random_photo()
{

    $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 1,
        'orderby'        => 'rand', // Ordre aléatoire
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $query->the_post();
        $random_photo_url = get_the_post_thumbnail_url();
        return $random_photo_url;
    }

    // Retourne une image par défaut ou une chaîne vide si aucune image n'est trouvée.
    return 'fonction ok';
}

// Fonction Ajax pour récupérer les informations de la photo
add_action('wp_ajax_get_photo_info', 'get_random_photo');
add_action('wp_ajax_nopriv_get_photo_info', 'get_random_photo');



// On fait appel à Ajax et pour le bouton de pagination de la page d'accueil
function load_more_photos()
{
    $response = '';
    $offset = $_POST['offset'];
    $photos_to_load = $_POST['photos_to_load'];

    // Construire les arguments de la requête WP_Query pour charger plus de photos
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => $photos_to_load,
        'offset' => $offset,
    );

    $query_photos = new WP_Query($args);

    if ($query_photos->have_posts()) {
        while ($query_photos->have_posts()) {
            $query_photos->the_post();
            $response .= '<div class="photo-content">';
            $response .= '<a href="' . esc_url(get_permalink()) . '" target="_blank" class="photo-link">';
            $response .= '<div class="overlay">';
            $response .= '<img src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="' . '">';
            $response .= '<div class="info-icon"><i class="fa fa-eye"></i></div>';
            $response .= '<div class="fullscreen-icon"><i class="fa fa-expand"></i></div>';
            $response .= '<div class="overlay-content">';
            $response .= '<p class="photo-reference">' . get_field('reference') . '</p>';
            $response .= '<p class="photo-category">';

            $terms_category = wp_get_post_terms(get_the_ID(), 'categorie');
            if (!empty($terms_category)) {
                $response .= $terms_category[0]->name;
            }

            $response .= '</p></div></div></a></div>';
        }
    }

    // Assurez-vous de renvoyer la réponse
    echo $response;

    wp_reset_postdata();

    die();
}

add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

// On ajoute une fonction query_photo et renvoyer les photos filtrées
function get_filtered_photos()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'my_nonce')) {
        echo 'Permission denied';
        wp_die();
    }

    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
    $annee = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';

    // Initialise un tableau d'arguments pour WP_Query basé sur les filtres
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => -1,
        'tax_query' => array(),
        'meta_query' => array(),
    );

    if ($category) {
        $args['tax_query'][] = array(
            'taxonomy' => 'categorie',
            'field' => 'slug',
            'terms' => $category,
        );
    }

    if ($format) {
        $args['tax_query'][] = array(
            'taxonomy' => 'format',
            'field' => 'slug',
            'terms' => $format,
        );
    }

    if ($annee) {
        $args['meta_query'][] = array(
            'key' => 'annee',
            'value' => $annee,
            'compare' => '=',
        );
    }

    $query = new WP_Query($args);

    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $reference = get_field('reference'); // Obtenez la référence de la photo
            $terms_category = wp_get_post_terms(get_the_ID(), 'categorie'); // Obtenez les termes de la catégorie
            $category_name = !empty($terms_category) ? $terms_category[0]->name : ''; // Assurez-vous qu'il y a des termes de catégorie
            echo '<div class="photo-content">';
            // Le lien vers la page détaillée de la photo
            echo '<a href="' . esc_url(get_permalink()) . '" target="_blank" class="photo-link">';
            echo '<div class="overlay">';
            the_post_thumbnail();
            echo '<div class="info-icon">';
            echo '<i class="fa fa-eye"></i>';
            echo '</div>';
            echo '</div>'; // Ferme .overlay ici, car le reste n'est pas censé être lié à la page de détail
            echo '</a>'; // Ferme le premier <a> ici
            // L'icône d'agrandissement pour la lightbox
            echo '<div class="fullscreen-icon">';
            echo '<a href="' . esc_url(get_the_post_thumbnail_url()) . '" class="photo-linka" data-reference="' . esc_attr($reference) . '" data-category="' . esc_attr($category_name) . '">';
            echo '<i class="fa fa-expand"></i>';
            echo '</a>';
            echo '</div>';
            echo '<div class="overlay-content">';
            echo '<p class="photo-reference">' . esc_html($reference) . '</p>';
            echo '<p class="photo-category">' . esc_html($category_name) . '</p>';
            echo '</div>'; // .overlay-content
            echo '</div>'; // .photo-content
        }
    } else {
        echo 'Aucune photo trouvée.';
    }
    wp_reset_postdata();
    
    $output = ob_get_clean();
    echo $output;
    wp_die();
}

add_action('wp_ajax_get_filtered_photos', 'get_filtered_photos');
add_action('wp_ajax_nopriv_get_filtered_photos', 'get_filtered_photos');



function register_custom_post_type_photo()
{
    $args = array(
        'labels' => array(
            'name' => 'Photos',
            'singular_name' => 'Photo',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),

    );
    register_post_type('photo', $args);
}
add_action('init', 'register_custom_post_type_photo');
// Pour la taxonomie "catégorie"
register_taxonomy('categorie', 'photo', array(
    'label' => 'Catégorie',
    'hierarchical' => true,

));

// Pour la taxonomie "format"
register_taxonomy('format', 'photo', array(
    'label' => 'Format',
    'hierarchical' => false,

));
add_action('init', 'register_custom_post_type_photo');


