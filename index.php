<?php get_header(); ?>


<div>
    <?php
    $random_photo_url = get_random_photo();
    if (!empty($random_photo_url)) :
    ?>
        <div>
            <img class="banner" src="<?php echo esc_url($random_photo_url); ?>" alt="Photos aléatoire">
        </div>
    <?php
    endif;
    ?>

    <h1 class="negative-text">PHOTOGRAPHE EVENT</h1>
</div>

<?php include_once "template-part/content.php"; ?>

</body>
<?php get_footer(); ?>