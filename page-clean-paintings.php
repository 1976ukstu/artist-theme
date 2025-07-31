<?php
/* 
Template Name: Clean Paintings Gallery
Description: Clean paintings gallery that uses existing navigation and lightbox
*/

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main clean-gallery-main">
        <?php while ( have_posts() ) : the_post(); ?>
            
            <!-- Clean Gallery Container -->
            <div class="clean-paintings-gallery">
                
                <!-- Gallery Grid -->
                <div class="clean-gallery-grid" id="paintings-grid">
                    <!-- Paintings will load here from JSON -->
                </div>
                
            </div>
            
        <?php endwhile; ?>
    </main>
</div>

<!-- Lightbox HTML Structure -->
<div class="lightbox-overlay" id="lightboxOverlay">
    <div class="lightbox-content">
        <button class="lightbox-close" id="lightboxClose"></button>
        <button class="lightbox-nav lightbox-prev" id="lightboxPrev"></button>
        <button class="lightbox-nav lightbox-next" id="lightboxNext"></button>
        <img class="lightbox-image" id="lightboxImage" src="" alt="">
        <div class="lightbox-info">
            <h3 class="lightbox-title" id="lightboxTitle"></h3>
            <p class="lightbox-subtitle" id="lightboxSubtitle"></p>
            <p class="lightbox-description" id="lightboxDescription"></p>
        </div>
        <div class="lightbox-counter" id="lightboxCounter"></div>
    </div>
</div>

<script>
// Gallery and lightbox functionality
document.addEventListener('DOMContentLoaded', function() {
    loadPaintingsGallery();
});

let galleryImages = [];
let currentImageIndex = 0;

function loadPaintingsGallery() {
    fetch('<?php echo get_template_directory_uri(); ?>/gallery-content.json')
        .then(response => response.json())
        .then(data => {
            const grid = document.getElementById('paintings-grid');
            const paintings = data.paintings;
            
            grid.innerHTML = '';
            galleryImages = []; // Reset gallery images array
            
            Object.keys(paintings).forEach((key, index) => {
                const painting = paintings[key];
                const card = createPaintingCard(painting, index);
                grid.appendChild(card);
                
                // Add to gallery images array for lightbox
                galleryImages.push({
                    src: '<?php echo get_template_directory_uri(); ?>/' + painting.image,
                    title: painting.title,
                    subtitle: painting.subtitle,
                    description: painting.description
                });
            });
            
            // Initialize lightbox
            initializeLightbox();
            
            console.log('Loaded ' + Object.keys(paintings).length + ' paintings from JSON');
        })
        .catch(error => {
            console.error('Error loading gallery data:', error);
            document.getElementById('paintings-grid').innerHTML = '<p style="text-align: center; color: #666; font-size: 1.1rem; padding: 40px;">Gallery content is being updated. Please check back soon.</p>';
        });
}

function createPaintingCard(painting, index) {
    const card = document.createElement('div');
    card.className = 'clean-painting-item';
    card.innerHTML = `
        <div class="clean-painting-image">
            <img src="<?php echo get_template_directory_uri(); ?>/${painting.image}" 
                 alt="${painting.title}" 
                 loading="lazy"
                 data-lightbox-index="${index}"
                 style="cursor: pointer;"
                 onerror="this.src='<?php echo get_template_directory_uri(); ?>/images/placeholder.jpg'">
        </div>
        <div class="clean-painting-details">
            <h3>${painting.title}</h3>
            <p class="painting-subtitle">${painting.subtitle}</p>
            <p class="painting-description">${painting.description}</p>
        </div>
    `;
    return card;
}

// Lightbox functionality
function initializeLightbox() {
    const lightboxOverlay = document.getElementById('lightboxOverlay');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxSubtitle = document.getElementById('lightboxSubtitle');
    const lightboxDescription = document.getElementById('lightboxDescription');
    const lightboxCounter = document.getElementById('lightboxCounter');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');
    
    // Add click listeners to all painting images
    document.addEventListener('click', function(e) {
        if (e.target.hasAttribute('data-lightbox-index')) {
            currentImageIndex = parseInt(e.target.getAttribute('data-lightbox-index'));
            openLightbox();
        }
    });
    
    // Close lightbox
    lightboxClose.addEventListener('click', closeLightbox);
    lightboxOverlay.addEventListener('click', function(e) {
        if (e.target === lightboxOverlay) {
            closeLightbox();
        }
    });
    
    // Navigation
    lightboxPrev.addEventListener('click', showPreviousImage);
    lightboxNext.addEventListener('click', showNextImage);
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (lightboxOverlay.classList.contains('active')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') showPreviousImage();
            if (e.key === 'ArrowRight') showNextImage();
        }
    });
    
    function openLightbox() {
        if (galleryImages.length === 0) return;
        
        const image = galleryImages[currentImageIndex];
        lightboxImage.src = image.src;
        lightboxTitle.textContent = image.title;
        lightboxSubtitle.textContent = image.subtitle;
        lightboxDescription.textContent = image.description;
        lightboxCounter.textContent = `${currentImageIndex + 1} / ${galleryImages.length}`;
        
        lightboxOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Hide navigation if only one image
        if (galleryImages.length === 1) {
            lightboxOverlay.classList.add('single-image');
        } else {
            lightboxOverlay.classList.remove('single-image');
        }
    }
    
    function closeLightbox() {
        lightboxOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    function showPreviousImage() {
        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
        openLightbox();
    }
    
    function showNextImage() {
        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
        openLightbox();
    }
}
</script>

<?php get_footer(); ?>