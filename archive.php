<?php
/**
 * The template for displaying archive pages
 *
 * This is the template that displays all archive pages, such as category or date archives.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Artist_Theme
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :

			// Start the Loop.
			while ( have_posts() ) :
				the_post();

				// Include the Post-Format-specific template for the content.
				get_template_part( 'template-parts/content' );

			endwhile;

			// Pagination
		 the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
?>