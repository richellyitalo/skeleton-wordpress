<?php

/*
 * Custom fields
 */
function my_meta_init() {
    add_meta_box('meta_box_link', 'URL', 'meta_box_link', 'cliente', 'normal', 'default');
    add_meta_box('meta_box_tipo', 'Modos de Exibição', 'meta_box_tipo', 'post', 'side', 'default');
}
// IMPLEMENT AND UNCOMMENT HERE
// add_action('admin_init', 'my_meta_init');

function meta_box_link($post) {
    global $post;
    $link = get_post_meta($post->ID, 'link', true);

    ?>
    <table class="form-table">
        <tr>
            <th><label for="link">URL</label> <small>Adicione <strong>http://</strong></small></th>
            <td>
                <input type="text" name="link" id="link" class="regular-text" value="<?php echo $link ?>"/>

            </td>
        </tr>
    </table>
    <?php
}

function meta_box_tipo($post){
    global $post;
    $tipo = get_post_meta($post->ID, 'tipo', true);
    ?>
    <table class="form-table">
        <tr>
            <td>
                <input type="radio" name="tipo" id="tipoa" value="tipoa" <?php echo $tipo == 'tipoa' || $tipo == '' ? 'checked' : '' ?>/>
                <label for="tipoa">
                    Horizontal Médio
                    <img src="<?php echo get_template_directory_uri() ?>/wp/images/icon/horz1.png"/>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="radio" name="tipo" id="tipob" value="tipob" <?php echo $tipo == 'tipob' ? 'checked' : '' ?>/>
                <label for="tipob">
                    Horizontal Largo
                    <img src="<?php echo get_template_directory_uri() ?>/wp/images/icon/horz2.png"/>
                </label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="radio" name="tipo" id="tipoc" value="tipoc" <?php echo $tipo == 'tipoc' ? 'checked' : '' ?>/>
                <label for="tipoc">
                    Vertical
                    <img src="<?php echo get_template_directory_uri() ?>/wp/images/icon/vertical.png"/>
                </label>
            </td>
        </tr>
    </table>
    <?php
}

//FIX PARA EVITAR SALVAR EM BRANCO
function save_custom_meta_data($post_id) {
    global $post;
    $campos = array('tipo', 'url');

    // Stop WP from clearing custom fields on autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    // Prevent quick edit from clearing custom fields
    if (defined('DOING_AJAX') && DOING_AJAX)
        return;

    foreach($campos as $key){
        if (is_null($_POST[$key]))
            delete_post_meta($post_id, $key);
        else
            update_post_meta($post_id, $key, esc_attr($_POST[$key]));
    }
}// end save_custom_meta_data

// IMPLEMENT AND UNCOMMENT HERE
// add_action('save_post', 'save_custom_meta_data');