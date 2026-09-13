<?php
/**
 * Three-up info cards. Used on religious, courses and contact-style sections.
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
<div class="grid g-3">
	<?php foreach ( $items as $i => $item ) : ?>
		<?php
		$delay = ( $i % 3 ) * 90;
		$title = isset( $item['title'] ) ? $item['title'] : '';
		$html  = isset( $item['html'] ) ? $item['html'] : '';
		$body  = isset( $item['body'] ) ? $item['body'] : '';
		?>
		<div class="card" data-reveal<?php echo $delay ? ' data-reveal-delay="' . esc_attr( (string) $delay ) . '"' : ''; ?>>
			<h3 class="display d-3"><?php echo esc_html( $title ); ?></h3>
			<?php if ( $html ) : ?>
				<div class="body-text" style="margin-top:12px"><?php echo cil_rich_text( $html ); ?></div>
			<?php elseif ( $body ) : ?>
				<p><?php echo cil_rich_text( $body ); ?></p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
