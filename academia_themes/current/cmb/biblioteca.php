<?php

add_action('init', 'biblioteca_register');

function biblioteca_register() {

	$labels = array(
		'name'               => _x('Biblioteca', 'post type general name'),
		'singular_name'      => _x('Biblioteca', 'post type singular name'),
		'add_new'            => _x('Nuevas Item Biblioteca', 'Biblioteca'),
		'add_new_item'       => __('Añadir Nuevo Item Biblioteca'),
		'edit_item'          => __('Editar Biblioteca'),
		'new_item'           => __('Nuevas Biblioteca'),
		'view_item'          => __('Ver Biblioteca'),
		'search_items'       => __('Buscar Biblioteca'),
		'not_found'          =>  __('Nada Encontrado'),
		'not_found_in_trash' => __('Nada Encontrado in basurero'),
		'parent_item_colon'  => ''
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'query_var'          => true,
		'menu_icon'          => 'dashicons-format-gallery',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title')
	  ); 

	register_post_type( 'biblioteca' , $args );
}

add_action( 'cmb2_init', 'biblioteca_CustomFields' );

function biblioteca_CustomFields(){

    $biblioteca_resenas = new_cmb2_box( array(
        'title' => 'Información de biblioteca',
        'id'    => 'info_biblioteca_resenas',
        'object_types' => array( 'biblioteca' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));

    function cmb2_get_post_options_cursos_biblioteca( $query_args ) {

        $args = wp_parse_args( $query_args, array(
            'post_type'   => 'curso',
            'numberposts' => -1,
        ) );

        $posts = get_posts( $args );

        $post_options = array();
        if ( $posts ) {
            foreach ( $posts as $post ) {
            $post_options[ $post->ID ] = $post->post_title;
            }
        }

        return $post_options;
    }

    function obtener_cursos_biblioteca() {
        return cmb2_get_post_options_cursos_biblioteca( array( 'post_type' => 'curso', 'numberposts' => -1 ) );
    }

	$biblioteca_resenas->add_field( array(
        'name'             => 'Curso',
        'desc'             => 'Selecciona un curso',
        'id'               => 'biblioteca_curso',
        'type'             => 'select',
        'show_option_none' => true,
        'options_cb'       => 'obtener_cursos_biblioteca',
        'column' => true,
    ) );

    $biblioteca_resenas->add_field( array(
        'name' => 'Archivo(s)',
        'desc' => '',
        'id'   => 'biblioteca_archivos',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $biblioteca_resenas->add_field( array(
        'id'            => 'biblioteca_links',
        'name'          => 'Link(s)',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir Link',
        ),
    ) );


}
?>