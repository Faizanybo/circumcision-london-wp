<?php
/**
 * Section heading block used across inner pages.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$heading = isset( $args['heading'] ) ? $args['heading'] : '';
$lede    = isset( $args['lede'] ) ? $args['lede'] : '';
$display = isset( $args['display'] ) ? $args['display'] : 'd-2';
$display = in_array( $display, array( 'd-1', 'd-2', 'd-3' ), true ) ? $display : 'd-2';
?>
<div class="section-head" data-reveal>
	<?php if ( $eyebrow ) : ?>
		<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
	<?php endif; ?>
	<?php if ( $heading ) : ?>
		<h2 class="display <?php echo esc_attr( $display ); ?>"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>
	<?php if ( $lede ) : ?>
		<p class="lede"><?php echo esc_html( $lede ); ?></p>
	<?php endif; ?>
</div>
