<?php
/**
 * London Cliniko booking iframe. Script lives in assets/js/cliniko-bookings.js.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cfg = cil_cliniko_bookings_config();
?>
<div class="cil-cliniko-bookings" data-cliniko-location="london">
	<iframe
		id="<?php echo esc_attr( $cfg['iframe_id'] ); ?>"
		title="<?php esc_attr_e( 'Book a consultation', 'circumcision-london' ); ?>"
		src="<?php echo esc_url( $cfg['src'] ); ?>"
		width="100%"
		height="1000"
		scrolling="auto"
		allow="payment"
		style="pointer-events: auto;"
	></iframe>
</div>
