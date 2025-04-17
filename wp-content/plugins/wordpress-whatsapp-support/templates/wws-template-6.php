<?php defined( 'ABSPATH' ) || exit; ?>

<div id="wws-layout-6" class="wws-popup-container wws-popup-container--position">

	<?php if ( 'yes' === get_option( 'wws_gradient_status' ) ) : ?>
		<div class="wws-gradient wws-gradient--position"></div>
	<?php endif; ?>

	<!-- Popup -->
	<div class="wws-popup" data-wws-popup-status="0">

		<!-- Popup header -->
		<div class="wws-popup__header">

			<!-- Popup close button -->
			<div class="wws-popup__close-btn wws--bg-color wws--text-color wws-shadow">
				<?php echo WWS_Icons::get( 'close' ); ?>
			</div>
			<div class="wws-clearfix"></div>
			<!-- .Popup close button -->

		</div>
		<!-- .Popup header -->

		<!-- Popup body -->
		<div class="wws-popup__body">

			<!-- Popup support -->
			<div class="wws-popup__support-wrapper  wws-shadow">
				<div class="wws-popup__support">
					<div class="wws-popup__support-about wws--bg-color wws--text-color">
						<?php echo esc_textarea( apply_filters( 'wws_about_support_text', get_option( 'wws_about_support_text' ) ) ) ?>
					</div>
				</div>
			</div>
			<div class="wws-clearfix"></div>
			<!-- .Popup support -->

			<!-- Popup support person -->
			<div class="wws-popup__support-person-container wws-shadow">

				<div class="wws-popup__support-person-wrapper">

				<?php
					// If multi person randomize is on.
					if ( 'yes' === get_option( 'wws_multi_support_person_randomize' ) ) {
						$support_persons = wws_shuffle_assoc( wws_get_support_persons() );
					} else {
						$support_persons = wws_get_support_persons();
					}

					if ( $support_persons ) {
						foreach ( $support_persons as $support_person_id => $support_person ) :

							$pre_message = str_replace(
								array( '{title}', '{url}', '{br}' ),
								array( get_the_title(), get_permalink(), '%0A' ),
								$support_person->get_pre_message()
							);

							// Hide unavailable support person.
							if ( 'yes' === get_option( 'wws_multi_support_person_hide_unavailable' ) && ! $support_person->is_available() ) {
								continue;
							}

							if ( $support_person->is_available() == true ) : ?>
								<div
									class="wws-popup__support-person"
									data-wws-name="<?php echo esc_html( $support_person->get_name() ); ?>"
									data-wws-id="<?php echo intval( $support_person->get_id() ) ?>"
									data-wws-multi-support-person-id="<?php echo intval( $support_person->get_id() ) ?>">
									<div class="wws-popup__support-person-img-wrapper">

										<?php if ( $support_person->has_image() ) : ?>
											<img class="wws-popup__support-person-img" src="<?php echo esc_url( $support_person->get_image() ) ?>" alt="//" width="54">
										<?php else: ?>
											<img class="wws-popup__support-person-img" src="<?php echo esc_url( WWS_PLUGIN_URL . 'assets/img/user.svg' ); ?>" alt="//" width="54">
										<?php endif; ?>

										<div class="wws-popup__support-person-available"></div>
									</div>
									<div class="wws-popup__support-person-info-wrapper">
										<div class="wws-popup__support-person-title"><?php echo esc_html( $support_person->get_title() ) ?></div>
										<div class="wws-popup__support-person-name"><?php echo esc_html( $support_person->get_name() ) ?></div>
										<div class="wws-popup__support-person-status"><?php echo esc_html( get_option( 'wws_support_person_available_text' ) ); ?></div>
									</div>
								</div>
							<?php else : // not available ?>
								<div class="wws-popup__support-person">
									<div class="wws-popup__support-person-img-wrapper">
										<?php if ( $support_person->has_image() ) : ?>
											<img class="wws-popup__support-person-img" src="<?php echo esc_url( $support_person->get_image() ) ?>" alt="//" width="54">
										<?php else: ?>
											<img class="wws-popup__support-person-img" src="<?php echo esc_url( WWS_PLUGIN_URL . 'assets/img/user.svg' ); ?>" alt="//" width="54">
										<?php endif; ?>
										<div class="wws-popup__support-person-away"></div>
									</div>
									<div class="wws-popup__support-person-info-wrapper">
										<div class="wws-popup__support-person-title"><?php echo esc_html( $support_person->get_title() ) ?></div>
										<div class="wws-popup__support-person-name"><?php echo esc_html( $support_person->get_name() ) ?></div>
										<div class="wws-popup__support-person-status"><?php echo esc_html( get_option( 'wws_support_person_not_available_text' ) ); ?></div>
									</div>
								</div>
							<?php endif;

						endforeach;
					}
				?>

			</div>

			<!-- Support person form -->
			<div class="wws-popup__support-person-form"></div>
			<!-- .Support person form -->

			</div>
			<!-- .Popup support person -->

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
