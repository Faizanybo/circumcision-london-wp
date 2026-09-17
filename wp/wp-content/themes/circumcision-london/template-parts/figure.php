<?php
/**
 * Responsive figure with optional webp srcset, ratio and caption.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$name     = isset( $args['name'] ) ? $args['name'] : '';
$src      = isset( $args['src'] ) ? $args['src'] : '';
$alt      = isset( $args['alt'] ) ? $args['alt'] : '';
$caption  = isset( $args['caption'] ) ? $args['caption'] : '';
$ratio    = isset( $args['ratio'] ) ? $args['ratio'] : '4-3';
$webp     = isset( $args['webp'] ) ? $args['webp'] : '';
$width    = isset( $args['width'] ) ? (int) $args['width'] : 1920;
$height   = isset( $args['height'] ) ? (int) $args['height'] : 1080;
$image_id = isset( $args['image_id'] ) ? cil_attachment_id( $args['image_id'] ) : 0;

if ( $image_id && cil_attachment_is_image( $image_id ) ) {
	$class = 'figure ratio-' . ( in_array( $ratio, array( '4-3', '5-4', '3-4' ), true ) ? $ratio : '4-3' );
	$alt   = $alt ? $alt : cil_attachment_alt( $image_id );
	echo '<figure class="' . esc_attr( $class ) . '" data-reveal>';
	echo cil_attachment_image_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image().
		$image_id,
		'cil-figure',
		array(
			'loading'  => 'lazy',
			'decoding' => 'async',
			'alt'      => $alt,
			'sizes'    => '(max-width: 900px) 92vw, 46vw',
		)
	);
	if ( $caption ) {
		echo '<figcaption>' . esc_html( $caption ) . '</figcaption>';
	}
	echo '</figure>';
	return;
}

if ( $name && function_exists( 'cil_figure_asset' ) ) {
	$asset = cil_figure_asset( $name );
	if ( $asset ) {
		if ( ! $src ) {
			$src    = $asset['src'];
			$webp   = $webp ? $webp : $asset['webp'];
			$width  = $asset['width'];
			$height = $asset['height'];
			$ratio  = $asset['ratio'];
		}
		$alt     = $alt ? $alt : $asset['alt'];
		$caption = $caption ? $caption : $asset['caption'];
	}
}

$class = 'figure ratio-' . ( in_array( $ratio, array( '4-3', '5-4', '3-4' ), true ) ? $ratio : '4-3' );

if ( ! $src ) {
	return;
}
?>
<figure class="<?php echo esc_attr( $class ); ?>" data-reveal>
	<picture>
		<?php if ( $webp ) : ?>
			<source type="image/webp" srcset="<?php echo esc_attr( $webp ); ?>" sizes="(max-width: 900px) 92vw, 46vw">
		<?php endif; ?>
		<img src="<?php echo esc_url( $src ); ?>" width="<?php echo esc_attr( (string) $width ); ?>" height="<?php echo esc_attr( (string) $height ); ?>" loading="lazy" decoding="async" alt="<?php echo esc_attr( $alt ); ?>">
	</picture>
	<?php if ( $caption ) : ?>
		<figcaption><?php echo esc_html( $caption ); ?></figcaption>
	<?php endif; ?>
</figure>
