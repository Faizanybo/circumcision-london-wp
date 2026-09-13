<?php
/**
 * Clinic introduction and practitioner photograph.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img = cil_asset( 'images/' );
?>
<section class="section">
	<div class="wrap">
		<div class="split">
			<div data-reveal>
				<span class="caps eyebrow"><?php esc_html_e( 'Welcome to the clinic', 'circumcision-london' ); ?></span>
				<h2 class="display d-1"><?php esc_html_e( 'Choosing circumcision is an important decision. We will talk you through all of it.', 'circumcision-london' ); ?></h2>
				<div class="body-text" style="margin-top:24px">
					<p><?php esc_html_e( 'Our clinic is in North-West London, about fifteen minutes from Wembley Stadium, outside the congestion charge, with free street parking all around it. We circumcise babies, infants, children, teenagers and adult men, day in and day out. It is what this clinic does.', 'circumcision-london' ); ?></p>
					<p><?php echo wp_kses_post( sprintf( /* translators: %s: courses URL */ __( 'Our two practitioners have more than forty years of combined experience between them, in the UK and abroad, and have carried out thousands of circumcisions across every age group. They also <a href="%s">train doctors in the UK and internationally</a> in how to circumcise safely.', 'circumcision-london' ), esc_url( home_url( '/courses/' ) ) ) ); ?></p>
					<p><?php esc_html_e( 'Whether you are here for religious, medical, cultural or personal reasons, the standard does not change: local anaesthetic every time, tested and shown to be working before we begin, and aftercare we explain to you and then give you in writing.', 'circumcision-london' ); ?></p>
				</div>
				<div class="btn-row" style="margin-top:30px">
					<a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/team/' ) ); ?>"><?php esc_html_e( 'Meet the two practitioners', 'circumcision-london' ); ?></a>
					<a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/aftercare/' ) ); ?>"><?php esc_html_e( 'What aftercare involves', 'circumcision-london' ); ?></a>
				</div>
			</div>

			<figure class="figure ratio-5-4" data-reveal data-reveal-delay="120">
				<picture>
					<source type="image/webp" srcset="<?php echo esc_url( $img . 'dr-haidar-700.webp' ); ?> 700w, <?php echo esc_url( $img . 'dr-haidar-1100.webp' ); ?> 1100w" sizes="(max-width: 900px) 92vw, 46vw">
					<img src="<?php echo esc_url( $img . 'dr-haidar-700.jpg' ); ?>" width="1920" height="1080" loading="lazy" decoding="async"
						alt="<?php esc_attr_e( 'Dr Haidar Al-Ali in clinic scrubs in the treatment room at the Edgware practice.', 'circumcision-london' ); ?>">
				</picture>
				<figcaption><?php esc_html_e( 'Dr Haidar Al-Ali carries out most of the circumcisions at this clinic.', 'circumcision-london' ); ?></figcaption>
			</figure>
		</div>
	</div>
</section>
