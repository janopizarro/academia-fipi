<?php

add_action('init', 'portfolio_register');

function portfolio_register() {

	$labels = array(
		'name' => _x('Conócenos', 'post type general name'),
		'singular_name' => _x('Conócenos', 'post type singular name'),
		'add_new' => _x('Añadir Nuevo', 'portfolio item'),
		'add_new_item' => __('Añadir Item Conócenos'),
		'edit_item' => __('Editar Item Conócenos'),
		'new_item' => __('Nuevo Item Conócenos'),
		'view_item' => __('Ver Item Conócenos'),
		'search_items' => __('Buscar Item Conócenos'),
		'not_found' =>  __('Nada Encontrado'),
		'not_found_in_trash' => __('Nada Encontrado in basurero'),
		'parent_item_colon' => ''
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => 'dashicons-universal-access',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title')
	  ); 

	register_post_type( 'conocenos' , $args );
}

function conocenos_CustomFields(){

    $conocenos = new_cmb2_box( array(
        'title' => 'Información Adicional',
        'id'    => 'informacion_conocenos',
        'object_types' => array( 'conocenos' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ) );

    $conocenos->add_field( array(
        'name' => 'Cargo',
        'id'   => 'cargo',
        'type' => 'text',
        'column' => true,
        'closed'       => true,
    ) );

    $conocenos->add_field( array(
        'name' => 'Fotografía',
        'id'   => 'fotografia',
        'type' => 'file',
        'text'    => array(
            'add_upload_file_text' => 'Añadir Fotografía',
            'file_text' => 'jano'
        ),
        'query_args' => array(
            'type' => array(
                'image/gif',
                'image/jpeg',
                'image/png',
                ),
        ),
        'column' => true
    ) );

}
add_action( 'cmb2_init', 'conocenos_CustomFields' );
?>