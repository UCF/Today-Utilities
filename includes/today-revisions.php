<?php
/**
 * Handles revision logic
 */
if ( ! function_exists( 'today_revision_number' ) ) {
	function today_revision_number( $num, $post ) {
		if ( $post->post_type === 'post' ) {
			$num = 10;
		}

		return $num;
	}

	add_filter( 'wp_revisions_to_keep', 'today_revision_number', 10, 2 );
}
