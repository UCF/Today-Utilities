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
	if ( $object['featured_media'] ) {
		$image = wp_get_attachment_image_src( $object['featured_media'] );
	} else {
		$attachments = get_attached_media( 'image', $object['id'] );
		if ( $attachments ) {
			foreach( $attachments as $key=>$val ) {
				if ( $image = wp_get_attachment_image_src( $key ) ) {
					break;
				}
			}
		}
	}
	return is_array( $image ) ? $image[0] : null;
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
