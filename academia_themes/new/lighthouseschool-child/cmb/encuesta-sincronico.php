<?php

add_action('init', 'encuesta_sincronico_curso_register');

function encuesta_sincronico_curso_register() {

	$labels = array(
		'name'               => _x('Encuesta Sincrónico', 'post type general name'),
		'singular_name'      => _x('Encuesta Sincrónico', 'post type singular name'),
		'add_new'            => _x('Nueva pregunta encuesta sincrónico', 'Encuesta Sincrónico Curso item'),
		'add_new_item'       => __('Añadir nueva pregunta par encuesta sincrónico'),
		'edit_item'          => __('Editar pregunta par encuesta sincrónico'),
		'new_item'           => __('Nueva pregunta par encuesta sincrónico'),
		'view_item'          => __('Ver pregunta par encuesta sincrónico'),
		'search_items'       => __('Buscar pregunta par encuesta sincrónico'),
		'not_found'          => __('Nada Encontrado'),
		'not_found_in_trash' => __('Nada Encontrado in basurero'),
		'parent_item_colon'  => ''
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'query_var'          => true,
		'menu_icon'          => 'dashicons-forms',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title'),
	  ); 

	register_post_type( 'encuesta_sincronico' , $args );
}

register_taxonomy("categoria_encuesta_sincronico", array("encuesta_sincronico"), array("hierarchical" => true, "label" => "Categorías Encuesta Sincrónica", "singular_label" => "Categorías Encuesta Sincrónica", "rewrite" => true, 'hierarchical' => true, 'show_admin_column' => true));

add_action( 'cmb2_init', 'encuesta_sincronico_curso_CustomFields' );

function encuesta_sincronico_curso_CustomFields(){

    $encuesta_sincronico_curso = new_cmb2_box( array(
        'title' => 'Información Adicional del Encuesta Sincrónico Curso',
        'id'    => 'encuesta_sincronico_info',
        'object_types' => array( 'encuesta_sincronico' ),
        'context'      => 'normal', // side
        'show_names'    => true,
        'default'          => 'custom',
    ) );

    $encuesta_sincronico_curso->add_field( array(
        'name'    => 'Campo obligatorio',
        'desc'    => 'Indicar si es obligatorio, por defecto es obligatorio',
        'id'      => 'encuesta_sincronico_pregunta_obligatoria',
        'type'    => 'select',
        'default' => 'si',
        'options' => array(
            'si' => __( 'Si', 'cmb2' ),
            'no' => __( 'No', 'cmb2' ),
        ),
    ));

    $encuesta_sincronico_curso->add_field( array(
        'name'    => 'Tipo de campo',
        'desc'    => 'En caso que no posea alternativas, indicar que tipo de campo es, por defecto es un campo normal de texto',
        'id'      => 'encuesta_sincronico_pregunta_tipo_campo',
        'type'    => 'select',
        'default' => 'input_text',
        'options' => array(
            'input' => __( 'Input', 'cmb2' ),
            'textarea' => __( 'Textarea', 'cmb2' ),
        ),
    ));
    
    $group_field_id = $encuesta_sincronico_curso->add_field( array(
        'id'          => 'encuesta_sincronico_alternativas',
        'type'        => 'group',
        'description' => __( '[ SI LA PREGUNTA NO CUENTA CON ALTERNATIVAS NO USAR EL MODULO QUE ESTÁ ABAJO, SOLO INDICAR LA PREGUNTA EN EL CAMPO DE ARRIBA Y ASOCIAR CON EL CURSO ]', 'cmb2' ),
        'options'     => array(
            'group_title'   => __( 'Alternativa {#}', 'cmb2' ),
            'add_button'    => __( 'Añadir nueva alternativa', 'cmb2' ),
            'remove_button' => __( 'Eliminar alternativa', 'cmb2' ),
            'sortable'      => true,
            'closed'        => true,
        ),
        'column' => true
    ) );
        
    $encuesta_sincronico_curso->add_group_field( $group_field_id, array(
        'name' => 'Alternativa: ',
        'desc' => 'Ingresa el valor de la alernativa',
        'id'   => 'encuesta_sincronico_alternativa',
        'type' => 'textarea_small',
    ) );

    $encuesta_sincronico_curso->add_group_field( $group_field_id, array(
        'name'    => 'Requiere texto: ',
        'desc'    => 'Indicar si esta alternativa requiere texto',
        'id'      => 'encuesta_sincronico_alternativa_texto',
        'type'    => 'select',
        'default'          => 'no',
        'options'          => array(
            'si' => __( 'Si', 'cmb2' ),
            'no' => __( 'No', 'cmb2' ),
        ),
    ));

}
?>