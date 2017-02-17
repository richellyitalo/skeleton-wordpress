<?php
get_header();

while (have_posts() ): the_post();
    the_title();
    the_content();

    // OTHER_POSTS
    $others = get_posts(
        array(
            'post_type' => get_post_type(),
            'exclude' => array( get_the_ID() ),
            'orderby' => 'rand',
            'numberposts' => 2
        )
    );
    if ( $others ) {
        echo '<h1>OTHER POSTS</h1>';

        foreach ( $others as $post ) {
            setup_postdata( $post );

            get_template_part( 'partials/loop/content', get_post_format() );
        }
    }

endwhile;

wp_reset_postdata();

get_footer();
?>
