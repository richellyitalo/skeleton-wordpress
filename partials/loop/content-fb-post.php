<?php $title = split_title_middle( $post->post_title ) ?>
<div class="post row">
    <div class="col-md-6">
        <div class="description">
            <small class="post-date">
                <?php echo $post->post_date_for_humans; ?>
                <span class="pull-right">
                    postagem via
                    <a href="<?php echo $post->post_url ?>" target="_blank">
                        <i class="color-fb fa fa-facebook-square"></i>
                    </a>
                </span>
            </small>
            <h1 class="post-title">
                <a href="<?php echo $post->post_url ?>" target="_blank">
                    <?php
                    if ( strlen($post->post_title )  < 35 ) {
                        echo $title[0] . '<span>' . $title[1] . '</span>';
                    } else {
                        echo $title[0] . '...';
                    }
                    ?>
                </a>
            </h1>
            <p class="excerpt">
                <?php echo $post->post_excerpt; ?>
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="gallery-slider" id="post-gallery-<?php echo $post->ID; ?>">

            <div class="slider">
                <div><img src="<?php echo $post->image_url ?>" width="500" height="335" /></div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
</div>