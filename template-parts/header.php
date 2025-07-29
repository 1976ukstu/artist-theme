<header id="masthead" class="site-header">
    <div class="site-branding">
        <?php
        if ( function_exists( 'the_custom_logo' ) ) {
            the_custom_logo();
        }
        ?>
        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
        <p class="site-description"><?php bloginfo( 'description' ); ?></p>
    </div><!-- .site-branding -->

    <nav id="site-navigation" class="main-navigation">
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'artist-theme' ); ?></button>
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'menu-1',
                'menu_id'        => 'primary-menu',
                'fallback_cb'    => false,
            )
        );
        ?>
    </nav><!-- #site-navigation -->
</header><!-- #masthead -->