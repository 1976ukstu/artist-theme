<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#404-not-found
 *
 * @package Artist_Theme
 */

get_header(); ?>

<main id="main" class="site-main">
	<section class="error-404 not-found">
		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'Oops! That page can’t be found.', 'artist-theme' ); ?></h1>
		</header><!-- .page-header -->

		<div class="page-content">
			<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'artist-theme' ); ?></p>

			<?php get_search_form(); ?>
		</div><!-- .page-content -->
	</section><!-- .error-404 -->
</main><!-- #main -->

<?php
get_footer();