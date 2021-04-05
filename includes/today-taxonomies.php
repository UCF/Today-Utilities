<?php
/**
 * Includes custom taxonomy definitions unique to UCF Today
 */

if ( ! class_exists( 'UCF_Authors_Taxonomy' ) ) {
	class UCF_Authors_Taxonomy {
		public static $label_singular = 'Author';
		public static $label_plural   = 'Authors';
		public static $text_domain    = 'tu_author';

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
	 	 * 							( 'text_domain' => 'tu_author' )
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
	 	 * 							( 'text_domain' => 'tu_author' )
		 * @return array
		 */
		public static function registration_args( $labels ) {
			$singular     = $labels['singular'] ?? self::$label_singular;
			$plural       = $labels['plural'] ?? self::$label_plural;
			$plural_lower = strtolower( $plural );

			$args = array(
				'labels'                     => self::labels( $labels ),
				'hierarchical'               => false,
				'public'                     => false,
				'show_ui'                    => true,
				'show_in_quick_edit'         => false,
				'meta_box_cb'                => false, // Define custom ACF field for managing these instead
				'show_admin_column'          => false,
				'show_in_nav_menus'          => true,
				'show_in_rest'               => true,
				'show_tagcloud'              => true,
				'rewrite'                    => array(
					'slug' => $plural_lower
				)
			);

			return $args;
		}

		/**
		 * Registers the ACF Fields for Authors
		 * @author Cadie Stockman
		 * @since 1.2.0
		 * @return void
		 */
		public static function register_acf_fields() {
			// Bail out if the function is missing.
			if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

			// Create the field array.
			$fields = array();

			/**
			 * Adds the title field
			 */
			$fields[] = array(
				'key'               => 'field_60351b469550e',
				'label'             => 'Title',
				'name'              => 'author_title',
				'type'              => 'text',
				'instructions'      => 'A title for this author, e.g. "Director of [department]".',
			);

			/**
			 * Adds the photo field
			 */
			$fields[] = array(
				'key'               => 'field_60351b549550f',
				'label'             => 'Photo',
				'name'              => 'author_photo',
				'type'              => 'image',
				'preview_size'      => 'thumbnail',
				'library'           => 'all',
			);

			/**
			 * Adds the bio field
			 **/
			$fields[] = array(
				'key'               => 'field_60351b5895510',
				'label'             => 'Bio',
				'name'              => 'author_bio',
				'type'              => 'wysiwyg',
				'instructions'      => 'A brief bio or description for this author.<br><br>This field must be set in order to display any author information (Author Name, Title, Bio, Photo) below post/statement content.',
				'tabs'              => 'text',
				'media_upload'      => 0,
			);

			/**
			 * Defines field group
			 */
			$field_group = array(
				'key'                   => 'group_60351a1a8f796',
				'title'                 => 'Author Fields',
				'fields'                => $fields,
				'location'              => array(
					array(
						array(
							'param'    => 'taxonomy',
							'operator' => '==',
							'value'    => 'tu_author',
						),
					),
				),
			);

			acf_add_local_field_group( $field_group );
		}
	}
}
