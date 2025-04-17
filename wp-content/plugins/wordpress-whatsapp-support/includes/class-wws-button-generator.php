<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );


class WWS_Button_Generator {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'shortcode_fonts' ) );
		add_shortcode( 'whatsappsupport', array($this, 'plugin_shortcode') );
	}

	/**
	* Custom google font in shortcode support button
	* @since 1.2
	*/
	public function shortcode_fonts() {

		wp_register_style(
			'wws-shortcode-font-lobster',
			'https://fonts.googleapis.com/css?family=Lobster');

		wp_register_style(
			'wws-shortcode-font-bree',
			'https://fonts.googleapis.com/css?family=Bree+Serif');

		wp_register_style(
			'wws-shortcode-font-satisfy',
			'https://fonts.googleapis.com/css?family=Satisfy');

		wp_register_style(
			'wws-shortcode-font-oswald',
			'https://fonts.googleapis.com/css?family=Oswald');

		wp_register_style(
			'wws-shortcode-font-ubuntu',
			'https://fonts.googleapis.com/css?family=Ubuntu');

		wp_register_style(
			'wws-shortcode-font-dancing',
			'https://fonts.googleapis.com/css?family=Dancing+Script');

	}


	/**
	* Plugin shortcode main function
	* @param  array $atts WordPress shortcode array
	* @return html
	* @since 1.2
	*/
	function plugin_shortcode($atts) {
		$a = shortcode_atts( array(
			'number'      => '911234567890',
			'group'       => 'XYZ123456789',
			'text'        => 'Contact Us',
			'text-color'  => '#fff',
			'text-bold'   => 'inherit',
			'font'        => 'inherit',
			'bg-color'    => '#22c15e',
			'message'     => 'Hello...',
			'full-width'  => 'no',
			'on-mobile'   => 'yes',
			'on-desktop'  => 'yes',
			'font'        => 'inherit',
		), $atts );

	switch ( $a['font'] ) {

		case 'Lobster':
			wp_enqueue_style( 'wws-shortcode-font-lobster' );
			$a['font'] = "'Lobster', cursive";
		break;

		case 'Bree Serif':
			wp_enqueue_style( 'wws-shortcode-font-satisfy' );
			$a['font'] = "'Satisfy', cursive";
		break;

		case 'Satisfy':
			wp_enqueue_style( 'wws-shortcode-font-bree' );
			$a['font'] = "'Bree Serif', serif";
		break;

		case 'Oswald':
			wp_enqueue_style( 'wws-shortcode-font-oswald' );
			$a['font'] = "'Oswald', sans-serif";
		break;

		case 'Ubuntu':
			wp_enqueue_style( 'wws-shortcode-font-ubuntu' );
			$a['font'] = "'Ubuntu', sans-serif";
		break;

		case 'Dancing Script':
			wp_enqueue_style( 'wws-shortcode-font-dancing' );
			$a['font'] = "'Dancing Script', cursive";
		break;
	}

	if ( wp_is_mobile() != true ) { // desktop
		$url = "https://web.whatsapp.com/send?phone={$a['number']}&text={$a['message']}";
		if ( $a['on-desktop'] == 'no' ) {
			return;
		}
	} else { // mobile
		$url = "https://api.whatsapp.com/send?phone={$a['number']}&text={$a['message']}";
		if ( $a['on-mobile'] == 'no' ) {
			return;
		}
	}

	if ( $a['group'] != 'XYZ123456789' )  {
		$url = "https://chat.whatsapp.com/{$a['group']}";
	}

	ob_start(); ?>

	<a href="<?php echo esc_url( $url ) ?>"
		class="wws-shortcode-btn"
		target="_blank"
		style="font-family: <?php echo esc_html( $a['font'] ) ?>; font-weight: <?php echo esc_html( $a['text-bold'] ) ?>; background-color: <?php echo esc_html( $a['bg-color'] ) ?>; <?php echo ( $a['full-width'] == 'yes' ) ? 'display: flex' : 'display: inline-flex'; ?>; color: <?php echo esc_html( $a['text-color'] ) ?>;"
		data-wws-button-number="<?php echo esc_attr( $a['number'] ); ?>"
		data-wws-button-message="<?php echo esc_attr( $a['message'] ); ?>">
		<?php echo WWS_Icons::get( 'whatsapp' ); ?> <?php echo esc_html( $a['text'] ) ?>
	</a>

	<?php
		return ob_get_clean();
	}


} // end of WWS_Button_Generator

$wws_button_generator = new WWS_Button_Generator;
