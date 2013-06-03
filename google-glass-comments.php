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

add_action('wp_insert_comment', 'ggc_send_notification', 10, 2 );
function ggc_send_notification( $id, $comment ) {
	// do stuff
}
