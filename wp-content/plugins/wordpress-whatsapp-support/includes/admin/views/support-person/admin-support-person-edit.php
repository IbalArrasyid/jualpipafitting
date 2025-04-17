<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Support Person', 'wc-wws' ); ?></h1>

	<?php if ( isset( $_GET['notice'] ) ) : ?>
	<div class="notice notice-success">
		<p><?php echo wp_kses_post( $_GET['notice'] ); ?></p>
	</div>
	<?php endif; ?>

	<hr>

	<?php ob_start(); ?>
	<table>
		<?php
		$support_person = new WWS_Support_Person( $_GET['id'] );
		$week           = array(
			'mon' => __( 'Monday', 'wc-wws' ),
			'tue' => __( 'Tuesday', 'wc-wws' ),
			'wed' => __( 'Wednesday', 'wc-wws' ),
			'thu' => __( 'Thursday', 'wc-wws' ),
			'fri' => __( 'Friday', 'wc-wws' ),
			'sat' => __( 'Saturday', 'wc-wws' ),
			'sun' => __( 'Sunday', 'wc-wws' ),
		);

		foreach ( $week as $day_key => $day_name ) :
			$schedule    = $support_person->get_schedule();
			$is_schedule = isset( $schedule[ $day_key ]['availability'] ) ? 'checked="checked"' : '';
			$titming     = isset( $schedule[ $day_key ]['timing'] ) ? $schedule[ $day_key ]['timing'] : '';

		?>
		<tr>
			<th>
				<label>
					<input
						type="checkbox"
						name="wws_multi_account[schedule][<?php echo esc_attr( $day_key ); ?>][availability]"
						value="yes"
						<?php echo esc_attr( $is_schedule ); ?> > <?php echo esc_html( $day_name ); ?>
				</label>
			</th>
			<td>
				<div class="wws-support-person-avaliability">

					<?php foreach ( $titming as $slot_key => $slot ) : ?>

						<div data-slot="<?php echo absint( $slot_key ); ?>">
							<input
								type="text"
								class="wws-timepicker"
								name="wws_multi_account[schedule][<?php echo esc_attr( $day_key ); ?>][timing][<?php echo esc_attr( $slot_key ); ?>][start]"
								value="<?php echo esc_attr( $slot['start'] ); ?>"
								required> -
							<input
								type="text"
								class="wws-timepicker"
								name="wws_multi_account[schedule][<?php echo esc_attr( $day_key ); ?>][timing][<?php echo esc_attr( $slot_key ); ?>][end]"
								value="<?php echo esc_attr( $slot['end'] ); ?>"
								required>

							<?php if ( 0 === $slot_key ) : ?>
								<a
									href="javascript:;"
									class="js-wws-support-person-avaliability-add wws-support-person-avaliability--add"
									data-day="<?php echo esc_attr( $day_key ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
									</svg>
								</a>
							<?php else : ?>
								<a
									href="javascript:;"
									class="js-wws-support-person-avaliability-remove wws-support-person-avaliability--remove"
									data-day="<?php echo esc_attr( $day_key ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
									</svg>
								</a>
							<?php endif; ?>
						</div>

					<?php endforeach; ?>
				</div>
			</td>
		</tr>
		<?php endforeach; ?>

	</table>
	<?php
	$schedule = ob_get_clean();

	$setting_api = new WWS_Admin_Settings_API;

	$fields = apply_filters( 'wws_edit_multiperson_settings', array(
		'contact'     => array(
			'title'             => __( 'Support Person Contact', 'wc-wws' ),
			'desc'              => __( 'Enter mobile phone number with the international country code, without "+" character. Example:  911234567890 for (+91) 1234567890', 'wc-wws' ),
			'id'                => 'wws_multi_account[contact]',
			'type'              => 'number',
			'class'             => 'regular-text',
			'value'             => $support_person->get_contact(), // WPCS: XSS ok.
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
			'value'     => $support_person->get_name(), // WPCS: XSS ok.
		),
		'title'       => array(
			'title' => __( 'Support Person Title', 'wc-wws' ),
			'desc'  => __( 'Enter support person title/designation.', 'wc-wws' ),
			'id'    => 'wws_multi_account[title]',
			'type'  => 'text',
			'class' => 'regular-text',
			'value'     => $support_person->get_title(), // WPCS: XSS ok.
		),
		'image'       => array(
			'title' => __( 'Support Person Image', 'wc-wws' ),
			'desc'  => __( 'Add support person image', 'wc-wws' ),
			'id'    => 'wws_multi_account[image]',
			'value'     => $support_person->get_image(), // WPCS: XSS ok.
			'type'  => 'file',
		),
		'pre_message' => array(
			'title' => __( 'Support Pre Message', 'wc-wws' ),
			'desc'  => wp_kses_post( sprintf( __( '%s to display current page title in chat.<br>%s to display current page URL in chat.<br>%s to break the line into a new line.', 'wc-wws' ), '<code>{title}</code>', '<code>{url}</code>', '<code>{br}</code>' ) ),
			'id'    => 'wws_multi_account[pre_message]',
			'value'     => $support_person->get_pre_message(), // WPCS: XSS ok.
			'type'  => 'textarea',
			'class' => 'regular-text',
			'css'   => 'height:120px',

		),
		'call_number' => array(
			'title'             => __( 'Support Call Number', 'wc-wws' ),
			'desc'              => __( 'Enter mobile phone number with the international country code, <strong>with "+"</strong> character. Example:  +911234567890 for (+91) 1234567890', 'wc-wws' ),
			'id'                => 'wws_multi_account[call_number]',
			'type'              => 'text',
			'value'  			=> 	$support_person->get_call_number() ? $support_person->get_call_number() : '', // WPCS: XSS ok.
			'class'             => 'regular-text',
		),
		'schedule'    => array(
			'title'  => __( 'Schedule', 'wc-wws' ),
			'desc'   => __( 'Schedule by days to show contact person availablity. Time format HH:MM', 'wc-wws' ),
			'type'   => 'custom',
			'custom' => $schedule,
		),
		'hidden_567'  => array(
			'type'  => 'hidden',
			'value' => $support_person->get_id(),
			'id'  => 'wws_multi_account[key]',
		),
		'hidden_879'  => array(
			'type'  => 'hidden',
			'value' => 'value',
			'id'  => 'wws_edit_multi_account_submit',
		),
	), $support_person->get_id() );

	$setting_api->render_form( $fields, array( 'class' => 'wws-admin-edit-multiperson-form' ) );
	?>

</div>
