<?php
/**
 * Template Name: Gallery Paintings Page
 */

// Include gallery functions
require_once get_template_directory() . '/gallery-functions.php';

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
        <?php while ( have_posts() ) : the_post(); ?>
            
            <!-- Paintings Content Structure -->
            <div class="paintings-content">
                
                
                
                <!-- WordPress Content (if you add content in admin) -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                
                <!-- Dynamic Paintings Gallery -->
                <div class="paintings-gallery">
                    <?php
                    // Get gallery content from JSON
                    $gallery_content = get_gallery_content();
                    $paintings = $gallery_content['paintings'] ?? [];
                    
                    // Render each painting
                    for ($i = 1; $i <= 9; $i++) {
                        if (isset($paintings[$i])) {
                            echo render_painting_item($paintings[$i], $i);
                        }
                    }
                    ?>
                </div>
                
                <!-- Contact Section -->
                <!-- COMMENTED OUT: Purple contact card - uncomment if needed
                <div class="paintings-contact">
                    <h3>Interested in These Paintings?</h3>
                    <p>Each painting is a unique original work, available for viewing by appointment. Inquire about availability, pricing, or to discuss commissioning similar work.</p>
                    <a href="<?php echo home_url('/contact'); ?>" class="paintings-btn">View Collection</a>
                </div>
                -->
                
            </div>
            
        <?php endwhile; ?>
    </main>
</div>

<?php get_footer(); ?>