<?php

add_action('init', 'docentes_register');

function docentes_register() {

	$labels = array(
		'name'               => _x('Docentes', 'post type general name'),
		'singular_name'      => _x('Docente', 'post type singular name'),
		'add_new'            => _x('Nuevo Docente', 'Docente item'),
		'add_new_item'       => __('Añadir Nuevo Docente'),
		'edit_item'          => __('Editar Docente'),
		'new_item'           => __('Nuevo Docente'),
		'view_item'          => __('Ver Docente'),
		'search_items'       => __('Buscar Docente'),
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
		'menu_icon'          => 'dashicons-edit',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title')
	  ); 

	register_post_type( 'docentes' , $args );
}

add_action( 'cmb2_init', 'docentes_CustomFields' );

function docentes_CustomFields(){

    $docentes = new_cmb2_box( array(
        'title' => 'Información Adicional Del Docente',
        'id'    => 'info_docente',
        'object_types' => array( 'docentes' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ) );
    
    $docentes->add_field( array(
        'name'   => 'Profesión',
        'id'     => 'curso_docente_profesion',
        'type'   => 'text',
        'column' => true,
    ));

    $docentes->add_field( array(
        'name'    => 'Fotografía',
        'id'      => 'curso_docente_fotografia',
        'type'    => 'file',
        'options' => array('url' => false),
        'text'    => array('add_upload_file_text' => 'Añadir Foto'),
        // 'preview_size' => 'medium', 
        'preview_size' => array( 100, 100 ),
        'column' => true,
    ));

    $docentes->add_field( array(
        'name' => 'Breve Reseña',
        'id' => 'curso_docente_resena',
        'type' => 'textarea'
    ));

    $docentes->add_field( array(
        'name' => 'Curriculum Vitae',
        'id' => 'curso_docente_cv',
        'type'    => 'file',
        'options' => array('url' => false),
        'text'    => array('add_upload_file_text' => 'Añadir Archivo'),
        'preview_size' => 'large', 
        'column' => true,
    ));

}
?>