<?php
/**
 * Embedded Google Map with a short address caption (cil/map-embed).
 *
 * The iframe is lazy-loaded, so Google Maps only loads when the map nears the
 * viewport. No API key is used (classic output=embed URL).
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$query    = isset( $args['query'] ) ? trim( (string) $args['query'] ) : '';
$title    = isset( $args['title'] ) && '' !== trim( (string) $args['title'] ) ? (string) $args['title'] : __( 'Map showing the clinic location', 'circumcision-london' );
$address  = isset( $args['address'] ) ? (string) $args['address'] : '';
$link_url = isset( $args['link_url'] ) ? (string) $args['link_url'] : '';
if ( '' === $query ) {
	return;
}
$src = add_query_arg(
	array(
		'q'      => rawurlencode( $query ),
		'z'      => 16,
		'output' => 'embed',
	),
	'https://www.google.com/maps'
);
?>
<figure class="cil-map" data-reveal>
	<div class="cil-map__frame">
		<iframe src="<?php echo esc_url( $src ); ?>" title="<?php echo esc_attr( $title ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
	</div>
	<?php if ( $address || $link_url ) : ?>
		<figcaption class="cil-map__caption">
			<?php if ( $address ) : ?>
				<span class="cil-map__address"><?php echo esc_html( $address ); ?></span>
			<?php endif; ?>
			<?php if ( $link_url ) : ?>
				<a class="link-arrow" href="<?php echo esc_url( $link_url ); ?>" target="_blank" rel="noopener noreferrer" data-track="map-open"><?php esc_html_e( 'Open in Google Maps', 'circumcision-london' ); ?></a>
			<?php endif; ?>
		</figcaption>
	<?php endif; ?>
</figure>
