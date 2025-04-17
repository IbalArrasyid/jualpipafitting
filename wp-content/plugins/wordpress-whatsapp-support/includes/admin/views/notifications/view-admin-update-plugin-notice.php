<div class="notice notice-success is-dismissible">
	<h3><?php esc_html_e( 'Thank you for choosing the WordPress Support Using WhatsApp. :)', 'wc-wws' ); ?></h3>
	<p><?php esc_html_e( 'We rapidly improving our plugin by fixing the major and minor bug. Please support us for the best.', 'wc-wws' ); ?></p>

	<h4><?php echo esc_html( sprintf( __( 'Change Log - Version %s', 'wc-wws' ), WWS_PLUGIN_VER ) ); ?></h4>
	<p><?php echo sprintf( wp_kses_post( 'Click <a href="%s" target="_blank">here</a> to see full change log.', 'wc-wws' ), 'https://codecanyon.net/item/x/20963962#item-description__change-log' ); ?></p>
	<p>
		<a href="https://codecanyon.net/item/x/20963962/support" target="_blank" class="button"><?php esc_html_e( 'I found a bug!', 'wc-wws' ); ?></a>
		<a href="https://codecanyon.net/item/x/20963962/support" target="_blank" class="button"><?php esc_html_e( 'I need support', 'wc-wws' ); ?></a>
		<a href="<?php echo wp_nonce_url( '?wws_action=dismiss_plugin_update_notice' ); ?>" class="button button-link-delete"><?php esc_html_e( 'Dismiss notice', 'wc-wws' ); ?></a>
	</p>
</div>
