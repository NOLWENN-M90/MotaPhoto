<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NMOTA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <?php
    wp_head();

    ?>
</head>
<body>
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
    </nav>
<?php include_once "template-part/contact.php" ;?>
<div>
<img id="bannerImage" src="wp-content\themes\mota-theme\assets\nathalie-11.jpeg" alt="Banner Image">
<h1 class="negative-text">PHOTOGRAPHE EVENT</h1>
</div>

<?php include_once "template-part/content.php"; ?>
    </header>
   

