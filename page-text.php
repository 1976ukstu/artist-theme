<?php
/**
 * Template Name: Custom Text Page
 */
get_header(); ?>

<?php if (wp_is_mobile() === false) : ?>
<!-- Desktop Only Elements -->
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
<?php endif; ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php while ( have_posts() ) : the_post(); ?>
            
            <!-- Text Page Content Structure -->
            <div class="text-page-content">
                
                
                
                <!-- WordPress Content (if you add content in admin) -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                
                <!-- 2x4 Grid Layout -->
                <div class="text-grid-container">
                    
                    <!-- Card 1: Top Left - Text Content -->
                    <div class="text-card text-content-card">
                        <div class="text-card-inner">
                            <h3>Dragica Carlin - Swirls of Contant Motion</h3>
                            <div class="text-content">
                                <p>Art has always been my language for expressing what words cannot capture. From the earliest days of experimenting with color and form, I've been drawn to the way paint moves across canvas, how textures tell stories, and how abstract forms can evoke profound emotions.</p>
                                
                                <p>My work explores the intersection between conscious intention and intuitive flow. Each piece begins with a spark of inspiration - perhaps a fleeting moment of light, an emotional experience, or simply the way colors interact in unexpected ways.</p>
                                
                                <p>The studio is my sanctuary, where time dissolves and creativity takes the lead. It's in these moments of deep focus that the most authentic expressions emerge.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 2: Top Right - Video Content -->
                    <div class="text-card video-content-card">
                        <div class="text-card-inner">
                            <h3>Process & Technique</h3>
                            <div class="video-wrapper">
                                <video controls autoplay loop muted poster="<?php echo get_template_directory_uri(); ?>/images/video-poster-1.jpg">
                                    <source src="<?php echo get_template_directory_uri(); ?>/videos/process-video.mp4" type="video/mp4">
                                    <source src="<?php echo get_template_directory_uri(); ?>/videos/process-video.webm" type="video/webm">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <p class="video-caption">Watch the creative process unfold as layers of meaning build through color and texture.</p>
                        </div>
                    </div>
                    
                    <!-- Card 3: Bottom Left - Video Content -->
                    <div class="text-card video-content-card">
                        <div class="text-card-inner">
                            <h3>Studio Insights</h3>
                            <div class="video-wrapper">
                                <video controls autoplay loop muted poster="<?php echo get_template_directory_uri(); ?>/images/video-poster-2.jpg">
                                    <source src="<?php echo get_template_directory_uri(); ?>/videos/studio-insights.mp4" type="video/mp4">
                                    <source src="<?php echo get_template_directory_uri(); ?>/videos/studio-insights.webm" type="video/webm">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <p class="video-caption">An intimate look at the tools, materials, and environment that shape each creation.</p>
                        </div>
                    </div>
                    
                    <!-- Card 4: Bottom Right - Text Content -->
                    <div class="text-card text-content-card">
                        <div class="text-card-inner">
                            <h3>Philosophy & Vision</h3>
                            <div class="text-content">
                                <p>I believe that art should challenge, comfort, and inspire in equal measure. Each piece I create is an invitation for viewers to embark on their own journey of interpretation and discovery.</p>
                                
                                <p>The beauty of abstract expression lies in its ability to speak to each person differently. What you see in my work may be entirely different from what I intended, and that's precisely the magic I'm after.</p>
                                
                                <p>My hope is that these creations serve as bridges between the visible and invisible, the known and mysterious, offering moments of contemplation in our increasingly fast-paced world.</p>
                                
                                <blockquote>"Art is not what you see, but what you make others see." - Edgar Degas</blockquote>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 5: Second Row Left - Text Content -->
                    <div class="text-card text-content-card">
                        <div class="text-card-inner">
                            <h3>Inspiration & Influences</h3>
                            <div class="text-content">
                                <p>Nature has always been my greatest teacher - the way light filters through morning mist, how water finds its path around stones, the endless variations in color that shift with each season.</p>
                                
                                <p>I draw inspiration from masters like Kandinsky and Rothko, who understood that color itself could be a language. Their courage to abandon representation in favor of pure emotion continues to guide my own explorations.</p>
                                
                                <p>Travel has also profoundly shaped my work. Each new landscape, architecture, and cultural expression adds layers to my visual vocabulary, enriching the stories I tell through paint.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card 6: Second Row Center Left - Video Content -->
                    <div class="text-card video-content-card">
                        <div class="text-card-inner">
                            <h3>Color & Emotion</h3>
                            <div class="video-wrapper">
                                <video controls autoplay loop muted poster="<?php echo get_template_directory_uri(); ?>/images/video-poster-3.jpg">
                                    <source src="<?php echo get_template_directory_uri(); ?>/videos/color-emotion.mp4" type="video/mp4">
                                    <source src="<?php echo get_template_directory_uri(); ?>/videos/color-emotion.webm" type="video/webm">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <p class="video-caption">Exploring how different color combinations evoke distinct emotional responses and memories.</p>
                        </div>
                    </div>
                    
                    <!-- Card 7: Second Row Center Right - Video Content -->
                    <div class="text-card video-content-card">
                        <div class="text-card-inner">
                            <h3>Materials & Innovation</h3>
                            <div class="video-wrapper">
                                <video controls autoplay loop muted poster="<?php echo get_template_directory_uri(); ?>/images/video-poster-4.jpg">
                                    <source src="<?php echo get_template_directory_uri(); ?>/videos/materials-innovation.mp4" type="video/mp4">
                                    <source src="<?php echo get_template_directory_uri(); ?>/videos/materials-innovation.webm" type="video/webm">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <p class="video-caption">Experimenting with unconventional materials and techniques to push creative boundaries.</p>
                        </div>
                    </div>
                    
                    <!-- Card 8: Second Row Right - Text Content -->
                    <div class="text-card text-content-card">
                        <div class="text-card-inner">
                            <h3>Future Directions</h3>
                            <div class="text-content">
                                <p>As my artistic journey continues to evolve, I'm excited to explore new territories - both literal and metaphorical. Recent experiments with mixed media have opened unexpected doors.</p>
                                
                                <p>I'm particularly drawn to the possibilities of integrating technology with traditional painting techniques, finding ways to make static art more interactive and engaging.</p>
                                
                                <p>The future holds collaborations, larger scale installations, and perhaps most excitingly, the continued discovery of what emerges when I simply trust the creative process and let intuition lead.</p>
                                
                                <blockquote>"Trust the process."</blockquote>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <!-- Call to Action Section -->
                <!-- <div class="text-page-contact"> -->
                    <!-- <h3>Connect Through Art</h3> -->
                    <!-- <p>Interested in discussing the stories behind specific pieces or commissioning work that speaks to your vision?</p> -->
                    <!-- <a href="<?php echo home_url('/contact'); ?>" class="text-page-btn">Start a Conversation</a> -->
                <!-- </div> -->
                
            </div>
            
        <?php endwhile; ?>
    </main>
</div>

<?php get_footer(); ?>