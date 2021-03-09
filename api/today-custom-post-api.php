<?php
/**
 * All custom wp-json API endpoints should be defined
 * within this file.
 */

if ( ! class_exists( 'UCF_Today_Custom_API' ) ) {
	class UCF_Today_Custom_API extends WP_REST_Controller {
		/**
		 * Registers the rest routes for the ucf_news api
		 * @since 1.0.0
		 * @author Jim Barnes
		 */
		public static function register_rest_routes() {
			$root    = 'ucf-news';
			$version = 'v1';

			register_rest_route( "{$root}/{$version}", "/external-stories", array(
				array(
					'methods'              => WP_REST_Server::READABLE,
					'callback'             => array( 'UCF_Today_Custom_API', 'get_external_stories' ),
					'permissions_callback' => array( 'UCF_Today_Custom_API', 'get_permissions' ),
					'args'                 => array( 'UCF_Today_Custom_API', 'get_external_story_args' )
				)
			) );

			register_rest_route( "{$root}/{$version}", "/gmucf-email-options", array(
				array(
					'methods'              => WP_REST_Server::READABLE,
					'callback'             => array( 'UCF_Today_Custom_API', 'get_gmucf_email_options' ),
					'permissions_callback' => array( 'UCF_Today_Custom_API', 'get_permissions' )
				)
			) );

			register_rest_route( "{$root}/{$version}", "/main-site-stories", array(
				array(
					'methods'              => WP_REST_Server::READABLE,
					'callback'             => array( 'UCF_Today_Custom_API', 'get_mainsite_stories' ),
					'permissions_callback' => array( 'UCF_Today_Custom_API', 'get_permissions' ),
					'args'                 => array( 'WP_REST_Post_Controller', 'get_collection_params' )
				)
			) );

			register_rest_route( "{$root}/{$version}", "/main-site-header-story", array(
				array(
					'methods'              => WP_REST_Server::READABLE,
					'callback'             => array( 'UCF_Today_Custom_API', 'get_mainsite_header_story' ),
					'permissions_callback' => array( 'UCF_Today_Custom_API', 'get_permissions' ),
					'args'                 => array( 'WP_REST_Post_Controller', 'get_collection_params' )
				)
			) );

			// Need to register this filter to allow the statement-archives
			// endpoint to properly retrieve list of years
			add_filter( 'get_archives_link', 'tu_get_archives_link', 10, 6 );

			register_rest_route( "{$root}/{$version}", "/statement-archives", array(
				array(
					'methods'              => WP_REST_Server::READABLE,
					'callback'             => array( 'UCF_Today_Custom_API', 'get_statement_archives' ),
					'permissions_callback' => array( 'UCF_Today_Custom_API', 'get_permissions' )
				)
			) );
		}

		/**
		 * Gets the external stories
		 * @since 1.0.0
		 * @author Jim Barnes
		 * @param WP_REST_Request $request | Contains GET params
		 * @return WP_REST_Response
		 */
		public static function get_external_stories( $request ) {
			// Handle args and set defaults
			$search     = $request['search'];
			$source     = $request['source'];
			$limit      = $request['limit'] ? $request['limit'] : 10;
			$offset     = $request['offset'] ? $request['offset'] : 0;
			$categories = $request['categories'];

			// Initialize out return value
			$retval = array();

			// Initialize and set defaults on argument array
			$args = array(
				'post_type'      => 'ucf_resource_link',
				'posts_per_page' => $limit,
				'offset'         => $offset,
				'tax_query'      => array(
					array(
						'taxonomy' => 'resource_link_types',
						'field'    => 'slug',
						'terms'    => 'external-story'
					)
				)
			);

			// Add search if it's set
			if ( $search ) {
				$args['s'] = $search;
			}

			// Add source meta query if it's set
			if ( $source ) {
				$sources = explode( ',', $source );

				$args['tax_query'][] = array(
					'taxonomy' => 'sources',
					'field'    => 'slug',
					'terms'    => $sources
				);
			}

			if ( $categories ) {
				$args['category_name'] = $categories;
			}

			$posts = get_posts( $args );

			foreach( $posts as $post ) {
				$data     = self::prepare_external_story_for_response( $post, $request );
				$retval[] = $data;
			}

			return new WP_REST_Response( $retval, 200 );
		}

		/**
		 * Formats the external story for response
		 * @since 1.0.0
		 * @author Jim Barnes
		 * @param WP_Post $post The post
		 * @param WP_REST_Request $request The request object
		 * @return array A serializable array.
		 */
		private static function prepare_external_story_for_response( $post, $request ) {

			$terms = wp_get_post_terms( $post->ID, 'sources' );
			$source_name = ! empty( $terms ) ? $terms[0]->name : null;
			$field = ( !empty( $terms ) ) ? get_field( 'source_icon', $terms[0] ) : null;
			$source_image = ( !empty( $field ) ) ? $field : null;

			// Prepare the return value format
			$retval = array(
				'title'        => '',
				'link_text'    => '',
				'description'  => '',
				'url'          => '',
				'source'       => '',
				'source_name'  => '',
				'source_image' => '',
				'publish_date' => ''
			);

			$retval['title']        = $post->post_title;
			$retval['link_text']    = $post->post_title;
			$retval['description']  = get_post_meta( $post->ID, 'ucf_resource_link_description', true );
			$retval['url']          = get_post_meta( $post->ID, 'ucf_resource_link_url', true );
			$retval['source']       = $source_name;
			$retval['source_name']  = $source_name;
			$retval['source_image'] = $source_image;
			$retval['publish_date'] = $post->post_date;

			return $retval;
		}

		/**
		 * Gets the default permissions
		 * @since 1.0.0
		 * @author Jim Barnes
		 */
		public static function get_permissions() {
			return true;
		}

		/**
		 * Gets the allowable args for external stories
		 * @since 1.0.0
		 * @author Jim Barnes
		 */
		public static function get_external_story_args() {
			return array(
				array(
					'search' => array(
						'default'           => false,
						'sanitize_callback' => 'sanitize_text_field'
					),
					'source' => array(
						'default'           => false,
						'sanitize_callback' => 'sanitize_text_field'
					),
					'limit' => array(
						'default'           => 10,
						'sanitize_callback' => 'absint'
					),
					'offset' => array(
						'default'           => 0,
						'sanitize_callback' => 'absint'
					),
					'categories' => array(
						'default'           => false,
						'sanitize_callback' => 'sanitize_text_field'
					)
				)
			);
		}

		/**
		 * Gets the GMUCF email options
		 * @since 1.0.0
		 * @author Cadie
		 * @param WP_REST_Request $request | Contains GET params
		 * @return WP_REST_Response
		 */
		public static function get_gmucf_email_options( $request ) {
			$retval = get_fields( 'gmucf_options' );

			$gmucf_content_rows            = $retval['gmucf_email_content'];
			$retval['gmucf_email_content'] = gmucf_stories_default_values( $gmucf_content_rows );

			return new WP_REST_Response( $retval, 200 );
		}

		/**
		 * Gets the Main Site Stories set in the
		 * EDU News Feed options page
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param WP_REST_Request $request | Contains GET params
		 * @return WP_REST_Response
		 */
		public static function get_mainsite_stories( $request ) {
			$stories = get_fields( 'main_site_news_feed' );
			$use_default = true;
			$args = array();

			$config = isset( $stories['main_site_stories_feed_config'] )
				? $stories['main_site_stories_feed_config']
				: 'default'; // Set to default in case there is no value

			$expiration = isset( $stories['main_site_stories_expire'] )
				? new DateTime( $stories['main_site_stories_expire'] )
				: null;

			$today = new DateTime('now');

			$story_ids = isset( $stories['main_site_stories'] )
				? $stories['main_site_stories']
				: null;

			if ( $config === 'custom' && $expiration && $today < $expiration && $story_ids ) {
				$use_default = false;
			}

			// Make sure we don't need to flip back the config
			if ( $use_default && $config === 'custom' ) {
				update_field( 'main_site_stories_feed_config', 'default', 'main_site_news_feed' );
			}

			if ( $use_default ) {
				$args = array(
					'fields'     => 'ids',
					'meta_query' => array(
						array(
							'key'     => 'post_main_site_story',
							'value'   => "1",
							'compare' => '='
						)
					)
				);
			} else {
				$args = array(
					'fields'   => 'ids',
					'post__in' => $stories['main_site_stories']
				);
			}

			$query = new WP_Query( $args );

			$request['include'] = $query->posts;

			if ( ! isset( $request['page'] ) ) {
				$request['page'] = 1;
			}

			// Use the post controller so we can tie into already set
			// formats and filters.
			$controller = new WP_REST_Posts_Controller( 'post' );

			$posts = $controller->get_items( $request );

			$retval = array();

			foreach ( $posts as $post ) {
				$retval[] = $controller->prepare_response_for_collection( $post );
			}

			return new WP_REST_Response( $retval[0], 200 );
		}

		/**
		 * Gets the Main Site Header Story set in the
		 * EDU News Feed options page
		 * @author RJ Bruneel
		 * @since 1.0.13
		 * @param WP_REST_Request $request | Contains GET params
		 * @return WP_REST_Response
		 */
		public static function get_mainsite_header_story( $request ) {

			$stories = get_fields( 'main_site_news_feed' );
			$post = $stories['main_site_header_story'];
			$title_override = $stories['main_site_header_story_title_override'];
			$subtitle_override = $stories['main_site_header_story_subtitle_override'];

			if( $post ) :

				$controller = new WP_REST_Posts_Controller( 'post' );
				$retval[] = $controller->prepare_item_for_response( $post, $request )->data;

				if( ! empty( $title_override ) ) {
					$retval[0]['title']['rendered'] = $title_override;
				}

				if( empty( $subtitle_override ) ) {
					$retval[0]['excerpt']['rendered'] = wp_strip_all_tags( get_field( 'post_header_deck', $post ) );
				} else {
					$retval[0]['excerpt']['rendered'] = $subtitle_override;
				}

				return new WP_REST_Response( $retval, 200 );
				
			else :

				return array();

			endif;

		}

		/**
		 * Returns a REST response that lists archives for
		 * the Statement post type
		 *
		 * @since 1.1.0
		 * @author Jo Dickson
		 * @param WP_REST_Request $request Contains GET params
		 * @return WP_REST_Response
		 */
		public static function get_statement_archives( $request ) {
			global $wpdb;
			$retval = array(
				'all' => array(
					'endpoint' => get_rest_url( null, '/wp/v2/statements' )
				),
				'years' => array(),
				'authors' => array()
			);

			// Get links to vanilla Statements REST endpoint,
			// filtered by year(s):
			$years  = array_map( 'intval', array_filter( explode( ',', wp_get_archives( array(
				'type'      => 'yearly',
				'after'     => ',',
				'echo'      => false,
				'format'    => 'text', // custom format handling in `tu_get_archives_link()`
				'post_type' => 'ucf_statement'
			) ) ) ) );
			foreach ( $years as $year ) {
				// Strip timezone formatting; WordPress doesn't like
				// it in before/after filters for whatever reason
				$year_before = str_replace( '+00:00', '', date( 'c', mktime( -1, -1, -1, 13, 1, $year ) ) );
				$year_after  = str_replace( '+00:00', '', date( 'c', mktime( 0, 0, 0, 1, 1, $year ) ) );
				$retval['years'][] = array( 'year' => $year, 'endpoint' => get_rest_url( null, '/wp/v2/statements?before=' . $year_before . '&after=' . $year_after ) );
			}

			// Get links to vanilla Statements REST endpoint,
			// filtered by authors assigned to at least one Statement:
			$authors = $wpdb->get_results(
				"SELECT t.term_id, t.name, t.slug from $wpdb->terms AS t
				INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
				INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id
				INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id
				WHERE p.post_type = 'ucf_statement' AND tt.taxonomy = 'tu_author'
				GROUP BY t.term_id"
			) ?: array();

			if ( $authors ) {
				array_walk( $authors, function( &$val, $key ) {
					$val->endpoint = get_rest_url( null, '/wp/v2/statements?&tu_author=' . $val->term_id );
				} );
			}

			$retval['authors'] = $authors;

			return new WP_REST_Response( $retval, 200 );
		}

		public static function register_author_field() {
			register_rest_field( 'ucf_statement', 'tu_author', array(
				'get_callback' => array( 'UCF_Today_Custom_API', 'get_author_data' )
			) );
		}

		public static function get_author_data( $statement ) {
			$author_id = count( $statement['tu_author'] ) > 0 ? (int) $statement['tu_author'][0] : null;

			$retval = null;

			if ( $author_id && $author = get_term( $author_id, 'tu_author' ) ) {
				$title = get_field( 'author_title', "tu_author_$author_id" );
				$photo = get_field( 'author_photo', "tu_author_$author_id", true );
				$bio   = get_field( 'author_bio', "tu_author_$author_id" );

				$fullname = ! empty( $title ) ? "{$author->name}, {$title}" : $author->name;

				$retval = array(
					'id'       => $author_id,
					'name'     => $author->name,
					'title'    => $title,
					'photo'    => $photo,
					'bio'      => $bio,
					'fullname' => $fullname
				);
			}

			return $retval;
		}
	}
}


/**
 * Adds support for custom "text" format for `get_archives_link()`.
 *
 * @since 1.1.0
 * @author Jo Dickson
 * @param string $link_html The archive HTML link content.
 * @param string $url       URL to archive.
 * @param string $text      Archive text description.
 * @param string $format    Link format. Can be 'link', 'option', 'html', or custom.
 * @param string $before    Content to prepend to the description.
 * @param string $after     Content to append to the description.
 * @return string Modified HTML link content
 */
function tu_get_archives_link( $link_html, $url, $text, $format, $before, $after ) {
	if ( $format === 'text' ) {
		$link_html = $before . $text . $after;
	}
	return $link_html;
}