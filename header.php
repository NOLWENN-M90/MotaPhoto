<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NMOTA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




    <?php
    wp_head();

    ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div class="background-overlay" id="backgroundOverlay"></div>
    <header>

        <nav role="navigation" aria-label="<?php _e('Menu principal', 'text-domain'); ?>">
            <img class="logo" src="/wp-content/themes/mota-theme/assets/NathalieMota.png" alt="logo">
            <?php
            wp_nav_menu([
                'theme_location' => 'header',
                'container'      => false // On retire le conteneur généré par WP
            ]);
            ?>
            <button id="mobile-menu-toggle" class="mobile-menu-toggle">☰</button>

            <div id="mobile" class="mobile__content">
                <?php
                wp_nav_menu([
                    'theme_location' => 'header',
                    'container'      => false
                ]);
                ?>
            </div>
        </nav>
        <?php include_once "template-part/contact.php"; ?>




    </header>