<?php
/**
 * Functions for adding and supporting the
 * Main Site Options Page
 */
function tu_add_main_site_feed_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' 	  => 'Main Site Options',
			'post_id'         => 'main_site_news_feed',
			'menu_title'	  => 'Main Site Options',
			'menu_slug' 	  => 'main-site-news-feed',
			'capability'	  => 'administrator',
			'icon_url'        => 'dashicons-images-alt2',
			'redirect'        => false,
			'updated_message' => 'Main site options updated'
		) );
	}
}


/**
 * Function for adding the Main Site Options
 * ACF field groups & fields
 **/
function tu_add_main_site_feed_options_fields() {
	if ( function_exists( 'acf_add_local_field_group' ) ) {

		// Create the array to add the
		// Header Story Options field group fields to
		$header_story_fields = array();

		// Adds header story field
		$header_story_fields[] = array(
			'key'               => 'field_60413825e0ada',
			'label'             => 'Header Story',
			'name'              => 'main_site_header_story',
			'type'              => 'post_object',
			'instructions'      => 'The story to display in the header on UCF.edu (if the custom layout is active)',
			'post_type'         => array(
				0 => 'post',
			),
			'allow_null'        => 1,
		);

		// Adds header story title override field
		$header_story_fields[] = array(
			'key'               => 'field_60413bfa7e88b',
			'label'             => 'Header Story Title Override',
			'name'              => 'main_site_header_story_title_override',
			'type'              => 'text',
			'instructions'      => 'The story title will be displayed if left blank.',
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_60413825e0ada',
						'operator' => '!=empty',
					),
				),
			),
		);

		// Adds header story subtitle override field
		$header_story_fields[] = array(
			'key'               => 'field_60413cde7e88c',
			'label'             => 'Header Story Subtitle Override',
			'name'              => 'main_site_header_story_subtitle_override',
			'type'              => 'text',
			'instructions'      => 'The story subtitle will be displayed if left blank.',
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_60413825e0ada',
						'operator' => '!=empty',
					),
				),
			),
		);

		// Defines Main Site Header Story field group
		$header_story_field_group = array(
			'key'                   => 'group_6054bc3d887d7',
			'title'                 => 'Main Site Header Story Options',
			'fields'                => $header_story_fields,
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'main-site-news-feed',
					),
				),
			),
		);

		acf_add_local_field_group( $header_story_field_group );

		// Create the array to add the
		// Feed Options field group fields to
		$news_feed_fields = array();

		// Adds feed mode field
		$news_feed_fields[] = array(
			'key'               => 'field_5c9e37641bcbe',
			'label'             => 'Feed Mode',
			'name'              => 'main_site_stories_feed_config',
			'type'              => 'radio',
			'instructions'      => 'Choose which feed should be used.',
			'required'          => 1,
			'choices'           => array(
				'default' => 'Display the most recent stories with "Promote on Main Site" field set to true.',
				'custom'  => 'Display a custom set of stories.',
			),
			'default_value'     => 'default',
		);

		// Adds feed expiration field
		$news_feed_fields[] = array(
			'key'               => 'field_5c9e2f1d9c2c5',
			'label'             => 'Feed Expiration',
			'name'              => 'main_site_stories_expire',
			'type'              => 'date_picker',
			'instructions'      => 'Choose when the custom feed should expire.',
			'required'          => 1,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_5c9e37641bcbe',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'display_format'    => 'F j, Y',
			'return_format'     => 'm/d/Y',
			'first_day'         => 0,
		);

		// Adds stories field
		$news_feed_fields[] = array(
			'key'               => 'field_5c9cddf6a827b',
			'label'             => 'Stories',
			'name'              => 'main_site_stories',
			'type'              => 'post_object',
			'instructions'      => 'Add stories to be displayed on www.ucf.edu.',
			'required'          => 1,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_5c9e37641bcbe',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'post_type'         => array(
				0 => 'post',
			),
			'multiple'          => 1,
			'return_format'     => 'id',
		);

		// Defines Main Site News Feed Options field group
		$news_feed_field_group = array(
			'key'                   => 'group_5c9cddeea9e45',
			'title'                 => 'Main Site News Feed Options',
			'fields'                => $news_feed_fields,
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'main-site-news-feed',
					),
				),
			),
		);

		acf_add_local_field_group( $news_feed_field_group );
	}
}
