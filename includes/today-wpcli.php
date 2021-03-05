<?php
/**
 * Various commands for managing Today posts and their data
 */

class Today_Embed_Commands extends WP_CLI_Command {
	/**
	 * Removes cache data from the database for embeds that
	 * did not return a successful response.
	 *
	 * ## EXAMPLES
	 *
	 * $ wp today embeds flush
	 *
	 * @when after_wp_load
	 */
	public function flush( $args ) {
		global $wpdb;

		// Retrieve embed hashes and their corresponding post IDs
		// for embeds with bad cache data.
		//
		// NOTE: hashes should be mapped to their corresponding
		// post IDs, and not assumed to be completely unique on their
		// own; identical hashes with valid embed data could exist for
		// other posts.
		$rows = $wpdb->get_results( $wpdb->prepare(
			"SELECT DISTINCT
			SUBSTR(meta_key, -32) AS embed_hash,
			post_id
			FROM $wpdb->postmeta
			WHERE meta_key LIKE '_oembed_%%'
			AND meta_key NOT LIKE '_oembed_time_%%'
			AND meta_value = %s",
			array(
				'{{unknown}}'
			)
		) ) ?: array();

		$result_count = count( $rows );
		$success_count = 0;

		// Perform a DELETE for each row of retrieved data that clears
		// corresponding _oembed_HASH and _oembed_time_HASH metadata:
		if ( $rows ) {
			foreach ( $rows as $row ) {
				$has_error = false;

				try {
					$post_id            = intval( $row->post_id );
					$embed_key          = "_oembed_$row->embed_hash";
					$embed_time_key     = "_oembed_time_$row->embed_hash";

					// Delete both the embed data itself, and its cache
					// time data.
					//
					// We only care about whether or not the actual embed
					// meta got deleted or not; the embed time data may or
					// may not exist, so we don't check if it actually got
					// deleted during this step:
					$embed_deleted      = delete_post_meta( $post_id, $embed_key );
					$embed_time_deleted = delete_post_meta( $post_id, $embed_time_key );

					if ( ! $embed_deleted ) {
						$has_error = true;
						WP_CLI::warning( "Could not delete metadata for post with ID $post_id, meta key $embed_key" );
					}

					if ( ! $has_error ) {
						$success_count++;
					}
				}
				catch ( Exception $e ) {
					WP_CLI::error( $e->getMessage(), $e->getCode() );
				}
			}
		}

		WP_CLI::success( "Finished processing $result_count instances of embed cache data." );
		if ( ! $result_count ) {
			WP_CLI::success( "No instances of bad embed cache data were found." );
		} else {
			WP_CLI::success( "Successfully removed $success_count instances of bad embed cache data." );
		}
	}
}
