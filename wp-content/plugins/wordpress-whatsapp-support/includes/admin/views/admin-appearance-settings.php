<?php ob_start();?>
<table>
	<tr>
		<td>
		<?php
		$wws_layouts = array(
			array(
				'layout'       => 1,
				'layout_image' => WWS_PLUGIN_URL . 'assets/img/admin/template-1.png',
			),
			array(
				'layout'       => 2,
				'layout_image' => WWS_PLUGIN_URL . 'assets/img/admin/template-2.png',
			),
			array(
				'layout'       => 3,
				'layout_image' => WWS_PLUGIN_URL . 'assets/img/admin/template-3.png',
			),
			array(
				'layout'       => 4,
				'layout_image' => WWS_PLUGIN_URL . 'assets/img/admin/template-4.png',
			),
			array(
				'layout'       => 5,
				'layout_image' => WWS_PLUGIN_URL . 'assets/img/admin/template-5.png',
			),
			array(
				'layout'       => 6,
				'layout_image' => WWS_PLUGIN_URL . 'assets/img/admin/template-6.png',
			),
			array(
				'layout'       => 7,
				'layout_image' => WWS_PLUGIN_URL . 'assets/img/admin/template-7.png',
			),
			array(
				'layout'       => 8,
				'layout_image' => WWS_PLUGIN_URL . 'assets/img/admin/template-8.png',
			),
		);
		$wws_layouts = apply_filters( 'wws_layouts', $wws_layouts, get_option( 'wws_layout' ) );

		foreach ( $wws_layouts as $layout ):
		?>
			<label class="sk-wws-admin-label">
				<input
					type="radio"
					name="wws_layout"
					class="sk-wws-admin-radio"
					value="<?php echo intval( $layout['layout'] ); ?>"
					<?php checked( get_option( 'wws_layout' ), $layout['layout'] );?>>
				<img src="<?php echo esc_url( $layout['layout_image'] ); ?>" class="sk-wws-admin-radio-image" width="240" alt="//">
			</label>
		<?php endforeach;?>
		</td>
	</tr>
</table>
<?php

$select_layout_field = ob_get_clean();

return apply_filters( 'wws_appearance_settings', array(
	'wws_layout'                   => array(
		'title'    => __( 'Select Layout', 'wc-wws' ),
		'desc_tip' => __( 'Select your layout.', 'wc-wws' ),
		'id'       => 'wws_layout',
		'default'  => 1,
		'type'     => 'custom',
		'custom'   => $select_layout_field,
	),
	'wws_layout_background_color'  => array(
		'title'    => __( 'Layout Background Color', 'wc-wws' ),
		'desc_tip' => __( 'Set popup layout background color.', 'wc-wws' ),
		'id'       => 'wws_layout_background_color',
		'default'  => '#22c15e',
		'type'     => 'color',
	),
	'wws_layout_text_color'        => array(
		'title'    => __( 'Layout Text Color', 'wc-wws' ),
		'desc_tip' => __( 'Set popup layout text color.', 'wc-wws' ),
		'id'       => 'wws_layout_text_color',
		'default'  => '#ffffff',
		'type'     => 'color',
	),
	'wws_gradient_status'          => array(
		'title'    => __( 'Enable Layout Gradient', 'wc-wws' ),
		'desc'     => __( 'Enable/ Disable', 'wc-wws' ),
		'desc_tip' => __( 'Enable/ Disable popup background gradient.', 'wc-wws' ),
		'id'       => 'wws_gradient_status',
		'default'  => 'yes',
		'type'     => 'checkbox',
	),
	'wws_trigger_button_only_icon' => array(
		'title'    => __( 'Show Only Icon on Mobile', 'wc-wws' ),
		'desc'     => __( 'Enable/ Disable', 'wc-wws' ),
		'desc_tip' => __( 'Enable this option if you what to display an icon instead of text button on mobile.', 'wc-wws' ),
		'id'       => 'wws_trigger_button_only_icon',
		'default'  => 'no',
		'type'     => 'checkbox',
	),
) );
