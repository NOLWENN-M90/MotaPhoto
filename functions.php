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

function enqueue_custom_scripts() {
    // Enregistrer jQuery
    wp_enqueue_script('jquery');

    // Enregistrer le script script.js
    wp_enqueue_script('script', get_template_directory_uri() . '/scripts/script.js', array('jquery'), '1.0', true);

    // Passer la valeur de l'URL du fichier admin-ajax.php à votre script
    wp_localize_script('script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

add_action('wp_enqueue_scripts', 'theme_enqueue_style');
function add_search_form2($items, $args)
{
	if ($args->theme_location == 'header') {
		$items .= '<button class="myBtn">CONTACT</button>';
	} else {
	}

	return $items;
}
add_filter('wp_nav_menu_items', 'add_search_form2', 10, 2);


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
function get_random_photo() {
	
    $args = array(
        'post_type'      => 'photo',
        'posts_per_page' => 1,
        'orderby'        => 'rand', // Ordre aléatoire
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $query->the_post();
        $random_photo_url = get_the_post_thumbnail_url();
        var_dump($random_photo_url); // Ajout pour le débogage
        return $random_photo_url;
    }

    // Retourne une image par défaut ou une chaîne vide si aucune image n'est trouvée.
    return 'fonction ok'; 
}

// Fonction Ajax pour récupérer les informations de la photo
add_action('wp_ajax_get_photo_info', 'get_photo_info_callback');
add_action('wp_ajax_nopriv_get_photo_info', 'get_photo_info_callback');

function get_photo_info_callback() {
    $image_url = $_POST['image_url'];

    //Récupérer le titre de la photo
    $photo_title = get_the_title(); 

    // Envoyer les informations de la photo en tant que réponse Ajax
    echo 'Titre de la photo : ' . $photo_title;

    wp_die();
}



