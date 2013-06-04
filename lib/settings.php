<?php

function ggc_settings_api_init() {
	// Settings Section
	add_settings_section(
		'ggc_settings',
		'Google Mirror API Settings',
		'ggc_setting_section_callback_function',
		'ggc_settings'
	);

		// Settings Field
		add_settings_field(
			'ggc-settings',
			'Example setting Name',
			'ggc_setting_callback_function',
			'ggc_settings',
			'ggc_settings'
		);

	// Setting name
	register_setting( 'ggc-settings', 'ggc-settings' );
} // ggc_settings_api_init()

	function ggc_setting_section_callback_function() {
		echo '<p>Intro text for our settings section</p>';
	}

	function ggc_setting_callback_function() {
		echo '<input name="ggc_setting_name" id="gv_thumbnails_insert_into_excerpt" type="checkbox" value="1" class="code" ' . checked( 1, get_option('ggc_setting_name'), false ) . ' /> Explanation text';
	}
