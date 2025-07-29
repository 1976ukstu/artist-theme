<?php
/**
 * Template Name: This Week Page
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
        
        <!-- This Week Page Content -->
        <div class="this-week-content">
            
            <!-- Introduction Section -->
            <!-- COMMENTED OUT: Introduction box - uncomment if needed
            <div class="this-week-intro">
                <h2>This Week...</h2>
                <p>A glimpse into the creative journey - fresh insights, works in progress, and studio moments.</p>
                <p>Follow along with weekly updates from the studio, capturing the evolving artistic process and inspiration behind each piece.</p>
            </div>
            -->
            
            <?php
            // Get the latest weekly updates
            $weekly_updates = new WP_Query(array(
                'post_type' => 'weekly_update',
                'posts_per_page' => 12,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC'
            ));
            
            if ($weekly_updates->have_posts()) : ?>
                
                <!-- Featured Latest Update -->
                <?php if ($weekly_updates->have_posts()) : 
                    $weekly_updates->the_post(); ?>
                    <div class="featured-update">
                        <div class="featured-update-content">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="featured-image">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="featured-text">
                                <div class="week-date">
                                    Week of <?php echo get_the_date('F j, Y'); ?>
                                </div>
                                <h2><?php the_title(); ?></h2>
                                <div class="featured-excerpt">
                                    <?php 
                                    if (has_excerpt()) {
                                        the_excerpt();
                                    } else {
                                        echo wp_trim_words(get_the_content(), 30, '...');
                                    }
                                    ?>
                                </div>
                                <a href="#" class="read-more-btn" onclick="toggleFullContent(this)" data-post-id="<?php echo get_the_ID(); ?>">
                                    Read Full Update
                                </a>
                                <div class="full-content" id="content-<?php echo get_the_ID(); ?>" style="display: none;">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Weekly Updates Grid -->
                <div class="weekly-updates-grid">
                    <h3>Previous Weeks</h3>
                    
                    <div class="updates-grid">
                        <?php while ($weekly_updates->have_posts()) : 
                            $weekly_updates->the_post(); ?>
                            
                            <div class="update-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="update-image">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="update-content">
                                    <div class="update-date">
                                        Week of <?php echo get_the_date('M j'); ?>
                                    </div>
                                    <h4><?php the_title(); ?></h4>
                                    <div class="update-excerpt">
                                        <?php 
                                        if (has_excerpt()) {
                                            echo wp_trim_words(get_the_excerpt(), 15, '...');
                                        } else {
                                            echo wp_trim_words(get_the_content(), 15, '...');
                                        }
                                        ?>
                                    </div>
                                    <button class="expand-btn" onclick="toggleUpdateContent(this)" data-post-id="<?php echo get_the_ID(); ?>">
                                        Read More
                                    </button>
                                    <div class="full-update-content" id="update-content-<?php echo get_the_ID(); ?>" style="display: none;">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                            </div>
                            
                        <?php endwhile; ?>
                    </div>
                    
                </div>
                
            <?php else : ?>
                
                <!-- No Updates Yet -->
                <div class="no-updates">
                    <div class="no-updates-content">
                        <h2>Coming Soon...</h2>
                        <p>The first weekly update will appear here soon. Stay tuned for insights into the creative process, works in progress, and studio moments.</p>
                        <div class="placeholder-cards">
                            <div class="placeholder-card">
                                <div class="placeholder-image"></div>
                                <div class="placeholder-text">
                                    <div class="placeholder-line"></div>
                                    <div class="placeholder-line short"></div>
                                </div>
                            </div>
                            <div class="placeholder-card">
                                <div class="placeholder-image"></div>
                                <div class="placeholder-text">
                                    <div class="placeholder-line"></div>
                                    <div class="placeholder-line short"></div>
                                </div>
                            </div>
                            <div class="placeholder-card">
                                <div class="placeholder-image"></div>
                                <div class="placeholder-text">
                                    <div class="placeholder-line"></div>
                                    <div class="placeholder-line short"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            <?php endif; 
            wp_reset_postdata(); ?>
            
            <!-- COMMENTED OUT: Mobile Publishing Info - Purple instruction card 
            <div class="mobile-info">
                <h3>Share Your Week</h3>
                <p>Updates can be posted directly from your mobile device using the WordPress app. Simply take a photo in the studio and share your creative journey with a few words.</p>
                <div class="mobile-steps">
                    <div class="step">
                        <span class="step-number">1</span>
                        <span class="step-text">Take a photo in your studio</span>
                    </div>
                    <div class="step">
                        <span class="step-number">2</span>
                        <span class="step-text">Open WordPress mobile app</span>
                    </div>
                    <div class="step">
                        <span class="step-number">3</span>
                        <span class="step-text">Create new "Weekly Update"</span>
                    </div>
                    <div class="step">
                        <span class="step-number">4</span>
                        <span class="step-text">Add image and share your thoughts</span>
                    </div>
                </div>
            </div>
            -->
            
        </div>
        
    </main>
</div>

<script>
function toggleFullContent(button) {
    const postId = button.getAttribute('data-post-id');
    const fullContent = document.getElementById('content-' + postId);
    
    if (fullContent.style.display === 'none') {
        fullContent.style.display = 'block';
        button.textContent = 'Show Less';
    } else {
        fullContent.style.display = 'none';
        button.textContent = 'Read Full Update';
    }
}

function toggleUpdateContent(button) {
    const postId = button.getAttribute('data-post-id');
    const fullContent = document.getElementById('update-content-' + postId);
    
    if (fullContent.style.display === 'none') {
        fullContent.style.display = 'block';
        button.textContent = 'Show Less';
    } else {
        fullContent.style.display = 'none';
        button.textContent = 'Read More';
    }
}
</script>

<?php get_footer(); ?>