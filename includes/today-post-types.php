<?php
/**
 * Includes custom post type definitions unique to Today,
 * and/or averrides/customizations to available post types
 */

if ( ! class_exists( 'UCF_Statement_PostType' ) ) {
	class UCF_Statement_PostType {
		public static $label_singular = 'Statement';
		public static $label_plural   = 'Statements';
		public static $text_domain    = 'ucf_statement';

		/**
		* Function that registers the custom post type
		*
		* @author Jo Dickson
		* @since 1.0.0
		* @return void
		*/
		public static function register() {
			$labels = array(
				'singular'    => self::$label_singular,
				'plural'      => self::$label_plural,
				'text_domain' => self::$text_domain
			);

			$post_type_name = $labels['text_domain'] ?? self::$text_domain;
			register_post_type( $post_type_name, self::registration_args( $labels ) );
		}

		/**
		* Returns an array of labels for the custom post type
		*
		* @author Jo Dickson
		* @since 1.0.0
		* @param array $labels The labels array
		* 						Defaults:
		* 							( 'singular'    => 'Statement' ),
		* 							( 'plural'      => 'Statements' ),
		* 							( 'text_domain' => 'ucf_statement' )
		* @return array
		*/
		public static function labels( $labels ) {
			$singular       = $labels['singular'] ?? self::$label_singular;
			$singular_lower = strtolower( $singular );
			$plural         = $labels['plural'] ?? self::$label_plural;
			$plural_lower   = strtolower( $plural );
			$text_domain    = $labels['text_domain'] ?? self::$text_domain;

			$retval = array(
				'name'                  => _x( $plural, "Post Type General Name", $text_domain ),
				'singular_name'         => _x( $singular, "Post Type Singular Name", $text_domain ),
				'menu_name'             => __( $plural, $text_domain ),
				'name_admin_bar'        => __( $singular, $text_domain ),
				'archives'              => __( "$singular Archives", $text_domain ),
				'parent_item_colon'     => __( "Parent $singular:", $text_domain ),
				'all_items'             => __( "All $plural", $text_domain ),
				'add_new_item'          => __( "Add New $singular", $text_domain ),
				'add_new'               => __( "Add New", $text_domain ),
				'new_item'              => __( "New $singular", $text_domain ),
				'edit_item'             => __( "Edit $singular", $text_domain ),
				'update_item'           => __( "Update $singular", $text_domain ),
				'view_item'             => __( "View $singular", $text_domain ),
				'search_items'          => __( "Search $plural", $text_domain ),
				'not_found'             => __( "Not found", $text_domain ),
				'not_found_in_trash'    => __( "Not found in Trash", $text_domain ),
				'featured_image'        => __( "Featured Image", $text_domain ),
				'set_featured_image'    => __( "Set featured image", $text_domain ),
				'remove_featured_image' => __( "Remove featured image", $text_domain ),
				'use_featured_image'    => __( "Use as featured image", $text_domain ),
				'insert_into_item'      => __( "Insert into $singular_lower", $text_domain ),
				'uploaded_to_this_item' => __( "Uploaded to this $singular_lower", $text_domain ),
				'items_list'            => __( "$plural list", $text_domain ),
				'items_list_navigation' => __( "$plural list navigation", $text_domain ),
				'filter_items_list'     => __( "Filter $plural_lower list", $text_domain ),
			);

			return $retval;
		}

		/**
		* Returns the arguments for registering
		* the custom post type
		*
		* @author Jo Dickson
		* @since 1.0.0
		* @param array $labels The labels array
		* 						Defaults:
		* 							( 'singular'    => 'Location' ),
		* 							( 'plural'      => 'Locations' ),
		* 							( 'text_domain' => 'ucf_location' )
		* @return array
		*/
		public static function registration_args( $labels ) {
			$taxonomies = array(
				'category',
				'post_tag'
			);

			$singular       = $labels['singular'] ?? self::$label_singular;
			$plural         = $labels['plural'] ?? self::$label_plural;
			$plural_lower   = strtolower( $plural );
			$text_domain    = $labels['text_domain'] ?? self::$text_domain;

			$args = array(
				'label'               => __( $singular, $text_domain ),
				'description'         => __( $plural, $text_domain ),
				'labels'              => self::labels( $labels ),
				'supports'            => array( 'title', 'editor', 'excerpt', 'revisions' ),
				'taxonomies'          => $taxonomies,
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_rest'        => true,
				'rest_base'           => $plural_lower,
				'menu_position'       => 8,
				'menu_icon'           => 'dashicons-megaphone',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'rewrite'             => array( 'slug' => $plural_lower ),
				'delete_with_user'    => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'post'
			);

			return $args;
		}
	}
}
