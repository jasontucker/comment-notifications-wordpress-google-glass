<?php if( ! defined('ABSPATH') ) die( __( 'You suck.' ) ); ?>

<div class="wrap">
	<?php screen_icon('options-general') ?><h2><?php _e( 'Google Glass Settings' ); ?></h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'ggc-settings' ); ?>
		<?php do_settings_sections( 'google-glass-comments' ) ?>
		<?php submit_button( __( 'Save Settings' ) ) ?>
		<?php
			$options = ggc_get_options();
			if( ! empty( $options['api-client-id'] ) && ! empty( $options['api-client-secret'] ) && ! empty( $options['api-simple-key'] ) && empty( $options['oauth-access-token'] ) ) :
				echo sprintf( '<p><a href="%s" class="button-secondary">%s</a></p>', gcc_get_oauth_link(), __( 'Authenticate with Google' ) );
			endif;
		?>
	</form>
</div>
