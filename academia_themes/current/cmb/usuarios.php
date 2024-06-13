<?php

add_action('init', 'usuarios_register');

function usuarios_register() {

	$labels = array(
		'name'               => _x('Usuarios', 'post type general name'),
		'singular_name'      => _x('Usuario', 'post type singular name'),
		'add_new'            => _x('Nuevo Usuario', 'Usuario item'),
		'add_new_item'       => __('Añadir Nuevo Usuario'),
		'edit_item'          => __('Editar Usuario'),
		'new_item'           => __('Nuevo Usuario'),
		'view_item'          => __('Ver Usuario'),
		'search_items'       => __('Buscar Usuario'),
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
		'menu_icon'          => 'dashicons-admin-users',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title')
	  ); 

	register_post_type( 'usuarios' , $args );
}

add_action( 'cmb2_init', 'usuarios_CustomFields' );

function usuarios_CustomFields(){

    $usuarios = new_cmb2_box( array(
        'title' => 'Información Adicional Del Usuario',
        'id'    => 'info_slide',
        'object_types' => array( 'usuarios' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ) );
    
    $usuarios->add_field( array(
        'name'    => 'Email',
        'id'      => 'usuario_email',
        'type'    => 'text',
    ));

    $usuarios->add_field( array(
        'name'    => 'Clave',
        'id'      => 'usuario_clave',
        'type'    => 'text',
    ));

}
?>