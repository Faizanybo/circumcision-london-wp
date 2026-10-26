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
$sample  = ! empty( $args['sample'] );
// Stars only where the source gave a rating; feedback without one shows none.
$stars   = ! isset( $args['stars'] ) || ! empty( $args['stars'] );
$classes = 'quote' . ( $sample ? ' quote--sample' : '' );
?>
<figure class="<?php echo esc_attr( $classes ); ?>" data-reveal data-reveal-delay="<?php echo esc_attr( (string) $delay ); ?>">
	<?php if ( $sample ) : ?>
		<span class="quote-sample-badge"><?php esc_html_e( 'Sample — replace in editor', 'circumcision-london' ); ?></span>
	<?php elseif ( $stars ) : ?>
		<span class="stars" aria-label="<?php esc_attr_e( 'Five out of five', 'circumcision-london' ); ?>">★★★★★</span>
	<?php endif; ?>
	<blockquote>&ldquo;<?php echo nl2br( esc_html( $quote ), false ); ?>&rdquo;</blockquote>
	<figcaption class="meta"><strong><?php echo esc_html( $name ); ?></strong><?php if ( $context ) : ?><br><?php echo esc_html( $context ); ?><?php endif; ?></figcaption>
</figure>
