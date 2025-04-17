<div class="wws-button-generator">

	<div class="flex-grid flex-grid-2">

		<!-- /.col -->
		<div class="col">

			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Button Type', 'wc-wws' ) ?></label>
				<select id="wws-button-gen-button-type">
					<option value="support-button" selected="selected"><?php esc_html_e( 'Support Button', 'wc-wws' ) ?></option>
					<option value="invitation-button"><?php esc_html_e( 'Group Invitation Button', 'wc-wws' ) ?></option>
				</select>
			</div>

			<div class="wws-admin-field" id="wws-button-gen-support">
				<label for=""><?php esc_html_e( 'Support Number', 'wc-wws' ) ?></label>
				<input type="number" id="wws-button-gen-support-number" value="911234567890">
				<p><?php esc_html_e( 'Enter mobile phone number with the international country code, without "+" character', 'wc-wws' ) ?></p>
			</div>

			<div class="wws-admin-field" id="wws-button-gen-invitation">
				<label for=""><?php esc_html_e( 'Group Chat ID', 'wc-wws' ) ?></label>
				<input type="text" id="wws-button-gen-invitation-id" value="XYZ12345678">
			</div>

			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Button Text', 'wc-wws' ) ?></label>
				<input type="text" id="wws-button-gen-button-text" value="Contact Us">
			</div>

			<div class="flex-grid flex-grid-2">

				<div class="col">
					<div class="wws-admin-field">
						<label for=""><?php esc_html_e( 'Button Background Color', 'wc-wws' ) ?></label>
						<input type="text" id="wws-button-gen-bg-color" class="colorpicker" value="#22c15e">
					</div>
				</div>
				<div class="col">
					<div class="wws-admin-field">
						<label for=""><?php esc_html_e( 'Button Text Color', 'wc-wws' ) ?></label>
						<input type="text" id="wws-button-gen-text-color" class="colorpicker" value="#fff">
					</div>
				</div>

			</div>

			<div class="flex-grid flex-grid-3">

				<div class="col">
					<div class="wws-admin-field">
						<label for=""><?php esc_html_e( 'Button Bold Text', 'wc-wws' ) ?></label>
						<select id="wws-button-gen-bold-text">
							<option value="0" selected="selected"><?php esc_html_e( 'No', 'wc-wws' ) ?></option>
							<option value="1"><?php esc_html_e( 'Yes', 'wc-wws' ) ?></option>
						</select>
					</div>
				</div>
				<div class="col">
					<div class="wws-admin-field">
						<label for=""><?php esc_html_e( 'Button Font Style', 'wc-wws' ) ?></label>
						<select id="wws-button-gen-font">
							<option value="inherit" selected=""><?php esc_html_e( 'Theme Default', 'wc-wws' ) ?></option>
								<option value="Lobster">Lobster</option>
								<option value="Satisfy">Satisfy</option>
								<option value="Bree Serif">Bree Serif</option>
								<option value="Oswald">Oswald</option>
								<option value="Ubuntu">Ubuntu</option>
								<option value="Dancing Script">Dancing Script</option>
						</select>
					</div>
				</div>
				<div class="col">
					<div class="wws-admin-field">
						<label for=""><?php esc_html_e( 'Full Width Button', 'wc-wws' ) ?></label>
						<select id="wws-button-gen-full-width">
							<option value="0" selected="selected"><?php esc_html_e( 'No', 'wc-wws' ) ?></option>
							<option value="1"><?php esc_html_e( 'Yes', 'wc-wws' ) ?></option>
						</select>
					</div>
				</div>

			</div>

			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Pre Populate Message', 'wc-wws' ) ?></label>
				<textarea rows="2" id="wws-button-gen-message">Hello.</textarea>
				<p><?php esc_html_e( 'This is what you will receive when user sent message first time.', 'wc-wws' ) ?></p>
			</div>


			<div class="flex-grid flex-grid-2">

				<div class="col">
					<div class="wws-admin-field">
						<label for=""><?php esc_html_e( 'Display On Mobile  ', 'wc-wws' ) ?></label>
						<select id="wws-button-gen-on-mobile">
							<option value="1" selected="selected"><?php esc_html_e( 'Yes', 'wc-wws' ) ?></option>
							<option value="0"><?php esc_html_e( 'No', 'wc-wws' ) ?></option>
						</select>
					</div>
				</div>
				<div class="col">
					<div class="wws-admin-field">
						<label for=""><?php esc_html_e( 'Display On Desktop', 'wc-wws' ) ?></label>
						<select id="wws-button-gen-on-desktop">
							<option value="1" selected="selected"><?php esc_html_e( 'Yes', 'wc-wws' ) ?></option>
							<option value="0"><?php esc_html_e( 'No', 'wc-wws' ) ?></option>
						</select>
					</div>
				</div>

			</div>

		</div><!-- /.col -->

		<!-- /.col -->
		<div class="col">

			<div class="wws-button-gen-visual-representation">
				<p><?php esc_html_e( 'Visual Representation', 'wc-wws' ) ?></p>
				<button id="wws-button-gen-btn-visual"><?php echo WWS_Icons::get( 'whatsapp' ); ?> <span>Contact Us</span></button>

			</div>

			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Generated Shortcode', 'wc-wws' ) ?></label>
				<textarea id="wws-button-gen-shortcode" rows="5"></textarea>
				<p><?php esc_html_e( 'Copy shortcode and paste wherever you want. Have fun! :)', 'wc-wws' ) ?></p>
			</div>

			<div class="wws-admin-field">
				<label for=""><?php esc_html_e( 'Generated HTML', 'wc-wws' ) ?></label>
				<textarea id="wws-button-gen-html" rows="5"></textarea>
				<p><?php esc_html_e( 'Copy HTML and paste wherever you want. Have fun! :)', 'wc-wws' ) ?></p>
			</div>

			<button id="wws-button-gen-code" class="button button-primary"><?php esc_html_e( 'Generate Now', 'wc-wws' ) ?></button>

		</div><!-- /.col -->

	</div>


</div>
