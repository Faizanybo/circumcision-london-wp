<?php
/**
 * Sticky site header, desktop nav and mobile overlay.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();
?>
<header class="header" id="siteHeader">
	<div class="header-inner">
		<?php get_template_part( 'template-parts/brand' ); ?>
		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'circumcision-london' ); ?>">
			<?php cil_primary_nav(); ?>
		</nav>
		<div class="header-cta">
			<a class="header-tel" href="<?php echo esc_url( $clinic['phone']['href'] ); ?>" data-track="call-header"><?php echo cil_icon( 'phone' ); ?><span><?php echo esc_html( $clinic['phone']['display'] ); ?></span></a>
			<a class="btn" href="<?php echo esc_url( cil_book_url() ); ?>" data-track="book-header"><?php esc_html_e( 'Book a consultation', 'circumcision-london' ); ?></a>
		</div>
		<button class="burger" id="burger" type="button" aria-expanded="false" aria-controls="mobileNav" aria-label="<?php esc_attr_e( 'Open menu', 'circumcision-london' ); ?>"><span></span></button>
	</div>
	<nav class="mobile-nav" id="mobileNav" aria-label="<?php esc_attr_e( 'Mobile', 'circumcision-london' ); ?>">
		<?php cil_mobile_nav(); ?>
		<div class="mob-actions">
			<a class="btn" href="<?php echo esc_url( cil_book_url() ); ?>" data-track="book-mobilenav"><?php esc_html_e( 'Book a consultation', 'circumcision-london' ); ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url( $clinic['phone']['href'] ); ?>" data-track="call-mobilenav"><?php echo esc_html( sprintf( /* translators: %s: phone number */ __( 'Call %s', 'circumcision-london' ), $clinic['phone']['display'] ) ); ?></a>
		</div>
	</nav>
</header>
