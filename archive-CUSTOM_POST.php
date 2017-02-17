<?php get_header(); ?>

    <section class="roll-posts">

        <div class="page-header-bg news"></div>

        <div class="container">
            <?php
            $page_number =  filter_input(INPUT_GET, 'pagen', FILTER_SANITIZE_SPECIAL_CHARS) ?: 1;

            $news = get_news_misc( 10, $page_number);

            foreach ( $news as $post) {
                if ( $post->post_type == 'fb' ) {
                    require get_template_directory() . '/partials/loop/content-fb-post.php';
                } else {
                    setup_postdata( $post );

                    get_template_part( 'partials/loop/content', 'post' );
                }
            }

            wp_reset_postdata();

//            while ( have_posts() ): the_post() ;
//
//                get_template_part( 'partials/loop/content', 'post' );
//
//            endwhile; wp_reset_postdata();

            // Pagination
            echo '<div class="pagination-footer row"><div class="col-md-8">';

            // prev
            if ( $page_number > 1 ) {
                $next_page_url = add_query_arg( 'pagen', $page_number - 1, esc_url( get_post_type_archive_link('news')));
                ?>
                <a href="<?php echo $next_page_url ?>" class="button-prev pull-left">
                    <i class="fa fa-angle-left fa-2x"></i>
                    <span>POSTAGENS <strong>ANTERIORES</strong></span>
                </a>
                <?php
            }

            // next
            $news_next_page = get_news_misc( 10, $page_number + 1);
            if ( $news_next_page ) {
                $next_page_url = add_query_arg( 'pagen', $page_number + 1, esc_url( get_post_type_archive_link('news')));
                ?>
                <a href="<?php echo $next_page_url ?>" class="button-next pull-right">
                    <span><strong>PRÓXIMAS</strong> POSTAGENS</span>
                    <i class="fa fa-angle-right fa-2x"></i>
                </a>
                <?php
            }
            echo '</div></div>';
            ?>
        </div>
    </section>

<?php get_footer() ?>