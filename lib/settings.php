<?php

/**
 * Sets up all of the settings for the settings page
 * @since  1.0
 */
function ggc_settings_api_init() {
	$options = ggc_get_options();

	// Setting name
	register_setting(
		'ggc-settings',
		'ggc-settings',
		'ggc_sanitize_settings'
	);

	// Settings Section
	add_settings_section(
		'ggc-api-settings',					// section id
		__( 'Google Mirror API Settings' ),	// section title
		'ggc_api_settings_title',			// section description callback
		'google-glass-comments'				// page slug
	);

		// Settings Field
		add_settings_field(
			'api-client-id',			// field id
			__( 'Client ID' ),		// field title
			'ggc_text_field',	// field output callback
			'google-glass-comments',	// page slug
			'ggc-api-settings',
			array(
				'name'	=> 'ggc-settings[api-client-id]',
				'value'	=> $options['api-client-id'],
				'class'	=> 'large-text',
				'desc'	=> __( '' )
			)
		);

		// Settings Field
		add_settings_field(
			'api-client-secret',			// field id
			__( 'Client Secret' ),		// field title
			'ggc_text_field',	// field output callback
			'google-glass-comments',	// page slug
			'ggc-api-settings',
			array(
				'name'	=> 'ggc-settings[api-client-secret]',
				'value'	=> $options['api-client-secret'],
				'class'	=> 'large-text',
				'desc'	=> __( '' )
			)
		);

		// Settings Field
		add_settings_field(
			'api-simple-key',			// field id
			__( 'Simple Key' ),		// field title
			'ggc_text_field',	// field output callback
			'google-glass-comments',	// page slug
			'ggc-api-settings',
			array(
				'name'	=> 'ggc-settings[api-simple-key]',
				'value'	=> $options['api-simple-key'],
				'class'	=> 'large-text',
				'desc'	=> __( '' )
			)
		);

} // ggc_settings_api_init()

	/**
	 * Outputs a description for the settings section
	 * @since  1.0
	 */
	function ggc_api_settings_title() {
		echo sprintf( '<p>%s</p>', __( 'Enter your Google Mirror API Information below.' ) );
	}

	/**
	 * A basic text field template
	 * @param  array  $args an array containing values to be used in the output
	 * @since  1.0
	 */
	function ggc_text_field( $args = array() ) {
		echo sprintf( '<input type="text" name="%s" value="%s" class="%s" /><span class="description">%s</span>', esc_attr( $args['name'] ), esc_attr( $args['value'] ), esc_attr( $args['class'] ), esc_html( $args['desc'] ) );
	}

	/**
	 * Sanitizes and validates input data
	 * @param  array  $input an array of user submitted post variables
	 * @return array        squeaky clean data ready to be saved
	 * @since  1.0
	 */
	function ggc_sanitize_settings( $input = array() ) {
		$options = ggc_get_options();

		$output['api-client-id'] = $input['api-client-id'];
		$output['api-client-secret'] = $input['api-client-secret'];
		$output['api-simple-key'] = $input['api-simple-key'];

		if( count( $input ) > 3 )
			add_settings_error( 'ggc-settings', 'bad-behavior', apply_filters( 'ggc-bad-behavior-message', __( 'We updated the settings, but are not impressed with your shady behavior. The system administrator has been notified.' ) ), 'updated' );
		else
			add_settings_error( 'ggc-settings', 'success', apply_filters( 'ggc-success-message', __( 'Settings have been saved!' ) ), 'updated' );

		return (array) $output;
	}

/**
 * Utility function to get all of the options filled with defaults if they don't exist
 * @return array all possible options with either saved or empty values
 * @since  1.0
 */
function ggc_get_options() {
	$defaults = array(
		'api-client-id' => '',
		'api-client-secret' => '',
		'api-simple-key' => ''
	);
	$saved = get_option( 'ggc-settings' );
	$options = wp_parse_args( $saved, $defaults );

	return $options;
}

/**
 * Utility function to update all of the options
 * @since  1.0
 */
function ggc_set_options( $options = array() ) {
	update_option( 'ggc-settings', $options );
}

/**
 * Displays errors and notices for Google Glass Coments settings
 * @since  1.0
 */
function ggc_admin_notices() {
    settings_errors( 'ggc-settings' );
}
