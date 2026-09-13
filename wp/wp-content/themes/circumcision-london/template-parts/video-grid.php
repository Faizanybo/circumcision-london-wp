<?php
/**
 * Video testimonial grid. Renders nothing when $args['items'] is empty,
 * matching the prototype VIDEO_TESTIMONIALS behaviour.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = ( isset( $args['items'] ) && is_array( $args['items'] ) ) ? $args['items'] : array();
if ( ! $items ) {
	return;
}
?>
<section class="section bg-card edge">
	<div class="wrap">
		<?php
		get_template_part(
			'template-parts/section-head',
			null,
			array(
				'eyebrow' => isset( $args['eyebrow'] ) ? $args['eyebrow'] : __( 'Video', 'circumcision-london' ),
				'heading' => isset( $args['heading'] ) ? $args['heading'] : __( 'Parents and patients, in their own words', 'circumcision-london' ),
				'lede'    => isset( $args['lede'] ) ? $args['lede'] : __( 'Filmed at the clinic immediately after the procedure. Nothing is scripted.', 'circumcision-london' ),
				'display' => 'd-1',
			)
		);
		?>
		<div class="grid g-3">
			<?php foreach ( $items as $i => $item ) : ?>
				<figure class="quote" style="padding:0;overflow:hidden" data-reveal data-reveal-delay="<?php echo esc_attr( (string) ( ( $i % 3 ) * 90 ) ); ?>">
					<video controls preload="none" playsinline poster="<?php echo esc_url( isset( $item['poster'] ) ? $item['poster'] : '' ); ?>" width="1280" height="720" style="width:100%;height:auto;display:block;border-radius:6px 6px 0 0">
						<?php if ( ! empty( $item['webm'] ) ) : ?>
							<source src="<?php echo esc_url( $item['webm'] ); ?>" type="video/webm">
						<?php endif; ?>
						<?php if ( ! empty( $item['mp4'] ) ) : ?>
							<source src="<?php echo esc_url( $item['mp4'] ); ?>" type="video/mp4">
						<?php endif; ?>
					</video>
					<figcaption class="meta" style="padding:16px 22px 20px"><?php echo esc_html( isset( $item['title'] ) ? $item['title'] : '' ); ?></figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>
