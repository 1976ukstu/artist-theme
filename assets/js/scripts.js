// Debug logging function
function debugLog(message) {
    console.log('[Artist Theme Debug]:', message);
}

debugLog('Scripts.js loaded successfully');

document.addEventListener('DOMContentLoaded', function() {
    debugLog('DOM Content Loaded');
    
    // ========================================
    // HAMBURGER MENU FUNCTIONALITY
    // ========================================
    
    function initHamburgerMenu() {
        debugLog('Initializing hamburger menu');
        
        const hamburger = document.querySelector('.hamburger');
        const sidePanel = document.querySelector('.side-panel');
        
        if (!hamburger || !sidePanel) {
            debugLog('Hamburger or side panel not found');
            return;
        }
        
        debugLog('Hamburger menu elements found');
        
        // Toggle side panel when hamburger is clicked
        hamburger.addEventListener('click', function(e) {
            debugLog('Hamburger clicked');
            e.preventDefault();
            e.stopPropagation();
            sidePanel.classList.toggle('open');
            debugLog('Side panel toggled, open:', sidePanel.classList.contains('open'));
        });
        
        // Prevent closing when clicking inside the side panel
        sidePanel.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Close side panel when clicking outside of it
        document.addEventListener('click', function(e) {
            if (sidePanel.classList.contains('open')) {
                // Check if click is outside both the panel and hamburger
                if (!sidePanel.contains(e.target) && !hamburger.contains(e.target)) {
                    debugLog('Clicking outside side panel, closing');
                    sidePanel.classList.remove('open');
                }
            }
        });
        
        // Close side panel when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidePanel.classList.contains('open')) {
                debugLog('Escape key pressed, closing side panel');
                sidePanel.classList.remove('open');
            }
        });
        
        debugLog('Hamburger menu initialized successfully');
    }

    
    // ========================================
    // LIGHTBOX GALLERY FUNCTIONALITY
    // ========================================
    
    function initLightbox() {
        debugLog('Initializing lightbox');
        
        let currentImageIndex = 0;
        let galleryImages = [];
        
        // Create lightbox HTML structure
        function createLightbox() {
            debugLog('Creating lightbox HTML');
            const lightboxHTML = `
                <div class="lightbox-overlay" id="lightbox">
                    <div class="lightbox-content">
                        <button class="lightbox-close" aria-label="Close gallery"></button>
                        <button class="lightbox-nav lightbox-prev" aria-label="Previous image"></button>
                        <button class="lightbox-nav lightbox-next" aria-label="Next image"></button>
                        <img class="lightbox-image" src="" alt="" id="lightbox-img">
                        <div class="lightbox-info">
                            <h3 class="lightbox-title" id="lightbox-title"></h3>
                            <p class="lightbox-subtitle" id="lightbox-subtitle"></p>
                            <p class="lightbox-description" id="lightbox-description"></p>
                        </div>
                        <div class="lightbox-counter" id="lightbox-counter"></div>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', lightboxHTML);
            debugLog('Lightbox HTML created');
        }
        
        // Initialize lightbox
        createLightbox();
        
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        const lightboxTitle = document.getElementById('lightbox-title');
        const lightboxSubtitle = document.getElementById('lightbox-subtitle');
        const lightboxDescription = document.getElementById('lightbox-description');
        const lightboxCounter = document.getElementById('lightbox-counter');
        const closeBtn = document.querySelector('.lightbox-close');
        const prevBtn = document.querySelector('.lightbox-prev');
        const nextBtn = document.querySelector('.lightbox-next');
        
        if (!lightbox) {
            debugLog('Lightbox creation failed');
            return;
        }
        
        // Find all gallery images
        const imageSelectors = [
            '.gallery-item img',
            '.grid-item img', 
            '.painting-item img',
            '.commission-item img',
            '.small-work-item img',
            '.update-image img',
            '.featured-image img'
        ];
        
        const allImages = document.querySelectorAll(imageSelectors.join(', '));
        debugLog('Found ' + allImages.length + ' images for lightbox');
        
        // Convert to array with metadata
        galleryImages = Array.from(allImages).map((img, index) => {
            const parent = img.closest('.gallery-item, .grid-item, .painting-item, .commission-item, .small-work-item, .update-card, .featured-update');
            let title = '';
            let subtitle = '';
            let description = '';
            
            if (parent) {
                // Extract title from various possible elements
                const titleEl = parent.querySelector('h3, h4, .painting-details h3, .commission-details h3, .small-work-details h3, .update-content h4, .featured-text h2');
                if (titleEl) title = titleEl.textContent.trim();
                
                // Extract subtitle (medium/dimensions for paintings)
                const subtitleEl = parent.querySelector('.painting-details p:first-of-type, .commission-details p:first-of-type');
                if (subtitleEl) subtitle = subtitleEl.textContent.trim();
                
                // Extract description
                const descEl = parent.querySelector('.painting-details p:last-of-type, .commission-details p:not(:first-of-type), .small-work-details p, .update-excerpt, .featured-excerpt');
                if (descEl) description = descEl.textContent.trim();
            }
            
            return {
                src: img.src,
                alt: img.alt || title || 'Artwork ' + (index + 1),
                title: title || 'Untitled',
                subtitle: subtitle,
                description: description
            };
        });
        
        debugLog('Gallery images prepared:', galleryImages.length);
        
        
        // Add click handlers to images
        allImages.forEach((img, index) => {
            img.style.cursor = 'pointer'; // Make it clear images are clickable
            img.addEventListener('click', function(e) {
                e.preventDefault();
                debugLog('Image clicked, opening lightbox at index:', index);
                openLightbox(index);
            });
        });
        
        // Open lightbox
        function openLightbox(index) {
            debugLog('Opening lightbox at index:', index);
            currentImageIndex = index;
            updateLightboxContent();
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
            
            // Update navigation visibility
            if (galleryImages.length <= 1) {
                lightbox.classList.add('single-image');
            } else {
                lightbox.classList.remove('single-image');
            }
        }
        
        // Close lightbox
        function closeLightbox() {
            debugLog('Closing lightbox');
            lightbox.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }
        
        // Update lightbox content
        function updateLightboxContent() {
            const image = galleryImages[currentImageIndex];
            if (!image) {
                debugLog('No image found at index:', currentImageIndex);
                return;
            }
            
            debugLog('Updating lightbox content:', image.title);
            
            lightboxImg.src = image.src;
            lightboxImg.alt = image.alt;
            lightboxTitle.textContent = image.title;
            lightboxSubtitle.textContent = image.subtitle;
            lightboxDescription.textContent = image.description;
            
            // Update counter
            if (galleryImages.length > 1) {
                lightboxCounter.textContent = `${currentImageIndex + 1} / ${galleryImages.length}`;
                lightboxCounter.style.display = 'block';
            } else {
                lightboxCounter.style.display = 'none';
            }
            
            // Hide subtitle if empty
            lightboxSubtitle.style.display = image.subtitle ? 'block' : 'none';
        }
        
        // Navigation functions
        function showNext() {
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            updateLightboxContent();
        }
        
        function showPrev() {
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            updateLightboxContent();
        }
        
        // Event listeners
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeLightbox();
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showNext();
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showPrev();
            });
        }
        
        // Close on overlay click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;
            
            switch(e.key) {
                case 'Escape':
                    closeLightbox();
                    break;
                case 'ArrowLeft':
                    if (galleryImages.length > 1) showPrev();
                    break;
                case 'ArrowRight':
                    if (galleryImages.length > 1) showNext();
                    break;
            }
        });
        
        debugLog('Lightbox initialized successfully');
    }
    
    // Initialize all functionality
    initHamburgerMenu();
    initLightbox();
    
    debugLog('All scripts initialized');
});