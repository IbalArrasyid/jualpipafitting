<div class="notice notice-warning is-dismissible" >
	<h3><?php esc_html_e( 'Leave A Review?', 'wc-wws' ) ?></h3>
	<p>
		<?php
			wp_kses_post(
				printf(
					__( 'We hope you\'ve enjoyed using %s Would you consider leaving us a review on %s', 'wc-wws' ),
					'<strong>WeCreativez WhatsApp Support!</strong>',
					'<a href="https://codecanyon.net/downloads/">codecanyon.net?</a>'
				)
			)
		?>
	</p>

	<ul class="wws-inline-ul">
		<li>
			<span class="wws-admin-icon dashicons dashicons-external"></span>
			<a href="https://codecanyon.net/downloads/">
				<strong><?php esc_html_e( 'Sure! I\'d love to!', 'wc-wws' ) ?></strong>
			</a>
		</li>
		<li>
			<span class="wws-admin-icon dashicons dashicons-smiley"></span>
			<a href="<?php echo wp_nonce_url( '?wws_action=wws_admin_plugin_reviewed' ) ?>"><?php esc_html_e( 'I\'ve already left a review', 'wc-wws' ) ?></a>
		</li>
		<li>
			<span class="wws-admin-icon dashicons dashicons-dismiss"></span>
			<a href="<?php echo wp_nonce_url( '?wws_action=wws_admin_plugin_reviewed' ) ?>"><?php esc_html_e( 'Never show again', 'wc-wws' ) ?></a>
		</li>
	</ul>
</div>
