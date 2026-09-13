<?php
/**
 * Three-up testimonial grid.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$quotes = ( isset( $args['quotes'] ) && is_array( $args['quotes'] ) ) ? $args['quotes'] : cil_testimonials();
?>
<div class="grid g-3">
	<?php foreach ( $quotes as $i => $quote ) : ?>
		<?php
		get_template_part(
			'template-parts/quote',
			null,
			array(
				'name'    => isset( $quote['name'] ) ? $quote['name'] : '',
				'context' => isset( $quote['context'] ) ? $quote['context'] : '',
				'quote'   => isset( $quote['quote'] ) ? $quote['quote'] : '',
				'delay'   => ( $i % 3 ) * 90,
			)
		);
		?>
	<?php endforeach; ?>
</div>
