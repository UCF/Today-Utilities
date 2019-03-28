<?php
/*
Plugin Name: Today Utilities
Description: Feature and utility plugin for the UCF Today website
Version: 0.0.0
Author: UCF Web Communications
License: GPL3
GitHub Plugin URI: UCF/Today-Utilities
*/

if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once 'includes/today-utilities-admin.php';
require_once 'includes/today-gmucf-options.php';

require_once 'api/today-custom-post-api.php';

if ( ! function_exists( 'tu_init' ) ) {
	function tu_init() {
		add_action( 'rest_api_init', array( 'UCF_Today_Custom_API', 'register_rest_routes' ), 10, 0 );

		// Add options pages.
		add_action( 'init', 'tu_add_gmucf_options_page' );
	}

	add_action( 'plugins_loaded', 'tu_init', 10, 0 );
}
