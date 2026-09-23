<?php
/**
 * Trust strip: rating, experience, CQC, languages.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();
$items  = ( isset( $args['items'] ) && is_array( $args['items'] ) && $args['items'] ) ? $args['items'] : array();
if ( ! $items ) {
	$items = array(
		array(
			'n' => $clinic['reviews']['rating'],
			'l' => sprintf( /* translators: 1: review count, 2: date */ __( 'From %1$s Google reviews, checked on %2$s.', 'circumcision-london' ), $clinic['reviews']['count_display'], $clinic['reviews']['verified'] ),
		),
		array(
			'n' => '40+',
			'l' => __( 'Years of combined experience between our two practitioners, in the UK and abroad.', 'circumcision-london' ),
		),
		array(
			'n' => 'CQC',
			'l' => sprintf( /* translators: %s: CQC rating */ __( 'Registered with the Care Quality Commission and rated %s at previous inspections.', 'circumcision-london' ), $clinic['cqc_rating'] ) . ' <a href="' . esc_url( $clinic['cqc_url'] ) . '" rel="noopener" target="_blank" style="color:var(--blue-deep)">' . esc_html__( 'Read the report', 'circumcision-london' ) . '</a>.',
		),
		array(
			'n' => '6',
			'l' => sprintf( /* translators: %s: languages */ __( 'Languages spoken here: %s.', 'circumcision-london' ), $clinic['languages'] ),
		),
	);
}
?>
<section class="section-sm bg-card edge">
	<div class="wrap">
		<ul class="grid g-4" style="list-style:none">
			<?php foreach ( $items as $i => $item ) : ?>
				<li class="stat" data-reveal<?php echo $i ? ' data-reveal-delay="' . esc_attr( (string) ( $i * 80 ) ) . '"' : ''; ?>>
					<span class="n"><?php echo esc_html( isset( $item['n'] ) ? $item['n'] : '' ); ?></span>
					<span class="l"><?php echo wp_kses_post( isset( $item['l'] ) ? $item['l'] : '' ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
