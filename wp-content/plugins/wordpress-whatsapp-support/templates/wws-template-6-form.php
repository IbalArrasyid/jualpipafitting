<?php defined( 'ABSPATH' ) || exit; ?>

<div class="wws-popup__support-person-back wws--bg-color wws--text-color" data-wws-multi-support-back>
	<?php echo WWS_Icons::get( 'arrow-left' ); ?>
</div>

<input class="wws-popup-multi-support-id" type="hidden" value="<?php echo esc_attr( $support_person->get_id() ); ?>">
<input class="wws-popup-multi-support-pre-essage" type="hidden" value="<?php echo esc_attr( $pre_message ); ?>">
<input class="wws-popup-multi-support-number" type="hidden" value="<?php echo esc_attr( $support_person->get_contact() ) ?>">

<input type="tel" class="wws-popup__fields-number" placeholder="<?php echo esc_attr( get_option( 'wws_number_placeholder_text' ) ) ?>">

<div class="wws-popup__fields-textarea-wrapper">

	<textarea name="" class="wws-popup__input wws-popup__fields-textarea" placeholder="<?php echo esc_attr( get_option( 'wws_input_placeholder_text' ) ) ?>"></textarea>


	<svg class="wws-popup__send-btn" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve">
		<style type="text/css">
			.wws-lau00001{fill:<?php echo esc_html( get_option( 'wws_layout_background_color' ) ) ?>80;}
			.wws-lau00002{fill:<?php echo esc_html( get_option( 'wws_layout_background_color' ) ) ?>;}
		</style>
		<path id="path0_fill" class="wws-lau00001" d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
		<path id="path0_fill_1_" class="wws-lau00002" d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
	</svg>

	<div class="wws-clearfix"></div>

</div>

<?php do_action( 'wws_action_plugin' ) ?>
