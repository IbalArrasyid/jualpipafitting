<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Add Support Person', 'wc-wws' ); ?></h1>
	<hr>

	<?php ob_start(); ?>
	<table>
		<?php
		$week = array(
			'mon' => __( 'Monday', 'wc-wws' ),
			'tue' => __( 'Tuesday', 'wc-wws' ),
			'wed' => __( 'Wednesday', 'wc-wws' ),
			'thu' => __( 'Thursday', 'wc-wws' ),
			'fri' => __( 'Friday', 'wc-wws' ),
			'sat' => __( 'Saturday', 'wc-wws' ),
			'sun' => __( 'Sunday', 'wc-wws' ),
		);
		foreach ( $week as $day_key => $day_name ) : ?>
		<tr>
			<th>
				<label>
					<input type="checkbox" name="wws_multi_account[schedule][<?php echo esc_attr( $day_key ); ?>][availability]" value="yes" checked> <?php echo esc_html( $day_name ); ?>
				</label>
			</th>
			<td>
				<div class="wws-support-person-avaliability">
					<div data-slot="0">
						<input
							type="text"
							class="wws-timepicker"
							name="wws_multi_account[schedule][<?php echo esc_attr( $day_key ); ?>][timing][0][start]"
							value="00:00:00"
							required> -
						<input
							type="text"
							class="wws-timepicker"
							name="wws_multi_account[schedule][<?php echo esc_attr( $day_key ); ?>][timing][0][end]"
							value="23:59:59"
							required>
						<a
							href="javascript:;"
							class="js-wws-support-person-avaliability-add wws-support-person-avaliability--add"
							data-day="<?php echo esc_attr( $day_key ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
							</svg>
						</a>
					</div>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>

	</table>
	<?php
	$schedule = ob_get_clean();

	$setting_api = new WWS_Admin_Settings_API;

	$fields = apply_filters( 'wws_add_multiperson_settings', array(
		'contact'     => array(
			'title'             => __( 'Support Person Contact', 'wc-wws' ),
			'desc'              => __( 'Enter mobile phone number with the international country code, without "+" character. Example:  911234567890 for (+91) 1234567890', 'wc-wws' ),
			'id'                => 'wws_multi_account[contact]',
			'type'              => 'number',
			'class'             => 'regular-text',
			'custom_attributes' => array(
				'step' => '1',
			),
		),
		'name'        => array(
			'title' => __( 'Support Person Name', 'wc-wws' ),
			'desc'  => __( 'Enter support person name', 'wc-wws' ),
			'id'    => 'wws_multi_account[name]',
			'type'  => 'text',
			'class' => 'regular-text',
		),
		'title'       => array(
			'title' => __( 'Support Person Title', 'wc-wws' ),
			'desc'  => __( 'Enter support person title/designation.', 'wc-wws' ),
			'id'    => 'wws_multi_account[title]',
			'type'  => 'text',
			'class' => 'regular-text',
		),
		'image'       => array(
			'title' => __( 'Support Person Image', 'wc-wws' ),
			'desc'  => __( 'Add support person image', 'wc-wws' ),
			'id'    => 'wws_multi_account[image]',
			'type'  => 'file',
		),
		'pre_message' => array(
			'title' => __( 'Support Pre Message', 'wc-wws' ),
			'desc'  => wp_kses_post( sprintf( __( '%s to display current page title in chat.<br>%s to display current page URL in chat.<br>%s to break the line into a new line.', 'wc-wws' ), '<code>{title}</code>', '<code>{url}</code>', '<code>{br}</code>' ) ),
			'id'    => 'wws_multi_account[pre_message]',
			'value' => '{br}' . PHP_EOL . 'Page Title: {title}{br}' . PHP_EOL . 'Page URL: {url}',
			'type'  => 'textarea',
			'class' => 'regular-text',
			'css'   => 'height:120px',
		),
		'call_number' => array(
			'title'             => __( 'Support Call Number', 'wc-wws' ),
			'desc'              => __( 'Enter mobile phone number with the international country code, <strong>with "+"</strong> character. Example:  +911234567890 for (+91) 1234567890', 'wc-wws' ),
			'id'                => 'wws_multi_account[call_number]',
			'type'              => 'text',
			'class'             => 'regular-text',
		),
		'schedule'    => array(
			'name'     => 'eee',
			'title'  => __( 'Schedule', 'wc-wws' ),
			'desc'   => __( 'Schedule by days to show contact person availablity. Time format HH:MM', 'wc-wws' ),
			'type'   => 'custom',
			'custom' => $schedule,
		),
		'hidden_879'  => array(
			'type'  => 'hidden',
			'value' => 'value',
			'name'  => 'wws_add_multi_account_submit',
		),
	) );

	$setting_api->render_form( $fields, array( 'class' => 'wws-admin-add-multiperson-form' ) );
	?>
</div>
