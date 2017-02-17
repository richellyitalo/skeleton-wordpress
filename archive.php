<?php get_header() ?>

<div class="custom-post-<?php echo get_post_type() ?>">
    <div class="container">
        <h1>
            <?php post_type_archive_title( '',  true ); ?>
        </h1>

    <?php

    while (have_posts() ): the_post() ;

        get_template_part( 'partials/loop/content', 'post' );

    endwhile; wp_reset_postdata(); ?>
</div>

<?php get_footer() ?>