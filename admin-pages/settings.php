<?php if( ! defined('ABSPATH') ) die( __('You suck.') ); ?>
<div class="wrap">
	<?php screen_icon('options-general') ?><h2><?php _e( 'Google Glass Settings' ); ?></h2>

	<?php do_settings_sections( 'ggc_settings' ) ?>
</div>
