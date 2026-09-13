<?php
/**
 * Urgent A&E note. Repeated on clinical pages in the prototype.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();
?>
<div class="callout urgent" data-reveal>
	<h2 class="display d-3"><?php esc_html_e( 'If this is urgent, do not wait for an appointment', 'circumcision-london' ); ?></h2>
	<p style="margin-top:10px"><?php echo wp_kses_post( sprintf( /* translators: %s: clinic phone URL */ __( 'Go to A&amp;E today if a pulled-back foreskin is trapped behind the head of the penis and swelling, if you or your child cannot pass urine, or if there is spreading redness with a fever. Those need treating in hours. For anything else, call <a href="%1$s" data-track="call-urgent" style="color:var(--blue-deep);font-weight:600">%2$s</a> and tell us what is happening, and we will bring you forward if you need it.', 'circumcision-london' ), esc_url( $clinic['phone']['href'] ), esc_html( $clinic['phone']['display'] ) ) ); ?></p>
</div>
