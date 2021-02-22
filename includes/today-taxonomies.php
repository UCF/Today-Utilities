<?php
/**
 * Includes custom taxonomy definitions unique to UCF Today
 */

if ( ! class_exists( 'UCF_Authors_Taxonomy' ) ) {
	class UCF_Authors_Taxonomy {
		public static $label_singular = 'Author';
		public static $label_plural   = 'Authors';
		public static $text_domain    = 'tu_authors';

		/**
		 * Registers the Authors custom taxonomy
		 *
		 * @author Jo Dickson
		 * @since 1.1.0
		 * @return void
		 **/
		public static function register() {
			$labels = array(
				'singular'    => self::$label_singular,
				'plural'      => self::$label_plural,
				'text_domain' => self::$text_domain
			);

			$post_types = array( 'post', 'ucf_statement' );

			register_taxonomy( self::$text_domain, $post_types, self::registration_args( $labels ) );
		}

		/**
		 * Returns an array of labels for the custom taxonomy.
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param array $labels The labels array
	 	 * 						Defaults:
	 	 * 							( 'singular'    => 'Author' ),
	 	 * 							( 'plural'      => 'Authors' ),
	 	 * 							( 'text_domain' => 'tu_authors' )
		 * @return array
		 **/
		public static function labels( $labels ) {
			$singular     = $labels['singular'] ?? self::$label_singular;
			$plural       = $labels['plural'] ?? self::$label_plural;
			$plural_lower = strtolower( $plural );
			$text_domain  = $labels['text_domain'] ?? self::$text_domain;

			return array(
				'name'                       => _x( $plural, 'Taxonomy General Name', $text_domain ),
				'singular_name'              => _x( $singular, 'Taxonomy Singular Name', $text_domain ),
				'menu_name'                  => __( $plural, $text_domain ),
				'all_items'                  => __( 'All ' . $plural, $text_domain ),
				'parent_item'                => __( 'Parent ' . $singular, $text_domain ),
				'parent_item_colon'          => __( 'Parent :' . $singular, $text_domain ),
				'new_item_name'              => __( 'New ' . $singular . ' Name', $text_domain ),
				'add_new_item'               => __( 'Add New ' . $singular, $text_domain ),
				'edit_item'                  => __( 'Edit ' . $singular, $text_domain ),
				'update_item'                => __( 'Update ' . $singular, $text_domain ),
				'view_item'                  => __( 'View ' . $singular, $text_domain ),
				'separate_items_with_commas' => __( 'Separate ' . $plural_lower . ' with commas', $text_domain ),
				'add_or_remove_items'        => __( 'Add or remove ' . $plural_lower, $text_domain ),
				'choose_from_most_used'      => __( 'Choose from the most used', $text_domain ),
				'popular_items'              => __( 'Popular ' . $plural_lower, $text_domain ),
				'search_items'               => __( 'Search ' . $plural, $text_domain ),
				'not_found'                  => __( 'Not Found', $text_domain ),
				'no_terms'                   => __( 'No items', $text_domain ),
				'items_list'                 => __( $plural . ' list', $text_domain ),
				'items_list_navigation'      => __( $plural . ' list navigation', $text_domain ),
			);
		}

		/**
		 * Returns the arguments for registering the custom taxonomy.
		 *
		 * @author Jo Dickson
		 * @since 1.0.0
		 * @param array $labels The labels array
	 	 * 						Defaults:
	 	 * 							( 'singular'    => 'Author' ),
	 	 * 							( 'plural'      => 'Authors' ),
	 	 * 							( 'text_domain' => 'tu_authors' )
		 * @return array
		 */
		public static function registration_args( $labels ) {
			$singular     = $labels['singular'] ?? self::$label_singular;
			$plural       = $labels['plural'] ?? self::$label_plural;
			$plural_lower = strtolower( $plural );

			$args = array(
				'labels'                     => self::labels( $labels ),
				'hierarchical'               => false,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_in_rest'               => true,
				'show_tagcloud'              => true,
				'rewrite'                    => array(
					'slug' => $plural_lower
				)
			);

			return $args;
		}
	}

	add_action( 'init', array( 'UCF_Authors_Taxonomy', 'register' ), 10, 0 );
}


/**
 * Force-toggles various Yoast settings related to taxonomies
 *
 * @since 1.1.0
 * @author Jo Dickson
 * @param array $options Array of nested option keys/vals
 * @return array
 */
function tu_author_taxonomy_yoast_titles( $options ) {
	// "Show in search results?"
	$options['noindex-tax-tu_authors'] = false; // TODO not working?

	// "Yoast SEO Meta Box"
	$options['display-metabox-tax-tu_authors'] = false;
}

add_filter( 'option_wpseo_titles', 'tu_author_taxonomy_yoast_titles', 99, 1 );
