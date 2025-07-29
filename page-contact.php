<?php
/**
 * Template Name: Custom Contact Page
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
        <?php while ( have_posts() ) : the_post(); ?>
            
            <!-- Contact Content Structure -->
            <div class="contact-content">
                
                <!-- Introduction Section -->
                <div class="contact-intro">
                    <h2>Get In Touch</h2>
                    <p>Ready to discuss your next project or inquire about existing artwork? I'd love to hear from you.</p>
                </div>
                
                <!-- Contact Layout: Form + Video -->
                <div class="contact-layout">
                    
                    <!-- Contact Form Section -->
                    <div class="contact-form-section">
                        <h3>Send a Message</h3>
                        
                        <!-- WordPress Content (for contact forms from admin) -->
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Fallback Contact Info if no form plugin -->
                        <div class="contact-info">
                            <div class="contact-item">
                                <h4>Email</h4>
                                <p><a href="mailto:dragica@example.com">dragica@example.com</a></p>
                            </div>
                            
                            <div class="contact-item">
                                <h4>Phone</h4>
                                <p><a href="tel:+1234567890">+1 (234) 567-890</a></p>
                            </div>
                            
                            <div class="contact-item">
                                <h4>Studio Location</h4>
                                <p>Available by appointment<br>
                                City, State</p>
                            </div>
                            
                            <div class="contact-item">
                                <h4>Response Time</h4>
                                <p>I typically respond within 24 hours</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Video Section -->
                    <!-- COMMENTED OUT: Background video - uncomment when video is decided
                    <div class="contact-video-section">
                        <h3>Meet the Artist</h3>
                        <div class="video-container">
                            <!-- Replace 'your-video.mp4' with your actual video file -->
                            <video controls poster="<?php echo get_template_directory_uri(); ?>/images/video-poster.jpg">
                                <source src="<?php echo get_template_directory_uri(); ?>/videos/artist-intro.mp4" type="video/mp4">
                                <source src="<?php echo get_template_directory_uri(); ?>/videos/artist-intro.webm" type="video/webm">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <p class="video-description">
                            Watch me discuss my artistic process, inspiration, and the stories behind my work.
                        </p>
                    </div>
                    -->
                    
                </div>
                
                <!-- Social Media / Additional Contact -->
                <div class="contact-additional">
                    <h3>Connect With Me</h3>
                    <div class="social-links">
                        <a href="#" class="social-link">Instagram</a>
                        <a href="#" class="social-link">Facebook</a>
                        <a href="#" class="social-link">LinkedIn</a>
                    </div>
                </div>
                
            </div>
            
        <?php endwhile; ?>
    </main>
</div>

<?php get_footer(); ?>