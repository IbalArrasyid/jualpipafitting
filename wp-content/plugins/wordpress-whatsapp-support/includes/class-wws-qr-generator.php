<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );


class WWS_QR_Generator {

	public function __construct() {

		add_shortcode( 'wws-qr', array( $this, 'qr_display' ) );

	}


	public function qr_display( $atts ) {

		$a = shortcode_atts( array(
			'qr'    => null,
			'size'  => '150',
			'text'  => 'Scan QR to talk us via WhatsApp',
		), $atts );

		$upload_dir = wp_upload_dir();
		$qr         = $upload_dir['baseurl'] . '/wws-qr-images/' . $a['qr'];


		ob_start();
		require WWS_PLUGIN_PATH . 'templates/wws-qr-template.php';
		return ob_get_clean();

	}

	public static function generate( $qr_link = '', $qr_size = '1000' ) {

		$upload_dir = wp_upload_dir();

		if ( ! file_exists( $upload_dir['basedir'] . '/wws-qr-images' ) ) {
			mkdir( $upload_dir['basedir'] . '/wws-qr-images', 0777, true );
		}

		$url = 'http://api.qrserver.com/v1/create-qr-code/?data=' . esc_url( $qr_link ) . '&size=' . esc_html( $qr_size ) . 'x' . $qr_size ;
		$dir = $upload_dir['basedir'] . '/wws-qr-images'; // Full Path
		$name = rand(100000, 999999).'.jpg';

		is_dir($dir) || @mkdir($dir) || die("Can't Create folder");
		copy($url, $dir . DIRECTORY_SEPARATOR . $name);

		return $name;

	}


} // end of WWS_QR_Generator

$wws_qr_generator = new WWS_QR_Generator;
