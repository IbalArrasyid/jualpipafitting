<?php defined( 'ABSPATH' ) || exit; ?>

<div id="wws-layout-3" class="wws-popup-container wws-popup-container--position">

	<!-- Popup -->
	<div class="wws-popup" data-wws-popup-status="0">

		<!-- Popup header -->
		<div class="wws-popup__header">

			<!-- Popup close button -->
			<div class="wws-popup__close-btn wws--bg-color wws--text-color">
				<?php echo WWS_Icons::get( 'close' ); ?>
			</div>
			<div class="wws-clearfix"></div>
			<!-- .Popup close button -->

		</div>
		<!-- .Popup header -->

		<!-- Popup body -->
		<div class="wws-popup__body">

			<!-- Popup support -->
			<div class="wws-popup__support-wrapper wws-shadow">
				<div class="wws-popup__support wws--bg-color wws--text-color">
					<div class="wws-popup__support-about">
						<div class="wws-popup__support-img-wrapper">
							<?php if ( '' === get_option( 'wws_support_person_image' ) ) : ?>
								<img class="wws-popup__support-img" src="<?php echo esc_url( WWS_PLUGIN_URL . 'assets/img/user.svg' ); ?>" alt="// width="50" height="50">
							<?php else: ?>
								<img class="wws-popup__support-img" src="<?php echo esc_url( get_option( 'wws_support_person_image' ) ); ?>" alt="// width="50" height="50">
							<?php endif; ?>
						</div>
					<?php echo esc_textarea( apply_filters( 'wws_about_support_text', get_option( 'wws_about_support_text' ) ) ) ?>
					</div>
				</div>
			</div>
			<!-- Popup support -->

			<!-- Popup input -->
			<div class="wws-popup__input-wrapper wws-shadow">

				<?php do_action( 'wws_action_plugin' ) ?>

				<input type="text" class="wws-popup__input" placeholder="<?php echo esc_attr( get_option( 'wws_input_placeholder_text' ) ) ?>" autocomplete="off">
				<svg class="wws-popup__send-btn" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve">
					<style type="text/css">
						.wws-lau00001{fill:<?php echo esc_html( get_option( 'wws_layout_background_color' ) ) ?>80;}
						.wws-lau00002{fill:<?php echo esc_html( get_option( 'wws_layout_background_color' ) ) ?>;}
					</style>
					<path id="path0_fill" class="wws-lau00001" d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
					<path id="path0_fill_1_" class="wws-lau00002" d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
				</svg>
			</div>
			<div class="wws-clearfix"></div>
			<!-- .Popup input -->

		</div>
		<!-- .Popup body -->

	</div>
	<!-- .Popup -->


	<!-- .Popup footer -->
	<div class="wws-popup__footer">

		<!-- Popup open button -->
		<div class="wws-popup__open-btn wws--bg-color wws--text-color wws-shadow">
			<?php echo WWS_Icons::get( 'whatsapp' ); ?> <span><?php echo esc_html( apply_filters( 'wws_trigger_button_text', get_option( 'wws_trigger_button_text' ) ) ) ?></span>
		</div>
		<div class="wws-clearfix"></div>
		<!-- .Popup open button -->

	</div>
	<!-- Popup footer -->


</div>
