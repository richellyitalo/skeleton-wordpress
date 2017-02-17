<?php
get_header();

while (have_posts() ): the_post();
    the_title();
    the_content();
endwhile;

wp_reset_postdata();

get_footer();
?>
