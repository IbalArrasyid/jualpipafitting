<?php defined( 'ABSPATH' ) || exit; ?>

<div id="wws-layout-1" class="wws-popup-container wws-popup-container--position">

	<?php if ( 'yes' === get_option( 'wws_gradient_status' ) ) : ?>
		<div class="wws-gradient wws-gradient--position"></div>
	<?php endif; ?>

	<!-- Popup -->
	<div class="wws-popup" data-wws-popup-status="0">

		<!-- Popup header -->
		<div class="wws-popup__header">

			<!-- Popup close button -->
			<div class="wws-popup__close-btn wws-shadow wws--bg-color wws--text-color">
				<?php echo WWS_Icons::get( 'close' ); ?>
			</div>
			<div class="wws-clearfix"></div>
			<!-- .Popup close button -->

		</div>
		<!-- .Popup header -->

		<!-- Popup body -->
		<div class="wws-popup__body">

			<!-- Popup support -->
			<div class="wws-popup__support-wrapper">
				<div class="wws-popup__support-img-wrapper wws-shadow">
					<?php if ( '' === get_option( 'wws_support_person_image' ) ) : ?>
						<img class="wws-popup__support-img" src="<?php echo esc_url( WWS_PLUGIN_URL . 'assets/img/user.svg' ) ?>" alt="//" width="50" height="50">
					<?php else: ?>
						<img class="wws-popup__support-img" src="<?php echo esc_url( get_option( 'wws_support_person_image' ) ) ?>" alt="//" width="50" height="50">
					<?php endif; ?>
				</div>
				<div class="wws-popup__support wws-shadow">
					<div class="wws-popup__support-about wws--text-color wws--bg-color">
						<?php echo esc_textarea( apply_filters( 'wws_about_support_text', get_option( 'wws_about_support_text' ) ) ); ?>
					</div>
					<div class="wws-popup__support-welcome">
						<?php echo esc_html( get_option( 'wws_welcome_message' ) ) ?>
					</div>
					<?php do_action( 'wws_action_plugin' ) ?>
				</div>
			</div>
			<!-- Popup support -->

			<!-- Popup input -->
			<div class="wws-popup__input-wrapper wws-shadow">
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
		<div class="wws-popup__open-btn wws-shadow wws--text-color wws--bg-color">
			<?php echo WWS_Icons::get( 'whatsapp' ); ?> <span><?php echo esc_html( apply_filters( 'wws_trigger_button_text', get_option( 'wws_trigger_button_text' ) ) ) ?></span>
		</div>
		<div class="wws-clearfix"></div>
		<!-- .Popup open button -->

	</div>
	<!-- Popup footer -->

</div>
