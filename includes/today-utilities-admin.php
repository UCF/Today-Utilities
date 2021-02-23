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
	$new_columns = array();
	foreach ( $columns as $key => $column ) {
		// Exclude default 'author' column and move+re-label it below
		if ( ! in_array( $key, array( 'author' ) ) ) {
			$new_columns[$key] = $column;
		}
	}
	$new_columns['tu-author'] = 'Author';
	$new_columns['author']    = 'Publisher';
	$new_columns['template']  = 'Template';
	return $new_columns;
}

add_filter( 'manage_post_posts_columns', 'tu_custom_post_columns' );


/**
 * Defines columns in the WordPress admin when viewing
 * all Statements or searching for Statement posts.
 *
 * @since 1.1.0
 * @author Jo Dickson
 */
function tu_custom_statement_columns( $columns ) {
	$columns = tu_custom_post_columns( $columns );
	unset( $columns['template'] );
	return $columns;
}

add_filter( 'manage_ucf_statement_posts_columns', 'tu_custom_statement_columns' );


/**
 * Defines content to display in custom columns for posts
 * in the WordPress admin.
 *
 * @since 1.0.0
 * @author Jo Dickson
 */
function tu_custom_post_columns_content( $column_name, $post_id ) {
	switch ( $column_name ) {
		case 'tu-author':
			// TODO print list of term edit links, if terms are in use;
			// else, print custom author byline
			echo 'TODO';
			break;
		case 'template':
			$all_templates = get_page_templates( null, 'post' );
			$template_slug = get_page_template_slug( $post_id );
			$template_name = $template_slug ? array_search( $template_slug, $all_templates ) : 'Default';
			echo $template_name;
			break;
	}
}

add_action( 'manage_post_posts_custom_column', 'tu_custom_post_columns_content', 10, 2 );
add_action( 'manage_ucf_statement_posts_custom_column', 'tu_custom_post_columns_content', 10, 2 );


/**
 * Removes the "author" metabox from the post edit screen.
 *
 * @author Jo Dickson
 * @since 1.1.0
 */
function tu_remove_author_metabox() {
	remove_meta_box( 'authordiv' , 'post' , 'normal' );
}

add_action( 'add_meta_boxes', 'tu_remove_author_metabox' );


/**
 * Hide the "description" field for new/edited Author terms.
 *
 * @author Jo Dickson
 * @since 1.1.0
 */
function tu_hide_author_description_field() {
	echo "<style> .term-description-wrap { display:none; } </style>";
}

add_action( 'tu_authors_edit_form', 'tu_hide_author_description_field' );
add_action( 'tu_authors_add_form', 'tu_hide_author_description_field' );


/**
 * Defines columns to display for the Author taxonomy in the
 * WordPress admin.
 *
 * @author Jo Dickson
 * @since 1.1.0
 */
function tu_author_columns( $columns ) {
	if ( isset( $columns['description'] ) ) {
		unset( $columns['description'] );
	}
	return $columns;
}

add_filter( 'manage_edit-tu_authors_columns', 'tu_author_columns', 10, 1 );



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
		'blockquote',
		'strong', 'em',
		'small', 'sup', 'sub',
		's', 'ins', 'del', 'abbr',
		'h2', 'h3', 'h4', 'h5', 'h6',
		'ul', 'li', 'ol',
		'dl', 'dt', 'dd'
	];

	// Generic function that replaces a URL with a query param value
	// based on specific search criteria in the URL
	function stripLinkPrefix(url, searchRegex, queryParam) {
		if (url.search(searchRegex) !== -1) {
			var dummylink = document.createElement('a');
			dummylink.href = url;
			var query = dummylink.search;
			var updatedUrl = '';
			if (query.indexOf(queryParam + '=') !== -1) {
				// Get the query param
				updatedUrl = query.replace('?', '').split('&').filter(function(x) { var kv = x.split('='); if (kv[0] === queryParam) return kv[1]; } ).shift().split('=')[1];

				// Decode special characters.
				// This is dumb, but colon (:) characters don't get
				// decoded properly without running decodeURIComponent()
				// on the string twice
				updatedUrl = decodeURIComponent(decodeURIComponent(updatedUrl));

				url = updatedUrl;
			}
		}

		return url;
	}

	// Replaces Outlook safelink URLs with the actual redirected URL
	function stripOutlookSafelinks(url) {
		return stripLinkPrefix(
			url,
			/^https\:\/\/(.*\.)safelinks\.protection\.outlook\.com\//i,
			'url'
		);
	}

	// Replaces Postmaster redirects with the actual redirected URL
	function stripPostmasterRedirects(url) {
		return stripLinkPrefix(
			url,
			/^https\:\/\/postmaster\.smca\.ucf\.edu\//i,
			'url'
		);
	}

	function sanitizeUrl(url) {
		return stripPostmasterRedirects(stripOutlookSafelinks(url));
	}

	var clean = sanitizeHtml(args.content, {
		allowedTags: whitelist,
		transformTags: {
			'b': 'strong',
			'i': 'em',
			'a': function(tagName, attribs) {
				if (attribs.href) {
					url = sanitizeUrl(attribs.href);
					if (url !== attribs.href) {
						attribs.href = url;
					}
				}

				return {
					tagName: tagName,
					attribs: attribs
				}
			}
		},
		exclusiveFilter: function(frame) {
			return (
				// Strip out empty tags
				!frame.text.trim()
			);
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


/**
 * Restrict the size of images uploaded to the media library.
 *
 * https://wordpress.stackexchange.com/a/228306
 *
 * @since 1.0.0
 * @author Jo Dickson
 * @param array $file The uploaded file information
 * @return array The uploaded file information
 */
function tu_handle_upload_prefilter( $file ) {
	// Set the desired file size limit per mimetype (in KB)
	$filesize_limits = array(
		'image/jpeg' => 800,
		'image/png'  => 800,
	);

	if ( isset( $file['type'] ) && array_key_exists( $file['type'], $filesize_limits ) ) {
		$filesize_limit = $filesize_limits[$file['type']];
		$current_size   = isset( $file['size'] ) ? $file['size'] / 1024 : 0; // get size in KB

		if ( $current_size > $filesize_limit ) {
			$file['error'] = sprintf( __( 'ERROR: File size limit for this type of file is %d KB.' ), $filesize_limit );
		}
	}

    return $file;
}

add_filter( 'wp_handle_upload_prefilter', 'tu_handle_upload_prefilter', 10, 1 );


/**
 * Adds oEmbed support for Juxtapose.
 *
 * @since v1.0.6
 * @author Jo Dickson
 */
wp_oembed_add_provider( 'https://cdn.knightlab.com/libs/juxtapose/latest/embed/*', 'https://oembed.knightlab.com/juxtapose/' );


/**
 * Increases the request timeout for oEmbed content
 * retrieval from the default 5 second limit.
 *
 * @since 1.0.11
 * @author Jo Dickson
 * @param array $args oEmbed remote get arguments
 * @return array Modified $args
 */
function tu_oembed_timeout( $args ) {
	$args['timeout'] = 15; // seconds
	return $args;
}

add_filter( 'oembed_remote_get_args', 'tu_oembed_timeout' );
