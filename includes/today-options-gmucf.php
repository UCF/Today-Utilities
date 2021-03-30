<?php
/**
 * Functions for adding and supporting the
 * GMUCF Options Page and corresponding ACF fields.
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

	if ( function_exists( 'acf_add_local_field_group' ) ) {
		// Create the array to add the fields to
		$fields = array();

		/**
		 * Adds email send date field
		 */
		$fields[] = array(
			'key'               => 'field_5be5fd67174cc',
			'label'             => 'Email Send Date',
			'name'              => 'gmucf_email_send_date',
			'type'              => 'date_picker',
			'instructions'      => 'Select the date this email is set to send out. <strong style="color: red;">If this date is not set to the same date the email is set to send out, a fallback email will be sent out.</strong>',
			'required'          => 1,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '50',
				'class' => '',
				'id'    => '',
			),
			'display_format'    => 'm/d/Y',
			'return_format'     => 'm/d/Y',
			'first_day'         => 0,
		);

		/**
		 * Adds show social share buttons field
		 **/
		$fields[] = array(
			'key'               => 'field_5be5fd67174e6',
			'label'             => 'Show Social Share Buttons',
			'name'              => 'gmucf_show_social_share_buttons',
			'type'              => 'true_false',
			'instructions'      => 'If checked, the social share buttons for each story will be displayed underneath the description text.',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '50',
				'class' => '',
				'id'    => '',
			),
			'message'           => '',
			'default_value'     => 1,
			'ui'                => 0,
			'ui_on_text'        => '',
			'ui_off_text'       => '',
		);

		/**
		 * Adds email content field
		 **/
		$fields[] = array(
			'key'               => 'field_5be5fd816ee47',
			'label'             => 'Email Content',
			'name'              => 'gmucf_email_content',
			'type'              => 'flexible_content',
			'instructions'      => 'Select and order the email content.<br><br>Select <strong>at least</strong> one Top Story and one Featured Stories Row. Each Featured Stories Row must have two featured stories selected; one in each column.<br><br>Spotlights can be added but are not required. There is a maximum of 2 spotlights per email.',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'layouts'           => array(
				'5be5fd915fd9d' => array(
					'key'        => '5be5fd915fd9d',
					'name'       => 'gmucf_top_story',
					'label'      => 'Top Story',
					'display'    => 'block',
					'sub_fields' => array(
						array(
							'key'               => 'field_5be5fdb56ee48',
							'label'             => 'Select a Story',
							'name'              => 'gmucf_story',
							'type'              => 'post_object',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'post_type'         => array(
								0 => 'post',
							),
							'taxonomy'          => array(
							),
							'allow_null'        => 0,
							'multiple'          => 0,
							'return_format'     => 'id',
							'ui'                => 1,
						),
						array(
							'key'               => 'field_5be5fefc6ee49',
							'label'             => 'Story Image',
							'name'              => 'gmucf_story_image',
							'type'              => 'image',
							'instructions'      => 'Select an image to replace the story\'s featured image in the email.',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'array',
							'preview_size'      => 'full',
							'library'           => 'all',
							'min_width'         => '',
							'min_height'        => '',
							'min_size'          => '',
							'max_width'         => '',
							'max_height'        => '',
							'max_size'          => '',
							'mime_types'        => 'jpg, jpeg, gif',
						),
						array(
							'key'               => 'field_5be5ff5b6ee4a',
							'label'             => 'Story Title',
							'name'              => 'gmucf_story_title',
							'type'              => 'text',
							'instructions'      => 'This will replace the story\'s title in the email.',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_5be5ff8c6ee4b',
							'label'             => 'Story Description',
							'name'              => 'gmucf_story_description',
							'type'              => 'textarea',
							'instructions'      => 'This will replace the story\'s Deck field value in the email.',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'maxlength'         => '',
							'rows'              => '',
							'new_lines'         => '',
						),
					),
					'min'        => '1',
					'max'        => '',
				),
				'5bef062b9758c' => array(
					'key'        => '5bef062b9758c',
					'name'       => 'gmucf_featured_stories_row',
					'label'      => 'Featured Stories Row',
					'display'    => 'block',
					'sub_fields' => array(
						array(
							'key'               => 'field_5bef1383868cc',
							'label'             => 'Left Featured Story',
							'name'              => 'gmucf_left_featured_story',
							'type'              => 'group',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '50',
								'class' => '',
								'id'    => '',
							),
							'layout'            => 'block',
							'sub_fields'        => array(
								array(
									'key'               => 'field_5bef069697592',
									'label'             => 'Select a Story',
									'name'              => 'gmucf_story',
									'type'              => 'post_object',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'post_type'         => array(
										0 => 'post',
									),
									'taxonomy'          => array(
									),
									'allow_null'        => 0,
									'multiple'          => 0,
									'return_format'     => 'id',
									'ui'                => 1,
								),
								array(
									'key'               => 'field_5bef06809758f',
									'label'             => 'Story Image',
									'name'              => 'gmucf_story_image',
									'type'              => 'image',
									'instructions'      => 'Select an image to replace the story\'s featured image in the email.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'return_format'     => 'array',
									'preview_size'      => 'full',
									'library'           => 'all',
									'min_width'         => '',
									'min_height'        => '',
									'min_size'          => '',
									'max_width'         => '',
									'max_height'        => '',
									'max_size'          => '',
									'mime_types'        => 'jpg, jpeg, gif',
								),
								array(
									'key'               => 'field_5bef068497590',
									'label'             => 'Story Title',
									'name'              => 'gmucf_story_title',
									'type'              => 'text',
									'instructions'      => 'This will replace the story\'s title in the email.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => '',
									'maxlength'         => '',
								),
								array(
									'key'               => 'field_5bef068697591',
									'label'             => 'Story Description',
									'name'              => 'gmucf_story_description',
									'type'              => 'textarea',
									'instructions'      => 'This will replace the story\'s Deck field value in the email.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'maxlength'         => '',
									'rows'              => '',
									'new_lines'         => '',
								),
							),
						),
						array(
							'key'               => 'field_5bef13d0868d1',
							'label'             => 'Right Featured Story',
							'name'              => 'gmucf_right_featured_story',
							'type'              => 'group',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '50',
								'class' => '',
								'id'    => '',
							),
							'layout'            => 'block',
							'sub_fields'        => array(
								array(
									'key'               => 'field_5bef13d1868d2',
									'label'             => 'Select a Story',
									'name'              => 'gmucf_story',
									'type'              => 'post_object',
									'instructions'      => '',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'post_type'         => array(
										0 => 'post',
									),
									'taxonomy'          => array(
									),
									'allow_null'        => 0,
									'multiple'          => 0,
									'return_format'     => 'id',
									'ui'                => 1,
								),
								array(
									'key'               => 'field_5bef13d1868d3',
									'label'             => 'Story Image',
									'name'              => 'gmucf_story_image',
									'type'              => 'image',
									'instructions'      => 'Select an image to replace the story\'s featured image in the email.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'return_format'     => 'array',
									'preview_size'      => 'full',
									'library'           => 'all',
									'min_width'         => '',
									'min_height'        => '',
									'min_size'          => '',
									'max_width'         => '',
									'max_height'        => '',
									'max_size'          => '',
									'mime_types'        => 'jpg, jpeg',
								),
								array(
									'key'               => 'field_5bef13d1868d4',
									'label'             => 'Story Title',
									'name'              => 'gmucf_story_title',
									'type'              => 'text',
									'instructions'      => 'This will replace the story\'s title in the email.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'prepend'           => '',
									'append'            => '',
									'maxlength'         => '',
								),
								array(
									'key'               => 'field_5bef13d1868d5',
									'label'             => 'Story Description',
									'name'              => 'gmucf_story_description',
									'type'              => 'textarea',
									'instructions'      => 'This will replace the story\'s Deck field value in the email.',
									'required'          => 0,
									'conditional_logic' => 0,
									'wrapper'           => array(
										'width' => '',
										'class' => '',
										'id'    => '',
									),
									'default_value'     => '',
									'placeholder'       => '',
									'maxlength'         => '',
									'rows'              => '',
									'new_lines'         => '',
								),
							),
						),
					),
					'min'        => '1',
					'max'        => '',
				),
				'5be5fff46ee51' => array(
					'key'        => '5be5fff46ee51',
					'name'       => 'gmucf_spotlight',
					'label'      => 'Spotlight',
					'display'    => 'block',
					'sub_fields' => array(
						array(
							'key'               => 'field_5be601316ee53',
							'label'             => 'Spotlight Link',
							'name'              => 'gmucf_spotlight_link',
							'type'              => 'url',
							'instructions'      => 'Enter the URL that users will be directed to when clicking on the spotlight image.',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_5be601706ee54',
							'label'             => 'Spotlight Alt Text',
							'name'              => 'gmucf_spotlight_alt_text',
							'type'              => 'text',
							'instructions'      => 'Enter the alternative text for the spotlight image here. Describe what the image is of and any text in the image. This text will also be used in the text only version of the email, so make sure it\'s descriptive.',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_5be600be6ee52',
							'label'             => 'Spotlight Image',
							'name'              => 'gmucf_spotlight_image',
							'type'              => 'image',
							'instructions'      => 'Select an image to use as the spotlight.',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'array',
							'preview_size'      => 'full',
							'library'           => 'all',
							'min_width'         => '',
							'min_height'        => '',
							'min_size'          => '',
							'max_width'         => '',
							'max_height'        => '',
							'max_size'          => '',
							'mime_types'        => 'jpg, jpeg, gif',
						),
					),
					'min'        => '',
					'max'        => '2',
				),
			),
			'button_label'      => 'Add Content',
			'min'               => '',
			'max'               => '',
		);

		/**
		 * Adds GMUCF Announcements field
		 **/
		$fields[] = array(
			'key'               => 'field_5c5ca43d3a608',
			'label'             => 'GMUCF Announcements',
			'name'              => 'gmucf_announcements',
			'type'              => 'select',
			'instructions'      => 'Select the announcements to be displayed on the News and Announcements email.',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'choices'           => array(
			),
			'default_value'     => array(
			),
			'allow_null'        => 1,
			'multiple'          => 1,
			'ui'                => 1,
			'ajax'              => 1,
			'return_format'     => 'value',
			'placeholder'       => '',
		);

		/**
		 * Defines field group
		 */
		$field_group = array(
			'key'                   => 'group_5be5fd670dcaf',
			'title'                 => 'GMUCF Options Page',
			'fields'                => $fields,
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'gmucf-email',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		);

		acf_add_local_field_group( $field_group );
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
	if ( isset( $story['acf_fc_layout'] ) && ! empty( $story['acf_fc_layout'] ) ) {
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
			$story['gmucf_spotlight_image'] = $story['gmucf_spotlight_image']['sizes']['medium_large'];
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
