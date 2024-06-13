<?php

add_action('init', 'accesos_curso_register');

function accesos_curso_register() {

	$labels = array(
		'name'               => _x('Accesos Cursos', 'post type general name'),
		'singular_name'      => _x('Acceso Curso', 'post type singular name'),
		'add_new'            => _x('Nuevo Acceso Curso', 'Acceso Curso item'),
		'add_new_item'       => __('Añadir Nuevo Acceso Curso'),
		'edit_item'          => __('Editar Acceso Curso'),
		'new_item'           => __('Nueva Acceso Curso'),
		'view_item'          => __('Ver Acceso Curso'),
		'search_items'       => __('Buscar Acceso Curso'),
		'not_found'          =>  __('Nada Encontrado'),
		'not_found_in_trash' => __('Nada Encontrado in basurero'),
		'parent_item_colon'  => ''
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'query_var'          => true,
		'menu_icon'          => 'dashicons-admin-network',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title'),
	  ); 

	register_post_type( 'accesos_curso' , $args );
}

add_action( 'cmb2_init', 'accesos_curso_CustomFields' );

function accesos_curso_CustomFields(){

    $accesos_curso = new_cmb2_box( array(
        'title' => 'Información Adicional del Acceso Curso',
        'id'    => 'accesos_info',
        'object_types' => array( 'accesos_curso' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ) );

    $accesos_curso->add_field( array(
        'name'    => 'Email',
        'desc'    => 'Este campo es la llave para acceder al curso',
        'id'      => 'accesos_email',
        'type'    => 'text',
        'column' => true,
        'attributes'  => array(
            'readonly'    => true,
        ),
    ));
    
    $accesos_curso->add_field( array(
        'name'    => 'ID transacción',
        'id'      => 'accesos_idtrx',
        'type'    => 'text',
        'column' => true,
        'attributes'  => array(
            'readonly'    => true,
        ),
    ));

    // function cmb2_get_post_options_cursos( $query_args ) {

    //     $args = wp_parse_args( $query_args );

    //     $posts = get_posts( $args );

    //     $post_options = array();
    //     if ( $posts ) {
    //         foreach ( $posts as $post ) {
    //             $post_options[ $post->ID ] = $post->post_title;
    //         }
    //     }

    //     return $post_options;
    // }

    // function obtener_cursos_presenciales() {
    //     return cmb2_get_post_options_cursos( array( 'post_type' => 'cursos', 'numberposts' => -1 ) );
    // }

    $accesos_curso->add_field( array(
        'name'    => 'Curso comprado',
        'id'      => 'accesos_curso_comprado',
        'type'    => 'text',
        'column' => true,
        'attributes'  => array(
            'readonly'    => true,
        ),
    ));

    $accesos_curso->add_field( array(
        'name'    => 'Curso comprado ID',
        'id'      => 'accesos_curso_comprado_id',
        'type'    => 'text',
        'column' => true,
        'attributes'  => array(
            'readonly'    => true,
        ),
    ));

    $accesos_curso->add_field( array(
        'name'    => 'Fecha Inicio',
        'id'      => 'accesos_fecha_inicio',
        'desc'    => 'Fecha de inicio especial para el alumno (opcional)',
        'type'    => 'text_date',
        'date_format' => 'd-m-Y',
        'column' => true,
    ));

    $accesos_curso->add_field( array(
        'name'    => 'Fecha Termino',
        'desc'    => 'Fecha de termino especial para el alumno (opcional)',
        'id'      => 'accesos_fecha_termino',
        'type'    => 'text_date',
        'date_format' => 'd-m-Y',
        'column' => true,
    ));

    $accesos_curso->add_field( array(
        'name'             => 'Estado de acceso',
        'id'               => 'accesos_estado',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => array(
            'activo'       => __( 'Activo', 'cmb2' ),
            'inactivo'     => __( 'Inactivo', 'cmb2' )
        ),
        'column' => true
    ) );

}
?>