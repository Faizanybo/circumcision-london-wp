<?php
/**
 * 404 template. Copy and layout from the prototype src/pages/misc.js notFound page.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();

get_header();

cil_page_head(
	__( 'That page does not exist', 'circumcision-london' ),
	sprintf(
		/* translators: %s: clinic phone number */
		__( 'The link may be out of date or mistyped. Everything on this site is one of the following. Or call the clinic on %s and we will find it for you.', 'circumcision-london' ),
		$clinic['phone']['display']
	),
	__( 'Error 404', 'circumcision-london' )
);
?>
<section class="section-sm">
	<div class="wrap">
		<?php get_template_part( 'template-parts/group-cards', null, array( 'level' => 2 ) ); ?>
		<div class="btn-row" style="margin-top:34px">
			<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the homepage', 'circumcision-london' ); ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/prices/' ) ); ?>"><?php esc_html_e( 'Prices', 'circumcision-london' ); ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/aftercare/' ) ); ?>"><?php esc_html_e( 'Aftercare', 'circumcision-london' ); ?></a>
			<a class="btn btn-ghost" href="<?php echo esc_url( $clinic['phone']['href'] ); ?>" data-track="call-404"><?php echo esc_html( sprintf( /* translators: %s: phone number */ __( 'Call %s', 'circumcision-london' ), $clinic['phone']['display'] ) ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
