<?php
/**
 * Functions for adding and supporting the
 * GMUCF Options Page
 */
function tu_add_gmucf_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page( array(
			'page_title' 	  => 'GMUCF Email',
			'post_id'         => 'gmucf_options',
			'menu_title'	  => 'GMUCF Email',
			'menu_slug' 	  => 'gmucf-email',
			'capability'	  => 'administrator',
			'icon_url'        => 'dashicons-email-alt',
			'redirect'        => false,
			'updated_message' => 'GMUCF Options Updated'
		) );
	}
}


/**
 * Sets the default values for each stories' image, title and
 * description for use in the GMUCF Today emails
 * @since 2.9.0
 * @author Cadie Brown
 * @param Array $story | single array with story data
 * @return Array
 */
function gmucf_replace_story_default_values( $story ) {
	$post_id = $story['gmucf_story'];
	if ( ! $story['gmucf_story_image'] ) {
		$story['gmucf_story_image'] = today_get_thumbnail_url( $post_id, 'medium_large' );
	} else {
		$story['gmucf_story_image'] = $story['gmucf_story_image']['sizes']['medium_large'];
	}
	if ( ! $story['gmucf_story_title'] ) {
		$story['gmucf_story_title'] = get_the_title( $post_id );
	}
	if ( ! $story['gmucf_story_description'] ) {
		$story['gmucf_story_description'] = get_post_meta( $post_id, 'promo', true );
	}
	$story['gmucf_story_permalink'] = get_permalink( $post_id );
	if ( $story['acf_fc_layout'] ) {
		$story['gmucf_layout'] = $story['acf_fc_layout'];
		unset( $story['acf_fc_layout'] );
	}
	unset( $story['gmucf_story'] );
	return $story;
}

/**
 * Sets the default values depending on what kind
 * of layout is being used
 * @since 2.9.0
 * @author Cadie Brown
 * @param Array $stories | gmucf_email_content array from ACF GMUCF Options Page
 * @return Array
 */
function gmucf_stories_default_values( $stories ) {
	foreach ( $stories as $story ) {
		if ( $story['acf_fc_layout'] === 'gmucf_top_story' ) {
			$retval[] = gmucf_replace_story_default_values( $story );
		} elseif ( $story['acf_fc_layout'] === 'gmucf_featured_stories_row' ) {
			$story['gmucf_layout']               = $story['acf_fc_layout'];
			$story['gmucf_featured_story_row'][] = gmucf_replace_story_default_values( $story['gmucf_left_featured_story'] );
			$story['gmucf_featured_story_row'][] = gmucf_replace_story_default_values( $story['gmucf_right_featured_story'] );
			unset( $story['acf_fc_layout'] );
			unset( $story['gmucf_left_featured_story'] );
			unset( $story['gmucf_right_featured_story'] );
			$retval[] = $story;
		} elseif ( $story['acf_fc_layout'] === 'gmucf_spotlight' ) {
			$story['gmucf_spotlight_image'] = $story['gmucf_spotlight_image']['sizes']['gmucf_top_story'];
			$story['gmucf_layout']          = $story['acf_fc_layout'];
			unset( $story['acf_fc_layout'] );
			$retval[] = $story;
		} else {
			$retval[] = $story;
		}
	}
	return $retval;
}

/**
 * Adds GMUCF Field Section
 * @author Jim Barnes
 * @since 1.0.2
 * @param WP_Customize $wp_customize Customizer class
 */
function tu_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		'tu_gmucf_section',
		array(
			'title' => 'GMUCF Settings'
		)
	);
}

add_action( 'customize_register', 'tu_customizer_sections', 10, 1 );

/**
 * Adds GMUCF Fields
 * @author Jim Barnes
 * @since 1.0.2
 * @param WP_Customize $wp_customize Customizer class
 */
function tu_customizer_fields( $wp_customize ) {
	$wp_customize->add_setting(
		'today_announcements_feed_url',
		array(
			'default' => 'https://www.ucf.edu/announcements/api/announcements/?time=this-week&exclude_ongoing=True&format=json&status=Publish'
		)
	);

	$wp_customize->add_control(
		'today_announcements_feed_url',
		array(
			'type'        => 'text',
			'label'       => 'Announcements JSON URL',
			'description' => 'The URL of the announcements JSON feed',
			'section'     => 'tu_gmucf_section'
		)
	);
}

add_action( 'customize_register', 'tu_customizer_fields', 10, 1 );

/**
 * Adds announcements as options to gmucf_announcements field
 * @author Jim Barnes
 * @since 1.0.2
 * @param array $field The ACF field
 * @return array
 */
function tu_load_announcement_choices( $field ) {
	$field['choices'] = array();

	$url = get_theme_mod( 'today_announcements_feed_url', 'https://www.ucf.edu/announcements/api/announcements/?time=this-week&exclude_ongoing=True&format=json&status=Publish' );

	$args = array(
		'timeout' => 15
	);

	$response      = wp_remote_get( $url, $args );

	$response_code = wp_remote_retrieve_response_code( $response );

	if ( is_array( $response ) && is_int( $response_code ) && $response_code < 400 ) {

		$results = json_decode( wp_remote_retrieve_body( $response ) );

		foreach( $results as $result ) {
			$field['choices'][$result->id] = $result->title;
		}
	}

	return $field;
}

add_filter( 'acf/load_field/name=gmucf_announcements', 'tu_load_announcement_choices' );
