<?php
/**
 * Template Name: Contact Page with Video Background
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
    <main id="main" class="site-main video-bg-content">
        <?php while ( have_posts() ) : the_post(); ?>
            
            <!-- Contact Content with Video Background -->
            <div class="contact-video-bg-content">
                
                <!-- Main Contact Card -->
                <div class="contact-main-card">
                    <div class="contact-card-header">
                        <h2>Contact me for more information</h2>
                        <p>Ready to bring your artistic vision to life? I'd love to hear about your project.</p>
                    </div>
                    
                    <!-- Contact Form Section -->
                    <div class="contact-form-video-bg">
                        <!-- WordPress Content (for contact forms from admin) -->
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Fallback Contact Form -->
                        <div class="contact-form-fallback">
                            <form class="artist-contact-form" action="#" method="post">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name">Name *</label>
                                        <input type="text" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email *</label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" id="subject" name="subject">
                                </div>
                                
                                <div class="form-group">
                                    <label for="project-type">Project Type</label>
                                    <select id="project-type" name="project-type">
                                        <option value="">Select a project type</option>
                                        <option value="commission">Custom Commission</option>
                                        <option value="mural">Mural Project</option>
                                        <option value="purchase">Purchase Existing Work</option>
                                        <option value="collaboration">Collaboration</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message">Message *</label>
                                    <textarea id="message" name="message" rows="6" required placeholder="Tell me about your vision, timeline, and any specific requirements..."></textarea>
                                </div>
                                
                                <button type="submit" class="contact-submit-btn">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                
            </div>
            
        <?php endwhile; ?>
    </main>
</div>

<?php get_footer(); ?>
