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

/*
 * Cards layout (testimonials): portrait poster tiles in a row, captioned.
 * Uses the same click-to-play facade as the list layout, so no video bytes
 * load until the visitor presses play. Rendered without its own <section>.
 */
if ( isset( $args['layout'] ) && 'cards' === $args['layout'] ) {
	$tiles = array();
	foreach ( $items as $item ) {
		$item = cil_resolve_video_item( is_array( $item ) ? $item : array() );
		if ( ! empty( $item['mp4'] ) || ! empty( $item['webm'] ) || ! empty( $item['mediaKey'] ) || ! empty( $item['title'] ) ) {
			$tiles[] = $item;
		}
	}
	if ( ! $tiles ) {
		return;
	}
	// Optional category heading (e.g. Baby Testimonials). Rendered only with
	// videos, so an empty category shows nothing on the page.
	$category = isset( $args['category_title'] ) ? trim( (string) $args['category_title'] ) : '';
	if ( '' !== $category ) {
		$category_id = isset( $args['category_id'] ) ? sanitize_html_class( (string) $args['category_id'] ) : '';
		?>
		<h3 class="display d-3 cil-video-category"<?php echo $category_id ? ' id="' . esc_attr( $category_id ) . '"' : ''; ?>><?php echo esc_html( $category ); ?></h3>
		<?php
	}
	?>
	<div class="grid g-4 cil-video-cards">
		<?php foreach ( $tiles as $i => $item ) : ?>
			<?php
			$title = isset( $item['title'] ) ? (string) $item['title'] : '';
			$label = $title ? $title : __( 'Video', 'circumcision-london' );
			$src   = ! empty( $item['mp4'] ) ? $item['mp4'] : ( ! empty( $item['webm'] ) ? $item['webm'] : '' );
			?>
			<figure class="cil-video-tile" data-reveal data-reveal-delay="<?php echo esc_attr( (string) ( ( $i % 4 ) * 80 ) ); ?>">
				<?php if ( ! $src ) : ?>
				<div class="cil-video-facade cil-video-facade--portrait is-unavailable">
					<?php if ( ! empty( $item['poster'] ) ) : ?>
						<img class="cil-video-facade__poster" src="<?php echo esc_url( $item['poster'] ); ?>" alt="" loading="lazy" decoding="async" width="720" height="1280">
					<?php endif; ?>
					<p class="cil-video-facade__error"><?php echo esc_html( sprintf( /* translators: %s: clinic phone number */ __( 'This video is not available at the moment. Please call the clinic on %s.', 'circumcision-london' ), cil_clinic()['phone']['display'] ) ); ?></p>
				</div>
				<?php else : ?>
				<div class="cil-video-facade cil-video-facade--portrait" data-cil-video data-src="<?php echo esc_url( $src ); ?>" data-type="<?php echo esc_attr( cil_video_mime_from_url( $src ) ); ?>"<?php echo ! empty( $item['poster'] ) ? ' data-poster="' . esc_url( $item['poster'] ) . '"' : ''; ?>>
					<a class="cil-video-facade__link" href="<?php echo esc_url( $src ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: video title */ __( 'Play video: %s', 'circumcision-london' ), $label ) ); ?>">
						<?php if ( ! empty( $item['poster'] ) ) : ?>
							<img class="cil-video-facade__poster" src="<?php echo esc_url( $item['poster'] ); ?>" alt="" loading="lazy" decoding="async" width="720" height="1280">
						<?php endif; ?>
						<span class="cil-video-facade__play" aria-hidden="true"></span>
					</a>
					<p class="cil-video-facade__error" role="alert" hidden><?php echo esc_html( sprintf( /* translators: %s: clinic phone number */ __( 'This video could not be loaded. Please try again later, or call the clinic on %s.', 'circumcision-london' ), cil_clinic()['phone']['display'] ) ); ?></p>
				</div>
				<?php endif; ?>
				<?php if ( $title ) : ?>
					<figcaption class="cil-video-tile__caption meta"><?php echo esc_html( $title ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endforeach; ?>
	</div>
	<?php
	return;
}

/*
 * List layout (aftercare age pages): one row per video with its heading and
 * note beside a poster. No <video> element or video bytes load until the
 * visitor presses play (theme.js swaps the link for a player). Without JS the
 * link opens the MP4 directly. Rendered without its own <section> so it can
 * sit inside a cil/section block.
 */
if ( isset( $args['layout'] ) && 'list' === $args['layout'] ) {
	$rows = array();
	foreach ( $items as $item ) {
		$item = cil_resolve_video_item( is_array( $item ) ? $item : array() );
		if ( ! empty( $item['mp4'] ) || ! empty( $item['webm'] ) || ! empty( $item['mediaKey'] ) || ! empty( $item['title'] ) ) {
			$rows[] = $item;
		}
	}
	if ( ! $rows ) {
		return;
	}
	$item_level = isset( $args['item_level'] ) ? (int) $args['item_level'] : 3;
	$item_tag   = 'h' . ( in_array( $item_level, array( 2, 3, 4 ), true ) ? $item_level : 3 );
	$note_tags  = array(
		'a'      => array(
			'href'   => true,
			'rel'    => true,
			'target' => true,
		),
		'strong' => array(),
		'em'     => array(),
		'br'     => array(),
		'span'   => array(
			'lang' => true,
			'dir'  => true,
		),
	);
	?>
	<div class="cil-video-list">
		<?php foreach ( $rows as $item ) : ?>
			<?php
			$title    = isset( $item['title'] ) ? (string) $item['title'] : '';
			$duration = isset( $item['duration'] ) ? (string) $item['duration'] : '';
			$label    = $title ? $title : __( 'Video', 'circumcision-london' );
			$src      = ! empty( $item['mp4'] ) ? $item['mp4'] : ( ! empty( $item['webm'] ) ? $item['webm'] : '' );
			?>
			<article class="cil-video-row" data-reveal>
				<div class="cil-video-row__text">
					<?php if ( $title ) : ?>
						<<?php echo tag_escape( $item_tag ); ?> class="cil-video-row__title"><?php echo esc_html( $title ); ?></<?php echo tag_escape( $item_tag ); ?>>
					<?php endif; ?>
					<?php if ( ! empty( $item['description'] ) ) : ?>
						<p class="cil-video-row__note"><?php echo wp_kses( $item['description'], $note_tags ); ?></p>
					<?php endif; ?>
					<?php
					$cta_label = isset( $item['ctaLabel'] ) ? trim( (string) $item['ctaLabel'] ) : '';
					$cta_url   = isset( $item['ctaUrl'] ) ? trim( (string) $item['ctaUrl'] ) : '';
					if ( $cta_label && $cta_url ) :
						?>
						<p class="cil-video-row__cta">
							<a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
						</p>
					<?php elseif ( $duration ) : ?>
						<p class="cil-video-row__meta meta"><?php echo esc_html( sprintf( /* translators: %s: video length, e.g. 2:30 */ __( 'Video · %s', 'circumcision-london' ), $duration ) ); ?></p>
					<?php endif; ?>
				</div>
				<div class="cil-video-row__media">
					<?php if ( ! $src ) : ?>
					<div class="cil-video-facade is-unavailable">
						<?php if ( ! empty( $item['poster'] ) ) : ?>
							<img class="cil-video-facade__poster" src="<?php echo esc_url( $item['poster'] ); ?>" alt="" loading="lazy" decoding="async" width="640" height="360">
						<?php endif; ?>
						<p class="cil-video-facade__error"><?php echo esc_html( sprintf( /* translators: %s: clinic phone number */ __( 'This video is not available at the moment. Please call the clinic on %s.', 'circumcision-london' ), cil_clinic()['phone']['display'] ) ); ?></p>
					</div>
					<?php else : ?>
					<div class="cil-video-facade" data-cil-video data-src="<?php echo esc_url( $src ); ?>" data-type="<?php echo esc_attr( cil_video_mime_from_url( $src ) ); ?>"<?php echo ! empty( $item['poster'] ) ? ' data-poster="' . esc_url( $item['poster'] ) . '"' : ''; ?>>
						<a class="cil-video-facade__link" href="<?php echo esc_url( $src ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: video title, 2: length */ __( 'Play video: %1$s %2$s', 'circumcision-london' ), $label, $duration ? '(' . $duration . ')' : '' ) ); ?>">
							<?php if ( ! empty( $item['poster'] ) ) : ?>
								<img class="cil-video-facade__poster" src="<?php echo esc_url( $item['poster'] ); ?>" alt="" loading="lazy" decoding="async" width="640" height="360">
							<?php endif; ?>
							<span class="cil-video-facade__play" aria-hidden="true"></span>
							<?php if ( $duration ) : ?>
								<span class="cil-video-facade__time" aria-hidden="true"><?php echo esc_html( $duration ); ?></span>
							<?php endif; ?>
						</a>
						<p class="cil-video-facade__error" role="alert" hidden><?php echo esc_html( sprintf( /* translators: %s: clinic phone number */ __( 'This video could not be loaded. Please try again later, or call the clinic on %s.', 'circumcision-london' ), cil_clinic()['phone']['display'] ) ); ?></p>
					</div>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
	<?php
	return;
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
