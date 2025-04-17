<?php defined( 'ABSPATH' ) || exit; ?>

<div id="wws-layout-4" class="wws-popup-container wws-popup-container--position">

	<input type="hidden" class="wws-popup__input" value="<?php echo esc_html( get_option( 'wws_welcome_message' ) ) ?>">

	<!-- .Popup footer -->
	<div class="wws-popup__footer">

		<!-- Popup open button -->
		<div class="wws-popup__open-btn wws-popup__send-btn wws-shadow wws--text-color wws--bg-color">
			<?php echo WWS_Icons::get( 'whatsapp' ); ?> <span><?php echo esc_html( get_option( 'wws_trigger_button_text' ) ) ?></span>
		</div>
		<div class="wws-clearfix"></div>
		<!-- .Popup open button -->

	</div>
	<!-- Popup footer -->

</div>
