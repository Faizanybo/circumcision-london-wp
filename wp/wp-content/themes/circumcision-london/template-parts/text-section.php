<?php
/**
 * Eyebrow + heading + body section. Alternating band and wrap width via args.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$heading = isset( $args['heading'] ) ? $args['heading'] : '';
$html    = isset( $args['html'] ) ? $args['html'] : '';
$narrow  = ! isset( $args['narrow'] ) || $args['narrow'];
$banded  = ! empty( $args['banded'] );
$display = isset( $args['display'] ) ? $args['display'] : 'd-2';
$class   = 'section-sm' . ( $banded ? ' bg-card edge' : '' );
$wrap    = $narrow ? 'wrap-narrow' : 'wrap';
?>
<section class="<?php echo esc_attr( $class ); ?>">
	<div class="<?php echo esc_attr( $wrap ); ?>">
		<div data-reveal>
			<?php if ( $eyebrow ) : ?>
				<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<?php endif; ?>
			<?php if ( $heading ) : ?>
				<h2 class="display <?php echo esc_attr( $display ); ?>"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $html ) : ?>
				<div style="margin-top:20px"><?php echo cil_rich_text( $html ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
