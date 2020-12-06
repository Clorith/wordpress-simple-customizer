<?php
	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		die();
	}

	//  Remove options added via the Options API
	delete_option( 'simple_customize' );
	delete_option( 'simple_customize_settings' );
	delete_option( 'simple_customize_google_fonts' );
	delete_option( 'simple-customize-version' );

	//  Clear out any taxonomy terms that were added
	$terms = get_terms(
		'simple-customize',
		array(
			'hide_empty' => false
		)
	);
	foreach( $terms AS $term )
	{
		wp_delete_term( $term->term_id, 'simple-customize' );
	}

	//  Remove customize post entries and associated meta values
	$entries = get_posts( array(
		'posts_per_page' => -1,
		'post_type'      => 'simple-customize'
	) );
	foreach( $entries AS $entry )
	{
		wp_delete_post( $entry->ID );
	}

	//  Remove font options also stored with the plugin
	$fonts = get_posts( array(
		'posts_per_page' => -1,
		'post_type'      => 's-c-font',
		'post_status'    => array( 'draft', 'publish' )
	) );

	foreach( $fonts AS $font )
	{
		wp_delete_post( $font->ID );
	}