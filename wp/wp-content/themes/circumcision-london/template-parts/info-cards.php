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
$columns = isset( $args['columns'] ) ? $args['columns'] : 'g-3';
$columns = in_array( $columns, array( 'g-2', 'g-3', 'g-4' ), true ) ? $columns : 'g-3';
$level   = isset( $args['heading_level'] ) ? (int) $args['heading_level'] : 3;
$level   = in_array( $level, array( 2, 3 ), true ) ? $level : 3;
$htag    = 'h' . $level;
?>
<div class="grid <?php echo esc_attr( $columns ); ?>">
	<?php foreach ( $items as $i => $item ) : ?>
		<?php
		$delay     = ( $i % 4 ) * 80;
		$eyebrow   = isset( $item['eyebrow'] ) ? $item['eyebrow'] : '';
		$title     = isset( $item['title'] ) ? $item['title'] : '';
		$html      = isset( $item['html'] ) ? $item['html'] : '';
		$body      = isset( $item['body'] ) ? $item['body'] : '';
		$cta_label = isset( $item['ctaLabel'] ) ? $item['ctaLabel'] : ( isset( $item['cta'] ) ? $item['cta'] : '' );
		$cta_url   = isset( $item['ctaUrl'] ) ? $item['ctaUrl'] : '';
		$cta_class = ( isset( $item['ctaClass'] ) && $item['ctaClass'] ) ? $item['ctaClass'] : 'btn';
		$cta2_label = isset( $item['cta2Label'] ) ? $item['cta2Label'] : '';
		$cta2_url   = isset( $item['cta2Url'] ) ? $item['cta2Url'] : '';
		$cta2_class = ( isset( $item['cta2Class'] ) && $item['cta2Class'] ) ? $item['cta2Class'] : 'btn btn-ghost';
		?>
		<div class="card" data-reveal<?php echo $delay ? ' data-reveal-delay="' . esc_attr( (string) $delay ) . '"' : ''; ?>>
			<?php if ( $eyebrow ) : ?>
				<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
			<?php endif; ?>
			<?php if ( $title ) : ?>
				<<?php echo tag_escape( $htag ); ?> class="display d-3"><?php echo esc_html( $title ); ?></<?php echo tag_escape( $htag ); ?>>
			<?php endif; ?>
			<?php if ( $html ) : ?>
				<div class="body-text" style="margin-top:12px"><?php echo cil_rich_text( $html ); ?></div>
			<?php elseif ( $body ) : ?>
				<p><?php echo cil_rich_text( $body ); ?></p>
			<?php endif; ?>
			<?php if ( $cta_label || $cta2_label ) : ?>
				<div class="btn-row" style="margin-top:20px">
					<?php if ( $cta_label && $cta_url ) : ?>
						<a class="<?php echo esc_attr( $cta_class ); ?>" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
					<?php endif; ?>
					<?php if ( $cta2_label && $cta2_url ) : ?>
						<a class="<?php echo esc_attr( $cta2_class ); ?>" href="<?php echo esc_url( $cta2_url ); ?>"><?php echo esc_html( $cta2_label ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
