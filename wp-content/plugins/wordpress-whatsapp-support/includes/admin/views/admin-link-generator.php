<div class="wws-link-generator">

	<div class="flex-grid flex-grid-2">

		<!-- .col -->
		<div class="col">

			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Link Type', 'wc-wws' ) ?></label>
				<select id="wws-link-gen-link-type">
					<option value="chat-link" selected="selected"><?php esc_html_e( 'Chat Link', 'wc-wws' ) ?></option>
					<option value="group-link"><?php esc_html_e( 'Group Invitation Link', 'wc-wws' ) ?></option>
				</select>
			</div>

			<div class="wws-admin-field" id="wws-link-gen-chat">
				<label for=""><?php esc_html_e( 'WhatsApp Number', 'wc-wws' ) ?></label>
				<input type="number" id="wws-link-gen-chat-number" value="911234567890">
				<p><?php esc_html_e( 'Enter mobile phone number with the international country code, without "+" character', 'wc-wws' ) ?></p>
			</div>

			<div class="wws-admin-field" id="wws-link-gen-group">
				<label for=""><?php esc_html_e( 'Group Chat ID', 'wc-wws' ) ?></label>
				<input type="text" id="wws-link-gen-group-id" value="XYZ12345678">
			</div>

			<div class="wws-admin-field" id="wws-link-gen-message-field">
				<label for=""><?php esc_html_e( 'Pre Populate Message', 'wc-wws' ) ?></label>
				<textarea rows="2" id="wws-link-gen-message">Hello.</textarea>
				<p><?php esc_html_e( 'Enter a message that you will receive when someone clicks on the link.', 'wc-wws' ) ?></p>
			</div>

		</div><!-- /.col -->

		<!-- .col -->
		<div class="col">

			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Generated Link', 'wc-wws' ) ?></label>
				<textarea id="wws-link-gen-link" rows="5"></textarea>
				<p><?php esc_html_e( 'Copy link and share. Have fun! :)', 'wc-wws' ) ?></p>
			</div>

			<button id="wws-link-gen-code" class="button button-primary"><?php esc_html_e( 'Generate Now', 'wc-wws' ) ?></button>

		</div><!-- /.col -->

	</div>

</div>
