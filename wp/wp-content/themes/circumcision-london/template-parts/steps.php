<?php
/**
 * Numbered process steps.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$items = ( isset( $args['items'] ) && is_array( $args['items'] ) ) ? $args['items'] : cil_home_steps();
?>
<ol class="steps">
	<?php foreach ( $items as $step ) : ?>
		<li>
			<h3><?php echo esc_html( $step['h'] ); ?></h3>
			<p><?php echo esc_html( $step['p'] ); ?></p>
		</li>
	<?php endforeach; ?>
</ol>
