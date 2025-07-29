<?php
/**
 * Template part for displaying post content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Artist_Theme
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php
        if ( is_singular() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        endif;
        ?>
    </header><!-- .entry-header -->

    <div class="entry-content">
        <?php
        the_content();
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php
        // Display post meta information (e.g., categories, tags, etc.)
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->