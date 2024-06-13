<?php

add_action('init', 'unidades_register');

function unidades_register() {

	$labels = array(
		'name'               => _x('Unidades', 'post type general name'),
		'singular_name'      => _x('Unidad', 'post type singular name'),
		'add_new'            => _x('Nuevas Unidades', 'Unidad'),
		'add_new_item'       => __('Añadir Nuevas Unidades'),
		'edit_item'          => __('Editar Unidades'),
		'new_item'           => __('Nuevas Unidades'),
		'view_item'          => __('Ver Unidades'),
		'search_items'       => __('Buscar Unidades'),
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
		'menu_icon'          => 'dashicons-welcome-learn-more',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title')
	  ); 

	register_post_type( 'unidad' , $args );
}

add_action( 'cmb2_init', 'unidades_CustomFields' );

function unidades_CustomFields(){

    $unidad_resenas = new_cmb2_box( array(
        'title' => 'Información de unidad',
        'id'    => 'info_unidad_resenas',
        'object_types' => array( 'unidad' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));

    function cmb2_get_post_options_cursos( $query_args ) {

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

    function obtener_cursos() {
        return cmb2_get_post_options_cursos( array( 'post_type' => 'curso', 'numberposts' => -1 ) );
    }

	$unidad_resenas->add_field( array(
        'name'             => 'Curso',
        'desc'             => 'Selecciona un curso',
        'id'               => 'unidad_curso',
        'type'             => 'select',
        'show_option_none' => true,
        'options_cb'       => 'obtener_cursos',
        'column' => true,
		'attributes'  => array(
			'required'    => 'required',
		),
    ) );

    $unidad_resenas->add_field( array(
        'name' => 'Reseña',
        'id' => 'unidad_resena',
        'type' => 'textarea'
    ));

    $unidad_resenas->add_field( array(
        'name' => 'Video',
        'desc' => '¡Ya no se ocupa este campo! El campo nuevo es: Video (IFRAME).',
        'id'   => 'unidad_resena_video',
        'type' => 'oembed',
    ) );

    $unidad_resenas->add_field( array(
        'name' => 'Video (IFRAME)',
        'id'   => 'unidad_resena_video_iframe',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'sanitization_cb' => false,
        'column' => true
    ) );

    $unidad_resenas->add_field( array(
        'name' => 'Lectura oblitatoria',
        'desc' => '',
        'id'   => 'unidad_resena_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $unidad_resenas->add_field( array(
        'name' => 'Reseña lectura obligatoria',
        'id' => 'adicional_lectura_obligatoria',
        'type' => 'textarea'
    ));

    $unidad_resenas->add_field( array(
        'id'            => 'unidad_resena_material_apoyo',
        'name'          => 'Material de apoyo',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir Link',
        ),
    ) );

    $unidad_resenas->add_field( array(
        'name' => 'Material de apoyo',
        'desc' => '',
        'id'   => 'unidad_resena_material_apoyo_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $unidad_resenas->add_field( array(
        'name' => 'Reseña material de apoyo',
        'id' => 'adicional_material_apoyo',
        'type' => 'textarea'
    ));

    $unidad_preguntas_etapa = new_cmb2_box( array(
        'title' => 'Preguntas & Alternativas',
        'id'    => 'info_unidad_preguntas',
        'object_types' => array( 'unidad' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));
    
    $etapa = $unidad_preguntas_etapa->add_field( array(
        'id'                => 'preguntas_unidad',
        'type'              => 'group',
        'options'           => array(
            'group_title'   => 'Pregunta n°{#}',
            'add_button'    => 'Añadir nueva pregunta',
            'remove_button' => 'Eliminar pregunta',
            'sortable'      => true,
            'closed'        => true,
        )
    ) );
    
    $unidad_preguntas_etapa->add_group_field( $etapa, array(
        'id'            => 'pregunta_unidad',
        'name'          => 'Pregunta',
        'type'          => 'textarea_small',
    ) );
    
    $unidad_preguntas_etapa->add_group_field( $etapa, array(
        'id'            => 'alternativa_unidad',
        'name'          => 'Alternativa',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir nueva alternativa',
        ),
    ) );
    
    $unidad_preguntas_etapa->add_group_field( $etapa, array(
        'id'            => 'alternativa_correcta_unidad',
        'desc'          => 'Ingresa textualmente la o las alternativas ingresadas arriba que sean las correctas.',
        'name'          => __('Alternativa Correcta', 'cgc-quiz'),
        'type'          => 'text',
        'repeatable'    => true,
        'options' => array(
            'add_row_text' => 'Añadir alternativa correcta',
        ),
    ) );

}
?>