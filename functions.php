<?php
/**
 * Functions and definitions for the Artist Theme
 *
 * This file is responsible for setting up the theme, including registering menus,
 * sidebars, and other theme features.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Theme setup function
function artist_theme_setup() {
    // Add support for automatic feed links
    add_theme_support( 'automatic-feed-links' );

    // Add support for title tag
    add_theme_support( 'title-tag' );

    // Add support for post thumbnails
    add_theme_support( 'post-thumbnails' );

    // Add theme support for menus
    add_theme_support( 'menus' );
    
    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'artist-theme' ),
        'side-panel' => __( 'Side Panel Menu', 'artist-theme' ),
    ) );
    
    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
}
add_action( 'after_setup_theme', 'artist_theme_setup' );

// Register Custom Post Type for Weekly Updates
function create_weekly_updates_post_type() {
    $labels = array(
        'name'                  => _x( 'Weekly Updates', 'Post Type General Name', 'artist-theme' ),
        'singular_name'         => _x( 'Weekly Update', 'Post Type Singular Name', 'artist-theme' ),
        'menu_name'             => __( 'This Week...', 'artist-theme' ),
        'name_admin_bar'        => __( 'Weekly Update', 'artist-theme' ),
        'archives'              => __( 'Weekly Archives', 'artist-theme' ),
        'attributes'            => __( 'Weekly Attributes', 'artist-theme' ),
        'parent_item_colon'     => __( 'Parent Weekly Update:', 'artist-theme' ),
        'all_items'             => __( 'All Weekly Updates', 'artist-theme' ),
        'add_new_item'          => __( 'Add New Weekly Update', 'artist-theme' ),
        'add_new'               => __( 'Add New', 'artist-theme' ),
        'new_item'              => __( 'New Weekly Update', 'artist-theme' ),
        'edit_item'             => __( 'Edit Weekly Update', 'artist-theme' ),
        'update_item'           => __( 'Update Weekly Update', 'artist-theme' ),
        'view_item'             => __( 'View Weekly Update', 'artist-theme' ),
        'view_items'            => __( 'View Weekly Updates', 'artist-theme' ),
        'search_items'          => __( 'Search Weekly Updates', 'artist-theme' ),
        'not_found'             => __( 'Not found', 'artist-theme' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'artist-theme' ),
        'featured_image'        => __( 'Weekly Image', 'artist-theme' ),
        'set_featured_image'    => __( 'Set weekly image', 'artist-theme' ),
        'remove_featured_image' => __( 'Remove weekly image', 'artist-theme' ),
        'use_featured_image'    => __( 'Use as weekly image', 'artist-theme' ),
        'insert_into_item'      => __( 'Insert into weekly update', 'artist-theme' ),
        'uploaded_to_this_item' => __( 'Uploaded to this weekly update', 'artist-theme' ),
        'items_list'            => __( 'Weekly updates list', 'artist-theme' ),
        'items_list_navigation' => __( 'Weekly updates list navigation', 'artist-theme' ),
        'filter_items_list'     => __( 'Filter weekly updates list', 'artist-theme' ),
    );
    
    $args = array(
        'label'                 => __( 'Weekly Update', 'artist-theme' ),
        'description'           => __( 'Weekly artistic updates and insights', 'artist-theme' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-calendar-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Enables Gutenberg editor and mobile app support
        'rest_base'             => 'weekly-updates', // Custom REST API endpoint
        'rest_controller_class' => 'WP_REST_Posts_Controller', // Use standard posts controller
    );
    
    register_post_type( 'weekly_update', $args );
}
add_action( 'init', 'create_weekly_updates_post_type', 0 );

// Ensure Weekly Updates appear in mobile app
function ensure_weekly_updates_in_rest_api() {
    // Force refresh of rewrite rules to ensure REST endpoints work
    if ( get_option( 'weekly_updates_rest_refresh' ) !== 'done' ) {
        flush_rewrite_rules();
        update_option( 'weekly_updates_rest_refresh', 'done' );
    }
}
add_action( 'init', 'ensure_weekly_updates_in_rest_api', 1 );

// Add custom capabilities for Weekly Updates
function add_weekly_updates_capabilities() {
    $role = get_role( 'administrator' );
    if ( $role ) {
        $role->add_cap( 'edit_weekly_updates' );
        $role->add_cap( 'edit_others_weekly_updates' );
        $role->add_cap( 'publish_weekly_updates' );
        $role->add_cap( 'read_private_weekly_updates' );
        $role->add_cap( 'delete_weekly_updates' );
        $role->add_cap( 'delete_private_weekly_updates' );
        $role->add_cap( 'delete_published_weekly_updates' );
        $role->add_cap( 'delete_others_weekly_updates' );
        $role->add_cap( 'edit_private_weekly_updates' );
        $role->add_cap( 'edit_published_weekly_updates' );
    }
}
add_action( 'admin_init', 'add_weekly_updates_capabilities' );

// Enqueue scripts and styles
function artist_theme_scripts() {
    // Enqueue Google Fonts (proper way, not @import)
    wp_enqueue_style( 'artist-theme-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap', array(), null );
// Fix the dependency to point to fonts instead
wp_enqueue_style( 'artist-theme-custom-style', get_template_directory_uri() . '/assets/css/style.css', array('artist-theme-fonts'), '1.0.3' );
    // Enqueue JavaScript file
    wp_enqueue_script( 'artist-theme-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array(), '1.0.3', true );
}
add_action( 'wp_enqueue_scripts', 'artist_theme_scripts' );

// Only remove specific problematic styles, keep accessibility ones
function artist_theme_clean_styles() {
    // Only remove block library styles if not using Gutenberg blocks
    // This keeps accessibility styles while removing potential @import issues
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'wp_enqueue_scripts', 'artist_theme_clean_styles', 1 );

// Additional custom functions can be added below

// Redirect all pages to the contact page except the contact page itself
add_action('template_redirect', function() {
    if (!is_page('contact-page')) {
        wp_redirect(home_url('/contact-page/'));
        exit;
    }
});

// Additional custom functions can be added below
