<?php 
function home_options_metabox() {

	$main_options = new_cmb2_box( array(
		'id'           => 'home_options_page',
		'title'        => esc_html__( 'Ajustes Sitio', 'cmb2' ),
		'object_types' => array( 'options-page' ),
		'option_key'  => 'home_options', 
		// 'parent_slug' => 'tools.php',

	) );

	$main_options->add_field( array(
		'name'    => esc_html__( 'Título Bienvenida', 'cmb2' ),
		'id'      => 'titulo_bienvenida',
		'type'    => 'text',
	) );
	
	$main_options->add_field( array(
		'name'    => esc_html__( 'Bajada Bienvenida', 'cmb2' ),
		'id'      => 'bajada_bienvenida',
		'type'    => 'wysiwyg',
	    'options' => array(),
	) );

	$main_options->add_field( array(
		'name'    => 'Logo Header',
		'id'      => 'home_logo_header',
		'type'    => 'file',
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Seleccionar' // Change upload button text. Default: "Add or Upload File"
		),
		'query_args' => array(
			'type' => array(
				'image/gif',
				'image/jpeg',
				'image/png',
			),
		),
		'preview_size' => 'large', // Image size to use when previewing in the admin.
	) );

	$main_options->add_field( array(
		'name'    => esc_html__( 'Link Facebook', 'cmb2' ),
		'id'      => 'link_facebook',
		'type'    => 'text',
	) );

	$main_options->add_field( array(
		'name'    => esc_html__( 'Link Instagram', 'cmb2' ),
		'id'      => 'link_instagram',
		'type'    => 'text',
	) );

	$main_options->add_field( array(
		'name'    => esc_html__( 'Correo Administrador', 'cmb2' ),
		'desc'    => 'A este correo llegará todo movimiento relacionado con la academia.',
		'id'      => 'correo_administrador',
		'type'    => 'text',
	) );


	// $main_options->add_field( array(
	// 	'name'    => esc_html__( 'Detalle Form Suscripción', 'cmb2' ),
	// 	'id'      => 'footer_detalle_suscripcion',
	// 	'type'    => 'textarea',
	// ) );

	// $main_options->add_field( array(
	// 	'name'    => esc_html__( 'Legal Footer', 'cmb2' ),
	// 	'id'      => 'home_legal_footer',
	// 	'type'    => 'wysiwyg',
	// ) );

	// $main_options->add_field( array(
	// 	'name'    => esc_html__( 'Link Linkedin', 'cmb2' ),
	// 	'id'      => 'link_linkedin',
	// 	'type'    => 'textarea',
	// ) );

	// $main_options->add_field( array(
	// 	'name'    => esc_html__( 'Link Instagram', 'cmb2' ),
	// 	'id'      => 'link_instagram',
	// 	'type'    => 'textarea',
	// ) );

}
add_action( 'cmb2_admin_init', 'home_options_metabox' );
?>
