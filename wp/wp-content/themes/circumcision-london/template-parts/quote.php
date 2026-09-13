<?php
/**
 * Single testimonial card.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$name    = isset( $args['name'] ) ? $args['name'] : '';
$context = isset( $args['context'] ) ? $args['context'] : '';
$quote   = isset( $args['quote'] ) ? $args['quote'] : '';
$delay   = isset( $args['delay'] ) ? (int) $args['delay'] : 0;
?>
<figure class="quote" data-reveal data-reveal-delay="<?php echo esc_attr( (string) $delay ); ?>">
	<span class="stars" aria-label="<?php esc_attr_e( 'Five out of five', 'circumcision-london' ); ?>">★★★★★</span>
	<blockquote>&ldquo;<?php echo esc_html( $quote ); ?>&rdquo;</blockquote>
	<figcaption class="meta"><strong style="color:var(--ink)"><?php echo esc_html( $name ); ?></strong><?php if ( $context ) : ?><br><?php echo esc_html( $context ); ?><?php endif; ?></figcaption>
</figure>
