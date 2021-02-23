<?php
/**
 * Overrides/customizations to available post types
 */

/**
 * Modifies registration args for the Statement post type.
 *
 * @since 1.1.0
 * @author Jo Dickson
 * @param array $args
 * @param string $post_type
 * @return array
 */
function tu_statement_registration_settings( $args, $post_type ) {
	if ( $post_type !== 'ucf_statement' ) return $args;

	// Remove author support on all post types that support it:
	if ( $key = array_search( 'author', $args['supports'] ) ) {
		unset( $args['supports'][$key] );
	}

	return $args;
}

add_filter( 'register_post_type_args', 'tu_statement_registration_settings', 10, 2 );
