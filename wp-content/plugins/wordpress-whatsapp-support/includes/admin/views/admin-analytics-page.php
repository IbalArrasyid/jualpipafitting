<?php add_thickbox(); ?>

<div class="wrap">

	<h1><?php esc_html_e( 'WeCreativez WhatsApp Support - Analytics', 'wc-wws' ) ?></h1>

	<?php do_action( 'wws_admin_notifications' ); ?>

	<hr>

	<h3><?php esc_html_e( 'Total Clicks Analytics', 'wc-wws' ) ?></h3>

	<div class="flex-grid flex-grid-3">
		<div class="col">
			<div class="wws-admin-card filled wws-total-click-analytics">
				<h1><?php echo esc_html( $total_clicks ) ?></h1>
				<p><?php echo WWS_Icons::get( 'mouse-pointer' ); ?> <?php echo esc_html_e( 'Total Clicks', 'wc-wws' ) ?></p>
			</div>
		</div>
		<div class="col">
			<div class="wws-admin-card filled wws-total-click-analytics">
				<h1><?php echo esc_html( $total_clicks_by_desktop ) ?></h1>
				<p><?php echo WWS_Icons::get( 'desktop' ); ?> <?php echo esc_html_e( 'Total Clicks By Desktop/ Laptop', 'wc-wws' ) ?></p>
			</div>
		</div>
		<div class="col">
			<div class="wws-admin-card filled wws-total-click-analytics">
				<h1><?php echo esc_html( $total_clicks_by_mobile ) ?></h1>
				<p><?php echo WWS_Icons::get( 'mobile' ); ?> <?php echo esc_html_e( 'Total Clicks By Mobile', 'wc-wws' ) ?></p>
			</div>
		</div>
	</div>

	<hr>

	<!-- Current month click chart -->
	<div class="flex-grid flex-grid-1">
		<div class="col">
			<h3><?php esc_html_e( 'Current Month Click Analytics', 'wc-wws' ); ?></h3>
			<div class="wws-admin-card filled">
				<div class="wws-month-chart"></div>
			</div>
		</div>
	</div>

	<hr>

	<!-- Top 10 click analytics  -->
	<h3><?php esc_html_e( 'Top 10 Click Analytics', 'wc-wws' ); ?></h3>
	<div class="flex-grid flex-grid-1 wws-top-10-click-analytics">
		<div class="col">
			<div class="wws-admin-card filled">
				<p><strong><?php esc_html_e( 'Top 10 IP Address', 'wc-wws' ); ?></strong></p>
				<?php WWS_Analytics::the_top_ten_ip_address(); ?>
			</div>
		</div>
		<div class="col">
			<div class="wws-admin-card filled">
				<p><strong><?php esc_html_e( 'Top 10 Referral Links', 'wc-wws' ); ?></strong></p>
				<?php WWS_Analytics::the_top_ten_referral_links(); ?>
			</div>
		</div>
		<div class="col">
			<div class="wws-admin-card filled">
				<p><strong><?php esc_html_e( 'Top 10 Operating Systems', 'wc-wws' ); ?></strong></p>
				<?php WWS_Analytics::the_top_ten_operating_systems(); ?>
			</div>
		</div>
		<div class="col">
			<div class="wws-admin-card filled">
				<p><strong><?php esc_html_e( 'Top 10 Browsers', 'wc-wws' ); ?></strong></p>
				<?php WWS_Analytics::the_top_ten_browsers(); ?>
			</div>
		</div>
	</div>

	<p>&nbsp;</p>
	<hr>

	<h3><?php esc_html_e( 'Complete Analytics', 'wc-wws' ); ?></h3>

	<?php
		$table = new WWS_Admin_Analytics_Table;
		$table->prepare_items();
	?>
	<form method="post" action="#">
		<?php $table->search_box( 'Search', 'wc-wws' ); ?>
	</form>
	<div>
		<?php $table->display(); ?>
	</div>

	<p>&nbsp;</p>
	<hr>

	<h3><?php esc_html_e( 'Analytics Actions', 'wc-wws' ); ?></h3>
	<div class="flex-grid flex-grid-1">
		<div class="col">
			<div class="wws-admin-card filled">
				<a href="<?php echo wp_nonce_url( '?wws_delete_complete_analytics=1' ); ?>" class="button button-secondary" onclick="return confirm('<?php esc_html_e( 'Are you sure?', 'wc-wws' ) ?>')">
					<?php esc_html_e( 'Delete Complete Analytics', 'wc-wws' ) ?>
				</a>
				<a href="<?php echo wp_nonce_url( '?wws_action=export_analytics_csv' ); ?>" class="button button-secondary">
					<?php esc_html_e( 'Export Analytics CSV', 'wc-wws' ); ?>
				</a>
			</div>
		</div>
	</div>

	<hr>

	<div>
		<p><?php wp_kses_post( _e( '<strong>N/A</strong> means not applicable for the selected layout.', 'wc-wws' ) )?></p>
	</div>

</div>

<script>
	jQuery( document ).ready( function() {
		new Chartist.Line( '.wws-month-chart', {
			labels: [ <?php echo "'" . implode( "', '", $current_month_analytics['labels'] ) . "'"; ?> ],
			series: [
				[ <?php echo implode( ',', $current_month_analytics['days'] ); ?> ]
			]
		}, {
			low: 0,
			showArea: true,
			height: 300,
			seriesBarDistance: 100,
			axisY: {
				low: 1.2,
				referenceValue: 3,
				scaleMinSpace: 40
			},
			plugins: [
				Chartist.plugins.ctPointLabels( {
					labelClass: 'wws-chart-points',
					textAnchor: 'middle',
					labelInterpolationFnc: function( value ) {
						return value ? value : '';
					}
				} )
			]
		} );
	} );
</script>
