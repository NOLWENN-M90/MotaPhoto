<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

?>

<?php get_header(); ?>

<div id="hero">
    <!-- Contenu du héros va ici -->
</div>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h2 class="entry-title">', '</h2>'); ?>
                </header>

                <div class="entry-content">
                    <?php
                    // Boucle pour afficher les photos
                    $photos = get_field('photos'); // Assurez-vous d'ajuster le champ ACF selon votre structure
                    if ($photos) :
                        foreach ($photos as $photo) :
                            // Ajoutez un lien autour de chaque photo
                            ?>
                            <a href="<?php echo esc_url(get_permalink($photo['ID'])); ?>">
                                <div class="photo-container">
                                    <h2 class="photo-title"><?php echo esc_html(get_the_title($photo['ID'])); ?></h2>
                                    <div class="photo-content">
                                        <!--  le contenu de la photo  -->
                                    </div>
                                    <div class="photo-meta">
                                        <p>Type: <?php echo esc_html(get_post_meta($photo['ID'], 'type', true)); ?></p>
                                        <p>Référence: <?php echo esc_html(get_post_meta($photo['ID'], 'référence', true)); ?></p>
                                    </div>
                                </div>
                            </a>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </article>
            <?php
        endwhile;
        ?>

    </main>
</div>

<?php get_footer(); ?>



