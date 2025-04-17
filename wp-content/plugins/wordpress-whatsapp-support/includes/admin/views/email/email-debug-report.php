<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>WWS Debug Report</title>
	<style>
		body {
			background-color: #eee;
		}
		.report-email-wrapper {
			width: 600px;
			margin: 0 auto;
			padding: 15px;
			background-color: #fff;
		}
		.report-email-wrapper-margin {
			height: 30px;
		}
		.report-email-hidden-text {
			display: none;
			font-size: 1px;
			height: 1px;
		}
		.report-email-site,
		.report-email-from,
		.report-email-site-admin-email,
		.report-email-msg,
		.report-email-report {
			font: small/1.5 Arial,Helvetica,sans-serif;
			margin-bottom: 5px;
			color: #222;
		}

		.report-email-hr {
			margin-bottom: 10px;
			margin-top: 10px;
			height: 1px;
			background-color: #ccc;
			width: 100%;
		}
	</style>
</head>
<body style="background-color: #eee;">

	<div class="report-email-wrapper" style="width: 600px;margin: 0 auto;padding: 15px;background-color: #fff;">

		<div class="report-email-wrapper-margin" style="height: 30px;"></div>

		<p class="report-email-hidden-text" style="display: none;font-size: 1px;height: 1px;"><?php echo esc_html( $msg ) ?> <br><br><br><br><br><br></p>
		<div class="report-email-site" style="font: small/1.5 Arial,Helvetica,sans-serif;margin-bottom: 5px;color: #222;"><strong>Report Site:</strong> <?php echo esc_url( site_url() ) ?></div>
		<div class="report-email-site-admin-email" style="font: small/1.5 Arial,Helvetica,sans-serif;margin-bottom: 5px;color: #222;"><strong>Admin Email: </strong> <?php echo esc_html( get_bloginfo( 'admin_email' ) ) ?></div>
		<div class="report-email-site-admin-email" style="font: small/1.5 Arial,Helvetica,sans-serif;margin-bottom: 5px;color: #222;"><strong>Report Generation Time: </strong> <?php echo current_time( "F j, Y, g:i a" ) ?></div>
		<div class="report-email-hr" style="margin-bottom: 10px;margin-top: 10px;height: 1px;background-color: #ccc;width: 100%;"></div>

		<div class="report-email-report" style="font: small/1.5 Arial,Helvetica,sans-serif;margin-bottom: 5px;color: #222;">

			<?php $report->get_report() ?>

			<div class="report-email-hr" style="margin-bottom: 10px;margin-top: 10px;height: 1px;background-color: #ccc;width: 100%;"></div>

			<!-- Plugin dependent report -->
			<p><strong>## Plugin Activation Key ##</strong></p>
			<?php echo "<pre>" . print_r( get_option( 'sk_wws_license_key', '' ), true ) . "</pre>"; ?>

			<p><strong>## Plugin Main Settings ##</strong></p>
			<?php echo "<pre>" . print_r( get_option( 'sk_wws_setting', array() ), true ) . "</pre>"; ?>

			<p><strong>## Plugin Multi Account Settings ##</strong></p>
			<?php echo "<pre>" . print_r( get_option( 'wws_multi_support_persons', array() ), true ) . "</pre>"; ?>

			<p><strong>## Plugin Product Query Settings ##</strong></p>
			<?php echo "<pre>" . print_r( get_option( 'wws_product_query', array() ), true ) . "</pre>"; ?>

			<p><strong>## Plugin GDPR Settings ##</strong></p>
			<?php echo "<pre>" . print_r( get_option( 'wws_gdpr_settings', array() ), true ) . "</pre>"; ?>

			<p><strong>## Plugin FB and GA Analytics Settings ##</strong></p>
			<?php echo "<pre>" . print_r( get_option( 'wws_fb_ga_analytics_settings', array() ), true ) . "</pre>"; ?>

			<p><strong>## Plugin Developers Settings ##</strong></p>
			<?php echo "<pre>" . print_r( get_option( 'wws_developer_settings', array() ), true ) . "</pre>"; ?>
			<!-- /Plugin dependent report -->


		</div>

		<div class="report-email-wrapper-margin" style="height: 30px;"></div>

	</div>

</body>
</html>

