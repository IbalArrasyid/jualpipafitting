<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Support Persons', 'wc-wws' ); ?>
		<a href="<?php echo admin_url( 'admin.php?page=wc-whatsapp-support-persons&tab=add' ); ?>" class="button"><?php esc_html_e( 'Add New', 'wc-wws' ); ?></a>
	</h1>

	<?php if ( isset( $_GET['notice'] ) ) : ?>
	<div class="notice notice-success">
		<p><?php echo wp_kses_post( $_GET['notice'] ); ?></p>
	</div>
	<?php endif; ?>

	<hr>

	<?php
		$table = new WWS_Admin_Support_Persons_List();
		$table->prepare_items();
	?>
	<form method="post" action="#">
		<?php $table->search_box( 'Search', 'wc-wws' ); ?>
		<?php $table->display(); ?>
	</form>

</div>
