<?php
/**
 * Main setting page.
 *
 * @author  WeCreativez
 * @package WordPress Support Using WhatsApp
 * @version 2.0.9
 */

defined( 'ABSPATH' ) || exit; ?>

<div class="wrap">
	<h1><?php esc_html_e( 'WordPress Support Using WhatsApp', 'wc-wws' ); ?></h1>

	<?php
		settings_errors();
		do_action( 'wws_admin_notifications' );
	?>

	<hr>

</div>

<?php
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'appearance';
	$tabs       = array( // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		'appearance'             => array(
			'label'   => __( 'Appearance', 'wc-wws' ),
			'display' => true,
		),
		'basic_settings'                  => array(
			'label'   => __( 'Basic Settings', 'wc-wws' ),
			'display' => true,
		),
		'manage_support_persons' => array(
			'label'   => __( 'Manage Support Person(s)', 'wc-wws' ),
			'display' => true,
		),
		'widget_text'            => array(
			'label'   => __( 'Widget Text Settings', 'wc-wws' ),
			'display' => true,
		),
		'button_generator'          => array(
			'label'   => __( 'Button Generator', 'wc-wws' ),
			'display' => true,
		),
		'link_generator'          => array(
			'label'   => __( 'Link Generator', 'wc-wws' ),
			'display' => true,
		),
		'qr_generator'          => array(
			'label'   => __( 'QR Generator', 'wc-wws' ),
			'display' => true,
		),
		'product_query'          => array(
			'label'   => __( 'Product Query', 'wc-wws' ),
			'display' => true,
		),
	);

	/**
	 * Filter tabs.
	 *
	 * @since 2.0.9
	 */
	apply_filters( 'wpzc_admin_setting_tabs', $tabs );
?>

<h2 class="nav-tab-wrapper">

	<?php foreach ( $tabs as $tab_id => $tab ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>

		<?php if ( isset( $tab['display'] ) && true === $tab['display'] ) : ?>

			<a href="?page=wc-whatsapp-support&tab=<?php echo esc_attr( $tab_id ); ?>" id="<?php echo esc_attr( $tab_id ); ?>" class="nav-tab <?php echo $tab_id === $active_tab ? 'nav-tab-active' : ''; ?>">
				<?php echo esc_html( $tab['label'] ); ?>
			</a>

		<?php endif; ?>

	<?php endforeach; ?>

</h2>

<form method="POST" action="options.php">

	<?php
		switch ( $active_tab ) {
			case 'appearance':
				settings_fields( 'wws_appearance_settings' );
				do_settings_sections( 'wws_appearance_settings' );
				submit_button();
				break;

			case 'basic_settings':
				settings_fields( 'wws_basic_settings' );
				do_settings_sections( 'wws_basic_settings' );
				submit_button();
				break;

			case 'manage_support_persons':
				settings_fields( 'wws_manage_support_persons_settings' );
				do_settings_sections( 'wws_manage_support_persons_settings' );
				submit_button();
				break;

			case 'widget_text':
				settings_fields( 'wws_widget_text_settings' );
				do_settings_sections( 'wws_widget_text_settings' );
				submit_button();
				break;

			case 'product_query':
				settings_fields( 'wws_product_query_settings' );
				do_settings_sections( 'wws_product_query_settings' );
				submit_button();
				break;

			case 'button_generator':
				require_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-button-generator.php';
				break;

			case 'link_generator':
				require_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-link-generator.php';
				break;

			case 'qr_generator':
				require_once WWS_PLUGIN_PATH . 'includes/admin/views/admin-qr-generator.php';
				break;

			default:
				settings_fields( 'wpzc_appearance_settings' );
				do_settings_sections( 'wpzc_appearance_settings' );
				submit_button();
				break;
		}
	?>

</form>
