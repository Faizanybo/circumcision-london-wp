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
$html    = ( isset( $args['html'] ) && '' !== $args['html'] ) ? $args['html'] : '';
$sub     = ( isset( $args['sub'] ) && '' !== $args['sub'] ) ? $args['sub'] : '';
$facts   = ( isset( $args['facts'] ) && is_array( $args['facts'] ) ) ? $args['facts'] : array();
if ( ! $html && ! $sub ) {
	$sub = __( 'Qualified practitioners, local anaesthetic every time, and we show you that no pain is felt before we begin.', 'circumcision-london' );
}

$poster_id     = isset( $args['poster_id'] ) ? cil_attachment_id( $args['poster_id'] ) : 0;
$video_mp4_id  = isset( $args['video_mp4_id'] ) ? cil_attachment_id( $args['video_mp4_id'] ) : 0;
$video_webm_id = isset( $args['video_webm_id'] ) ? cil_attachment_id( $args['video_webm_id'] ) : 0;
$poster_html   = $poster_id ? cil_attachment_image_html(
	$poster_id,
	'cil-hero',
	array(
		'sizes'         => '100vw',
		'fetchpriority' => 'high',
		'decoding'      => 'async',
		'alt'           => cil_attachment_alt( $poster_id, __( 'The clinic frontage in Edgware, with the practice name and telephone numbers etched into the treatment-room window.', 'circumcision-london' ) ),
	)
) : '';
$poster_url    = $poster_id ? cil_attachment_url( $poster_id, 'image' ) : ( $img . 'hero-poster-800.jpg' );
$custom_mp4    = $video_mp4_id ? cil_attachment_url( $video_mp4_id, 'video' ) : '';
$custom_webm   = $video_webm_id ? cil_attachment_url( $video_webm_id, 'video' ) : '';
$use_custom_video = ( $custom_mp4 || $custom_webm );
?>
<section class="hero">
	<div class="hero-media">
		<picture class="hero-poster">
			<?php if ( $poster_html ) : ?>
				<?php echo $poster_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image(). ?>
			<?php else : ?>
			<source type="image/webp" srcset="<?php echo esc_url( $img . 'hero-poster-800.webp' ); ?> 800w, <?php echo esc_url( $img . 'hero-poster-1200.webp' ); ?> 1200w, <?php echo esc_url( $img . 'hero-poster-1800.webp' ); ?> 1800w" sizes="100vw">
			<img src="<?php echo esc_url( $img . 'hero-poster-800.jpg' ); ?>"
				srcset="<?php echo esc_url( $img . 'hero-poster-800.jpg' ); ?> 800w, <?php echo esc_url( $img . 'hero-poster-1200.jpg' ); ?> 1200w" sizes="100vw"
				width="1920" height="1080"
				alt="<?php esc_attr_e( 'The clinic frontage in Edgware, with the practice name and telephone numbers etched into the treatment-room window.', 'circumcision-london' ); ?>"
				fetchpriority="high" decoding="async">
			<?php endif; ?>
		</picture>
		<video class="hero-video" id="heroVideo"
			width="1920" height="1080"
			autoplay muted loop playsinline preload="none"
			poster="<?php echo esc_url( $poster_url ); ?>"
			aria-hidden="true" tabindex="-1">
			<?php if ( $use_custom_video ) : ?>
				<?php if ( $custom_webm ) : ?>
					<source src="<?php echo esc_url( $custom_webm ); ?>" type="video/webm">
				<?php endif; ?>
				<?php if ( $custom_mp4 ) : ?>
					<source src="<?php echo esc_url( $custom_mp4 ); ?>" type="video/mp4">
				<?php endif; ?>
			<?php else : ?>
			<source src="<?php echo esc_url( $vid . 'hero.webm' ); ?>" type="video/webm">
			<source src="<?php echo esc_url( $vid . 'hero.mp4' ); ?>" type="video/mp4">
			<?php endif; ?>
		</video>
	</div>

	<div class="hero-inner">
		<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<h1 class="display d-hero"><?php echo esc_html( $title ); ?></h1>
		<?php
		$hero_copy = $html ? $html : $sub;
		if ( $hero_copy && false !== strpos( $hero_copy, '<' ) ) :
			?>
		<div class="hero-copy"><?php echo cil_rich_text( $hero_copy ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allowlisted in cil_rich_text(). ?></div>
		<?php elseif ( $hero_copy ) : ?>
		<p class="hero-sub"><?php echo esc_html( $hero_copy ); ?></p>
		<?php endif; ?>

		<?php
		$cta_label   = ( isset( $args['cta_label'] ) && '' !== $args['cta_label'] ) ? $args['cta_label'] : __( 'Book a consultation', 'circumcision-london' );
		$cta_url     = ( isset( $args['cta_url'] ) && '' !== $args['cta_url'] ) ? $args['cta_url'] : cil_book_url();
		$phone_label = ( isset( $args['phone_label'] ) && '' !== $args['phone_label'] ) ? $args['phone_label'] : sprintf( /* translators: %s: phone number */ __( 'Call %s', 'circumcision-london' ), $clinic['phone']['display'] );
		$phone_url   = ( isset( $args['phone_url'] ) && '' !== $args['phone_url'] ) ? $args['phone_url'] : $clinic['phone']['href'];
		?>
		<div class="btn-row hero-actions">
			<a class="btn" href="<?php echo esc_url( $cta_url ); ?>" data-track="book-hero"><?php echo esc_html( $cta_label ); ?></a>
			<a class="btn btn-light" href="<?php echo esc_url( $phone_url ); ?>" data-track="call-hero"><?php echo esc_html( $phone_label ); ?></a>
		</div>

		<ul class="hero-facts">
			<?php
			$fact_icons = array( 'star', 'shield', 'clock', 'pin' );
			$fact_lines = array();
			foreach ( $facts as $fact ) {
				if ( is_array( $fact ) ) {
					$line = isset( $fact['text'] ) ? $fact['text'] : ( isset( $fact['label'] ) ? $fact['label'] : '' );
				} else {
					$line = (string) $fact;
				}
				$line = trim( wp_strip_all_tags( $line ) );
				if ( '' !== $line ) {
					$fact_lines[] = $line;
				}
			}
			if ( $fact_lines ) :
				foreach ( $fact_lines as $i => $line ) :
					?>
			<li><?php echo cil_icon( $fact_icons[ $i % count( $fact_icons ) ] ); ?><span><?php echo esc_html( $line ); ?></span></li>
					<?php
				endforeach;
			else :
				?>
			<li><?php echo cil_icon( 'star' ); ?><span><b><?php echo esc_html( $clinic['reviews']['rating'] ); ?></b> <?php echo esc_html( sprintf( /* translators: %s: review count */ __( 'from %s Google reviews', 'circumcision-london' ), $clinic['reviews']['count_display'] ) ); ?></span></li>
			<li><?php echo cil_icon( 'shield' ); ?><span><?php echo esc_html( sprintf( /* translators: %s: CQC rating */ __( 'CQC registered, rated %s', 'circumcision-london' ), $clinic['cqc_rating'] ) ); ?></span></li>
			<li><?php echo cil_icon( 'clock' ); ?><span><?php esc_html_e( 'Open', 'circumcision-london' ); ?> <b><?php esc_html_e( 'Monday to Saturday', 'circumcision-london' ); ?></b></span></li>
			<li><?php echo cil_icon( 'pin' ); ?><span><?php esc_html_e( 'Free street parking. Outside the Congestion Charge zone', 'circumcision-london' ); ?></span></li>
			<?php endif; ?>
		</ul>
	</div>
</section>
