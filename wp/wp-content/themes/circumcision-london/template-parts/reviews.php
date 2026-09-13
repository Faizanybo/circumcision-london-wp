<?php
/**
 * Homepage reviews: rating, Google badge, stats and three testimonials.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();
$quotes = cil_testimonials();
?>
<section class="section bg-warm">
	<div class="wrap">
		<div class="split" style="align-items:center;margin-bottom:clamp(34px,4vw,60px)">
			<div data-reveal>
				<span class="caps eyebrow"><?php esc_html_e( 'Reviews', 'circumcision-london' ); ?></span>
				<h2 class="display d-1"><?php echo esc_html( sprintf( /* translators: 1: rating, 2: review count */ __( '%1$s from %2$s Google reviews', 'circumcision-london' ), $clinic['reviews']['rating'], $clinic['reviews']['count_display'] ) ); ?></h2>
				<p class="lede" style="margin-top:18px"><?php echo esc_html( sprintf( /* translators: %s: verification date */ __( 'Checked on %s, and refreshed quarterly so the number here is the number on the profile.', 'circumcision-london' ), $clinic['reviews']['verified'] ) ); ?></p>
				<p class="body-text" style="margin-top:18px"><?php esc_html_e( 'Most of our patients arrive because somebody they trust sent them. The families who come back are the part we are proudest of: some return after many years whenever there is a new son, others bring a brother, then a nephew, then a cousin. We have families we have now seen across three children and the better part of a decade.', 'circumcision-london' ); ?></p>
				<div style="display:flex;flex-wrap:wrap;gap:14px;align-items:center;margin-top:26px">
					<a class="rating-badge" href="<?php echo esc_url( $clinic['reviews']['read_url'] ); ?>" rel="noopener" target="_blank" data-track="reviews-read">
						<span class="score"><?php echo esc_html( $clinic['reviews']['rating'] ); ?></span>
						<span class="txt"><strong><?php esc_html_e( 'Read them on Google', 'circumcision-london' ); ?></strong><br><?php echo esc_html( sprintf( /* translators: %s: review count */ __( '%s reviews, new tab', 'circumcision-london' ), $clinic['reviews']['count_display'] ) ); ?></span>
					</a>
					<a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/testimonials/' ) ); ?>"><?php esc_html_e( 'Read patient testimonials', 'circumcision-london' ); ?></a>
				</div>
			</div>
			<div data-reveal data-reveal-delay="120">
				<ul class="grid g-2" style="list-style:none;gap:18px">
					<li class="stat"><span class="n"><?php echo esc_html( $clinic['reviews']['count_display'] ); ?></span><span class="l"><?php echo esc_html( sprintf( /* translators: %s: rating */ __( 'Google reviews, at %s out of 5.', 'circumcision-london' ), $clinic['reviews']['rating'] ) ); ?></span></li>
					<li class="stat"><span class="n"><?php echo wp_kses( __( 'Word of<br>mouth', 'circumcision-london' ), array( 'br' => array() ) ); ?></span><span class="l"><?php esc_html_e( 'How most of our patients find us: family and friends who came first.', 'circumcision-london' ); ?></span></li>
					<li class="stat"><span class="n"><?php echo wp_kses( __( 'Years<br>apart', 'circumcision-london' ), array( 'br' => array() ) ); ?></span><span class="l"><?php esc_html_e( 'Families returning whenever there is a new son in the family.', 'circumcision-london' ); ?></span></li>
					<li class="stat"><span class="n"><?php echo esc_html( sprintf( /* translators: %s: CQC rating */ __( 'CQC %s', 'circumcision-london' ), $clinic['cqc_rating'] ) ); ?></span><span class="l"><?php esc_html_e( 'Rated at previous inspections by the regulator.', 'circumcision-london' ); ?></span></li>
				</ul>
			</div>
		</div>

		<div class="grid g-3">
			<?php foreach ( $quotes as $i => $quote ) : ?>
				<figure class="quote" data-reveal data-reveal-delay="<?php echo esc_attr( (string) ( ( $i % 3 ) * 90 ) ); ?>">
					<span class="stars" aria-label="<?php esc_attr_e( 'Five out of five', 'circumcision-london' ); ?>">★★★★★</span>
					<blockquote>&ldquo;<?php echo esc_html( $quote['quote'] ); ?>&rdquo;</blockquote>
					<figcaption class="meta"><strong style="color:var(--ink)"><?php echo esc_html( $quote['name'] ); ?></strong><br><?php echo esc_html( $quote['context'] ); ?></figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
