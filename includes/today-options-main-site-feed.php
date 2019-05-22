<?php
/**
 * Functions for adding and supporting the
 * Main Site News Feed Options Page
 */
function tu_add_main_site_feed_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' 	  => 'Main Site News Feed',
			'post_id'         => 'main_site_news_feed',
			'menu_title'	  => 'EDU News Feed',
			'menu_slug' 	  => 'main-site-news-feed',
			'capability'	  => 'administrator',
			'icon_url'        => 'dashicons-images-alt2',
			'redirect'        => false,
			'updated_message' => 'Main Site News Feed Updated'
		) );
	}
}
