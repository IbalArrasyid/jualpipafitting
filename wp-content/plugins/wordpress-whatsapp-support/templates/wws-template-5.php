<?php defined( 'ABSPATH' ) || exit; ?>

<div id="wws-layout-5" class="wws-popup-container wws-popup-container--position">

	<!-- .Popup footer -->
	<div class="wws-popup__footer">

		<!-- Popup open button -->
		<div class="wws-popup__open-btn wws-popup-group-invitation__button wws-shadow wws--text-color wws--bg-color">
			<?php echo WWS_Icons::get( 'whatsapp' ); ?> <span><?php echo esc_html( apply_filters( 'wws_trigger_button_text', get_option( 'wws_trigger_button_text' ) ) ) ?></span>
		</div>

		<div class="wws-clearfix"></div>
		<!-- .Popup open button -->

	</div>
	<!-- Popup footer -->

</div>
