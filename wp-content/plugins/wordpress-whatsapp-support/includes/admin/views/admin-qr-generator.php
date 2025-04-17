<div class="wws-button-generator">
	<div class="flex-grid flex-grid-2">
		<div class="col">
			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Support Number', 'wc-wws' ) ?></label>
				<input id="wws-qr-number" type="number" value="911234567890">
				<p><?php esc_html_e( 'Enter mobile phone number with the international country code, without "+" character', 'wc-wws' ) ?></p>
			</div>
			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Enter Message', 'wc-wws' ) ?></label>
				<textarea id="wws-qr-textarea" rows="5"><?php esc_html_e( 'Scan QR to talk us via WhatsApp', 'wc-wws' ) ?></textarea>
				<p></p>
			</div>
			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'QR Image Size', 'wc-wws' ) ?></label>
				<input id="wws-qr-size" type="number" value="150" min="50" max="1000">
				<p><?php esc_html_e( 'Enter the QR image size in pixel.', 'wc-wws' ) ?></p>
			</div>
		</div>
		<div class="col">
			<div id="wws-admin-qr-view">
				<p><strong><?php esc_html_e( 'Visual Representation', 'wc-wws' ) ?></strong></p>
				<img src="<?php echo WWS_PLUGIN_URL . 'assets/img/admin/qr-demo.png' ?>" alt="//" title="" />
				<div><?php esc_html_e( 'Scan QR to talk us via WhatsApp', 'wc-wws' ) ?></div>
			</div>
			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Generated Shortcode', 'wc-wws' ) ?></label>
				<textarea id="wws-qr-gen-shortcode" rows="5"></textarea>
				<p><?php esc_html_e( 'Copy shortcode and paste wherever you want. Have fun! :)', 'wc-wws' ) ?></p>
			</div>
			<button id="wws-qr-gen-code" class="button button-primary"> <i class="wc-fa wc-fa-spinner wc-fa-spin"></i> <?php esc_html_e( 'Generate Now', 'wc-wws' ) ?></button>
		</div>
	</div>
</div>
