<?php

/**
 * Divide o título em 2
 *
 * @param string $title
 * @return array
 */
function split_title ( $title = '' ) {

    $result [0] = $title;
    if ( strpos( $title, ' ') ) {
        $result [0] = substr( $title, 0, strpos( $title, ' ') );
        $result [1] = substr( $title, strpos( $title, ' ' ), strlen( $title ) );
    }

    return $result;
}

function split_title_middle ( $title ) {
//    $middle = strrpos(substr( $title, 0, floor(strlen( $title ) / 2)), ' ') + 1;
//
//    $response[0] = substr( $title, 0, $middle);
//    $response[1] = substr( $title, $middle);
    $title = strip_tags( $title );
    $middle_length = floor( strlen( $title ) / 2 );

    $new_title = explode( '<br />', wordwrap( $title, $middle_length, '<br />') );

    if (isset( $new_title[2] ) ) {
        $new_title[1] .= ' ' . $new_title[2];
        unset( $new_title[2] );
    }

    return $new_title;
}

function get_bannerize_images ( $group = '', $single = false, $limit = 3 ) {
    global $wpdb;

    if ( ! function_exists ( 'wp_bannerize'))
        return false;

    $prefix = $wpdb->prefix;

    if ( $single )
        $limit = 1;

    $sql = "SELECT * FROM `" . $prefix . "bannerize` WHERE `enabled` = '1' AND `trash` = '0' AND " .
        "(`maximpressions` = 0 OR `impressions` < `maximpressions`) AND " .
        "( (`start_date` < NOW() OR `start_date` = '0000-00-00 00:00:00' ) AND (`end_date` > NOW() OR `end_date` = '0000-00-00 00:00:00') )
                 AND `group` = '%s' ORDER BY RAND() LIMIT $limit";

    if ( $single )
        $banner = $wpdb->get_row( $wpdb->prepare( $sql, $group ) );
    else
        $banner = $wpdb->get_results( $wpdb->prepare( $sql, $group ) );

    return $banner;
}

function banner_text_is_large ( $title ) {
    return strlen( $title ) > BANNER_MAXIMUM_CHARS;
}

function make_link_url( $url ) {
    if ( strpos( $url, 'http' ) === false && $url != '' ) {
        return esc_url( 'http://' . $url );
    }
    return esc_url( $url );
}

function strip_shortcode_gallery( $content ) {
    preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );

    if ( ! empty( $matches ) ) {
        foreach ( $matches as $shortcode ) {
            if ( 'gallery' === $shortcode[2] ) {
                $pos = strpos( $content, $shortcode[0] );
                if( false !== $pos ) {
                    return substr_replace( $content, '', $pos, strlen( $shortcode[0] ) );
                }
            }
        }
    }

    return $content;
}

function get_ids_from_gallery( $content ) {
    preg_match( '/\[gallery.*ids=.(.*).\]/', $content, $ids );
    $ids = explode( ",", $ids[1] );

    return $ids;
}