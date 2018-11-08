<?php


// CUSTOM PAGES //////////////////////////////////////////////////////////////////////

add_action('init', function(){

	if( ! get_page_by_path('send-alerts') ){
		$page = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title'  => 'Enviar Alertas',
			'post_name'   => 'send-alerts',
			'post_type'   => 'page'
		);
		wp_insert_post( $page, true );
	}
	
});