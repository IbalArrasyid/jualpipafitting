<?php
defined( 'ABSPATH' ) || exit;

class WWS_Admin_Dashboard_Widget {

	public function __construct() {
		add_action( 'admin_head', array( $this, 'admin_style' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );
	}

	/**
	 * Add dashobard widget for quick click analytics
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function add_dashboard_widgets() {
		wp_add_dashboard_widget(
			'wws_analytics_dashboard_widget',
			esc_html__( 'Quick Click Analytics', 'wc-wws' ),
			array( $this, 'dashboard_widget_render' )
		);
	}

	/**
	 * Display dashboard widget.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function dashboard_widget_render() {
		?>
		<div class="wws-analytics-dashobard-widget">
			<div class="wws-analytics-dashobard-widget__header">
				<a href="https://wecreativez.com/" title="WeCreativez">
					<img src="<?php echo WWS_PLUGIN_URL . 'assets/img/wecreativez-logo.svg' ?>" alt="//" width="60">
				</a>
				<div class="wws-branding">
					<a href="https://codecanyon.net/item/x/20963962" target="_blank">
						<strong><?php echo esc_html( 'WordPress Support Using WhatsApp', 'wc-wws' ); ?></strong>
					</a>
					<p><?php echo wp_kses_post( sprintf( esc_html__( 'Current Version: %s', 'wc-wws' ), WWS_PLUGIN_VER ) ); ?></p>
				</div>
			</div>
			<div class="wws-analytics-dashobard-widget__body">

				<?php $recent_contact_numbers = WWS_Analytics::get_recent_contact_numbers( 5 ); ?>
				<?php if ( $recent_contact_numbers && in_array( get_option( 'wws_layout' ), array( 6, 7 ) ) ) : ?>
					<p><strong><?php esc_html_e( 'Recent Contact Numbers', 'wc-wws' ); ?></strong></p>
					<table class="wws-recent-contact-numbers">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Number', 'wc-wws' ); ?></th>
								<th><?php esc_html_e( 'Date', 'wc-wws' ); ?></th>
								<th><?php esc_html_e( 'Actions', 'wc-wws' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $recent_contact_numbers as $rcn ) : ?>
							<tr>
								<td width="40%"><?php echo esc_html( $rcn['number'] ); ?></td>
								<td width="40%"><?php echo esc_html( $rcn['date'] ); ?></td>
								<td width="20%">
									<a href="tel:<?php echo esc_html( $rcn['number'] ); ?>">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><title><?php esc_html_e( 'Call', 'wc-wws' ); ?></title><g fill="#607d8b"><path d="M497.39 361.8l-112-48a24 24 0 0 0-28 6.9l-49.6 60.6A370.66 370.66 0 0 1 130.6 204.11l60.6-49.6a23.94 23.94 0 0 0 6.9-28l-48-112A24.16 24.16 0 0 0 122.6.61l-104 24A24 24 0 0 0 0 48c0 256.5 207.9 464 464 464a24 24 0 0 0 23.4-18.6l24-104a24.29 24.29 0 0 0-14.01-27.6z"></path></g></svg>
									</a>
									<a href="<?php echo esc_url( "https://ip-api.com/#{$rcn['visitor_ip']}" ); ?>" target="_blank">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><title><?php esc_html_e( 'Information', 'wc-wws' ); ?></title><g fill="#607d8b"><path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></g></svg>
									</a>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<p><?php esc_html_e( 'Recent Contact Numbers analytics works with only layout 6 and layout 7.', 'wc-wws' ); ?></p>
				<?php endif; ?>

			</div>
			<div class="wws-analytics-dashobard-widget__footer">
				<div>
					<p><?php esc_html_e( 'Today', 'wc-wws' ); ?></p>
					<span class="counter"><?php echo sprintf( esc_html__( '%d clicks', 'wc-wws' ), WWS_Analytics::get_today_click_count() ); ?></span>
				</div>
				<div>
					<p><?php esc_html_e( 'Last Week', 'wc-wws' ); ?></p>
					<span class="counter"><?php echo sprintf( esc_html__( '%d clicks', 'wc-wws' ), WWS_Analytics::get_last_week_click_count() ); ?></span>
				</div>
				<div>
					<p><?php esc_html_e( 'Last Month', 'wc-wws' ); ?></p>
					<span class="counter"><?php echo sprintf( esc_html__( '%d clicks', 'wc-wws' ), WWS_Analytics::get_last_month_click_count() ); ?></span>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * CSS for dashboard widget styling
	 *
	 * @return void
	 */
	public function admin_style() {
		$currentScreen = get_current_screen();

		if( $currentScreen->id !== "dashboard" ) {
			return;
		}
		?>
		<style>
			/* Analytics dashboard widgets CSS */
			.wws-analytics-dashobard-widget p {
				margin: 0;
			}

			.wws-analytics-dashobard-widget__header {
				display: flex;
				align-items: center;
				justify-content: center;
				padding: 15px;
				border-bottom: 1px solid #cfd8dc;
			}

			.wws-analytics-dashobard-widget__header img {
				display: block;
			}

			.wws-analytics-dashobard-widget__header .wws-branding {
				margin-left: 10px;
			}

			.wws-analytics-dashobard-widget__body {
				padding: 15px;
			}

			.wws-analytics-dashobard-widget__footer {
				display: flex;
				padding: 15px;
				border-top: 1px solid #cfd8dc;
			}

			.wws-analytics-dashobard-widget__footer > div {
				width: 33.33332%;
				text-align: center;
				border-right: 1px solid #cfd8dc;
			}

			.wws-analytics-dashobard-widget__footer > div:last-child {
				border: 1px solid transparent;
			}

			.wws-analytics-dashobard-widget__footer p {
				font-weight: 700;
			}

			.wws-recent-contact-numbers {
				width: 100%;
				margin-top: 10px;
			}

			.wws-recent-contact-numbers a {
				text-decoration: none;
				padding: 0 3px;
			}

			.wws-recent-contact-numbers th,
			.wws-recent-contact-numbers td {
				text-align: left;
				padding: 5px 5px;
			}

			.wws-recent-contact-numbers thead tr {
				background: #eceff1;
			}

			.wws-recent-contact-numbers tbody tr:nth-child( even )  {
				background-color: #fafafa;
			}

			.wws-recent-contact-numbers thead tr th:last-child {
				width: 70px;
			}

		</style>
		<?php
	}

}

new WWS_Admin_Dashboard_Widget;
