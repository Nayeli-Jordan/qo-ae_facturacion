<?php


// TAXONOMIES ////////////////////////////////////////////////////////////////////////
add_action( 'init', 'custom_taxonomies_callback', 0 );
function custom_taxonomies_callback(){

	if( ! taxonomy_exists('tax_ejecutivo')){

		$labels = array(
			'name'              => 'Ejecutivo',
			'singular_name'     => 'Ejecutivo',
			'search_items'      => 'Buscar',
			'all_items'         => 'Todos',
			'edit_item'         => 'Editar Ejecutivo',
			'update_item'       => 'Actualizar Ejecutivo',
			'add_new_item'      => 'Nueva Ejecutivo',
			'menu_name'         => 'Ejecutivo'
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'tax_ejecutivo' ),
		);

		register_taxonomy( 'tax_ejecutivo', 'ae_alertas', $args );
	}

	wp_insert_term( 'Maria del Carmen Cervantes', 'tax_ejecutivo', array( 'description'=> 'mccervantes@altoempleo.com.mx') );
	wp_insert_term( 'Elliott Silva', 'tax_ejecutivo', array( 'description'=> 'esilva@altoempleo.com.mx') );
	wp_insert_term( 'Rodrigo Sánchez', 'tax_ejecutivo', array( 'description'=> 'rsanchez@altoempleo.com.mx') );

}