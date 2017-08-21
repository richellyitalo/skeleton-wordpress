<?php
/**
 * ------------------------------------------------------
 * Médicos: AJAX
 */
// scripts
function searchmedicos_scripts() {
    ?>

    <script>
        (function ($) {

            'use strict';

            $(document).ready(function() {
                var $container = jQuery('#filter-medicos-list');
                var medicosAll = [];
                var medicosFiltered = [];

                var showMedicos = function() {
                    $container.html('');
                    $('.easyPaginateNav').remove();

                    if (medicosFiltered.length == 0) {
                        $container.html('Nenhum médico encontrado com os requisitos, verifique novamente os filtros.')
                    } else {
                        for (var i = 0; i < medicosFiltered.length ; i++) {
                            $container.append(medicosFiltered[ i ].html);
                        }
                        $container.easyPaginate({
                            paginateElement: '>div',
                            elementsPerPage: 6,
                            effect: 'fade'
                        });
                    }
                };

                var formFilter = function (letter) {
                    var $form = $('form#search-medicos');
                    var searchObj = {
                        'localidade' : $form.find('[name="localidade"]').val(),
                        'nome' : $form.find('[name="nome"]').val(),
                        'especialidade' : $form.find('[name="especialidade"]').val(),
                        'crm' : $form.find('[name="crm"]').val(),
                    };
                    filterMedicos(searchObj, letter);
                };

                $('#filter-medicos-letter a').on('click', function (event) {
                    $('form#search-medicos').find('[name="nome"]').val('');
                    formFilter($(this).data('value'));
                    event.preventDefault();
                });

                $('form#search-medicos').on('keyup change', 'input, select, textarea', function (e) {
                    formFilter();
                });

                var filterMedicos = function(searchObj, letter) {
                    var letter = letter || '';
                    medicosFiltered = medicosAll.filter(function (medico) {
                        var filterName = true,
                            filterCrm = true,
                            filterEspecialidade = true,
                            filterLocalidade = true,
                            filterLetter = true;

                        // Filtros de busca
                        if (searchObj.nome.trim().length) {
                            filterName = (
                                medico.nome.toLowerCase().search(searchObj.nome.toLowerCase()) > -1 ||
                                medico.searchable.toLowerCase().search(searchObj.nome.toLowerCase()) > -1
                            );
                        }

                        if (searchObj.crm.trim().length) {
                            filterCrm = medico.crm.toLowerCase().search(searchObj.crm.toLowerCase()) > -1;
                        }

                        if (searchObj.especialidade.trim().length) {
                            filterEspecialidade = medico.especialidade.some(function (key) {
                                return key == searchObj.especialidade;
                            });
                        }

                        if (searchObj.localidade.trim().length) {
                            filterLocalidade = medico.localidade.some(function (key) {
                                return key == searchObj.localidade;
                            });
                        }

                        if (letter.trim().length) {
                            if (medico.nome.toLowerCase().indexOf('dr. ') == 0)
                                filterLetter = medico.nome[4].toLowerCase() == letter.toLowerCase();
                            else if(medico.nome.toLowerCase().indexOf('dra. ') == 0)
                                filterLetter = medico.nome[5].toLowerCase() == letter.toLowerCase();
                            else
                                filterLetter = medico.nome[0].toLowerCase() == letter.toLowerCase();
                        }
                        return filterName && filterCrm && filterEspecialidade && filterLocalidade && filterLetter;
                    });
                    showMedicos();
                };

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    cache: true,
                    url: VARS.ajaxUrl,
                    data: {
                        action: 'get_medicos',
                        nonce: VARS.ajaxNonce
                    },
                    success: function (response) {
                        medicosFiltered = medicosAll = response.data.object;
                        showMedicos();
                    },
                    error: function (response) {
                        console.log('Houston, temos um problema! --> ', response);
                    },
                    complete: function () {
                    }
                });
            });

        }(jQuery));
    </script>
    <?php
}
add_action( 'searchmedicos_scripts', 'searchmedicos_scripts', 1 );

// get
function get_medicos()
{
    switch_to_blog( MAIN_ID );

    if( ! check_ajax_referer( SECURITY_CODE , 'nonce', false ) ){
        die( wp_send_json_error('Atualize a página e tente novamente.') );
    }

    $args = array(
        'post_type' => 'medico',
        'orderby' => 'name',
        'order' => 'ASC',
        //'s' => $keyword,
        'showposts' => -1
    );
    $search_query = new WP_Query( $args );
    $count = $search_query->post_count;
    // $html = $args;
    $object = array();


    if ( $search_query->have_posts() ) {

        $i = 0;

        while ( $search_query->have_posts() ) {
            $search_query->the_post();

            $nome = get_the_title();
            $telefone = get_post_meta(get_the_ID(), 'telefone', true);
            $email = get_post_meta(get_the_ID(), 'email', true);

            $localidades = array();
            foreach(get_the_terms(get_the_ID(), 'localidade') as $l)
                $localidades[] = $l->slug;

            $especialidades = array();
            foreach(get_the_terms(get_the_ID(), 'especialidade') as $e)
                $especialidades[] = $e->slug;
            $crm = get_post_meta(get_the_ID(), 'crm', true);

            $object[$i] = array(
                'nome' => $nome,
                'searchable' => $nome . ' ' . $telefone . ' ' . $email,
                'localidade' => $localidades,
                'especialidade' => $especialidades,
                'crm' => $crm
            );

            ob_start();
            get_template_part( 'partials/loop/content', 'medico' );
            $html = ob_get_clean();

            $object[$i++]['html'] = $html;
        }
    } else {
        $object[0]['html'] = 'Nenhum resultado encontrado';
    }
    echo wp_send_json_success( compact( 'count',  'object' ) );

    restore_current_blog();
}
add_action( 'wp_ajax_get_medicos', 'get_medicos' );
add_action( 'wp_ajax_nopriv_get_medicos', 'get_medicos' );

/**
 * ------------------------------------------------------
 * Exames: AJAX
 */

// scripts
function searchexames_scripts() {
    ?>

    <script>
        (function ($) {

            'use strict';

            $(document).ready(function() {
                var $container = jQuery('#results-exames ul');
                var $modal = jQuery('#modalExame');
                var examesAll = [];
                var examesFiltered = [];

                var showExames = function() {
                    $container.html('');

                    if (examesFiltered.length == 0) {
                        $container.html('Nenhum exame encontrado com os requisitos de busca.')
                    } else {
                        for (var i = 0; i < examesFiltered.length ; i++) {
                            $container.append(examesFiltered[ i ].html);
                        }
                    }
                };

                $('#results-exames ul').on('click', 'a', function (event) {
                    var clickedId = $(this).data('id');
                    var clickedExam = examesAll.filter(function(exame) {
                        return exame.id == clickedId;
                    })[0];

                    $modal.find('.modal-title').html(clickedExam.title);
                    $modal.find('.modal-body').html(clickedExam.body);
                    $modal.modal('show');

                    event.preventDefault();
                });
                $('#filter-letters a').on('click', function (event) {
                    $('#exameQuery').val('');
                    formFilter($(this).data('value'));

                    event.preventDefault();
                });

                var formFilter = function (letter) {
                    var searchObj = {
                        'query' : $('form#filter-pesquisar [name="query"]').val()
                    };
                    filterExames(searchObj, letter);
                };

                $('form#filter-pesquisar').on('keyup change', 'input, select, textarea', function (e) {
                    formFilter();
                });

                var filterExames = function(searchObj, letter) {
                    var letter = letter || '';
                    examesFiltered = examesAll.filter(function (exame) {
                        // Filtros de busca
                        if (letter.trim().length) {
                            return exame.title[0].toLowerCase() == letter.toLowerCase();
                        }
                        if (searchObj.query.trim().length) {
                            return exame.searchable.toLowerCase().search(searchObj.query.toLowerCase()) > -1;
                        }
                        return true;
                    });
                    showExames();
                };

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    cache: true,
                    url: VARS.ajaxUrl,
                    data: {
                        action: 'get_exames',
                        nonce: VARS.ajaxNonce
                    },
                    success: function (response) {
                        examesFiltered = examesAll = response.data.object;
                        showExames();
                    },
                    error: function (response) {
                        console.log('Houston, temos um problema! --> ', response);
                    },
                    complete: function () {
                    }
                });
            });

        }(jQuery));
    </script>
    <?php
}
add_action( 'searchexames_scripts', 'searchexames_scripts', 1 );

// get
function get_exames()
{
    switch_to_blog( MAIN_ID );

    if( ! check_ajax_referer( SECURITY_CODE , 'nonce', false ) ){
        die( wp_send_json_error('Atualize a página e tente novamente.') );
    }

    $args = array(
        'post_type' => 'exame',
        'orderby' => 'name',
        'order' => 'ASC',
        //'s' => $keyword,
        'showposts' => -1
    );
    $search_query = new WP_Query( $args );
    $count = $search_query->post_count;

    $object = array();

    if ( $search_query->have_posts() ) {

        $i = 0;

        while ( $search_query->have_posts() ) {
            $search_query->the_post();

            $searchable = get_the_title() . ' ' . esc_html(get_the_excerpt());

            $object[$i] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'searchable' => $searchable,
                'html' => sprintf(
                        '<li class="col-sm-4 col-xs-6"><a href="" data-id="%s" class="text-uppercase">%s</a></li>',
                        get_the_ID(),
                        get_the_title()
                )
            );

            ob_start();
            get_template_part( 'partials/loop/content', 'exame' );
            $body = ob_get_clean();

            $object[$i++]['body'] = $body;
        }
    } else {
        $object[0]['html'] = 'Nenhum resultado encontrado';
    }
    echo wp_send_json_success( compact( 'count', 'object') );

    restore_current_blog();
}
add_action( 'wp_ajax_get_exames', 'get_exames' );
add_action( 'wp_ajax_nopriv_get_exames', 'get_exames' );


/**
 * ------------------------------------------------------
 * Auto redirecionamento de unidades
 */
function my_page_template_redirect()
{
    // para o site principal
    // redirecionar para à sessão definida
    if ( get_current_blog_id() == MAIN_ID &&
        ! empty( $_SESSION[ 'unidade' ] ) &&
        is_front_page()
    ) {
        wp_redirect( get_blog_details( $_SESSION[ 'unidade' ] )->siteurl );
    }

    // Define a sessão padrão
    if ( get_current_blog_id() != MAIN_ID ) {
        $_SESSION[ 'unidade' ] = get_current_blog_id();
    }
}
add_action( 'template_redirect', 'my_page_template_redirect' );