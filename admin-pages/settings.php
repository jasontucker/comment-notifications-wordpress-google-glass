<?php if( ! defined('ABSPATH') ) die( __('You suck.') ); ?>
<div class="wrap">
	<?php screen_icon('options-general') ?><h2><?php _e( 'Google Glass Settings' ); ?></h2>

	<form method="post" action="options.php">
		<?php settings_fields( 'ggc-settings' ); ?>
		<?php do_settings_sections( 'google-glass-comments' ) ?>
		<?php submit_button( __( 'Save Settings' ) ) ?>
	</form>
</div>
