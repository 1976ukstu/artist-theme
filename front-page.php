<?php get_header(); ?>

<div class="site-title">
    Dragica<br>Carlin
</div>

<!-- Permanent menu for home page - no hamburger needed -->
<div class="home-permanent-menu">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'side-panel',
        'menu_class'     => 'home-menu',
        'fallback_cb'    => false,
    ) );
    ?>
</div>

<?php get_footer(); ?>