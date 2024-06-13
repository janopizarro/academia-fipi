<?php

add_action('init', 'status_curso_register');

function status_curso_register() {

	$labels = array(
		'name'               => _x('Status Cursos', 'post type general name'),
		'singular_name'      => _x('Status Curso', 'post type singular name'),
		'add_new'            => _x('Nuevo Status Curso', 'Status Curso item'),
		'add_new_item'       => __('Añadir Nuevo Status Curso'),
		'edit_item'          => __('Editar Status Curso'),
		'new_item'           => __('Nueva Status Curso'),
		'view_item'          => __('Ver Status Curso'),
		'search_items'       => __('Buscar Status Curso'),
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
		'menu_icon'          => 'dashicons-welcome-learn-more',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title'),
        // 'capabilities' => array(
        //     'create_posts' => 'do_not_allow'
        // )
	  ); 

	register_post_type( 'status_curso' , $args );
}

add_action( 'cmb2_init', 'status_curso_CustomFields' );

function status_curso_CustomFields(){

    $status_curso = new_cmb2_box( array(
        'title' => 'Información Adicional del Status Curso',
        'id'    => 'status_info',
        'object_types' => array( 'status_curso' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ) );

    $status_curso->add_field( array(
        'name'    => 'Email',
        'desc'    => 'Este campo es la llave para acceder al curso',
        'id'      => 'status_email',
        'type'    => 'text',
        'column' => true,
        'attributes'  => array(
            'readonly'    => true,
        ),
    ));
    
    $status_curso->add_field( array(
        'name'    => 'ID transacción',
        'id'      => 'status_idtrx',
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
    //         $post_options[ $post->ID ] = $post->post_title;
    //         }
    //     }

    //     return $post_options;
    // }

    // function obtener_cursos_presenciales() {
    //     return cmb2_get_post_options_cursos( array( 'post_type' => 'cursos-presenciales', 'numberposts' => -1 ) );
    // }

	// $status_curso->add_field( array(
    //     'name'             => 'Cursos Presenciales',
    //     'id'               => 'status_cursos_presenciales',
    //     'type'             => 'select',
    //     'show_option_none' => true,
    //     'options_cb'       => 'obtener_cursos_presenciales',
    //     'column' => true,
	// 	'attributes'  => array(
	// 		'required'    => 'required',
    //         'disabled'    => true,
    //     ),
    // ) );

    $status_curso->add_field( array(
        'name'             => 'Estado de acceso',
        'id'               => 'status_estado',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => array(
            'activo'       => __( 'Activo', 'cmb2' ),
            'innactivo'    => __( 'Innactivo', 'cmb2' )
        ),
        'column' => true
    ) );

}
?>