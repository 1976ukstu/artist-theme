<?php
/**
 * Theme Functions
 *
 * This file contains custom functions and theme setup code, such as registering menus, sidebars, and other theme features.
 */

// Theme setup function
function artist_theme_setup() {
    // Add support for automatic feed links
    add_theme_support( 'automatic-feed-links' );

    // Add support for post thumbnails
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'artist-theme' ),
        'footer'  => __( 'Footer Menu', 'artist-theme' ),
    ) );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Add support for custom header
    add_theme_support( 'custom-header' );
}

// Hook the theme setup function to the after_setup_theme action
add_action( 'after_setup_theme', 'artist_theme_setup' );

// Enqueue scripts and styles
function artist_theme_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style( 'artist-theme-style', get_stylesheet_uri() );

    // Enqueue JavaScript file
    wp_enqueue_script( 'artist-theme-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array(), null, true );
}

// Hook the scripts function to the wp_enqueue_scripts action
add_action( 'wp_enqueue_scripts', 'artist_theme_scripts' );
?>