<?php ob_start();?>
<table id="sk-wws__schedule-table">
<?php
foreach ( get_option( 'wws_filter_by_schedule' ) as $day => $schedule ):
	$day_name = '';

	if ( 'mon' === $day ) {
		$day_name = esc_html__( 'Monday', 'wc-wws' );
	}
	if ( 'tue' === $day ) {
		$day_name = esc_html__( 'Tuesday', 'wc-wws' );
	}
	if ( 'wed' === $day ) {
		$day_name = esc_html__( 'Wednesday', 'wc-wws' );
	}
	if ( 'thu' === $day ) {
		$day_name = esc_html__( 'Thursday', 'wc-wws' );
	}
	if ( 'fri' === $day ) {
		$day_name = esc_html__( 'Friday', 'wc-wws' );
	}
	if ( 'sat' === $day ) {
		$day_name = esc_html__( 'Saturday', 'wc-wws' );
	}
	if ( 'sun' === $day ) {
		$day_name = esc_html__( 'Sunday', 'wc-wws' );
	}
	?>
		<tr>
			<td>
				<input type="checkbox" name="wws_filter_by_schedule[<?php echo esc_attr( $day ); ?>][status]" <?php checked( $schedule['status'], 'yes' );?>> <?php echo $day_name; // WPCS: XSS ok     ?>
			</td>
			<td>
				<input type="text" name="wws_filter_by_schedule[<?php echo esc_attr( $day ); ?>][start]" class="wws-timepicker" value="<?php echo esc_attr( $schedule['start'] ); ?>">
			</td>
			<td>-</td>
			<td>
				<input type="text" name="wws_filter_by_schedule[<?php echo esc_attr( $day ); ?>][end]" class="wws-timepicker" value="<?php echo esc_attr( $schedule['end'] ); ?>">
			</td>
		</tr>
<?php endforeach;?>
</table>
<?php $schedule_option = ob_get_clean();

return apply_filters( '', array(
	'wws_x_axis_offset'             => array(
		'title'             => __( 'X-axis Offset', 'wc-wws' ),
		'desc'              => __( 'In px ( pixels ) only. Default 12px.', 'wc-wws' ),
		'desc_tip'          => __( 'Enter the value of x-axis ( horizontal ) widget spacing.', 'wc-wws' ),
		'id'                => 'wws_x_axis_offset',
		'default'           => '12',
		'type'              => 'number',
		'class'             => 'small-text',
		'custom_attributes' => array(
			'step' => '1',
			'min'  => '0',
			'max'  => '200',
		),
	),
	'wws_y_axis_offset'             => array(
		'title'             => __( 'Y-axis Offset', 'wc-wws' ),
		'desc'              => __( 'In px ( pixels ) only. Default 12px.', 'wc-wws' ),
		'desc_tip'          => __( 'Enter the value of y-axis ( vertical ) widget spacing.', 'wc-wws' ),
		'id'                => 'wws_y_axis_offset',
		'default'           => '12',
		'type'              => 'number',
		'class'             => 'small-text',
		'custom_attributes' => array(
			'step' => '1',
			'min'  => '0',
			'max'  => '200',
		),
	),
	'wws_display_on_desktop'        => array(
		'title'    => __( 'Display On Desktop', 'wc-wws' ),
		'desc_tip' => __( 'Display on desktop/laptop', 'wc-wws' ),
		'id'       => 'wws_display_on_desktop',
		'default'  => 'yes',
		'type'     => 'select',
		'options'  => array(
			'yes' => __( 'Yes', 'wc-wws' ),
			'no'  => __( 'No', 'wc-wws' ),
		),
	),
	'wws_desktop_location'          => array(
		'desc'    => __( 'Select the location of the widget on desktop.', 'wc-wws' ),
		'id'      => 'wws_desktop_location',
		'default' => 'br',
		'type'    => 'select',
		'options' => array(
			'tl' => __( 'Top Left', 'wc-wws' ),
			'tc' => __( 'Top Center', 'wc-wws' ),
			'tr' => __( 'Top Right', 'wc-wws' ),
			'bl' => __( 'Bottom Left', 'wc-wws' ),
			'bc' => __( 'Bottom Center', 'wc-wws' ),
			'br' => __( 'Bottom Right', 'wc-wws' ),
		),
	),
	'wws_display_on_mobile'         => array(
		'title'    => __( 'Display On Mobile', 'wc-wws' ),
		'desc_tip' => __( 'Display on mobile devices', 'wc-wws' ),
		'id'       => 'wws_display_on_mobile',
		'default'  => 'yes',
		'type'     => 'select',
		'options'  => array(
			'yes' => __( 'Yes', 'wc-wws' ),
			'no'  => __( 'No', 'wc-wws' ),
		),
	),
	'wws_mobile_location'           => array(
		'desc'    => __( 'Select the location of the widget on mobile.', 'wc-wws' ),
		'id'      => 'wws_mobile_location',
		'default' => 'br',
		'type'    => 'select',
		'options' => array(
			'tl' => __( 'Top Left', 'wc-wws' ),
			'tc' => __( 'Top Center', 'wc-wws' ),
			'tr' => __( 'Top Right', 'wc-wws' ),
			'bl' => __( 'Bottom Left', 'wc-wws' ),
			'bc' => __( 'Bottom Center', 'wc-wws' ),
			'br' => __( 'Bottom Right', 'wc-wws' ),
		),
	),
	'wws_auto_popup'                => array(
		'title'    => __( 'Auto Popup', 'wc-wws' ),
		'desc_tip' => __( 'Enter the popup auto display time in seconds', 'wc-wws' ),
		'id'       => 'wws_auto_popup',
		'default'  => 'yes',
		'type'     => 'select',
		'options'  => array(
			'yes' => __( 'Yes', 'wc-wws' ),
			'no'  => __( 'No', 'wc-wws' ),
		),
	),
	'wws_auto_popup_time'           => array(
		'title'             => __( 'Auto Popup Delay', 'wc-wws' ),
		'desc_tip'          => __( 'Select the time in seconds for autopopup delay.', 'wc-wws' ),
		'id'                => 'wws_auto_popup_time',
		'default'           => '5',
		'type'              => 'number',
		'class'             => 'small-text',
		'custom_attributes' => array(
			'step' => '1',
			'min'  => '0',
			'max'  => '100',
		),
	),
	'wws_rtl_status'                => array(
		'title'             => __( 'Enable RTL', 'wc-wws' ),
		'desc'              => __( 'Enable/ Disable', 'wc-wws' ),
		'desc_tip'          => __( 'You can enable RTL ( Right to Left ) if your website has language like Arabic, Persian and Hebrew.', 'wc-wws' ),
		'id'                => 'wws_rtl_status',
		'default'           => 'no',
		'type'              => 'checkbox',
	),
	'wws_scroll_length'             => array(
		'title'             => __( 'Scroll Length', 'wc-wws' ),
		'desc'              => sprintf( __( 'Default %s.', 'wc-wws' ), '<code>Blank</code>' ),
		'desc_tip'          => __( 'Display button when scroll length matched with above value.', 'wc-wws' ),
		'id'                => 'wws_scroll_length',
		'default'           => '',
		'type'              => 'number',
		'class'             => 'small-text',
		'custom_attributes' => array(
			'step' => '1',
			'min'  => '0',
			'max'  => '100',
		),
	),
	'wws_filter_by_everywhere'      => array(
		'title'   => __( 'Filter By All Pages', 'wc-wws' ),
		'desc'    => __( 'Enable/ Disable', 'wc-wws' ),
		'id'      => 'wws_filter_by_everywhere',
		'default' => 'yes',
		'type'    => 'checkbox',
	),
	'wws_filter_by_front_page'      => array(
		'title'   => __( 'Filter By Front Page', 'wc-wws' ),
		'desc'    => __( 'Enable/ Disable', 'wc-wws' ),
		'id'      => 'wws_filter_by_front_page',
		'default' => 'yes',
		'type'    => 'checkbox',
	),
	'dropdown_pages'                => array(
		'desc'     => __( 'Include popup on Pages', 'wc-wws' ),
		'id'       => 'wws_filter_by_page_id_include',
		'default'  => array(),
		'type'     => 'dropdown_pages',
		'multiple' => true,
		'class'    => 'wws-select2 regular-text',
		'select'   => array(
			'multiple' => true,
		),
	),
	'wws_filter_by_page_id_exclude' => array(
		'desc'     => __( 'Exclude popup on Pages', 'wc-wws' ),
		'id'       => 'wws_filter_by_page_id_exclude',
		'default'  => array(),
		'type'     => 'dropdown_pages',
		'multiple' => true,
		'class'    => 'wws-select2 regular-text',
		'select'   => array(
			'multiple' => true,
		),
	),
	'wws_filter_by_url_exclude'     => array(
		'title'    => __( 'Hide Widget By URL', 'wc-wws' ),
		'desc'     => __( 'One URL per line.', 'wc-wws' ),
		'desc_tip' => __( 'Hide widget by the URL', 'wc-wws' ),
		'id'       => 'wws_filter_by_url_exclude',
		'type'     => 'textarea',
		'css'      => 'height:140px;width:520px;max-width:100%;',
	),
	'wws_filter_by_schedule'        => array(
		'title'             => __( 'Schedule', 'wc-wws' ),
		'desc'              => array(
			__( 'Schedule by days to show WhatsApp Support Popup. Time format HH:MM:SS', 'wc-wws' ),
			sprintf( __( 'Your selected time zone is %s', 'wc-wws' ), '<a href="' . admin_url( 'options-general.php' ) . '">' . wws_selected_timezone() . '</a>' ),
		),
		'id'                => 'wws_filter_by_schedule',
		'type'              => 'custom',
		'custom'            => $schedule_option,
		'sanitize_callback' => 'wws_sanitize_filter_by_schedule',
	),
	'wws_custom_css'                => array(
		'title'    => __( 'Custom CSS', 'wc-wws' ),
		'desc_tip' => __( 'Enter your custom CSS.', 'wc-wws' ),
		'id'       => 'wws_custom_css',
		'type'     => 'textarea',
		'class'    => 'regular-text',
		'css'      => 'height:200px;background:#263238;color:#fff;font-size:13px;width:520px;max-width:100%;',
	),
	'wws_developer_link'            => array(
		'title'    => __( 'Developer Settings', 'wc-wws' ),
		'desc_tip' => __( 'Please do not make changes here without our permission.', 'wc-wws' ),
		'id'       => 'wws_developer_link',
		'value'    => __( 'Goto developer settings', 'wc-wws' ),
		'link'     => admin_url( 'admin.php?page=wc-whatsapp-support_developer-settings' ),
		'type'     => 'link',
	),
) );
