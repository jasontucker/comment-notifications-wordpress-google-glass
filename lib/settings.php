<?php

function ggc_settings_api_init() {
	$options = get_ggc_options();

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

	function ggc_api_settings_title() {
		echo sprintf( '<p>%s</p>', __( 'Enter your Google Mirror API Information below.' ) );
	}

	function ggc_text_field( $args ) {
		echo sprintf( '<input type="text" name="%s" value="%s" class="%s" /><span class="description">%s</span>', esc_attr( $args['name'] ), esc_attr( $args['value'] ), esc_attr( $args['class'] ), esc_html( $args['desc'] ) );
	}

function ggc_sanitize_settings( $input ) {
	$options = get_ggc_options();

	$output['api-client-id'] = $input['api-client-id'];
	$output['api-client-secret'] = $input['api-client-secret'];
	$output['api-simple-key'] = $input['api-simple-key'];
	return $output;
}

function get_ggc_options() {
	$defaults = array(
		'api-client-id' => '',
		'api-client-secret' => '',
		'api-simple-key' => ''
	);
	$saved = get_option( 'ggc-settings' );
	$options = wp_parse_args( $saved, $defaults );

	return $options;
}
