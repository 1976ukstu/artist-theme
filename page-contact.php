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
                        <!-- Centered Red Cog Icon -->
                        <div style="width:100%;text-align:center;margin:32px 0 0 0;">
                            <span class="red-cog-icon" style="display:inline-block;width:48px;height:48px;">
                                <svg viewBox="0 0 48 48" width="48" height="48" fill="#c93333" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="24" cy="24" r="10" stroke="#c93333" stroke-width="4" fill="none"/>
                                    <g stroke="#c93333" stroke-width="4">
                                        <line x1="24" y1="2" x2="24" y2="10"/>
                                        <line x1="24" y1="38" x2="24" y2="46"/>
                                        <line x1="2" y1="24" x2="10" y2="24"/>
                                        <line x1="38" y1="24" x2="46" y2="24"/>
                                        <line x1="8" y1="8" x2="14" y2="14"/>
                                        <line x1="34" y1="34" x2="40" y2="40"/>
                                        <line x1="8" y1="40" x2="14" y2="34"/>
                                        <line x1="34" y1="14" x2="40" y2="8"/>
                                    </g>
                                </svg>
                            </span>
                        </div>
                    </div>
                    

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