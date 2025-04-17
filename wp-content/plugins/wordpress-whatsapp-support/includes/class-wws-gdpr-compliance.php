<?php
// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

/**
 * Plugin GDPR Compliance
 */
class WWS_GDPR_Compliance   {

	public function __construct()  {
		if ( 'yes' !== get_option( 'wws_gdpr_status' ) ) {
			return;
		}

		add_action( 'wws_action_plugin', array( $this, 'display_gdpr' ) );
	}

	public function display_gdpr() {
		?>
		<div class="wws-gdpr">
			<div>
				<label for="wws-gdpr-checkbox">
					<input type="checkbox" id="wws-gdpr-checkbox"> <?php echo $this->get_gdpr_link(); ?>
					</label>
			</div>
		</div>
		<?php
	}


	public function get_gdpr_link() {
		$gdpr_page_id = get_option( 'wws_gdpr_privacy_page' );

		$gdpr_msg = str_replace(
			'{policy_url}',
			'<a href="' . get_permalink( $gdpr_page_id ) . '" target="_blank">' . get_the_title( $gdpr_page_id ) . '</a>',
			get_option( 'wws_gdpr_message' )
		);

		return do_shortcode( $gdpr_msg );
	}

} // end of WWS_GDPR_Compliance class

$wws_gdpr_compliance = new WWS_GDPR_Compliance;
