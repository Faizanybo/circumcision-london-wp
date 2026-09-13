<?php
/**
 * Mobile Call / WhatsApp / Book bar and back-to-top control.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();
?>
<div class="action-bar" role="navigation" aria-label="<?php esc_attr_e( 'Quick contact', 'circumcision-london' ); ?>">
	<ul>
		<li><a href="<?php echo esc_url( $clinic['phone']['href'] ); ?>" data-track="call-bar"><?php echo cil_icon( 'phone' ); ?><span><?php esc_html_e( 'Call', 'circumcision-london' ); ?></span></a></li>
		<li><a href="<?php echo esc_url( $clinic['whatsapp']['href'] ); ?>" rel="noopener" target="_blank" data-track="whatsapp-bar"><?php echo cil_icon( 'whatsapp' ); ?><span><?php esc_html_e( 'WhatsApp', 'circumcision-london' ); ?></span></a></li>
		<li><a class="primary" href="<?php echo esc_url( cil_book_url() ); ?>" data-track="book-bar"><?php echo cil_icon( 'calendar' ); ?><span><?php esc_html_e( 'Book', 'circumcision-london' ); ?></span></a></li>
	</ul>
</div>
<button class="to-top" id="toTop" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'circumcision-london' ); ?>"><?php echo cil_icon( 'arrow-up' ); ?></button>
