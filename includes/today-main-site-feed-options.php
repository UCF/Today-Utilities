<?php
/**
 * Provides functions for the Main Site Story feed
 */

function tu_add_main_site_feed_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' 	  => 'Main Site News Feed',
			'post_id'         => 'main_site_news_feed',
			'menu_title'	  => 'EDU News Feed',
			'menu_slug' 	  => 'main-site-news-feed',
			'capability'	  => 'administrator',
			'icon_url'        => 'dashicons-images-alt2',
			'redirect'        => false,
			'updated_message' => 'Main Site News Feed Updated'
		) );
	}
}

function tu_post_get_thumbnail( $object, $field_name, $request ) {
	$image = null;

	if ( function_exists( 'today_get_thumbnail_url' ) ) {
		$image = today_get_thumbnail_url( $object['id'], 'medium', false ) ?: null;
	}

	return $image;
}

function add_image_to_post_feed() {
	register_rest_field( 'post', 'thumbnail',
		array(
			'get_callback' => 'tu_post_get_thumbnail',
			'schema'       => null,
		)
	);
}

add_action( 'rest_api_init', 'add_image_to_post_feed' );
