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
        <nav role="navigation" aria-label="<?php _e('Menu principal', 'text-domain'); ?>" class="menu-desktop">
            <img class="logo" src="/wp-content/themes/mota-theme/assets/NathalieMota.png" alt="logo">
            <?php
            wp_nav_menu([
                'theme_location' => 'header',
                'container'      => false,
                'menu_class'     => 'desktop-menu',
            ]);
            ?>
        </nav>


        <button id="mobileMenuToggle" class="mobile-menu-toggle">☰</button>
        <img class="logo2" src="/wp-content/themes/mota-theme/assets/NathalieMota.png" alt="logo">
        <div id="mobileMenuModal" class="mobile-menu-modal">
            <div class="mobile-menu-content">
                <nav id="mobileMenu" class="mobile-menu" aria-label="<?php _e('Menu mobile', 'text-domain'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'header',
                        'container'      => false,
                        'menu_class'     => 'mobile-menu-items',
                    ]);
                    ?>
                    <span id="closeMobileMenu" class="close">&times;</span>
                </nav>
            </div>
        </div>
        <?php include_once "template-part/contact.php"; ?>

    </header>