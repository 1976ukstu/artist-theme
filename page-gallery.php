<?php
/**
 * Template Name: Gallery Page
 *
 * This is the template for the custom gallery page.
 *
 * @package dragica-carlin // IMPORTANT: Replace 'your_theme_name' with your actual theme's slug (folder name, e.g., 'my-custom-theme')
 */

get_header(); // This will include your theme's header.php
?>

<div class="site-title">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color: inherit; text-decoration: none;">
        Dragica<br>Carlin
    </a>
</div>

<button class="hamburger" aria-label="Open menu">
    <span></span>
    <span></span>
    <span></span>
</button>

<div class="side-panel">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'side-panel',
        'menu_class'     => 'side-menu',
        'fallback_cb'    => false,
    ) );
    ?>
</div>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><div class="entry-content">
                    <?php
                    the_content();
                    ?>
                    <div class="image-grid">
                        <div class="grid-item">
                            <img src="https://dragicacarlin.1976uk.com/wp-content/uploads/2025/07/DC-SS-01.png" alt="DC-SS-01">
                            <div class="overlay">
                                <div class="overlay-text">DC-SS-01</div>
                            </div>
                        </div>
                        <div class="grid-item">
                            <img src="https://dragicacarlin.1976uk.com/wp-content/uploads/2025/07/DC-SS-02.png" alt="Artwork 2">
                            <div class="overlay">
                                <div class="overlay-text">Painting Title 2</div>
                            </div>
                        </div>
                        <div class="grid-item">
                            <img src="https://dragicacarlin.1976uk.com/wp-content/uploads/2025/07/DC-SS-03.png" alt="Artwork 3">
                            <div class="overlay">
                                <div class="overlay-text">Painting Title 3</div>
                            </div>
                        </div>
                        <div class="grid-item">
                            <img src="https://dragicacarlin.1976uk.com/wp-content/uploads/2025/07/DC-SS-04.png" alt="Artwork 4">
                            <div class="overlay">
                                <div class="overlay-text">Painting Title 4</div>
                            </div>
                        </div>
                        <div class="grid-item">
                            <img src="https://dragicacarlin.1976uk.com/wp-content/uploads/2025/07/DC-SS-05.png" alt="Artwork 5">
                            <div class="overlay">
                                <div class="overlay-text">Painting Title 5</div>
                            </div>
                        </div>
                        <div class="grid-item">
                            <img src="https://dragicacarlin.1976uk.com/wp-content/uploads/2025/07/DC-SS-06.png" alt="Artwork 6">
                            <div class="overlay">
                                <div class="overlay-text">Painting Title 6</div>
                            </div>
                        </div>
                        <div class="grid-item">
                            <img src="https://dragicacarlin.1976uk.com/wp-content/uploads/2025/07/DC-SS-07.png" alt="Artwork 7">
                            <div class="overlay">
                                <div class="overlay-text">Painting Title 7</div>
                            </div>
                        </div>
                        <div class="grid-item">
                            <img src="https://dragicacarlin.1976uk.com/wp-content/uploads/2025/07/DC-SS-08.png" alt="Artwork 8">
                            <div class="overlay">
                                <div class="overlay-text">Painting Title 8</div>
                            </div>
                        </div>
                        <div class="grid-item">
                            <img src="https://dragicacarlin.1976uk.com/wp-content/uploads/2025/07/DC-SS-09.png" alt="Artwork 9">
                            <div class="overlay">
                                <div class="overlay-text">Painting Title 9</div>
                            </div>
                        </div>
                    </div><?php
                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', '_s' ),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div><?php if ( get_edit_post_link() ) : ?>
                    <footer class="entry-footer">
                        <?php
                        edit_post_link(
                            sprintf(
                                wp_kses(
                                    /* translators: %s: Post title. */
                                    __( 'Edit <span class="screen-reader-text">%s</span>', '_s' ),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                wp_kses_post( get_the_title() )
                            ),
                            '<span class="edit-link">',
                            '</span>'
                        );
                        ?>
                    </footer><?php endif; ?>
            </article><?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>

    </main></div><?php
get_sidebar();
get_footer();
