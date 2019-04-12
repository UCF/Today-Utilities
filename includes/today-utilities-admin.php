<?php
/**
 * Updates to the WordPress admin interface.
 */

/**
 * Conditionally enqueues the sanitize-html lib on the
 * post edit screen.
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
function tu_admin_enqueue_sanitizehtml( $hook ) {
	if ( ! in_array( $hook, array( 'post-new.php', 'post.php' ) ) ) {
		return;
	}

	wp_enqueue_script( 'tu_sanitizehtml', TU_PLUGIN_JS_URL . 'sanitize-html.min.js' );
}

add_action( 'admin_enqueue_scripts', 'tu_admin_enqueue_sanitizehtml' );


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


/**
 * Customizes TinyMCE's configuration.
 *
 * Adds a paste_preprocess rule that applies a whitelist of
 * HTML elements and strips out empty tags.
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $in TinyMCE init config
 * @return array TinyMCE init config
 */
function tu_configure_tinymce( $in ) {
	ob_start();
?>
function(plugin, args) {
	var whitelist = [
		'p', 'a',
		'strong', 'em',
		'small', 'sup', 'sub',
		's', 'ins', 'del', 'abbr',
		'h2', 'h3', 'h4', 'h5', 'h6',
		'ul', 'li', 'ol',
		'dl', 'dt', 'dd'
	];
	var clean = sanitizeHtml(args.content, {
		allowedTags: whitelist,
		transformTags: {
			'b': 'strong',
			'i': 'em'
		},
		exclusiveFilter: function(frame) {
			// Strip out empty tags
			return !frame.text.trim();
		}
	});

	// Return the clean HTML
	args.content = clean;
}
<?php
	$in['paste_preprocess'] = ob_get_clean();

	return $in;
}

add_filter( 'tiny_mce_before_init', 'tu_configure_tinymce' );
