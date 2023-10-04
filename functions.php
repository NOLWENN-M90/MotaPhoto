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

function theme_enqueue_script()
{
	wp_enqueue_script('modal', get_template_directory_uri() . '/scripts/script.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'theme_enqueue_script');
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

