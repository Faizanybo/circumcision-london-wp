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
?>
<section class="section-sm bg-card edge">
	<div class="wrap">
		<ul class="grid g-4" style="list-style:none">
			<li class="stat" data-reveal>
				<span class="n"><?php echo esc_html( $clinic['reviews']['rating'] ); ?></span>
				<span class="l"><?php echo esc_html( sprintf( /* translators: 1: review count, 2: date */ __( 'From %1$s Google reviews, checked on %2$s.', 'circumcision-london' ), $clinic['reviews']['count_display'], $clinic['reviews']['verified'] ) ); ?></span>
			</li>
			<li class="stat" data-reveal data-reveal-delay="80">
				<span class="n">40+</span>
				<span class="l"><?php esc_html_e( 'Years of combined experience between our two practitioners, in the UK and abroad.', 'circumcision-london' ); ?></span>
			</li>
			<li class="stat" data-reveal data-reveal-delay="160">
				<span class="n">CQC</span>
				<span class="l"><?php echo esc_html( sprintf( /* translators: %s: CQC rating */ __( 'Registered with the Care Quality Commission and rated %s at previous inspections.', 'circumcision-london' ), $clinic['cqc_rating'] ) ); ?> <a href="<?php echo esc_url( $clinic['cqc_url'] ); ?>" rel="noopener" target="_blank" style="color:var(--blue-deep)"><?php esc_html_e( 'Read the report', 'circumcision-london' ); ?></a>.</span>
			</li>
			<li class="stat" data-reveal data-reveal-delay="240">
				<span class="n">6</span>
				<span class="l"><?php echo esc_html( sprintf( /* translators: %s: languages */ __( 'Languages spoken here: %s.', 'circumcision-london' ), $clinic['languages'] ) ); ?></span>
			</li>
		</ul>
	</div>
</section>
