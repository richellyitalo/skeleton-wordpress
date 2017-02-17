
<?php
$title = split_title_middle( get_the_title() );

$images = array();

$image_id_exclude = array();

if (has_post_thumbnail()) {
    $image_id_exclude[] = get_post_thumbnail_id();
}
// images
$images = get_posts( array(
    'post_type'   => 'attachment',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'numberposts' => 6,
    'post_status' => null,
    'post_parent' => get_the_ID(),
    'exclude' => $image_id_exclude
) );

if (has_post_thumbnail()) {
    array_unshift( $images, (object) array('ID' => get_post_thumbnail_id() ) );
}

?>
<div class="post row">
    <div class="col-md-6">
        <div class="description">
            <small class="post-date">
                <?php echo get_the_date() ?>
            </small>
            <h1 class="post-title">
                <a href="<?php the_permalink() ?>">
                    <?php echo $title[0] . '<span>' . $title[1] . '</span>' ?>
                </a>
            </h1>
            <p class="excerpt">
                <?php echo get_the_excerpt(); ?>
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="gallery-slider" id="post-gallery-<?php echo get_the_ID(); ?>">

            <div class="slider">
                <?php

                // imagens principais
                foreach ( $images as $image ) {

                    echo sprintf(
                        '<div><a href="%s">%s</a></div>',
                        get_permalink(),
                        wp_get_attachment_image( $image->ID, 'gallery_archive' )
                    );
                }
                ?>

            </div>

            <div class="clearfix"></div>
            <div class="gallery-pager">
                <ul class="list-inline text-center">
                    <?php foreach ( $images as $image ): ?>
                        <li>
                            <span class="box"></span>
                            <?php echo wp_get_attachment_image( $image->ID, 'gallery_slider_thumbnail', false, array( 'width' => 90) ) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>