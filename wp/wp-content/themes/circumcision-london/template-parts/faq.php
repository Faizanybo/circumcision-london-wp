<?php
/**
 * FAQ accordion. Pass $args['heading'] and optional $args['items'].
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = isset( $args['heading'] ) && $args['heading'] ? $args['heading'] : __( 'Questions people ask us', 'circumcision-london' );
$items   = ( isset( $args['items'] ) && is_array( $args['items'] ) ) ? $args['items'] : cil_home_faqs();
?>
<section class="section bg-card edge">
	<div class="wrap-narrow">
		<div class="section-head" data-reveal>
			<span class="caps eyebrow"><?php esc_html_e( 'Questions', 'circumcision-london' ); ?></span>
			<h2 class="display d-2"><?php echo esc_html( $heading ); ?></h2>
		</div>
		<div class="faq" data-reveal>
			<?php foreach ( $items as $item ) : ?>
				<details>
					<summary><?php echo esc_html( $item['q'] ); ?></summary>
					<div class="answer body-text"><?php echo cil_rich_text( $item['a'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allowlisted in cil_rich_text(). ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
