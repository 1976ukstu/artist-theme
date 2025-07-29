<?php
/**
 * Gallery Content Helper Functions
 * Loads content from JSON file for dynamic editing
 */

function get_gallery_content() {
    $content_file = get_template_directory() . '/gallery-content.json';
    
    if (file_exists($content_file)) {
        $content = json_decode(file_get_contents($content_file), true);
        if ($content) {
            return $content;
        }
    }
    
    // Return default content if file doesn't exist
    return [
        'paintings' => [
            1 => ['title' => 'Blue Shapes on Blue Background', 'subtitle' => 'Oil on Canvas, 150cm x 150cm', 'description' => 'An exploration of form and color relationships within a monochromatic palette, creating depth through subtle variations in tone and texture.', 'image' => 'images/23.Blue Shapes on blue background, Oil on Canvas, 150cm x 150cm.jpg'],
            2 => ['title' => 'Abstract Expression', 'subtitle' => 'Acrylic on Canvas, 120cm x 100cm', 'description' => 'A dynamic composition exploring the relationship between spontaneous mark-making and deliberate color choices.', 'image' => 'images/painting-2.jpg'],
            3 => ['title' => 'Emotional Landscape', 'subtitle' => 'Oil on Canvas, 100cm x 80cm', 'description' => 'A personal interpretation of natural forms, expressing the emotional connection between artist and environment.', 'image' => 'images/painting-3.jpg'],
            4 => ['title' => 'Urban Rhythms', 'subtitle' => 'Mixed Media on Canvas, 90cm x 70cm', 'description' => 'Capturing the energy and movement of contemporary urban life through bold gestural marks and vibrant color.', 'image' => 'images/painting-4.jpg'],
            5 => ['title' => 'Contemplative Space', 'subtitle' => 'Oil on Canvas, 110cm x 90cm', 'description' => 'A meditation on interior space and light, exploring the quiet moments of daily life through subtle color transitions.', 'image' => 'images/painting-5.jpg'],
            6 => ['title' => 'Textural Studies', 'subtitle' => 'Acrylic and Mixed Media, 85cm x 65cm', 'description' => 'An investigation into surface quality and material properties, building layers of meaning through physical texture.', 'image' => 'images/painting-6.jpg'],
            7 => ['title' => 'New Work 7', 'subtitle' => 'Medium and Dimensions TBD', 'description' => 'Description to be added.', 'image' => 'images/painting-1.jpg'],
            8 => ['title' => 'New Work 8', 'subtitle' => 'Medium and Dimensions TBD', 'description' => 'Description to be added.', 'image' => 'images/painting-2.jpg'],
            9 => ['title' => 'New Work 9', 'subtitle' => 'Medium and Dimensions TBD', 'description' => 'Description to be added.', 'image' => 'images/painting-3.jpg']
        ],
        'commissions' => [
            1 => ['title' => 'Custom Portraits', 'description' => 'Personalized portraits from photographs, capturing the essence and personality of your loved ones.', 'image' => 'images/commission-1.jpg'],
            2 => ['title' => 'Pet Portraits', 'description' => 'Beautiful artwork celebrating your beloved pets with attention to their unique characteristics.', 'image' => 'images/commission-2.jpg'],
            3 => ['title' => 'Landscape Paintings', 'description' => 'Custom landscapes of meaningful places - your home, favorite vacation spot, or dream destination.', 'image' => 'images/commission-3.jpg'],
            4 => ['title' => 'Interior Murals', 'description' => 'Large-scale murals that transform rooms into artistic environments, from children\'s rooms to businesses.', 'image' => 'images/mural-1.jpg'],
            5 => ['title' => 'Exterior Murals', 'description' => 'Weather-resistant outdoor murals for buildings, fences, and public spaces.', 'image' => 'images/mural-2.jpg'],
            6 => ['title' => 'Abstract Artwork', 'description' => 'Custom abstract pieces designed to complement your interior design and color scheme.', 'image' => 'images/commission-4.jpg'],
            7 => ['title' => 'New Commission Type 7', 'description' => 'Description to be added.', 'image' => 'images/commission-1.jpg'],
            8 => ['title' => 'New Commission Type 8', 'description' => 'Description to be added.', 'image' => 'images/commission-2.jpg'],
            9 => ['title' => 'New Commission Type 9', 'description' => 'Description to be added.', 'image' => 'images/commission-3.jpg']
        ],
        'small_works' => [
            1 => ['title' => 'Contemplation', 'description' => 'A delicate study in light and shadow, exploring the quiet moments of reflection.', 'image' => 'images/small-work-1.jpg'],
            2 => ['title' => 'Urban Rhythms', 'description' => 'Capturing the energy and movement of city life in bold, expressive strokes.', 'image' => 'images/small-work-2.jpg'],
            3 => ['title' => 'Nature\'s Whisper', 'description' => 'A gentle exploration of natural forms and organic textures in miniature.', 'image' => 'images/small-work-3.jpg'],
            4 => ['title' => 'Abstract Harmony', 'description' => 'An exploration of color relationships and compositional balance in a compact format.', 'image' => 'images/small-work-4.jpg'],
            5 => ['title' => 'Emotional Landscape', 'description' => 'A personal journey expressed through expressive mark-making and intuitive color choices.', 'image' => 'images/small-work-5.jpg'],
            6 => ['title' => 'Textural Studies', 'description' => 'An investigation into surface and material, creating depth through layered techniques.', 'image' => 'images/small-work-6.jpg'],
            7 => ['title' => 'New Small Work 7', 'description' => 'Description to be added.', 'image' => 'images/small-work-1.jpg'],
            8 => ['title' => 'New Small Work 8', 'description' => 'Description to be added.', 'image' => 'images/small-work-2.jpg'],
            9 => ['title' => 'New Small Work 9', 'description' => 'Description to be added.', 'image' => 'images/small-work-3.jpg']
        ]
    ];
}

function render_painting_item($painting_data, $index) {
    if (empty($painting_data)) return '';
    
    $title = esc_html($painting_data['title'] ?? '');
    $subtitle = esc_html($painting_data['subtitle'] ?? '');
    $description = esc_html($painting_data['description'] ?? '');
    $image = esc_attr($painting_data['image'] ?? '');
    $alt_text = esc_attr($title ?: 'Painting ' . $index);
    
    return '<div class="painting-item">
        <img src="' . get_template_directory_uri() . '/' . $image . '" alt="' . $alt_text . '">
        <div class="painting-details">
            <h3>' . $title . '</h3>
            ' . ($subtitle ? '<p>' . $subtitle . '</p>' : '') . '
            <p>' . $description . '</p>
        </div>
    </div>';
}

function render_commission_item($commission_data, $index) {
    if (empty($commission_data)) return '';
    
    $title = esc_html($commission_data['title'] ?? '');
    $description = esc_html($commission_data['description'] ?? '');
    $image = esc_attr($commission_data['image'] ?? '');
    $alt_text = esc_attr($title ?: 'Commission ' . $index);
    
    return '<div class="commission-item">
        <img src="' . get_template_directory_uri() . '/' . $image . '" alt="' . $alt_text . '">
        <div class="commission-details">
            <h3>' . $title . '</h3>
            <p>' . $description . '</p>
        </div>
    </div>';
}

function render_small_work_item($small_work_data, $index) {
    if (empty($small_work_data)) return '';
    
    $title = esc_html($small_work_data['title'] ?? '');
    $description = esc_html($small_work_data['description'] ?? '');
    $image = esc_attr($small_work_data['image'] ?? '');
    $alt_text = esc_attr($title ?: 'Small Work ' . $index);
    
    return '<div class="small-work-item">
        <img src="' . get_template_directory_uri() . '/' . $image . '" alt="' . $alt_text . '">
        <div class="small-work-details">
            <h3>' . $title . '</h3>
            <p>' . $description . '</p>
        </div>
    </div>';
}
?>
