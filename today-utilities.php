<?php
/*
Plugin Name: Today Utilities
Description: Feature and utility plugin for the UCF Today website
Version: 1.0.9
Author: UCF Web Communications
License: GPL3
GitHub Plugin URI: UCF/Today-Utilities
*/

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'TU_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TU_PLUGIN_STATIC_URL', TU_PLUGIN_URL . 'static/' );
define( 'TU_PLUGIN_JS_URL', TU_PLUGIN_STATIC_URL . 'js/' );

require_once 'includes/today-utilities-admin.php';
require_once 'includes/today-options-gmucf.php';
require_once 'includes/today-options-main-site-feed.php';
require_once 'includes/today-feeds.php';
require_once 'includes/today-revisions.php';

require_once 'api/today-custom-post-api.php';

if ( ! function_exists( 'tu_init' ) ) {
	function tu_init() {
		add_action( 'rest_api_init', array( 'UCF_Today_Custom_API', 'register_rest_routes' ), 10, 0 );

		// Add options pages.
		add_action( 'init', 'tu_add_gmucf_options_page' );
		add_action( 'init', 'tu_add_main_site_feed_options_page' );
	}

	add_action( 'plugins_loaded', 'tu_init', 10, 0 );
}
