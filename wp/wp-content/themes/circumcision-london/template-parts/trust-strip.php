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
			'l' => __( 'Years of combined experience between our two practitioners, in the UK and abroad. They both train doctors from UK and abroad.', 'circumcision-london' ),
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
$google_logo = ! empty( $args['google_logo'] );
$cqc_widget  = ! empty( $args['cqc_widget'] ) && ! empty( $clinic['cqc_location_id'] );
$enhanced    = $google_logo || $cqc_widget;
?>
<section class="section-sm bg-card edge<?php echo $enhanced ? ' cil-trust' : ''; ?>">
	<div class="wrap">
		<ul class="grid g-4" style="list-style:none">
			<?php foreach ( $items as $i => $item ) : ?>
				<?php
				$n         = isset( $item['n'] ) ? (string) $item['n'] : '';
				$l         = isset( $item['l'] ) ? (string) $item['l'] : '';
				$is_google = $google_logo && false !== stripos( $l, 'Google' );
				$is_cqc    = $cqc_widget && 'CQC' === trim( $n );
				$icon      = '';
				if ( $enhanced ) {
					$icon = $is_google ? 'google' : ( 'CQC' === trim( $n ) ? 'shield' : ( false !== stripos( $l, 'Languages' ) ? 'globe' : 'award' ) );
				}
				?>
				<li class="stat<?php echo $is_google ? ' stat--google' : ''; ?><?php echo $is_cqc ? ' stat--cqc' : ''; ?>" data-reveal<?php echo $i ? ' data-reveal-delay="' . esc_attr( (string) ( $i * 80 ) ) . '"' : ''; ?>>
					<?php if ( $icon ) : ?>
						<span class="stat-icon"><?php echo cil_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static theme SVG. ?></span>
					<?php endif; ?>
					<span class="n"><?php echo esc_html( $n ); ?></span>
					<span class="l"><?php echo wp_kses_post( $l ); ?></span>
					<?php if ( $is_google ) : ?>
						<a class="stat-link" href="<?php echo esc_url( $clinic['reviews']['read_url'] ); ?>" target="_blank" rel="noopener noreferrer" data-track="reviews-trust-strip"><?php esc_html_e( 'View on Google Maps', 'circumcision-london' ); ?></a>
					<?php endif; ?>
					<?php if ( $is_cqc ) : ?>
						<div class="cqc-widget-slot">
							<?php // Official CQC ratings widget (code from the clinic's CQC "Ratings posters and widgets" page) with async so a slow CQC server never blocks the page. ?>
							<script type="text/javascript" async src="<?php echo esc_url( 'https://www.cqc.org.uk/sites/all/modules/custom/cqc_widget/widget.js?data-id=' . rawurlencode( $clinic['cqc_location_id'] ) . '&data-host=https://www.cqc.org.uk&type=location' ); ?>"></script>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
