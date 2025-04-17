<?php
defined( 'ABSPATH' ) || exit;

class WWS_Admin_Metabox {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_custom_box' ) );
		add_action( 'save_post', array( $this, 'save_postdata' ) );
	}

	public function add_custom_box() {
		add_meta_box(
			'wwsMetaboxDiv',                                                      // Unique ID
			esc_html__( 'WordPress Support Using WhatsApp', 'wc-wws' ),                 // Box title
			array( $this, 'custom_box_html' ),                                    // Content callback, must be of type callable
			apply_filters( 'wws_post_metabox_screens', array( 'post', 'page', 'product' ) ), // Post type
			'side',
			'low'
		);
	}

	public function custom_box_html( $post ) {
		$support_contact_number = get_post_meta( $post->ID, '_wws_support_contact_number', true );
		$about_support_text     = get_post_meta( $post->ID, '_wws_about_support_text', true );
		$trigger_button_text    = get_post_meta( $post->ID, '_wws_trigger_button_text', true );
		$predefined_text        = get_post_meta( $post->ID, '_wws_predefined_text', true );
		$product_query_number   = get_post_meta( $post->ID, '_wws_product_query_button_number', true );
		?>
			<?php do_action( 'wws_admin_post_metabox_notice' ); ?>

			<?php if ( $this->is_single_support() ) : ?>
				<div id="wws_support_contact_number" class="components-base-control">
					<div class="components-base-control__field">
						<label for="" class="components-base-control__label"><?php esc_html_e( 'Support Contact Number', 'wc-wws' ); ?></label>
						<input type="text" class="components-select-control__input" name="_wws_support_contact_number" value="<?php echo esc_attr( $support_contact_number ); ?>">
					</div>
				</div>
			<?php endif; ?>
			<div class="components-base-control">
				<div class="components-base-control__field">
					<label for="" class="components-base-control__label"><?php esc_html_e( 'About Support Text', 'wc-wws' ); ?></label>
					<textarea class="components-textarea-control__input" name="_wws_about_support_text" rows="5" style="width: 100%;"><?php echo esc_textarea( $about_support_text ); ?></textarea>
				</div>
			</div>
			<?php if ( $this->is_single_support() ) : ?>
				<div class="components-base-control">
					<div class="components-base-control__field">
						<label for="" class="components-base-control__label"><?php esc_html_e( 'Predefined Message', 'wc-wws' ); ?></label>
						<textarea class="components-textarea-control__input" name="_wws_predefined_text" rows="5" style="width: 100%;"><?php echo esc_textarea( $predefined_text ); ?></textarea>
						<p class="description"><?php echo wp_kses_post( sprintf( __( '%s to display current page title in chat.<br>%s to display current page URL in chat.<br>%s to break the line into a new line.', 'wc-wws' ), '<code>{title}</code>', '<code>{url}</code>', '<code>{br}</code>' ) ) ?></p>
					</div>
				</div>
			<?php endif; ?>
			<div class="components-base-control">
				<div class="components-base-control__field">
					<label for="" class="components-base-control__label"><?php esc_html_e( 'Trigger Button Text', 'wc-wws' ); ?></label>
					<input type="text" class="components-select-control__input" name="_wws_trigger_button_text" value="<?php echo esc_attr( $trigger_button_text ); ?>">
				</div>
			</div>

			<?php if ( 'product' === get_post_type() && 'yes' === get_option( 'wws_product_query_status' ) ) : ?>
			<hr>
			<div id="wws_support_contact_number" class="components-base-control">
				<div class="components-base-control__field">
					<label for="" class="components-base-control__label"><?php esc_html_e( 'Product Query Number', 'wc-wws' ); ?></label>
					<input type="text" class="components-select-control__input" name="_wws_product_query_button_number" value="<?php echo esc_attr( $product_query_number ); ?>">
				</div>
			</div>
			<?php endif; ?>

		<?php
	}

	public function save_postdata( $post_id ) {
		if ( array_key_exists( '_wws_support_contact_number', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_wws_support_contact_number',
				sanitize_text_field( $_POST['_wws_support_contact_number'] )
			);
		}
		if ( array_key_exists( '_wws_about_support_text', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_wws_about_support_text',
				sanitize_textarea_field( $_POST['_wws_about_support_text'] )
			);
		}
		if ( array_key_exists( '_wws_trigger_button_text', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_wws_trigger_button_text',
				sanitize_textarea_field( $_POST['_wws_trigger_button_text'] )
			);
		}
		if ( array_key_exists( '_wws_predefined_text', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_wws_predefined_text',
				sanitize_textarea_field( $_POST['_wws_predefined_text'] )
			);
		}
		if ( array_key_exists( '_wws_product_query_button_number', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_wws_product_query_button_number',
				sanitize_textarea_field( $_POST['_wws_product_query_button_number'] )
			);
		}

	}

	private function is_single_support() {
		return in_array( get_option( 'wws_layout' ), array( '1', '2', '3', '4', '7' ) );
	}

}

new WWS_Admin_Metabox;
