<?php
/**
 * Clinic figure slideshow: prev/next + indicators, same footprint as cil/figure.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items   = isset( $args['items'] ) && is_array( $args['items'] ) ? $args['items'] : array();
$caption = isset( $args['caption'] ) ? (string) $args['caption'] : '';
$ratio   = isset( $args['ratio'] ) ? $args['ratio'] : '4-3';
$label   = isset( $args['label'] ) ? (string) $args['label'] : '';
$ratio   = in_array( $ratio, array( '4-3', '5-4', '3-4' ), true ) ? $ratio : '4-3';

$slides = array();
foreach ( $items as $raw ) {
	if ( ! is_array( $raw ) ) {
		continue;
	}
	$name     = isset( $raw['name'] ) ? sanitize_key( (string) $raw['name'] ) : '';
	$src      = isset( $raw['src'] ) ? (string) $raw['src'] : '';
	$webp     = isset( $raw['webp'] ) ? (string) $raw['webp'] : '';
	$alt      = isset( $raw['alt'] ) ? (string) $raw['alt'] : '';
	$width    = isset( $raw['width'] ) ? (int) $raw['width'] : 1200;
	$height   = isset( $raw['height'] ) ? (int) $raw['height'] : 900;
	$image_id = isset( $raw['imageId'] ) ? cil_attachment_id( $raw['imageId'] ) : 0;
	if ( ! $image_id && isset( $raw['image_id'] ) ) {
		$image_id = cil_attachment_id( $raw['image_id'] );
	}

	if ( $image_id && cil_attachment_is_image( $image_id ) ) {
		$slides[] = array(
			'type'     => 'attachment',
			'image_id' => $image_id,
			'alt'      => $alt ? $alt : cil_attachment_alt( $image_id ),
		);
		continue;
	}

	if ( $name && function_exists( 'cil_figure_asset' ) ) {
		$asset = cil_figure_asset( $name );
		if ( $asset ) {
			if ( ! $src ) {
				$src    = $asset['src'];
				$webp   = $webp ? $webp : $asset['webp'];
				$width  = $asset['width'];
				$height = $asset['height'];
			}
			$alt = $alt ? $alt : $asset['alt'];
		}
	}

	if ( ! $src ) {
		continue;
	}

	$slides[] = array(
		'type'   => 'url',
		'src'    => $src,
		'webp'   => $webp,
		'alt'    => $alt,
		'width'  => $width,
		'height' => $height,
	);
}

if ( ! $slides ) {
	return;
}

$count = count( $slides );
if ( '' === $label ) {
	$label = __( 'Clinic photographs', 'circumcision-london' );
}

$class = 'figure ratio-' . $ratio . ' cil-figure-slideshow';
if ( $count < 2 ) {
	$class .= ' is-single';
}
?>
<figure
	class="<?php echo esc_attr( $class ); ?>"
	data-cil-slideshow
	data-reveal
	role="region"
	aria-roledescription="<?php echo esc_attr__( 'carousel', 'circumcision-london' ); ?>"
	aria-label="<?php echo esc_attr( $label ); ?>"
>
	<div class="cil-figure-slideshow__viewport">
		<ul class="cil-figure-slideshow__track" aria-live="off">
			<?php foreach ( $slides as $i => $slide ) : ?>
				<li
					class="cil-figure-slideshow__slide<?php echo 0 === $i ? ' is-active' : ''; ?>"
					data-slide-index="<?php echo esc_attr( (string) $i ); ?>"
					<?php echo 0 === $i ? '' : ' hidden'; ?>
					aria-hidden="<?php echo 0 === $i ? 'false' : 'true'; ?>"
				>
					<?php if ( 'attachment' === $slide['type'] ) : ?>
						<?php
						echo cil_attachment_image_html( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image().
							$slide['image_id'],
							'cil-figure',
							array(
								'loading'  => 0 === $i ? 'eager' : 'lazy',
								'decoding' => 'async',
								'alt'      => $slide['alt'],
								'sizes'    => '(max-width: 900px) 92vw, 46vw',
							)
						);
						?>
					<?php else : ?>
						<picture>
							<?php if ( ! empty( $slide['webp'] ) ) : ?>
								<source type="image/webp" srcset="<?php echo esc_attr( $slide['webp'] ); ?>" sizes="(max-width: 900px) 92vw, 46vw">
							<?php endif; ?>
							<img
								src="<?php echo esc_url( $slide['src'] ); ?>"
								width="<?php echo esc_attr( (string) $slide['width'] ); ?>"
								height="<?php echo esc_attr( (string) $slide['height'] ); ?>"
								loading="<?php echo 0 === $i ? 'eager' : 'lazy'; ?>"
								decoding="async"
								alt="<?php echo esc_attr( $slide['alt'] ); ?>"
							>
						</picture>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<?php if ( $count > 1 ) : ?>
		<div class="cil-figure-slideshow__controls">
			<button type="button" class="cil-figure-slideshow__btn cil-figure-slideshow__prev" aria-label="<?php echo esc_attr__( 'Previous photo', 'circumcision-london' ); ?>">
				<span aria-hidden="true">‹</span>
			</button>
			<div class="cil-figure-slideshow__dots" role="tablist" aria-label="<?php echo esc_attr__( 'Choose a photo', 'circumcision-london' ); ?>">
				<?php foreach ( $slides as $i => $slide ) : ?>
					<button
						type="button"
						class="cil-figure-slideshow__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
						role="tab"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'Show photo %d', 'circumcision-london' ), $i + 1 ) ); ?>"
						data-slide-to="<?php echo esc_attr( (string) $i ); ?>"
					></button>
				<?php endforeach; ?>
			</div>
			<button type="button" class="cil-figure-slideshow__btn cil-figure-slideshow__next" aria-label="<?php echo esc_attr__( 'Next photo', 'circumcision-london' ); ?>">
				<span aria-hidden="true">›</span>
			</button>
		</div>
		<p class="sr-only" data-cil-slideshow-status aria-live="polite"></p>
	<?php endif; ?>

	<?php if ( $caption ) : ?>
		<figcaption><?php echo esc_html( $caption ); ?></figcaption>
	<?php endif; ?>
</figure>
