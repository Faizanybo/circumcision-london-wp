<?php
/**
 * External rating / CQC badge.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$score = isset( $args['score'] ) ? $args['score'] : '';
$strong = isset( $args['strong'] ) ? $args['strong'] : '';
$sub    = isset( $args['sub'] ) ? $args['sub'] : '';
$href   = isset( $args['href'] ) ? $args['href'] : '';
$track  = isset( $args['track'] ) ? $args['track'] : 'reviews-read';
$small  = ! empty( $args['small_score'] );
if ( ! $href ) {
	return;
}
?>
<a class="rating-badge" href="<?php echo esc_url( $href ); ?>" rel="noopener" target="_blank" data-track="<?php echo esc_attr( $track ); ?>">
	<span class="score"<?php echo $small ? ' style="font-size:22px"' : ''; ?>><?php echo esc_html( $score ); ?></span>
	<span class="txt"><strong><?php echo esc_html( $strong ); ?></strong><br><?php echo esc_html( $sub ); ?></span>
</a>
