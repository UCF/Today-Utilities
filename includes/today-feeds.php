<?php
/**
 * Functions that provide baseline overrides to available
 * WordPress REST API feeds
 **/

/**
 * REST API callback function that returns a thumbnail URL using
 * logic from the Today Child Theme
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $object Array of single feed object data
 * @param string $field_name Name of the current field (in this case, 'thumbnail')
 * @param object $request WP_REST_Request object; contains details about the current request
 * @return mixed Image URL string or null
 */
function tu_post_get_thumbnail( $object, $field_name, $request ) {
	$image = null;

	if ( function_exists( 'today_get_thumbnail_url' ) ) {
		$image = today_get_thumbnail_url( $object['id'], 'medium', false ) ?: null;
	}

	return $image;
}


/**
 * Registers the custom "thumbnail" field in REST API results for posts.
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
function tu_add_image_to_post_feed() {
	register_rest_field( 'post', 'thumbnail',
		array(
			'get_callback' => 'tu_post_get_thumbnail',
			'schema'       => null,
		)
	);
}

add_action( 'rest_api_init', 'tu_add_image_to_post_feed' );


/**
 * REST API callback function that returns a post's author
 * name, using logic that mimics the Today Child Theme
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $object Array of single feed object data
 * @param string $field_name Name of the current field (in this case, 'author_byline')
 * @param object $request WP_REST_Request object; contains details about the current request
 * @return mixed Image URL string or null
 */
function tu_post_get_post_author_name( $object, $field_name, $request ) {
	$author_name = get_the_author_meta( 'display_name', $object['author'] );

	$author_byline = get_field( 'post_author_byline', $object['id'] );
	if ( $author_byline ) {
		$author_name = $author_byline;
	}

	return $author_name;
}


/**
 * Registers the custom "author_byline" field in REST API results for posts.
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
function tu_add_author_byline_to_post_feed() {
	register_rest_field( 'post', 'author_byline',
		array(
			'get_callback' => 'tu_post_get_post_author_name',
			'schema'       => null,
		)
	);
}

add_action( 'rest_api_init', 'tu_add_author_byline_to_post_feed' );
