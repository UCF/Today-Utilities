<?php
/*
Plugin Name: Today Utilities
Description: Feature and utility plugin for the UCF Today website
Version: 1.2.0
Author: UCF Web Communications
License: GPL3
GitHub Plugin URI: UCF/Today-Utilities
*/

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'TU_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TU_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TU_PLUGIN_FILE', __FILE__ );
define( 'TU_PLUGIN_STATIC_URL', TU_PLUGIN_URL . 'static/' );
define( 'TU_PLUGIN_JS_URL', TU_PLUGIN_STATIC_URL . 'js/' );

require_once TU_PLUGIN_DIR . 'includes/today-utilities-admin.php';

require_once TU_PLUGIN_DIR . 'includes/today-taxonomies.php';
require_once TU_PLUGIN_DIR . 'includes/today-post-types.php';
require_once TU_PLUGIN_DIR . 'includes/today-options-gmucf.php';
require_once TU_PLUGIN_DIR . 'includes/today-options-main-site-feed.php';
require_once TU_PLUGIN_DIR . 'includes/today-feeds.php';
require_once TU_PLUGIN_DIR . 'includes/today-revisions.php';

require_once TU_PLUGIN_DIR . 'api/today-custom-post-api.php';


if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once TU_PLUGIN_DIR . 'includes/today-wpcli.php';

	WP_CLI::add_command( 'today embeds', 'Today_Embed_Commands' );
}


/**
 * Function that runs on plugin activation
 *
 * @author Jo Dickson
 * @since 1.1.0
 * @return void
 */
function tu_plugin_activation() {
	add_action( 'init', array( 'UCF_Authors_Taxonomy', 'register' ) );
	add_action( 'init', array( 'UCF_Statement_PostType', 'register' ) );
	flush_rewrite_rules();
}

register_activation_hook( TU_PLUGIN_FILE,  'tu_plugin_activation' );


/**
 * Function that runs on plugin deactivation
 *
 * @author Jo Dickson
 * @since 1.1.0
 * @return void
 */
function tu_plugin_deactivation() {
	flush_rewrite_rules();
}

register_deactivation_hook( TU_PLUGIN_FILE,  'tu_plugin_deactivation' );


if ( ! function_exists( 'tu_init' ) ) {
	function tu_init() {
		add_action( 'rest_api_init', array( 'UCF_Today_Custom_API', 'register_rest_routes' ), 10, 0 );
		add_action( 'rest_api_init', array( 'UCF_Today_Custom_API', 'register_author_field' ), 10, 0 );

		// Register CPTs, Taxonomies
		add_action( 'init', array( 'UCF_Authors_Taxonomy', 'register' ) );
		add_action( 'init', array( 'UCF_Statement_PostType', 'register' ) );

		// Add options pages.
		add_action( 'init', 'tu_add_gmucf_options_page' );
		add_action( 'init', 'tu_add_main_site_feed_options_page' );
	}

	add_action( 'plugins_loaded', 'tu_init', 10, 0 );
}
