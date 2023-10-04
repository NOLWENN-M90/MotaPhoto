<div id="line-up"><hr></div>


<nav role="navigation" class="footer" aria-label="<?php _e('footer', 'text-domain'); ?>">
        
        <?php
        wp_nav_menu([
            'theme_location' => 'footer',
            'container'      => false // On retire le conteneur généré par WP
        ]);
        ?>
    </nav>
    <?php wp_footer() ?>
</body>
</html>