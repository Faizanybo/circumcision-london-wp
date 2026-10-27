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
$google_link_label = ( isset( $args['google_link_label'] ) && '' !== $args['google_link_label'] ) ? $args['google_link_label'] : __( 'View on Google Maps', 'circumcision-london' );
$google_link_url   = ( isset( $args['google_link_url'] ) && '' !== $args['google_link_url'] ) ? $args['google_link_url'] : $clinic['reviews']['read_url'];
$cqc_link_url      = ( isset( $args['cqc_link_url'] ) && '' !== $args['cqc_link_url'] ) ? $args['cqc_link_url'] : $clinic['cqc_url'];
$cqc_link_label    = ( isset( $args['cqc_link_label'] ) && '' !== $args['cqc_link_label'] ) ? $args['cqc_link_label'] : __( 'Read the report', 'circumcision-london' );
?>
<section class="section-sm bg-card edge<?php echo $enhanced ? ' cil-trust' : ''; ?>">
	<div class="wrap">
		<ul class="grid g-4" style="list-style:none">
			<?php foreach ( $items as $i => $item ) : ?>
				<?php
				$n          = isset( $item['n'] ) ? (string) $item['n'] : '';
				$l          = isset( $item['l'] ) ? (string) $item['l'] : '';
				$link_label = isset( $item['linkLabel'] ) ? trim( (string) $item['linkLabel'] ) : '';
				$link_url   = isset( $item['linkUrl'] ) ? trim( (string) $item['linkUrl'] ) : '';
				$is_cqc     = $cqc_widget && 'CQC' === trim( $n );
				if ( ! $is_cqc && '' !== $link_label && '' !== $link_url ) {
					$l .= ' <a href="' . esc_url( $link_url ) . '" rel="noopener" target="_blank" style="color:var(--blue-deep)">' . esc_html( $link_label ) . '</a>.';
				}
				$is_google = $google_logo && false !== stripos( $l, 'Google' );
				$icon      = '';
				if ( $enhanced ) {
					if ( $is_cqc ) {
						$icon = 'shield';
					} else {
						$icon = $is_google ? 'google' : ( false !== stripos( $l, 'Languages' ) ? 'globe' : 'award' );
					}
				}
				?>
				<li class="stat<?php echo $is_google ? ' stat--google' : ''; ?><?php echo $is_cqc ? ' stat--cqc' : ''; ?>" data-reveal<?php echo $i ? ' data-reveal-delay="' . esc_attr( (string) ( $i * 80 ) ) . '"' : ''; ?>">
					<?php if ( $icon ) : ?>
						<span class="stat-icon"><?php echo cil_icon( $icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static theme SVG. ?></span>
					<?php endif; ?>
					<?php if ( $is_cqc ) : ?>
						<?php
						$cqc_report_label = __( 'Read the report', 'circumcision-london' );
						if ( '' !== trim( (string) $cqc_link_label ) && ! in_array( trim( (string) $cqc_link_label ), array( 'CQC Good', 'CQC rated Good' ), true ) ) {
							$cqc_report_label = $cqc_link_label;
						}
						?>
						<span class="n"><?php esc_html_e( 'CQC Registered', 'circumcision-london' ); ?></span>
						<?php echo cil_cqc_widget_html( $cqc_link_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in helper. ?>
						<a class="stat-link" href="<?php echo esc_url( $cqc_link_url ); ?>" target="_blank" rel="noopener noreferrer" data-track="cqc-trust-strip"><?php echo esc_html( $cqc_report_label ); ?></a>
					<?php else : ?>
						<span class="n"><?php echo esc_html( $n ); ?></span>
						<span class="l"><?php echo wp_kses_post( $l ); ?></span>
						<?php if ( $is_google ) : ?>
							<a class="stat-link" href="<?php echo esc_url( $google_link_url ); ?>" target="_blank" rel="noopener noreferrer" data-track="reviews-trust-strip"><?php echo esc_html( $google_link_label ); ?></a>
						<?php endif; ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
