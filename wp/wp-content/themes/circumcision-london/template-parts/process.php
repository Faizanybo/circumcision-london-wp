<?php
/**
 * Process section wrapping the reusable steps list.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = array_key_exists( 'eyebrow', $args ) ? (string) $args['eyebrow'] : __( 'What happens', 'circumcision-london' );
$heading = ( isset( $args['heading'] ) && '' !== $args['heading'] ) ? $args['heading'] : __( 'Four steps, and no surprises in any of them', 'circumcision-london' );
$lede    = array_key_exists( 'lede', $args ) ? (string) $args['lede'] : __( 'This is the whole process. If anything on the day differs from what is written here, we will have told you why before it happens.', 'circumcision-london' );
$items   = ( isset( $args['items'] ) && is_array( $args['items'] ) ) ? $args['items'] : array();
?>
<section class="section bg-card edge">
	<div class="wrap">
		<div class="section-head" data-reveal>
			<?php if ( $eyebrow ) : ?>
			<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<?php endif; ?>
			<h2 class="display d-1"><?php echo esc_html( $heading ); ?></h2>
			<?php if ( $lede ) : ?>
			<p class="lede"><?php echo esc_html( $lede ); ?></p>
			<?php endif; ?>
		</div>
		<div data-reveal>
			<?php
			get_template_part(
				'template-parts/steps',
				null,
				$items ? array( 'items' => $items ) : array()
			);
			?>
		</div>
	</div>
</section>
