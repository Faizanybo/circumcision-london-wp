<?php
/**
 * Clinic introduction / location split with photograph or Google Map.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img      = cil_asset( 'images/' );
$eyebrow  = array_key_exists( 'eyebrow', $args ) ? (string) $args['eyebrow'] : __( 'Welcome to the clinic', 'circumcision-london' );
$heading  = ( isset( $args['heading'] ) && '' !== $args['heading'] ) ? $args['heading'] : __( 'Choosing circumcision is an important decision. We will talk you through all of it.', 'circumcision-london' );
$html     = ( isset( $args['html'] ) && '' !== $args['html'] ) ? $args['html'] : '';
$image_id = isset( $args['image_id'] ) ? cil_attachment_id( $args['image_id'] ) : 0;
$image_html = $image_id ? cil_attachment_image_html(
	$image_id,
	'cil-figure',
	array(
		'loading'  => 'lazy',
		'decoding' => 'async',
		'sizes'    => '(max-width: 900px) 92vw, 46vw',
	)
) : '';

$is_location = ( 0 === strcasecmp( trim( $heading ), 'Location' ) );
$media_type  = isset( $args['media_type'] ) ? sanitize_key( (string) $args['media_type'] ) : '';
if ( ! in_array( $media_type, array( 'image', 'map' ), true ) ) {
	$media_type = $is_location ? 'map' : 'image';
}

$figure = array(
	'file'    => 'dr-haidar',
	'alt'     => __( 'Dr Haidar Al-Ali in clinic scrubs in the treatment room at the Edgware practice.', 'circumcision-london' ),
	'caption' => __( 'Dr Haidar Al-Ali carries out most of the circumcisions at this clinic.', 'circumcision-london' ),
);
if ( $is_location ) {
	$figure = array(
		'file'    => 'waiting-room',
		'alt'     => __( 'The clinic waiting area at Beverley Clinic in Edgware, with adult seating and a small child\'s chair.', 'circumcision-london' ),
		'caption' => __( 'The waiting area at Beverley Clinic, Edgware.', 'circumcision-london' ),
	);
}

$caption = ( isset( $args['caption'] ) && '' !== $args['caption'] ) ? $args['caption'] : ( 'map' === $media_type ? '' : $figure['caption'] );

$map_embed = ( isset( $args['map_embed_url'] ) && '' !== trim( (string) $args['map_embed_url'] ) )
	? esc_url_raw( trim( (string) $args['map_embed_url'] ) )
	: cil_maps_embed_url();
$clinic    = cil_clinic();

$btn1_label = ( isset( $args['btn1_label'] ) && '' !== $args['btn1_label'] ) ? $args['btn1_label'] : '';
$btn1_url   = ( isset( $args['btn1_url'] ) && '' !== $args['btn1_url'] ) ? $args['btn1_url'] : '';
$btn2_label = ( isset( $args['btn2_label'] ) && '' !== $args['btn2_label'] ) ? $args['btn2_label'] : '';
$btn2_url   = ( isset( $args['btn2_url'] ) && '' !== $args['btn2_url'] ) ? $args['btn2_url'] : '';
if ( '' === $btn1_label ) {
	$btn1_label = $is_location ? __( 'Book online', 'circumcision-london' ) : __( 'Meet the two practitioners', 'circumcision-london' );
}
if ( '' === $btn1_url ) {
	$btn1_url = $is_location ? cil_book_url() : home_url( '/about/#samir' );
}
if ( '' === $btn2_label ) {
	$btn2_label = $is_location ? __( 'Call us', 'circumcision-london' ) : __( 'What aftercare involves', 'circumcision-london' );
}
if ( '' === $btn2_url ) {
	$btn2_url = $is_location ? $clinic['phone']['href'] : home_url( '/aftercare/' );
}
?>
<section class="section<?php echo $is_location ? ' cil-location-section' : ''; ?>">
	<div class="wrap">
		<div class="split">
			<div data-reveal>
				<?php if ( $eyebrow ) : ?>
				<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<h2 class="display d-1"><?php echo esc_html( $heading ); ?></h2>
				<div class="body-text" style="margin-top:24px">
					<?php if ( $html ) : ?>
						<?php echo cil_rich_text( $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allowlisted in cil_rich_text(). ?>
					<?php else : ?>
					<p><?php esc_html_e( 'Our clinic is in North-West London, about fifteen minutes from Wembley Stadium, outside the congestion charge, with free street parking all around it. We circumcise babies, infants, children, teenagers and adult men, day in and day out. It is what this clinic does.', 'circumcision-london' ); ?></p>
					<p><?php echo wp_kses_post( sprintf( /* translators: %s: courses URL */ __( 'Our two practitioners have more than forty years of combined experience between them, in the UK and abroad, and have carried out thousands of circumcisions across every age group. They also <a href="%s">train doctors in the UK and internationally</a> in how to circumcise safely.', 'circumcision-london' ), esc_url( home_url( '/courses/' ) ) ) ); ?></p>
					<p><?php esc_html_e( 'Whether you are here for religious, medical, cultural or personal reasons, the standard does not change: local anaesthetic every time, tested and shown to be working before we begin, and aftercare we explain to you and then give you in writing.', 'circumcision-london' ); ?></p>
					<?php endif; ?>
				</div>
				<div class="btn-row" style="margin-top:30px">
					<a class="btn<?php echo $is_location ? '' : ' btn-ghost'; ?>" href="<?php echo esc_url( $btn1_url ); ?>"<?php echo $is_location ? ' data-track="book-location"' : ''; ?>><?php echo esc_html( $btn1_label ); ?></a>
					<a class="btn btn-ghost" href="<?php echo esc_url( $btn2_url ); ?>"<?php echo $is_location ? ' data-track="call-location"' : ''; ?>><?php echo esc_html( $btn2_label ); ?></a>
				</div>
			</div>

			<?php if ( 'map' === $media_type ) : ?>
			<figure class="figure ratio-5-4 cil-location-map" data-cil-map-zoom>
				<div class="cil-location-map__stage">
					<div class="cil-location-map__orbit" aria-hidden="true"></div>
					<div class="cil-location-map__zoom">
						<iframe
							class="cil-location-map__frame"
							title="<?php echo esc_attr( sprintf( /* translators: %s: clinic legal name */ __( 'Google Map showing %s', 'circumcision-london' ), $clinic['legal_name'] ) ); ?>"
							src="<?php echo esc_url( $map_embed ); ?>"
							loading="lazy"
							referrerpolicy="no-referrer-when-downgrade"
							allowfullscreen></iframe>
					</div>
					<div class="cil-location-map__veil" aria-hidden="true"></div>
				</div>
				<?php if ( $caption ) : ?>
				<figcaption><?php echo esc_html( $caption ); ?></figcaption>
				<?php endif; ?>
			</figure>
			<?php else : ?>
			<figure class="figure ratio-5-4" data-reveal data-reveal-delay="120">
				<?php if ( $image_html ) : ?>
					<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image(). ?>
				<?php else : ?>
				<picture>
					<source type="image/webp" srcset="<?php echo esc_url( $img . $figure['file'] . '-700.webp' ); ?> 700w, <?php echo esc_url( $img . $figure['file'] . '-1100.webp' ); ?> 1100w" sizes="(max-width: 900px) 92vw, 46vw">
					<img src="<?php echo esc_url( $img . $figure['file'] . '-700.jpg' ); ?>" width="1100" height="880" loading="lazy" decoding="async"
						alt="<?php echo esc_attr( $figure['alt'] ); ?>">
				</picture>
				<?php endif; ?>
				<figcaption><?php echo esc_html( $caption ); ?></figcaption>
			</figure>
			<?php endif; ?>
		</div>
	</div>
</section>
