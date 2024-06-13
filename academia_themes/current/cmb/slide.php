<?php

add_action('init', 'Slide_register');

function Slide_register() {

	$labels = array(
		'name'               => _x('Slides', 'post type general name'),
		'singular_name'      => _x('Slide', 'post type singular name'),
		'add_new'            => _x('Nuevo Slide', 'Servicio item'),
		'add_new_item'       => __('Añadir Nuevo Slide'),
		'edit_item'          => __('Editar Slide'),
		'new_item'           => __('Nuevo Slide'),
		'view_item'          => __('Ver Slide'),
		'search_items'       => __('Buscar Slide'),
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
		'menu_icon'          => 'dashicons-format-image',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title')
	  ); 

	register_post_type( 'slide' , $args );
}

add_action( 'cmb2_init', 'slide_CustomFields' );

function slide_CustomFields(){

    $slide = new_cmb2_box( array(
        'title' => 'Información Adicional Del Slide',
        'id'    => 'bloque_slide',
        'object_types' => array( 'slide' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ) );
    
    $slide->add_field( array(
        'name'    => 'Imagen',
        'id'      => 'imagen_slide',
        'type'    => 'file',
        // Optional:
        'options' => array(
            'url' => false, // Hide the text input for the url
        ),
        'text'    => array(
            'add_upload_file_text' => 'Añadir Imagen' // Change upload button text. Default: "Add or Upload File"
        ),
        // query_args are passed to wp.media's library query.
        'preview_size' => 'medium', // Image size to use when previewing in the admin.
        'column' => true
    ) );

    $slide->add_field( array(
        'name'    => 'URL',
        'desc'    => 'Este campo es opcional',
        'id'      => 'link_slide',
        'type'    => 'text',
    ) );

}
?>