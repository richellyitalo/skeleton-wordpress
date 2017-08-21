<?php
// Mossoró premoldados
// Author: RichellyItalo
// Author URI: http://richellyitalo.com

require_once get_parent_theme_file_path( 'includes/defines.php' );
require_once get_parent_theme_file_path( 'includes/optimizations.php' );
require_once get_parent_theme_file_path( 'includes/functions.php' );

require_once get_parent_theme_file_path( 'includes/custom-posts.php' );
require_once get_parent_theme_file_path( 'includes/custom-fields.php' );

//require_once get_parent_theme_file_path( 'includes/walkers/wp-bootstrap-navwalker.php' );

//require_once get_parent_theme_file_path( 'includes/functions-news.php' );
//require_once get_parent_theme_file_path( 'includes/plugins/facebook.php' );

//require_once get_parent_theme_file_path( 'includes/filters.php' );

/*
 * Theme setup
 */
function theme_setup() {
    add_theme_support( 'menus' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'po-formats', array( 'gallery' ));

    register_nav_menus( array(
        'main'      => 'Menu Principal', // MENU 1
        'footer'    => 'Rodapé' // MENU 2
        // how use
        // wp_nav_menu( array('theme_location' => '{"main" or "footer"}' ) );

    ) );

    add_post_type_support( 'page', array( 'excerpt', 'post-formats' ) );

    add_image_size( 'NAME_SIZE1', 500, 335, true );
    add_image_size( 'NAME_SIZE2', 165, 120, array( 'top', 'center' ) );
}
add_action( 'after_setup_theme', 'theme_setup' );

/*
 * Enqueues
 */
function theme_scripts() {
    //wp_deregister_script('jquery');
    wp_deregister_style('noticons');
    wp_dequeue_script('devicepx');
    wp_dequeue_script('e-201408');

    wp_enqueue_style( 'theme-style', get_stylesheet_uri() );
    //wp_enqueue_style( 'theme-lib', get_theme_file_uri( 'dist/css/lib.min.css' ) );
    //wp_enqueue_style( 'theme-main', get_theme_file_uri( 'dist/css/main.min.css' ) );

    //wp_register_script( 'theme-script',  get_theme_file_uri( '/dist/js/all.min.js' ), array(), '1.0', true );
    //wp_enqueue_script( 'theme-script' );

    // $vars = array(
    //     'homeUrl' => esc_url( home_url('/') ),
    //     'directoryUri' => get_template_directory_uri(),
    //     'ajaxUrl' => admin_url( 'admin-ajax.php' ),
    //     'ajaxNonce'=> wp_create_nonce( SECURITY_CODE )
    // );
    // wp_localize_script( 'theme-script', 'VARS', $vars );
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );

function wpdocs_excerpt_more( $more ) {
    return '';
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );

// remove especial characteres
function sa_sanitize_spanish_chars ( $filename ) {
    return remove_accents( $filename );
}
add_filter( 'sanitize_file_name', 'sa_sanitize_spanish_chars', 10 );

// Session
// add_action('init', 'start_session_wp', 1);

// function start_session_wp() {
//     if ( session_id() == '' ) {
//         session_start();
//     }
// }