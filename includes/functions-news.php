<?php

function get_news_misc($limit = 10, $page = 1) {

    $limit_new = floor( $limit / 2 );
    $offset = --$page * $limit_new;

    $args = array(
        'post_type' => 'news',
        'offset'    => $offset,
        'numberposts' => $limit_new
    );
    $news = get_posts( $args );

    if ( !$news ) {
        $limit_new = $limit;
        $offset = $page * $limit_new;
    }

    $news_fb = get_feed_facebook_aspost( FACEBOOK_GROUP, $limit_new, $offset );

    foreach ( $news_fb as $post_fb ) {
        array_unshift( $news, $post_fb );
    }

    $news = order_misc_posts_by_date( $news );

    return $news;
}

function compare_order_posts_by_date( $a, $b )
{
    return $a->post_date < $b->post_date;
}

function order_misc_posts_by_date( $posts ) {
    $posts_instance = $posts;
    usort( $posts_instance, 'compare_order_posts_by_date' );

    return $posts;
}