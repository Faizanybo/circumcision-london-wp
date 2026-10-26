<?php
/**
 * Homepage reviews: rating, platform links, stats, quotes and video clips.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();
$quotes = ( isset( $args['quotes'] ) && is_array( $args['quotes'] ) && $args['quotes'] ) ? $args['quotes'] : cil_testimonials();
$eyebrow = ( isset( $args['eyebrow'] ) && '' !== $args['eyebrow'] ) ? $args['eyebrow'] : __( 'Reviews', 'circumcision-london' );
/* Optional lede lines — left empty when platform buttons replace them (points 26–27). */
$checked = isset( $args['checked_note'] ) ? (string) $args['checked_note'] : '';
$body    = isset( $args['body'] ) ? (string) $args['body'] : '';
$platforms = ( isset( $args['platforms'] ) && is_array( $args['platforms'] ) ) ? $args['platforms'] : array();
$videos    = ( isset( $args['videos'] ) && is_array( $args['videos'] ) ) ? $args['videos'] : array();
$videos_heading = isset( $args['videos_heading'] ) ? (string) $args['videos_heading'] : '';
$videos_cta_label = isset( $args['videos_cta_label'] ) ? (string) $args['videos_cta_label'] : '';
$videos_cta_url   = isset( $args['videos_cta_url'] ) ? (string) $args['videos_cta_url'] : '';
$testimonials_label = ( isset( $args['testimonials_label'] ) && '' !== $args['testimonials_label'] )
	? $args['testimonials_label']
	: __( 'Read patient testimonials', 'circumcision-london' );
$testimonials_url = ( isset( $args['testimonials_url'] ) && '' !== $args['testimonials_url'] )
	? $args['testimonials_url']
	: home_url( '/testimonials/' );
$heading = isset( $args['heading'] ) ? (string) $args['heading'] : '';
if ( '' === $heading ) {
	$heading = sprintf( /* translators: 1: rating, 2: review count */ __( '%1$s from %2$s Google reviews', 'circumcision-london' ), $clinic['reviews']['rating'], $clinic['reviews']['count_display'] );
}
$stars = ( isset( $args['stars'] ) && '' !== $args['stars'] ) ? (string) $args['stars'] : '★★★★★';
$hide_stats = ! empty( $args['hide_stats'] );
/*
 * Homepage always hides the four side tiles (Google / Word of mouth / Years
 * apart / CQC). They duplicate the trust strip and review heading. This runs
 * even when the live post_content still has the old stats (Content Sync not
 * applied yet).
 */
if ( ! $hide_stats && function_exists( 'is_front_page' ) && is_front_page() ) {
	$hide_stats = true;
}
$stats      = ( ! $hide_stats && isset( $args['stats'] ) && is_array( $args['stats'] ) && $args['stats'] ) ? $args['stats'] : array();
$show_stats = ! $hide_stats;

$resolved_videos = array();
foreach ( $videos as $item ) {
	$item = cil_resolve_video_item( is_array( $item ) ? $item : array() );
	/*
	 * Always serve the theme-bundled Adults clip on the homepage so production
	 * does not keep the old Blob URL until Content Sync is applied.
	 */
	$title = isset( $item['title'] ) ? strtolower( trim( (string) $item['title'] ) ) : '';
	$key   = isset( $item['mediaKey'] ) ? (string) $item['mediaKey'] : '';
	if ( 'adults' === $title || false !== strpos( $key, 'img-5430' ) ) {
		$item['mp4']    = get_template_directory_uri() . '/assets/video/home-adults.mp4';
		$item['poster'] = get_template_directory_uri() . '/assets/images/home/adults-video-poster.jpg';
		unset( $item['mediaKey'], $item['posterKey'] );
	}
	if ( ! empty( $item['mp4'] ) || ! empty( $item['webm'] ) || ! empty( $item['mediaKey'] ) ) {
		$resolved_videos[] = $item;
	}
}
?>
<section class="section bg-warm cil-home-reviews" id="reviews">
	<div class="wrap">
		<div class="split<?php echo $show_stats ? '' : ' cil-reviews-split--solo'; ?>" style="align-items:center;margin-bottom:clamp(34px,4vw,60px)">
			<div data-reveal>
				<span class="caps eyebrow cil-eyebrow-google"><?php echo cil_icon( 'google' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static theme SVG. ?><?php echo esc_html( $eyebrow ); ?></span>
				<h2 class="display d-1"><?php echo esc_html( $heading ); ?></h2>
				<?php if ( '' !== $checked ) : ?>
				<p class="cil-reviews-note"><?php echo esc_html( $checked ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $body ) : ?>
				<p class="cil-reviews-note body-text"><?php echo esc_html( $body ); ?></p>
				<?php endif; ?>
				<?php if ( $platforms ) : ?>
				<div class="cil-reviews-platforms" role="list">
					<?php foreach ( $platforms as $platform ) : ?>
						<?php
						$plabel = isset( $platform['label'] ) ? (string) $platform['label'] : '';
						$purl   = isset( $platform['url'] ) ? (string) $platform['url'] : '';
						$picon  = isset( $platform['icon'] ) ? sanitize_key( (string) $platform['icon'] ) : '';
						if ( '' === $plabel || '' === $purl ) {
							continue;
						}
						$icon_svg = $picon ? cil_icon( $picon ) : '';
						?>
						<a class="cil-reviews-platform" role="listitem" href="<?php echo esc_url( $purl ); ?>" rel="noopener noreferrer" target="_blank" data-track="reviews-platform-<?php echo esc_attr( $picon ? $picon : 'link' ); ?>">
							<?php if ( $icon_svg ) : ?>
								<span class="cil-reviews-platform__icon" aria-hidden="true"><?php echo $icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static theme SVG. ?></span>
							<?php endif; ?>
							<span class="cil-reviews-platform__label"><?php echo esc_html( $plabel ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<div class="cil-reviews-actions">
					<a class="btn btn-ghost" href="<?php echo esc_url( $testimonials_url ); ?>"><?php echo esc_html( $testimonials_label ); ?></a>
				</div>
			</div>
			<?php if ( $show_stats ) : ?>
			<div data-reveal data-reveal-delay="120">
				<ul class="grid g-2" style="list-style:none;gap:18px">
					<?php if ( $stats ) : ?>
						<?php foreach ( $stats as $stat ) : ?>
							<?php
							$stat_n = isset( $stat['n'] ) ? (string) $stat['n'] : '';
							$stat_l = isset( $stat['l'] ) ? (string) $stat['l'] : '';
							?>
					<li class="stat"><span class="n"><?php echo str_replace( "\n", '<br>', esc_html( $stat_n ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped, then a line break is restored. ?></span><span class="l"><?php echo esc_html( $stat_l ); ?></span></li>
						<?php endforeach; ?>
					<?php else : ?>
					<li class="stat"><span class="n"><?php echo esc_html( $clinic['reviews']['count_display'] ); ?></span><span class="l"><?php echo esc_html( sprintf( /* translators: %s: rating */ __( 'Google reviews, at %s out of 5.', 'circumcision-london' ), $clinic['reviews']['rating'] ) ); ?></span></li>
					<li class="stat"><span class="n"><?php echo wp_kses( __( 'Word of<br>mouth', 'circumcision-london' ), array( 'br' => array() ) ); ?></span><span class="l"><?php esc_html_e( 'How most of our patients find us: family and friends who came first.', 'circumcision-london' ); ?></span></li>
					<li class="stat"><span class="n"><?php echo wp_kses( __( 'Years<br>apart', 'circumcision-london' ), array( 'br' => array() ) ); ?></span><span class="l"><?php esc_html_e( 'Families returning whenever there is a new son in the family.', 'circumcision-london' ); ?></span></li>
					<li class="stat"><span class="n"><?php echo esc_html( sprintf( /* translators: %s: CQC rating */ __( 'CQC %s', 'circumcision-london' ), $clinic['cqc_rating'] ) ); ?></span><span class="l"><?php esc_html_e( 'Rated at previous inspections by the regulator.', 'circumcision-london' ); ?></span></li>
					<?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>

		<div class="grid g-3">
			<?php foreach ( $quotes as $i => $quote ) : ?>
				<figure class="quote" data-reveal data-reveal-delay="<?php echo esc_attr( (string) ( ( $i % 3 ) * 90 ) ); ?>">
					<span class="stars" aria-label="<?php esc_attr_e( 'Five out of five', 'circumcision-london' ); ?>"><?php echo esc_html( $stars ); ?></span>
					<blockquote>&ldquo;<?php echo esc_html( $quote['quote'] ); ?>&rdquo;</blockquote>
					<figcaption class="meta"><strong><?php echo esc_html( $quote['name'] ); ?></strong><br><?php echo esc_html( $quote['context'] ); ?></figcaption>
				</figure>
			<?php endforeach; ?>
		</div>

		<?php if ( $resolved_videos || $videos_heading || $videos_cta_label ) : ?>
		<div class="cil-reviews-videos" data-reveal>
			<?php if ( '' !== $videos_heading ) : ?>
			<h3 class="display d-2 cil-reviews-videos__heading"><?php echo esc_html( $videos_heading ); ?></h3>
			<?php endif; ?>
			<?php
			if ( $resolved_videos ) {
				echo cil_render_part( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- template part.
					'video-grid',
					array(
						'items'     => $resolved_videos,
						'show_head' => false,
						'layout'    => 'cards',
					)
				);
			}
			?>
			<?php if ( '' !== $videos_cta_label && '' !== $videos_cta_url ) : ?>
			<div class="cil-reviews-videos__cta">
				<a class="btn" href="<?php echo esc_url( $videos_cta_url ); ?>"><?php echo esc_html( $videos_cta_label ); ?></a>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
