<?php

add_action('init', 'evaluacion_register');

function evaluacion_register() {

	$labels = array(
		'name'               => _x('Evaluaciones', 'post type general name'),
		'singular_name'      => _x('Evaluación', 'post type singular name'),
		'add_new'            => _x('Nuevo Evaluación', 'Evaluación item'),
		'add_new_item'       => __('Añadir Nuevo Evaluación'),
		'edit_item'          => __('Editar Evaluación'),
		'new_item'           => __('Nueva Evaluación'),
		'view_item'          => __('Ver Evaluación'),
		'search_items'       => __('Buscar Evaluación'),
		'not_found'          =>  __('Nada Encontrado'),
		'not_found_in_trash' => __('Nada Encontrado in basurero'),
		'parent_item_colon'  => ''
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => true,
		'menu_icon'          => 'dashicons-dashboard',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title')
	  ); 

	register_post_type( 'evaluacion' , $args );
}

add_action( 'cmb2_init', 'evaluacion_CustomFields' );

function evaluacion_CustomFields(){

    $evaluacion = new_cmb2_box( array(
        'title' => 'Información Adicional de la Evaluación',
        'id'    => 'evaluacion_campos',
        'object_types' => array( 'evaluacion' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ) );

    $evaluacion->add_field( array(
        'name'    => 'ID Usuario',
        'id'      => 'id_user_evaluado',
        'type'    => 'text',
    ));

    $evaluacion->add_field( array(
        'name'    => 'ID Curso',
        'id'      => 'id_curso_evaluado',
        'type'    => 'text',
    ));
    
    $evaluacion->add_field( array(
        'name'    => 'Email Usuario',
        'id'      => 'email_evaluado',
        'type'    => 'text',
        'column' => true
    ));

    $evaluacion->add_field( array(
        'name'    => 'Curso',
        'id'      => 'curso_evaluado',
        'type'    => 'text',
        'column' => true
    ));
    
    $evaluacion->add_field( array(
        'name'             => 'Nivel Evaluado',
        'id'               => 'nivel_evaluado',
        'type'             => 'select',
        'show_option_none' => true,
        'default'          => 'uno',
        'options'          => array(
            'uno'    => __( '1 estrella', 'cmb2' ),
            'dos'    => __( '2 estrellas', 'cmb2' ),
            'tres'   => __( '3 estrellas', 'cmb2' ),
            'cuatro' => __( '4 estrellas', 'cmb2' ),
            'cinco'  => __( '5 estrellas', 'cmb2' ),
        ),
        'column' => true
    ) );

}
?>