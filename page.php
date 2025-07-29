<?php
/**
 * The template for displaying individual pages
 *
 * This is the template that displays all pages by default.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Artist_Theme
 */

get_header(); ?>

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

			get_template_part( 'template-parts/content' );

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('JavaScript is loading!'); // Debug message
    
    // Hamburger menu functionality
    const hamburger = document.querySelector('.hamburger');
    const sidePanel = document.querySelector('.side-panel');
    
    if (hamburger && sidePanel) {
        console.log('Hamburger and side panel found!'); // Debug message
        
        // Toggle side panel when hamburger is clicked
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            sidePanel.classList.toggle('open');
            console.log('Hamburger clicked, panel toggled'); // Debug message
        });
        
        // Prevent closing when clicking inside the side panel
        sidePanel.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Close side panel when clicking outside of it
        document.addEventListener('click', function(e) {
            if (sidePanel.classList.contains('open')) {
                if (!sidePanel.contains(e.target) && !hamburger.contains(e.target)) {
                    sidePanel.classList.remove('open');
                    console.log('Panel closed by outside click'); // Debug message
                }
            }
        });
        
        // Close side panel when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidePanel.classList.contains('open')) {
                sidePanel.classList.remove('open');
                console.log('Panel closed by Escape key'); // Debug message
            }
        });
    } else {
        console.log('Hamburger or side panel not found!'); // Debug message
    }
});
</script>

<?php
get_sidebar();
get_footer();
?>