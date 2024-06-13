<?php

add_action('init', 'cursos_register');

function cursos_register() {

	$labels = array(
		'name'               => _x('Cursos', 'post type general name'),
		'singular_name'      => _x('Curso', 'post type singular name'),
		'add_new'            => _x('Nuevo Curso', 'Curso Presencial item'),
		'add_new_item'       => __('Añadir Nuevo Curso'),
		'edit_item'          => __('Editar Curso'),
		'new_item'           => __('Nuevo Curso'),
		'view_item'          => __('Ver Curso'),
		'search_items'       => __('Buscar Curso'),
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
		'menu_icon'          => 'dashicons-admin-users',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title','thumbnail', 'tags')
	); 

	register_post_type( 'curso' , $args );
}

register_taxonomy("categoria", array("curso"), array("hierarchical" => true, "label" => "Categorías Cursos", "singular_label" => "Categorías Cursos", "rewrite" => true, 'hierarchical' => true));
register_taxonomy("tags_cursos", array("curso"), array("hierarchical" => true, "label" => "Tags Cursos", "singular_label" => "Tags Cursos", "rewrite" => true, 'hierarchical' => true));
register_taxonomy("modalidad_cursos", array("curso"), array("hierarchical" => true, "label" => "Modalidad Cursos", "singular_label" => "Modalidad Cursos", "rewrite" => true, 'hierarchical' => true));

add_action( 'cmb2_init', 'cursos_CustomFields' );

function cursos_CustomFields(){

    $curso = new_cmb2_box( array(
        'title' => 'Información Adicional Del Curso Presencial',
        'id'    => 'info_curso_presencial',
        'object_types' => array( 'curso' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));

    $curso->add_field( array(
        'name' => 'Fecha Lanzamiento',
        'id'   => 'curso_fecha',
        'type' => 'text_date',
        'date_format' => 'd-m-Y',
    ) );

    $curso->add_field( array(
        'name' => 'Fecha Termino',
        'id'   => 'curso_fecha_termino',
        'type' => 'text_date',
        'date_format' => 'd-m-Y',
    ) );

    $curso->add_field( array(
        'name' => 'ID Curso',
        'id'   => 'curso_id',
        'type' => 'text'
    ));

    $curso->add_field( array(
        'name' => '¿Qué vas a ver en este curso?',
        'id'   => 'curso_vamos_a_ver',
        'type' => 'textarea_small'
    ));

    $curso->add_field( array(
        'name' => 'A quien está dirigido',
        'id'   => 'curso_dirigido',
        'type' => 'textarea_small'
    ));

    $curso->add_field( array(
        'name' => 'Objetivos',
        'id'   => 'curso_objetivos',
        'type' => 'textarea_small'
    ));

    $curso->add_field( array(
        'name' => 'Contenidos',
        'id'   => 'curso_contenidos',
        'type' => 'textarea_small'
    ));

    $curso->add_field( array(
        'name' => 'Cantidad de horas',
        'id'   => 'curso_horas',
        'type' => 'text'
    ));

    $curso->add_field( array(
        'name' => 'Certifica',
        'id'   => 'curso_certifica',
        'type' => 'text'
    ));

    $curso->add_field( array(
        'name' => 'Monto',
        'desc' => 'Ingresar sin puntos ni $',
        'id'   => 'curso_monto',
        'type' => 'text'
    ));

    $curso->add_field( array(
        'name' => 'Video Intro',
        'desc' => '¡Ya no se ocupa este campo! El campo nuevo es: Video (IFRAME).',
        'id'   => 'curso_intro_video',
        'type' => 'oembed',
    ) );

    $curso->add_field( array(
        'name' => 'Video (IFRAME)',
        'id'   => 'curso_intro_video_iframe',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'sanitization_cb' => false,
    ) );

    $curso->add_field( array(
        'name' => 'Descripción Intro',
        'id'   => 'curso_intro_desc',
        'type' => 'textarea_small'
    ));

    $curso->add_field( array(
        'name' => 'Mensaje Curso Finalizado',
        'id'   => 'curso_finalizado_msg',
        'type' => 'textarea_small'
    ));

    $curso->add_field( array(
        'name'    => 'Imagen Pequeña',
        'id'      => 'curso_imagen_pequena',
        'type'    => 'file',
        'options' => array('url' => false),
        'text'    => array('add_upload_file_text' => 'Añadir Imagen'),
        'preview_size' => 'large', 
        'preview_size' => array( 300, 200 ),
    ));

    $curso->add_field( array(
        'name'    => 'Imagen Grande',
        'id'      => 'curso_imagen_grande',
        'type'    => 'file',
        'options' => array('url' => false),
        'text'    => array('add_upload_file_text' => 'Añadir Imagen'),
        'preview_size' => 'large', 
        'preview_size' => array( 400, 300 ),
    ));

    function cmb2_get_post_options_docentes( $query_args ) {

        $args = wp_parse_args( $query_args, array(
            'post_type'   => 'docentes',
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

    function obtener_docentes() {
        return cmb2_get_post_options_docentes( array( 'post_type' => 'docentes', 'numberposts' => -1 ) );
    }

	$curso->add_field( array(
        'name'             => 'Docente',
        'desc'             => 'Selecciona un docente',
        'id'               => 'curso_docente',
        'type'             => 'select',
        'show_option_none' => true,
        'options_cb'       => 'obtener_docentes',
        'column' => true,
		'attributes'  => array(
			'required'    => 'required',
		),
    ) );

    $curso->add_field( array(
        'name'             => 'Docente',
        'desc'             => 'Selecciona un docente',
        'id'               => 'curso_docente_segundo',
        'type'             => 'select',
        'show_option_none' => true,
        'options_cb'       => 'obtener_docentes',
        'column' => true,
    ) );

    $curso->add_field( array(
        'name' => 'Bibliografía - Descargable',
        'desc' => '',
        'id'   => 'bibliografia_descargable',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $curso_resenas = new_cmb2_box( array(
        'title' => 'Reseña de unidades',
        'id'    => 'info_curso_resenas',
        'object_types' => array( 'curso' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));

    $curso_resenas->add_field( array(
        'name' => 'Reseña Unidad 01',
        'id' => 'curso_resena_01',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'name' => 'Video Unidad 01',
        'desc' => '¡Ya no se ocupa este campo! El campo nuevo es: Video (IFRAME).',
        'id'   => 'curso_resena_01_video',
        'type' => 'oembed',
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Video (IFRAME)',
        'id'   => 'curso_resena_01_resena_video_iframe',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'sanitization_cb' => false,
        'column' => true
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Lectura oblitatoria · Unidad 01',
        'desc' => '',
        'id'   => 'curso_resena_01_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Adicional Lectura 01',
        'id' => 'adicional_lectura_obligatoria_01',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'id'            => 'curso_resena_01_material_apoyo',
        'name'          => 'Material de apoyo · Unidad 01',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir Link',
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Material de apoyo · Unidad 01 [ARCHIVOS]',
        'desc' => '',
        'id'   => 'curso_resena_01_material_apoyo_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Adicional Material Apoyo 01',
        'id' => 'adicional_material_apoyo_01',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'name' => 'Reseña Unidad 02',
        'id' => 'curso_resena_02',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'name' => 'Video Unidad 02',
        'desc' => '¡Ya no se ocupa este campo! El campo nuevo es: Video (IFRAME).',
        'id'   => 'curso_resena_02_video',
        'type' => 'oembed',
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Video (IFRAME)',
        'id'   => 'curso_resena_02_resena_video_iframe',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'sanitization_cb' => false,
        'column' => true
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Lectura oblitatoria · Unidad 02',
        'desc' => '',
        'id'   => 'curso_resena_02_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Adicional Lectura 02',
        'id' => 'adicional_lectura_obligatoria_02',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'id'            => 'curso_resena_02_material_apoyo',
        'name'          => 'Material de apoyo · Unidad 02',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir Link',
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Material de apoyo · Unidad 02 [ARCHIVOS]',
        'desc' => '',
        'id'   => 'curso_resena_02_material_apoyo_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Adicional Material Apoyo 02',
        'id' => 'adicional_material_apoyo_02',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'name' => 'Reseña Unidad 03',
        'id' => 'curso_resena_03',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'name' => 'Video Unidad 03',
        'desc' => '¡Ya no se ocupa este campo! El campo nuevo es: Video (IFRAME).',
        'id'   => 'curso_resena_03_video',
        'type' => 'oembed',
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Video (IFRAME)',
        'id'   => 'curso_resena_03_resena_video_iframe',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'sanitization_cb' => false,
        'column' => true
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Lectura oblitatoria · Unidad 03',
        'desc' => '',
        'id'   => 'curso_resena_03_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Adicional Lectura 03',
        'id' => 'adicional_lectura_obligatoria_03',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'id'            => 'curso_resena_03_material_apoyo',
        'name'          => 'Material de apoyo · Unidad 03',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir Link',
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Adicional Material Apoyo 03',
        'id' => 'adicional_material_apoyo_03',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'name' => 'Material de apoyo · Unidad 03 [ARCHIVOS]',
        'desc' => '',
        'id'   => 'curso_resena_03_material_apoyo_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Reseña Unidad 04',
        'id' => 'curso_resena_04',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'name' => 'Video Unidad 04',
        'desc' => '¡Ya no se ocupa este campo! El campo nuevo es: Video (IFRAME).',
        'id'   => 'curso_resena_04_video',
        'type' => 'oembed',
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Video (IFRAME)',
        'id'   => 'curso_resena_04_resena_video_iframe',
        'type'    => 'wysiwyg',
        'options' => array( 'textarea_rows' => 5, ),
        'sanitization_cb' => false,
        'column' => true
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Lectura oblitatoria · Unidad 04',
        'desc' => '',
        'id'   => 'curso_resena_04_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Adicional Lectura 04',
        'id' => 'adicional_lectura_obligatoria_04',
        'type' => 'textarea'
    ));

    $curso_resenas->add_field( array(
        'id'            => 'curso_resena_04_material_apoyo',
        'name'          => 'Material de apoyo · Unidad 04',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir Link',
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Material de apoyo · Unidad 04 [ARCHIVOS]',
        'desc' => '',
        'id'   => 'curso_resena_04_material_apoyo_docs',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Seleccionar Archivo(s)', // default: "Add or Upload Files"
            'remove_image_text' => 'Eliminar', // default: "Remove Image"
            'file_text' => 'Archivo(s)', // default: "File:"
            'file_download_text' => 'Descargar', // default: "Download"
            'remove_text' => 'Eliminar', // default: "Remove"
        ),
    ) );

    $curso_resenas->add_field( array(
        'name' => 'Adicional Material Apoyo 04',
        'id' => 'adicional_material_apoyo_04',
        'type' => 'textarea'
    ));

    $curso_preguntas_etapa_01 = new_cmb2_box( array(
        'title' => 'Preguntas/Alternativas · Unidad 01',
        'id'    => 'info_curso_preguntas_etapa_01',
        'object_types' => array( 'curso' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));
    
    $etapa_01 = $curso_preguntas_etapa_01->add_field( array(
        'id'                => 'preguntas_unidad_1',
        'type'              => 'group',
        'options'           => array(
            'group_title'   => 'Pregunta n°{#}',
            'add_button'    => 'Añadir nueva pregunta',
            'remove_button' => 'Eliminar pregunta',
            'sortable'      => true,
            'closed'        => true,
        )
    ) );
    
    $curso_preguntas_etapa_01->add_group_field( $etapa_01, array(
        'id'            => 'pregunta_unidad_1',
        'name'          => 'Pregunta',
        'type'          => 'textarea_small',
    ) );
    
    $curso_preguntas_etapa_01->add_group_field( $etapa_01, array(
        'id'            => 'alternativa_unidad_1',
        'name'          => 'Alternativa',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir nueva alternativa',
        ),
    ) );
    
    $curso_preguntas_etapa_01->add_group_field( $etapa_01, array(
        'id'            => 'alternativa_correcta_unidad_1',
        'desc'          => 'Ingresa textualmente la o las alternativas ingresadas arriba que sean las correctas.',
        'name'          => __('Alternativa Correcta', 'cgc-quiz'),
        'type'          => 'text',
        'repeatable'    => true,
        'options' => array(
            'add_row_text' => 'Añadir alternativa correcta',
        ),
    ) );

    /*
     * 
     * UNIDAD 02
     * * * * */

    $curso_preguntas_etapa_02 = new_cmb2_box( array(
        'title' => 'Preguntas/Alternativas · Unidad 02',
        'id'    => 'info_curso_preguntas_etapa_02',
        'object_types' => array( 'curso' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));
    
    // $group_field_id is the field id string, so in this case: $prefix . 'demo'
    $etapa_02 = $curso_preguntas_etapa_02->add_field( array(
        'id'                => 'preguntas_unidad_2',
        'type'              => 'group',
        'options'           => array(
            'group_title'   => 'Pregunta n°{#}',
            'add_button'    => 'Añadir nueva pregunta',
            'remove_button' => 'Eliminar pregunta',
            'sortable'      => true,
            'closed'        => true,
        )
    ) );
    
    $curso_preguntas_etapa_02->add_group_field( $etapa_02, array(
        'id'            => 'pregunta_unidad_2',
        'name'          => 'Pregunta',
        'type'          => 'textarea_small',
    ) );
    
    $curso_preguntas_etapa_02->add_group_field( $etapa_02, array(
        'id'            => 'alternativa_unidad_2',
        'name'          => 'Alternativa',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir nueva alternativa',
        ),
    ) );
    
    $curso_preguntas_etapa_02->add_group_field( $etapa_02, array(
        'id'            => 'alternativa_correcta_unidad_2',
        'desc'          => 'Ingresa textualmente la alternativa ingresada arriba que sea la correcta.',
        'name'          => __('Alternativa Correcta', 'cgc-quiz'),
        'type'          => 'text',
        'repeatable'    => true,
        'options' => array(
            'add_row_text' => 'Añadir alternativa correcta',
        ),
    ) );
     
    /*
     * 
     * UNIDAD 03
     * * * * */

    $curso_preguntas_etapa_03 = new_cmb2_box( array(
        'title' => 'Preguntas/Alternativas · Unidad 03',
        'id'    => 'info_curso_preguntas_etapa_03',
        'object_types' => array( 'curso' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));
    
    // $group_field_id is the field id string, so in this case: $prefix . 'demo'
    $etapa_03 = $curso_preguntas_etapa_03->add_field( array(
        'id'                => 'preguntas_unidad_3',
        'type'              => 'group',
        'options'           => array(
            'group_title'   => 'Pregunta n°{#}',
            'add_button'    => 'Añadir nueva pregunta',
            'remove_button' => 'Eliminar pregunta',
            'sortable'      => true,
            'closed'        => true,
        )
    ) );
    
    $curso_preguntas_etapa_03->add_group_field( $etapa_03, array(
        'id'            => 'pregunta_unidad_3',
        'name'          => 'Pregunta',
        'type'          => 'textarea_small',
    ) );
    
    $curso_preguntas_etapa_03->add_group_field( $etapa_03, array(
        'id'            => 'alternativa_unidad_3',
        'name'          => 'Alternativa',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir nueva alternativa',
        ),
    ) );
    
    $curso_preguntas_etapa_03->add_group_field( $etapa_03, array(
        'id'            => 'alternativa_correcta_unidad_3',
        'desc'          => 'Ingresa textualmente la alternativa ingresada arriba que sea la correcta.',
        'name'          => __('Alternativa Correcta', 'cgc-quiz'),
        'type'          => 'text',
        'repeatable'    => true,
        'options' => array(
            'add_row_text' => 'Añadir alternativa correcta',
        ),
    ) );

    /*
     * 
     * UNIDAD 04
     * * * * */

    $curso_preguntas_etapa_04 = new_cmb2_box( array(
        'title' => 'Preguntas/Alternativas · Unidad 04',
        'id'    => 'info_curso_preguntas_etapa_04',
        'object_types' => array( 'curso' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));
    
    // $group_field_id is the field id string, so in this case: $prefix . 'demo'
    $etapa_04 = $curso_preguntas_etapa_04->add_field( array(
        'id'                => 'preguntas_unidad_4',
        'type'              => 'group',
        'options'           => array(
            'group_title'   => 'Pregunta n°{#}',
            'add_button'    => 'Añadir nueva pregunta',
            'remove_button' => 'Eliminar pregunta',
            'sortable'      => true,
            'closed'        => true,
        )
    ) );
    
    $curso_preguntas_etapa_04->add_group_field( $etapa_04, array(
        'id'            => 'pregunta_unidad_4',
        'name'          => 'Pregunta',
        'type'          => 'textarea_small',
    ) );
    
    $curso_preguntas_etapa_04->add_group_field( $etapa_04, array(
        'id'            => 'alternativa_unidad_4',
        'name'          => 'Alternativa',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir nueva alternativa',
        ),
    ) );
    
    $curso_preguntas_etapa_04->add_group_field( $etapa_04, array(
        'id'            => 'alternativa_correcta_unidad_4',
        'desc'          => 'Ingresa textualmente la alternativa ingresada arriba que sea la correcta.',
        'name'          => __('Alternativa Correcta', 'cgc-quiz'),
        'type'          => 'text',
        'repeatable'    => true,
        'options' => array(
            'add_row_text' => 'Añadir alternativa correcta',
        ),
    ) );

}
?>