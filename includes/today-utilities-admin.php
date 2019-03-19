<?php
/**
 * Updates to the WordPress admin interface.
 */

/**
 * Defines new columns in the WordPress admin when viewing
 * all posts or searching for posts.
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
function tu_custom_post_columns( $columns ) {
    $columns['template'] = 'Template';
    return $columns;
}

add_filter( 'manage_post_posts_columns', 'tu_custom_post_columns' );


/**
 * Defines content to display in custom columns for posts
 * in the WordPress admin.
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
function tu_custom_post_columns_content( $column_name, $post_id ) {
    switch ( $column_name ) {
		case 'template':
			$all_templates = get_page_templates( null, 'post' );
			$template_slug = get_page_template_slug( $post_id );
			$template_name = $template_slug ? array_search( $template_slug, $all_templates ) : 'Default';
			echo $template_name;
			break;
    }
}

add_action( 'manage_post_posts_custom_column', 'tu_custom_post_columns_content', 10, 2 );
