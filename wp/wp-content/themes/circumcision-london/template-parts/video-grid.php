<?php
/**
 * Video testimonial grid.
 *
 * Renders nothing when $args['items'] is empty unless show_empty is true
 * (dedicated Video Testimonials page empty state).
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items      = ( isset( $args['items'] ) && is_array( $args['items'] ) ) ? $args['items'] : array();
$show_empty = ! empty( $args['show_empty'] );
$show_head  = ! array_key_exists( 'show_head', $args ) || ! empty( $args['show_head'] );
$eyebrow   = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$heading    = isset( $args['heading'] ) ? $args['heading'] : '';
$lede       = isset( $args['lede'] ) ? $args['lede'] : '';

if ( $show_head ) {
	if ( '' === $eyebrow ) {
		$eyebrow = __( 'Video', 'circumcision-london' );
	}
	if ( '' === $heading ) {
		$heading = __( 'Parents and patients, in their own words', 'circumcision-london' );
	}
	if ( '' === $lede ) {
		$lede = __( 'Filmed at the clinic immediately after the procedure. Nothing is scripted.', 'circumcision-london' );
	}
}

$resolved = array();
foreach ( $items as $item ) {
	$item = cil_resolve_video_item( is_array( $item ) ? $item : array() );
	if ( empty( $item['mp4'] ) && empty( $item['webm'] ) ) {
		continue;
	}
	$resolved[] = $item;
}

if ( ! $resolved && ! $show_empty ) {
	return;
}

$section_class = $show_head ? 'section bg-card edge cil-video-section' : 'section-sm bg-card edge cil-video-section cil-video-section--flush';
?>
<section class="<?php echo esc_attr( $section_class ); ?>">
	<div class="wrap">
		<?php if ( $show_head ) : ?>
			<?php
			get_template_part(
				'template-parts/section-head',
				null,
				array(
					'eyebrow' => $eyebrow,
					'heading' => $heading,
					'lede'    => $lede,
					'display' => 'd-1',
				)
			);
			?>
		<?php endif; ?>
		<?php if ( $resolved ) : ?>
			<div class="grid g-3 cil-video-grid">
				<?php foreach ( $resolved as $i => $item ) : ?>
					<figure class="cil-video-card" data-reveal data-reveal-delay="<?php echo esc_attr( (string) ( ( $i % 3 ) * 90 ) ); ?>">
						<div class="cil-video-card__frame">
							<video controls preload="metadata" playsinline class="cil-video-card__player"<?php echo ! empty( $item['poster'] ) ? ' poster="' . esc_url( $item['poster'] ) . '"' : ''; ?>>
								<?php if ( ! empty( $item['webm'] ) ) : ?>
									<source src="<?php echo esc_url( $item['webm'] ); ?>" type="<?php echo esc_attr( cil_video_mime_from_url( $item['webm'] ) ); ?>">
								<?php endif; ?>
								<?php if ( ! empty( $item['mp4'] ) ) : ?>
									<source src="<?php echo esc_url( $item['mp4'] ); ?>" type="<?php echo esc_attr( cil_video_mime_from_url( $item['mp4'] ) ); ?>">
								<?php endif; ?>
							</video>
						</div>
						<?php if ( ! empty( $item['title'] ) ) : ?>
							<figcaption class="cil-video-card__caption meta"><?php echo esc_html( $item['title'] ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="cil-video-empty body-text" data-reveal>
				<p><?php esc_html_e( 'Approved video testimonials will appear here. Add or replace videos in the block editor when they are ready to publish. Source footage is kept separately and is not uploaded automatically.', 'circumcision-london' ); ?></p>
				<p class="muted" style="margin-top:12px"><?php esc_html_e( 'Use the Video testimonials block inspector to attach Media Library files or external MP4/WebM URLs.', 'circumcision-london' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
