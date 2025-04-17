<?php

// Preventing to direct access
defined( 'ABSPATH' ) OR die( 'Direct access not acceptable!' );

/**
* WooCommerce Product Query
* @package WeCreativez/Classes
* @since 1.5
*/
class WWS_Product_Query {


	public function __construct() {
		if ( 'yes' !== get_option( 'wws_product_query_status' ) ) {
			return false;
		}
		if ( 'woocommerce_before_add_to_cart_form' === get_option( 'wws_product_query_button_location' ) ) {
			add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'product_query_button' ) );
		}
		if ( 'woocommerce_after_add_to_cart_button' === get_option( 'wws_product_query_button_location' ) ) {
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'product_query_button' ) );
		}

		add_filter( 'wws_product_query_number', array( $this, 'override_product_query_button_number' ), 10, 2 );

		add_shortcode( 'wws_product_query', array( $this, 'product_query_button_shortcode' ) );
		add_shortcode( 'wws_woo_product_query', array( $this, 'product_woo_query_button_shortcode' ) );
	}

	public function product_query_button() {
		/**
		 * Filter visiablity of the product query button on the current product page.
		 *
		 * @since 2.3.0
		 * @param bool $displayable True for display, false for hide.
		 */
		if ( true !== apply_filters( 'wws_display_product_query_on_current_product', $this->is_displayable() ) ) {
			return false;
		}

		$args = array(
			'product_link' => get_the_permalink( get_the_ID() ),
			'product_name' => get_the_title( get_the_ID() ),
			'br'           => '%0A',
			'bg_color'     => get_option( 'wws_product_query_button_background_color' ),
			'text_color'   => get_option( 'wws_product_query_button_text_color' ),
			'button_text'  => get_option( 'wws_product_query_button_label' ),
			'number'       => get_option( 'wws_product_query_support_number' ),
			'name' 	       => get_option( 'wws_product_query_support_person_name' ),
			'title'        => get_option( 'wws_product_query_support_person_title' ),
			'image'        => get_option( 'wws_product_query_support_person_image' ),
			'message'      => get_option( 'wws_product_query_support_pre_message' ),
		);

		// Clean shortcode input.
		$args = array_map( 'wws_clean_shortcode_input', $args );

		// Process placeholders.
		$support_pre_message = $args['message'];
		$support_pre_message = str_replace( '{url}', $args['product_link'], $support_pre_message );
		$support_pre_message = str_replace( '{title}', $args['product_name'], $support_pre_message );
		$support_pre_message = str_replace( '{br}', $args['br'], $support_pre_message );

		$support_pre_message = wp_sprintf(
			'[wws_product_query bg-color="%s" text-color="%s" button-text="%s" number="%s" name="%s" title="%s" image="%s" message="%s"]',
			esc_html( $args['bg_color'] ),
			esc_html( $args['text_color'] ),
			esc_html( $args['button_text'] ),
			esc_html( $args['number'] ),
			esc_html( $args['name'] ),
			esc_html( $args['title'] ),
			esc_url( $args['image'] ),
			esc_html( do_shortcode( $support_pre_message ) )
		);

		echo do_shortcode( $support_pre_message );
	}

	public function product_woo_query_button_shortcode() {
		ob_start();
		$this->product_query_button();
		return ob_get_clean();
	}

	public function is_displayable() {
		if ( function_exists( 'is_product' ) && ! is_product() ) {
			return false;
		}
		if ( in_array( get_the_ID(), get_option( 'wws_product_query_exclude_by_products' ) ) ) {
			return false;
		}

		//  Get the WooCommerce products categories
		$terms      = get_the_terms ( get_the_ID(), 'product_cat' );
		$cat_ids    = array();

		foreach ( (array)$terms as $term ) {
			// Assign each category id in $cat_ids array
			$cat_ids[] = $term->term_id;
		}
		// Check, if category exists in admin selected category
		foreach ( $cat_ids as $cat_id) {
			// If exists then return true.
			if ( in_array( $cat_id, get_option( 'wws_product_query_exclude_by_categories' ) ) ) {
				return false;
			}
		}

		return true;
	}

	public function product_query_button_shortcode( $atts ) {

		$a = shortcode_atts( array(
			'bg-color'      => '#22C15E',
			'text-color'    => '#ffffff',
			'button-text'   => 'Need Help? Contact Us via WhatsApp',
			'number'        => '911234567890',
			'name'          => 'Maya',
			'title'         => 'Pre-sale Questions',
			'image'         => '',
			'message'       => 'Hi, I need help with {title} {url}'
		), $atts );

		ob_start();
		?>
		<style>
			a.wws-product-query-btn {
				display: inline-flex;
				width: auto;
				align-items: center;
				justify-content: center;
				padding: 5px 8px;
				border-radius: 3px;
				position: relative;
				text-decoration: none !important;
				margin: 5px 0;
			}
			.wws-product-query-btn__img {
				width: 50px;
				height: 50px;
			}
			.wws-product-query-btn__img img {
				width: 100%;
				height: 100%;
			}
			.wws-product-query-btn__text {
				margin-left: 10px;
				display: flex;
				flex-direction: column;
			}
			.wws-product-query-btn__text span {
				line-height: 20px;
				font-size: 15px;
			}
		</style>

		<?php
			/**
			 * Filter product query mobile number.
			 *
			 * Filter: WWS_Product_Query::override_product_query_button_number() : 10
			 *
			 * @since 2.3.0
			 * @since 2.3.3 Added $product_id Current product ID.
			 *
			 * @param int $number     Support person's WhatsApp number.
			 * @param int $product_id Current product ID.
 			 */
			$a['number'] = apply_filters( 'wws_product_query_number', $a['number'], get_the_ID() );

			if ( wp_is_mobile() ) {
				$link =  "https://api.whatsapp.com/send?phone={$a['number']}&text={$a['message']}";
				$whatsapp_link = apply_filters( 'wws_product_query_mobile_link', $link, $a['number'], $a['message'] );
			} else {
				$link = "whatsapp://send?phone={$a['number']}&text={$a['message']}";
				$whatsapp_link = apply_filters( 'wws_product_query_desktop_link', $link, $a['number'], $a['message'] );
			}
		?>

		<a class="wws-product-query-btn" href="<?php echo esc_url( $whatsapp_link ) ?>" target="_blank" style="background-color: <?php echo esc_html( $a['bg-color'] ) ?>; color: <?php echo esc_html( $a['text-color'] ) ?>;">

		<?php if ( $a['image'] ) : ?>
			<span class='wws-product-query-btn__img' class=''>
			<img src='<?php echo esc_url( $a['image'] ) ?>' alt='wws'>
			</span>
		<?php endif; ?>

		<span class='wws-product-query-btn__text'>

		<?php if ( $a['name'] && $a['title'] ) : ?>
			<span><?php echo esc_html( $a['name'] ) ?> / <?php echo esc_html( $a['title'] ) ?></span>
		<?php elseif ( $a['name'] ) : ?>
			<span><?php echo esc_html( $a['name'] ) ?></span>
		<?php endif; ?>

		<span><strong><?php echo esc_html( $a['button-text'] ) ?></strong></span>
		</span>
		</a><br>

		<?php
		return ob_get_clean();
	}

	/**
	 * Override product query button contact number.
	 *
	 * @since 2.3.3
	 *
	 * @return int Product query button contact number.
	 */
	public function override_product_query_button_number( $number, $product_id ) {
		$product_query_number = get_post_meta( $product_id, '_wws_product_query_button_number', true );

		if ( $product_query_number ) {
			return $product_query_number;
		}

		return $number;
	}

} // end of class WWS_Product_Query

global $wws_product_query;
$wws_product_query = new WWS_Product_Query;
