<?php
/**
 * Template Name: Custom Commissions and Murals Page
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
            
            <!-- Commission Content Structure -->
            <div class="commissions-content">
                
                <!-- Introduction Section -->
                <!-- COMMENTED OUT: Introduction box - uncomment if needed
                <div class="commission-intro">
                    <h2>Custom Commissions & Murals</h2>
                    <p>Transform your space with original artwork created specifically for you. I work closely with clients to bring their vision to life through custom paintings, portraits, and large-scale murals.</p>
                    <p>Each piece is carefully crafted using professional materials and techniques, ensuring your artwork will be treasured for years to come.</p>
                </div>
                -->
                
                <!-- WordPress Content (if you add content in admin) -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                
                <!-- Dynamic Commission Gallery -->
                <div class="commission-gallery">
                    <?php
                    // Get gallery content from JSON
                    $gallery_content = get_gallery_content();
                    $commissions = $gallery_content['commissions'] ?? [];
                    
                    // Render each commission
                    for ($i = 1; $i <= 9; $i++) {
                        if (isset($commissions[$i])) {
                            echo render_commission_item($commissions[$i], $i);
                        }
                    }
                    ?>
                </div>
                
                <!-- Contact Section -->
                <!-- COMMENTED OUT: Purple contact box - uncomment if needed
                <div class="commission-contact">
                    <h3>Ready to Commission Your Artwork?</h3>
                    <p>Let's discuss your vision and bring it to life. Every commission begins with a conversation about your ideas, space, and preferences.</p>
                    <a href="<?php echo home_url('/contact'); ?>" class="commission-btn">Start Your Commission</a>
                </div>
                -->
                
            </div>
            
        <?php endwhile; ?>
    </main>
</div>

<?php get_footer(); ?>