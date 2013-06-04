<?php
/*
Plugin Name: Google Glass Comments
Plugin URI: http://example.com/
Description: WordPress plugin that delivers notifications to the site owner's Google Glass when a new comment is made on the site.
Version: 1.0
Author: OCWP Developers
Author URI: http://ocwp.org/
*/

/**
 * Copyright (c) `date "+%Y"` Your Name. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

// SET UP PATH CONSTANTS
define( 'GGC',					'google-glass-comments' );
define( 'GGC_VERSION',			'1.0' );
define( 'GGC_URL',				plugin_dir_url( __FILE__ ) );
define( 'GGC_ABS',				plugin_dir_path( __FILE__ ) );
define( 'GGC_SLUG',				plugin_basename( __FILE__ ) );
define( 'GGC_REL',				basename( dirname( __FILE__ ) ) );
define( 'GGC_LANG',				GGC_ABS.'i18n/' );
define( 'GGC_ADMIN_PAGES',		GGC_ABS.'admin-pages/' );
define( 'GGC_LIB',				GGC_ABS.'lib/' );
define( 'GGC_CSS',				GGC_URL.'css/' );
define( 'GGC_IMG',				GGC_URL.'images/' );
define( 'GGC_JS',				GGC_URL.'js/' );

// INTERNATIONALIZATION
load_plugin_textdomain( GGC, null, GGC_REL );

require_once( GGC_LIB.'hooks.php' );
require_once( GGC_LIB.'settings.php' );
require_once( GGC_LIB.'notifications.php' );

/**
 * Create the settings page
 * @since  1.0
 */
function ggc_create_admin_menus() {
	add_comments_page( 'Google Glass', 'Google Glass', 'edit_posts', 'google-glass-comments', 'ggc_settings_page' );
}
	function ggc_settings_page() {
		require_once( GGC_ADMIN_PAGES.'settings.php' );
	}
