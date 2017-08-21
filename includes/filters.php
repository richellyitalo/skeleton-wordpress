<?php

function custom_query_vars( $query ) {
    if ( !is_admin() && $query->is_archive() ) {
        if (  is_post_type_archive('POST_TYPE_NAME') ) {


            $query->set( 'orderby', 'menu_order');
            $query->set( 'order', 'ASC');

        }
    }
}

add_action( 'pre_get_posts', 'custom_query_vars' );

function menu_custom_class( $classes, $item, $args ) {
	if ( in_array($args->theme_location, array( 'main_left', 'main_right') ) ) {
		$classes[] = 'hvr-underline-from-center';
    }
    
	return $classes;
}

add_filter( 'nav_menu_css_class', 'menu_custom_class', 10, 3 );