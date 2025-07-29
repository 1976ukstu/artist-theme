<?php
/**
 * Template Name: Custom Small Works Page
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
            
            <!-- Small Works Content Structure -->
            <div class="small-works-content">
                
                <!-- Introduction Section -->
                <!-- COMMENTED OUT: Introduction box - uncomment if needed
                <div class="small-works-intro">
                    <h2>Small Works Collection</h2>
                    <p>Intimate pieces that capture moments of inspiration and experimentation. These smaller works offer an accessible way to own original artwork while exploring different themes and techniques.</p>
                    <p>Each piece tells its own story, perfect for personal spaces or as thoughtful gifts for art lovers.</p>
                </div>
                -->
                
                <!-- WordPress Content (if you add content in admin) -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                
                <!-- Dynamic Small Works Gallery -->
                <div class="small-works-gallery">
                    <?php
                    // Get gallery content from JSON
                    $gallery_content = get_gallery_content();
                    $small_works = $gallery_content['small_works'] ?? [];
                    
                    // Render each small work
                    for ($i = 1; $i <= 9; $i++) {
                        if (isset($small_works[$i])) {
                            echo render_small_work_item($small_works[$i], $i);
                        }
                    }
                    ?>
                </div>
                
                <!-- Contact Section -->
                <!-- COMMENTED OUT: Purple contact box - uncomment if needed
                <div class="small-works-contact">
                    <h3>Interested in Small Works?</h3>
                    <p>These intimate pieces are perfect for collectors and art enthusiasts. Each work is unique and available for viewing by appointment.</p>
                    <a href="<?php echo home_url('/contact'); ?>" class="small-works-btn">Inquire About Availability</a>
                </div>
                -->
                
            </div>
            
        <?php endwhile; ?>
    </main>
</div>

<?php get_footer(); ?>