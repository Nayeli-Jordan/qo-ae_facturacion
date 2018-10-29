<?php

// CUSTOM POST TYPES /////////////////////////////////////////////////////////////////


add_action('init', function(){
	
	$labels = array(
		'name'          => 'Alerta Facturación',
		'singular_name' => 'Alerta Facturación',
		'add_new'       => 'Nueva Alerta',
		'add_new_item'  => 'Nueva Alerta',
		'edit_item'     => 'Editar Alerta',
		'new_item'      => 'Nueva Alerta',
		'all_items'     => 'Todo',
		'view_item'     => 'Ver Alerta',
		'search_items'  => 'Buscar Alerta',
		'not_found'     => 'No hay Alerta.',
		'menu_name'     => 'Alerta Facturación'
	);
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'ae_alertas' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'supports'           => array( 'title' ),
		'menu_icon' 		 => 'dashicons-calendar-alt'
	);
	register_post_type( 'ae_alertas', $args );

});