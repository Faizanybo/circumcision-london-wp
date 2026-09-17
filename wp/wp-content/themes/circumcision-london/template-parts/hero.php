<?php
/**
 * Homepage hero: poster, optional video, CTAs and facts.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic  = cil_clinic();
$img     = cil_asset( 'images/' );
$vid     = cil_asset( 'video/' );
$eyebrow = ( isset( $args['eyebrow'] ) && '' !== $args['eyebrow'] ) ? $args['eyebrow'] : __( 'CQC registered · Edgware, North-West London', 'circumcision-london' );
$title   = ( isset( $args['title'] ) && '' !== $args['title'] ) ? $args['title'] : __( 'A dedicated circumcision clinic in North-West London', 'circumcision-london' );
$sub     = ( isset( $args['sub'] ) && '' !== $args['sub'] ) ? $args['sub'] : __( 'Qualified practitioners, local anaesthetic every time, and we show you that no pain is felt before we begin.', 'circumcision-london' );
?>
<section class="hero">
	<div class="hero-media">
		<picture class="hero-poster">
			<source type="image/webp" srcset="<?php echo esc_url( $img . 'hero-poster-800.webp' ); ?> 800w, <?php echo esc_url( $img . 'hero-poster-1200.webp' ); ?> 1200w, <?php echo esc_url( $img . 'hero-poster-1800.webp' ); ?> 1800w" sizes="100vw">
			<img src="<?php echo esc_url( $img . 'hero-poster-800.jpg' ); ?>"
				srcset="<?php echo esc_url( $img . 'hero-poster-800.jpg' ); ?> 800w, <?php echo esc_url( $img . 'hero-poster-1200.jpg' ); ?> 1200w" sizes="100vw"
				width="1920" height="1080"
				alt="<?php esc_attr_e( 'The clinic frontage in Edgware, with the practice name and telephone numbers etched into the treatment-room window.', 'circumcision-london' ); ?>"
				fetchpriority="high" decoding="async">
		</picture>
		<video class="hero-video" id="heroVideo"
			width="1920" height="1080"
			autoplay muted loop playsinline preload="none"
			poster="<?php echo esc_url( $img . 'hero-poster-800.jpg' ); ?>"
			aria-hidden="true" tabindex="-1">
			<source src="<?php echo esc_url( $vid . 'hero.webm' ); ?>" type="video/webm">
			<source src="<?php echo esc_url( $vid . 'hero.mp4' ); ?>" type="video/mp4">
		</video>
	</div>

	<div class="hero-inner">
		<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<h1 class="display d-hero"><?php echo esc_html( $title ); ?></h1>
		<p class="hero-sub"><?php echo esc_html( $sub ); ?></p>

		<div class="btn-row hero-actions">
			<a class="btn" href="<?php echo esc_url( cil_book_url() ); ?>" data-track="book-hero"><?php esc_html_e( 'Book a consultation', 'circumcision-london' ); ?></a>
			<a class="btn btn-light" href="<?php echo esc_url( $clinic['phone']['href'] ); ?>" data-track="call-hero"><?php echo esc_html( sprintf( /* translators: %s: phone number */ __( 'Call %s', 'circumcision-london' ), $clinic['phone']['display'] ) ); ?></a>
		</div>

		<ul class="hero-facts">
			<li><?php echo cil_icon( 'star' ); ?><span><b><?php echo esc_html( $clinic['reviews']['rating'] ); ?></b> <?php echo esc_html( sprintf( /* translators: %s: review count */ __( 'from %s Google reviews', 'circumcision-london' ), $clinic['reviews']['count_display'] ) ); ?></span></li>
			<li><?php echo cil_icon( 'shield' ); ?><span><?php echo esc_html( sprintf( /* translators: %s: CQC rating */ __( 'CQC registered, rated %s', 'circumcision-london' ), $clinic['cqc_rating'] ) ); ?></span></li>
			<li><?php echo cil_icon( 'clock' ); ?><span><?php esc_html_e( 'Open', 'circumcision-london' ); ?> <b><?php esc_html_e( 'Monday to Saturday', 'circumcision-london' ); ?></b></span></li>
			<li><?php echo cil_icon( 'pin' ); ?><span><?php esc_html_e( 'Free street parking, outside ULEZ', 'circumcision-london' ); ?></span></li>
		</ul>
	</div>
</section>
