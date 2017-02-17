<?php
//global $wp_embed;
$title = split_title( get_the_title() );

$video_url = get_post_meta( get_the_ID(), 'url', true);
$video_content = apply_filters( 'the_content', $video_url );

//$video_embed = '[embed width="700" height="500"]' . $video . '[/embed]';
//$video_coded = $wp_embed->run_shortcode( $video );
?>
<div class="post row">
    <div class="col-md-4">
        <div class="description">
            <small class="post-date">
                <?php echo get_the_date() ?>
            </small>
            <h1 class="post-title">
                <?php echo $title[0] . '<span>' . $title[1] . '</span>' ?>
            </h1>
            <p class="excerpt">
                <?php the_content(); ?>
            </p>
        </div>
    </div>
    <div class="col-md-8 container-video">
        <?php echo $video_content ?>
    </div>
</div>