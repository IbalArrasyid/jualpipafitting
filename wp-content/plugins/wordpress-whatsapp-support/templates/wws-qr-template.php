<?php defined( 'ABSPATH' ) || exit; ?>

<div class="wws-qr">
	<img src="<?php echo esc_url( $qr ); ?>" width="<?php echo esc_html( $a['size'] ) ?>" alt="//">
	<p><?php echo esc_html( $a['text'] ); ?></p>
</div>
