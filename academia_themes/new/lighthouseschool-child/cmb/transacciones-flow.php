<?php

add_action('init', 'transacciones_register');

function transacciones_register() {

	$labels = array(
		'name'               => _x('Transacciones', 'post type general name'),
		'singular_name'      => _x('Transacción', 'post type singular name'),
		'add_new'            => _x('Nuevo Transacción', 'Transacción item'),
		'add_new_item'       => __('Añadir Nuevo Transacción'),
		'edit_item'          => __('Editar Transacción'),
		'new_item'           => __('Nueva Transacción'),
		'view_item'          => __('Ver Transacción'),
		'search_items'       => __('Buscar Transacción'),
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
		'menu_icon'          => 'dashicons-cart',
		'rewrite'            => true,
		'capability_type'    => 'post',
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title')
	  ); 

	register_post_type( 'transacciones' , $args );
}

add_action( 'cmb2_init', 'transacciones_CustomFields' );

function transacciones_CustomFields(){

    $transacciones = new_cmb2_box( array(
        'title' => 'Información Adicional de la Transacción',
        'id'    => 'trx_info',
        'object_types' => array( 'transacciones' ),
        'context'      => 'normal', // side
        'show_names'    => true
    ) );

    $transacciones->add_field( array(
        'name'    => 'ID transacción',
        'id'      => 'trx_id',
        'type'    => 'text',
        'column' => true
    ));
    
    $transacciones->add_field( array(
        'name'    => 'OC transacción',
        'id'      => 'trx_oc',
        'type'    => 'text',
        'column' => true
    ));

    $transacciones->add_field( array(
        'name'    => 'Nombre',
        'id'      => 'trx_nombre',
        'type'    => 'text',
        'column' => true
    ));

    $transacciones->add_field( array(
        'name'    => 'Email',
        'id'      => 'trx_email',
        'desc'    => 'Si el tipo de pago no es efectivo ni transferencia electrónica y el usuario si existe en Wordpress se debe ingresar el correo literalmente igual',
        'type'    => 'text',
        'column' => true
    ));

    $transacciones->add_field( array(
        'name'    => 'Monto',
        'id'      => 'trx_monto',
        'type'    => 'text',
        'column' => true
    ));

    $transacciones->add_field( array(
        'name'    => 'Fecha',
        'id'      => 'trx_fecha',
        'type'    => 'text',
        'column' => true
    ));

    $transacciones->add_field( array(
        'name'    => 'Hora',
        'id'      => 'trx_hora',
        'type'    => 'text',
        'column' => true
    ));

    $transacciones->add_field( array(
        'name'             => 'Tipo',
        'id'               => 'trx_tipo',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => array(
            'webpay'         => __( 'Webpay Plus', 'cmb2' ),
            'transferencia'  => __( 'Transferencia Electrónica', 'cmb2' ),
            'efectivo'       => __( 'Efectivo', 'cmb2' ),
        ),
        'column' => true
    ) );

}
?>