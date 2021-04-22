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
 * REST API callback function that returns a post's
 * primary category ID (Yoast plugin feature) using logic from
 * the Today Child Theme.
 *
 * @since 1.0.10
 * @author Jo Dickson
 * @param array $object Array of single feed object data
 * @param string $field_name Name of the current field (in this case, 'primary_category')
 * @param object $request WP_REST_Request object; contains details about the current request
 * @return mixed WP_Term object, or null on failure
 */
function tu_post_get_primary_category( $object, $field_name, $request ) {
	$primary = null;

	if ( function_exists( 'today_get_primary_category' ) ) {
		$post = get_post( $object['id'] );
		if ( $cat = today_get_primary_category( $post ) ) {
			$primary = $cat->term_id;
		}
	}

	return $primary;
}


/**
 * Registers the custom "primary_category" field in REST API
 * results for posts.
 *
 * @since 1.0.10
 * @author Jo Dickson
 */
function tu_add_primary_cat_to_post_feed() {
	register_rest_field( 'post', 'primary_category',
		array(
			'get_callback' => 'tu_post_get_primary_category',
			'schema'       => null,
		)
	);
}

add_action( 'rest_api_init', 'tu_add_primary_cat_to_post_feed' );


/**
 * REST API callback function that returns a post's
 * primary tag ID (ACF plugin feature) using logic from
 * the Today Child Theme.
 *
 * @since 1.0.10
 * @author Jo Dickson
 * @param array $object Array of single feed object data
 * @param string $field_name Name of the current field (in this case, 'primary_tag')
 * @param object $request WP_REST_Request object; contains details about the current request
 * @return mixed WP_Term object, or null on failure
 */
function tu_post_get_primary_tag( $object, $field_name, $request ) {
	$primary = null;

	if ( function_exists( 'today_get_primary_tag' ) ) {
		$post = get_post( $object['id'] );
		if ( $tag = today_get_primary_tag( $post ) ) {
			$primary = $tag->term_id;
		}
	}

	return $primary;
}


/**
 * Registers the custom "primary_tag" field in REST API
 * results for posts.
 *
 * @since 1.0.10
 * @author Jo Dickson
 */
function tu_add_primary_tag_to_post_feed() {
	register_rest_field( 'post', 'primary_tag',
		array(
			'get_callback' => 'tu_post_get_primary_tag',
			'schema'       => null,
		)
	);
}

add_action( 'rest_api_init', 'tu_add_primary_tag_to_post_feed' );


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


/**
 * Adds support for "category_slugs" and "tag_slugs" params
 * in REST API results for posts.
 *
 * Ported over from Today-Bootstrap
 *
 * @since 1.0.3
 */
function tu_add_tax_query_to_posts_endpoint( $args, $request ) {
	$params = $request->get_params();

	$tax_query = array();

	if ( isset( $params['category_slugs'] ) ) {
		$tax_query[] =
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $params['category_slugs']
			);
	}

	if ( isset( $params['tag_slugs'] ) ) {
		$tax_query[] =
			array(
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => $params['tag_slugs']
			);
	}

	if ( count( $tax_query ) > 0 ) {
		$args['tax_query'] = $tax_query;
	}

	return $args;
}

add_action( 'rest_post_query', 'tu_add_tax_query_to_posts_endpoint', 2, 10 );


/**
 * Adds a feed to support the UCF Mobile App concise
 *
 * @since 1.1.2
 * @author RJ Bruneel
 */
function add_concise_feed() {
    add_feed( 'concise', 'render_concise_feed' );
}
add_action( 'init', 'add_concise_feed' );
 
function render_concise_feed() {
    header( 'Content-Type: application/rss+xml' );
	require_once TU_PLUGIN_DIR . 'template-parts/rss/concise.php';
}