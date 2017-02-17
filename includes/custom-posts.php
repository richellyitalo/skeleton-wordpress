<?php

function theme_register_post_type ( $post_type_name = '' ) {
    if ( $post_type_name === '' ) {
        return false;
    }

    $post_types= array();

    $post_types['video'] = array(
        'name' => 'video',
        'args' => array(
            'labels' => array(
                'name' => 'Videos',
                'singular_name' => 'Video',
                'menu_name' => 'Videos',
                'add_new' => 'Adicionar',
                'add_new_item' => 'Adicionar',
                'edit_item' => 'Editar',
                'all_items' => 'Listar',
                'view_items' => 'Visualizar',
                'search_items' => 'Procurar'
            ),
            'has_archive' => 'videos',
            'rewrite' => true,
            'menu_position' => 6,
            'public' => true,
            'menu_icon' => get_template_directory_uri() . '/wp/images/icon/video.png',
            'hierarchical' => false,
            'supports' => array('title')
        )
    );
    $post_types['news'] = array(
        'name' => 'news',
        'args' => array(
            'labels' => array(
                'name' => 'News',
                'singular_name' => 'News',
                'menu_name' => 'News',
                'add_new' => 'Adicionar',
                'add_new_item' => 'Adicionar',
                'edit_item' => 'Editar',
                'all_items' => 'Listar',
                'view_items' => 'Visualizar',
                'search_items' => 'Procurar'
            ),
            'has_archive' => 'news',
            'rewrite' => true,
            'menu_position' => 4,
            'public' => true,
            'menu_icon' => get_template_directory_uri() . '/wp/images/icon/news.png',
            'hierarchical' => false,
            'supports' => array('title', 'thumbnail', 'editor', 'excerpt', 'post-formats')
        )
    );

    if ( array_key_exists( $post_type_name, $post_types ) ) {
        register_post_type(
            $post_types[ $post_type_name ]['name'], $post_types[ $post_type_name ]['args']
        );
    } else {
        return false;
    }


}

function theme_custom_taxonomies() {
    /*
     * Criação de taxonomias (categorias
     *
     * Portfolio
     */
    register_taxonomy(
        "CATEGORY_NAME",

        array("POST_TYPE_NAME"), // POST TYPE HAS BEEND CREATED

        array(
            "hierarchical"      => true,
            "label"             => "LABEL NAME",
            "singular_label"    => "NAME",
            'show_ui'           => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'SLUG1/SLUG2')
        )
    );
}

function theme_post_types() {

    theme_register_post_type( 'news' );
    //theme_register_post_type( 'video' );

    //theme_custom_taxonomies();

    flush_rewrite_rules(  );
}
// IMPLEMENT AND UNCOMMENT HERE
// add_action( 'init', 'theme_post_types' );