
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

    // Enregistre le script script.js
    wp_enqueue_script('script', get_template_directory_uri() . '/scripts/script.js', array('jquery'), '1.0', true);

    $current_category = get_queried_object();

    // Initialise un tableau 
    $script_data = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'currentCategoryId' => $current_category ? $current_category->term_id : 0,
    );
    wp_localize_script('script', 'customScriptData', $script_data);
    // Passe la valeur de l'URL du fichier admin-ajax.php à votre script
    wp_localize_script('script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

add_action('wp_enqueue_scripts', 'theme_enqueue_style');

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
add_action('wp_ajax_get_photo_info', 'get_photo_info_callback');
add_action('wp_ajax_nopriv_get_photo_info', 'get_photo_info_callback');

// On fait appel à Ajax et pour le bouton de pagination de la page d'accueil
function load_more_photos()
{
    $offset = $_POST['offset'];
    $photos_to_load = $_POST['photos_to_load'];

    // Construire les arguments de la requête WP_Query pour charger plus de photos
    $args = array(
        'post_type' => 'photo',
        'posts_per_page' => $photos_to_load,
        'offset' => $offset,

    );

    $query_photos = new WP_Query($args);

    if ($query_photos->have_posts()) :

        while ($query_photos->have_posts()) :
            $query_photos->the_post();
            echo '<div class="photo-content">';
            echo '<img src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="' . '">';
            echo '</div>';

        endwhile;
    endif;

    wp_reset_postdata();

    die();
}

add_action('wp_ajax_load_more_photos', 'load_more_photos');
add_action('wp_ajax_nopriv_load_more_photos', 'load_more_photos');

// On ajoute une fonction query_photo et renvoyer les photos filtrées
function get_filtered_photos()
{
    if (isset($_POST['action']) && $_POST['action'] == 'get_filtered_photos') {
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $format = isset($_POST['format']) ? sanitize_text_field($_POST['format']) : '';
        $annee = isset($_POST['annee']) ? sanitize_text_field($_POST['annee']) : '';

        // Initialise un tableau d'arguments pour WP_Query basé sur les filtres
        $args = array(
            'post_type' => 'photo',
            'posts_per_page' => -1,
            'tax_query' => array(),
            'meta_query' => array(),
        );

        // Ajoute la taxonomie 'Catégorie' à la requête si une catégorie est sélectionnée
        if ($category) {
            $args['tax_query'][] = array(
                'taxonomy' => 'categorie',
                'field' => 'slug',
                'terms' => $category,
            );
        }

        // Ajoute la taxonomie 'Format' à la requête si un format est sélectionné
        if ($format) {
            $args['tax_query'][] = array(
                'taxonomy' => 'format',
                'field' => 'slug',
                'terms' => $format,
            );
        }

        // Ajoute un filtre basé sur le champ ACF 'Année' si une année est sélectionnée
        if ($annee) {
            $args['meta_query'][] = array(
                'key' => 'annee',
                'value' => $annee,
                'compare' => '=',
            );
        }

        // Utilise WP_Query pour obtenir les résultats
        $query = new WP_Query($args);

        ob_start();  // Commence la capture de la sortie
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                // Affiche chaque photo
                echo '<div class="photo-content">';
                echo '<img src="' . esc_url(get_the_post_thumbnail_url()) . '" alt="' . '">';
                echo '</div>';
            }
            echo 'test';
        } else {
            echo 'Aucune photo trouvée.';
        }
        wp_reset_postdata();
        $output = ob_get_clean();  // Récupère la sortie capturée

        echo $output;
        wp_die();
    }
    echo'test';
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


function get_related_photos()
{
    // Récupère la catégorie actuelle depuis la requête AJAX
    $current_category = $_POST['offset'];
    $photos_to_load = $_POST['photos_to_load'];

    // Effectue une requête WP_Query pour récupérer les photos de la même catégorie
    $related_photos = new WP_Query(array(
        'post_type' => 'photo',
        'posts_per_page' => $photos_to_load,
        'tax_query' => array(
            array(
                'taxonomy' => 'categorie',
                'field' => 'id',
                'terms' => $current_category ? $current_category:0,
            ),
        ),
    ));

    while ($related_photos->have_posts()) : $related_photos->the_post();

    endwhile;

    // Réinitialise les données de publication
    wp_reset_postdata();

    //arrête l'exécution après l'envoi de la réponse
    wp_die();
}

add_action('wp_ajax_get_related_photos', 'get_related_photos');
add_action('wp_ajax_nopriv_get_related_photos', 'get_related_photos');

function get_prev_next_image_urls() {
    $prev_image_url = esc_url(get_the_post_thumbnail_url(get_adjacent_post(false, '', true)));
    $next_image_url = esc_url(get_the_post_thumbnail_url(get_adjacent_post(false, '', false)));

    $image_urls = array(
        'prev' => $prev_image_url,
        'next' => $next_image_url,
    );

    wp_send_json($image_urls);
}

add_action('wp_ajax_get_prev_next_image_urls', 'get_prev_next_image_urls');
add_action('wp_ajax_nopriv_get_prev_next_image_urls', 'get_prev_next_image_urls');
