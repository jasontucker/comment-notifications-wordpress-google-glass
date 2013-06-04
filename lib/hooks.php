<?php

// google-glass-comments.php
add_action( 'wp_insert_comment',	'ggc_send_notification', 10, 2 );
add_action( 'admin_menu',			'ggc_create_admin_menus' );

// lib/settings.php
add_action( 'admin_init',			'ggc_settings_api_init' );
add_action( 'admin_notices',		'ggc_admin_notices' );
