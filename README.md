# Skeleton WP

##Content
This skeleton contains optimization (`/includes/optimizations.php`), custom posts syntax (`/includes/custom-posts`), example definitions (`/includes/defines.php`), auxiliary functions (`/includes/functions.php`), custom fields (`/includes/custom-fields.php`).
Also contains feed function for facebook (`/includes/plugins/facebook.php`).
And a filter of the posts with the posts of the wp set to list sorting by date.

## Helpers

#####URL Home:
```php
esc_url( home_url('/') );
```

#####Nav menu:
```php
wp_nav_menu(array(
    'theme_location' => 'main',
    'container' => '',
    'menu_class' => 'menu list-unstyled text-center'
));
```

#####Loop (Page, Single):
```php
while (have_posts() ): the_post();
    the_title();
    the_content();
endwhile;
wp_reset_postdata();
```

#####Loop Section:
```php
$args = array(
    'post_type' => 'CUSTOM_POST_TYPE_NAME', //'post'
    'meta_key' => '_thumbnail_id', // ONLY POST WITH POST THUMBNAIL
    'numberposts' => -1
);
$posts = get_posts( $args );
if ( $posts ) {
    foreach ( $posts as $post ) : setup_postdata( $post ) {
        the_title();
        the_content();
    }
    wp_reset_postdata();
}
```

##The 'Partials'
The 'partials' contain a simple structure. See below:
```$xslt

/partials/
    |_/{section}/file.php example 'front'
    
    |_/loop/content-post.php
    |_/loop/content-{POST_TYPE}.php
    |_/loop/content-{POST_FORMAT}.php
    
    |_/post/content.php
    |_/post/content-{POST_TYPE}.php
    |_/post/content-{POST_FORMAT}.php
```
