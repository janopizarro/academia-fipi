<?php

add_action('init', 'cursos_presenciales_register');

function cursos_presenciales_register() {

	$labels = array(
		'name'               => _x('Cursos Presenciales', 'post type general name'),
		'singular_name'      => _x('Curso Presencial', 'post type singular name'),
		'add_new'            => _x('Nuevo Curso Presencial', 'Curso Presencial item'),
		'add_new_item'       => __('Añadir Nuevo Curso Presencial'),
		'edit_item'          => __('Editar Curso Presencial'),
		'new_item'           => __('Nuevo Curso Presencial'),
		'view_item'          => __('Ver Curso Presencial'),
		'search_items'       => __('Buscar Curso Presencial'),
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
		'supports'           => array('title')
	  ); 

	register_post_type( 'cursos-presenciales' , $args );
}

// register_taxonomy("preguntas", array("cursos-presenciales"), array("hierarchical" => true, "label" => "Preguntas", "singular_label" => "Preguntas", "rewrite" => true, 'hierarchical' => true));

add_action( 'cmb2_init', 'cursos_presenciales_CustomFields' );

function cursos_presenciales_CustomFields(){

    $cursos_presenciales = new_cmb2_box( array(
        'title' => 'Información Adicional Del Curso Presencial',
        'id'    => 'info_curso_presencial',
        'object_types' => array( 'cursos-presenciales' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));

    $cursos_presenciales->add_field( array(
        'name' => 'Monto',
        'desc' => 'Ingresar sin puntos ni $',
        'id'   => 'curso_presencial_monto',
        'type' => 'text'
    ));

    $cursos_presenciales->add_field( array(
        'name'    => 'Video Intro',
        'desc'    => 'Video introductorio después de comprar el curso',
        'id'      => 'curso_presencial_intro_video',
        'type'    => 'wysiwyg',
        'options' => array(),
        'sanitization_cb' => false,
    ) );
    
    $cursos_presenciales->add_field( array(
        'name'    => 'Imagen Intro',
        'id'      => 'curso_presencial_imagen_intro',
        'type'    => 'file',
        'options' => array('url' => false),
        'text'    => array('add_upload_file_text' => 'Añadir Imagen'),
        'preview_size' => 'large', 
    ));

    $cursos_presenciales->add_field( array(
        'name'    => 'Imagen Grande',
        'id'      => 'curso_presencial_imagen_grande',
        'type'    => 'file',
        'options' => array('url' => false),
        'text'    => array('add_upload_file_text' => 'Añadir Imagen'),
        'preview_size' => 'large', 
    ));

    $cursos_presenciales_resenas = new_cmb2_box( array(
        'title' => 'Reseñas de etapas',
        'id'    => 'info_curso_presencial_resenas',
        'object_types' => array( 'cursos-presenciales' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));

    $cursos_presenciales_resenas->add_field( array(
        'name' => 'Reseña Etapa 01',
        'id' => 'curso_presencial_resena_01',
        'type' => 'textarea'
    ));

    $cursos_presenciales_resenas->add_field( array(
        'name' => 'Reseña Etapa 02',
        'id' => 'curso_presencial_resena_02',
        'type' => 'textarea'
    ));

    $cursos_presenciales_resenas->add_field( array(
        'name' => 'Reseña Etapa 03',
        'id' => 'curso_presencial_resena_03',
        'type' => 'textarea'
    ));

    $cursos_presenciales_resenas->add_field( array(
        'name' => 'Reseña Etapa 04',
        'id' => 'curso_presencial_resena_04',
        'type' => 'textarea'
    ));

    $cursos_presenciales_preguntas_etapa_01 = new_cmb2_box( array(
        'title' => 'Preguntas/Alternativas · Etapa 01',
        'id'    => 'info_curso_presencial_preguntas_etapa_01',
        'object_types' => array( 'cursos-presenciales' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));
    
    // $group_field_id is the field id string, so in this case: $prefix . 'demo'
    $etapa_01 = $cursos_presenciales_preguntas_etapa_01->add_field( array(
        'id'                => 'presencial_preguntas',
        'type'              => 'group',
        'options'           => array(
            'group_title'   => 'Pregunta n°{#}',
            'add_button'    => 'Añadir nueva pregunta',
            'remove_button' => 'Eliminar pregunta',
            'sortable'      => true,
            'closed'        => true,
        )
    ) );
    
    $cursos_presenciales_preguntas_etapa_01->add_group_field( $etapa_01, array(
        'id'            => 'presencial_pregunta',
        'name'          => 'Pregunta',
        'type'          => 'textarea_small',
    ) );
    
    $cursos_presenciales_preguntas_etapa_01->add_group_field( $etapa_01, array(
        'id'            => 'presencial_alternativa',
        'name'          => 'Alternativa',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir nueva alternativa',
        ),
    ) );
    
    $cursos_presenciales_preguntas_etapa_01->add_group_field( $etapa_01, array(
        'id'            => 'presencial_alternativa_correcta',
        'desc'          => 'Ingresa textualmente la alternativa ingresada arriba que sea la correcta.',
        'name'          => __('Alternativa Correcta', 'cgc-quiz'),
        'type'          => 'text'
    ) );

    /*
     * 
     * UNIDAD 02
     * * * * */

    $cursos_presenciales_preguntas_etapa_02 = new_cmb2_box( array(
        'title' => 'Preguntas/Alternativas · Etapa 02',
        'id'    => 'info_curso_presencial_preguntas_etapa_02',
        'object_types' => array( 'cursos-presenciales' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));
    
    // $group_field_id is the field id string, so in this case: $prefix . 'demo'
    $etapa_02 = $cursos_presenciales_preguntas_etapa_02->add_field( array(
        'id'                => 'presencial_preguntas_unidad_2',
        'type'              => 'group',
        'options'           => array(
            'group_title'   => 'Pregunta n°{#}',
            'add_button'    => 'Añadir nueva pregunta',
            'remove_button' => 'Eliminar pregunta',
            'sortable'      => true,
            'closed'        => true,
        )
    ) );
    
    $cursos_presenciales_preguntas_etapa_02->add_group_field( $etapa_02, array(
        'id'            => 'presencial_pregunta_unidad_2',
        'name'          => 'Pregunta',
        'type'          => 'textarea_small',
    ) );
    
    $cursos_presenciales_preguntas_etapa_02->add_group_field( $etapa_02, array(
        'id'            => 'presencial_alternativa_unidad_2',
        'name'          => 'Alternativa',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir nueva alternativa',
        ),
    ) );
    
    $cursos_presenciales_preguntas_etapa_02->add_group_field( $etapa_02, array(
        'id'            => 'presencial_alternativa_correcta_unidad_2',
        'desc'          => 'Ingresa textualmente la alternativa ingresada arriba que sea la correcta.',
        'name'          => __('Alternativa Correcta', 'cgc-quiz'),
        'type'          => 'text'
    ) );
     
    /*
     * 
     * UNIDAD 03
     * * * * */

    $cursos_presenciales_preguntas_etapa_03 = new_cmb2_box( array(
        'title' => 'Preguntas/Alternativas · Etapa 03',
        'id'    => 'info_curso_presencial_preguntas_etapa_03',
        'object_types' => array( 'cursos-presenciales' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));
    
    // $group_field_id is the field id string, so in this case: $prefix . 'demo'
    $etapa_03 = $cursos_presenciales_preguntas_etapa_03->add_field( array(
        'id'                => 'presencial_preguntas_unidad_3',
        'type'              => 'group',
        'options'           => array(
            'group_title'   => 'Pregunta n°{#}',
            'add_button'    => 'Añadir nueva pregunta',
            'remove_button' => 'Eliminar pregunta',
            'sortable'      => true,
            'closed'        => true,
        )
    ) );
    
    $cursos_presenciales_preguntas_etapa_03->add_group_field( $etapa_03, array(
        'id'            => 'presencial_pregunta_unidad_3',
        'name'          => 'Pregunta',
        'type'          => 'textarea_small',
    ) );
    
    $cursos_presenciales_preguntas_etapa_03->add_group_field( $etapa_03, array(
        'id'            => 'presencial_alternativa_unidad_3',
        'name'          => 'Alternativa',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir nueva alternativa',
        ),
    ) );
    
    $cursos_presenciales_preguntas_etapa_03->add_group_field( $etapa_03, array(
        'id'            => 'presencial_alternativa_correcta_unidad_3',
        'desc'          => 'Ingresa textualmente la alternativa ingresada arriba que sea la correcta.',
        'name'          => __('Alternativa Correcta', 'cgc-quiz'),
        'type'          => 'text'
    ) );

    /*
     * 
     * UNIDAD 04
     * * * * */

    $cursos_presenciales_preguntas_etapa_04 = new_cmb2_box( array(
        'title' => 'Preguntas/Alternativas · Etapa 04',
        'id'    => 'info_curso_presencial_preguntas_etapa_04',
        'object_types' => array( 'cursos-presenciales' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ));
    
    // $group_field_id is the field id string, so in this case: $prefix . 'demo'
    $etapa_04 = $cursos_presenciales_preguntas_etapa_04->add_field( array(
        'id'                => 'presencial_preguntas_unidad_4',
        'type'              => 'group',
        'options'           => array(
            'group_title'   => 'Pregunta n°{#}',
            'add_button'    => 'Añadir nueva pregunta',
            'remove_button' => 'Eliminar pregunta',
            'sortable'      => true,
            'closed'        => true,
        )
    ) );
    
    $cursos_presenciales_preguntas_etapa_04->add_group_field( $etapa_04, array(
        'id'            => 'presencial_pregunta_unidad_4',
        'name'          => 'Pregunta',
        'type'          => 'textarea_small',
    ) );
    
    $cursos_presenciales_preguntas_etapa_04->add_group_field( $etapa_04, array(
        'id'            => 'presencial_alternativa_unidad_4',
        'name'          => 'Alternativa',
        'type'          => 'text',
        'sortable'      => true,
        'repeatable'     => true,
        'repeatable_max' => 10,
        'options' => array(
            'add_row_text' => 'Añadir nueva alternativa',
        ),
    ) );
    
    $cursos_presenciales_preguntas_etapa_04->add_group_field( $etapa_04, array(
        'id'            => 'presencial_alternativa_correcta_unidad_4',
        'desc'          => 'Ingresa textualmente la alternativa ingresada arriba que sea la correcta.',
        'name'          => __('Alternativa Correcta', 'cgc-quiz'),
        'type'          => 'text'
    ) );

}
?>