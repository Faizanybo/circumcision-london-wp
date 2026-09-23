<?php
/**
 * Generic callout. Pass $args['urgent'] for the A&amp;E wash.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$urgent = ! empty( $args['urgent'] );
$title  = isset( $args['title'] ) ? $args['title'] : '';
$body   = isset( $args['body'] ) ? $args['body'] : '';
$level  = isset( $args['level'] ) ? (int) $args['level'] : 2;
$level  = in_array( $level, array( 2, 3 ), true ) ? $level : 2;
$tag    = 'h' . $level;
$class  = 'callout' . ( $urgent ? ' urgent' : '' );
$disp   = ( 3 === $level ) ? 'd-3' : 'd-2';
$body_html = function_exists( 'cil_balance_html_fragment' )
	? cil_balance_html_fragment( cil_rich_text( $body ) )
	: cil_rich_text( $body );

/*
 * A split column that only contains a nested urgent note was stored as cil/callout.
 * The trailing </div> was stripped during migration, so the callback card rendered
 * inside this block and the two-column layout collapsed. Keep Gutenberg attributes
 * as they are; only restore the original column markup on the frontend.
 */
$is_split_column = (
	false !== strpos( $body, 'btn-row' )
	|| ( false !== strpos( $body, 'eyebrow' ) && false !== strpos( $body, 'callout' ) )
);

if ( $is_split_column ) {
	if ( $title && false === strpos( wp_strip_all_tags( $body_html ), $title ) ) {
		$heading = sprintf(
			'<%1$s class="display %2$s">%3$s</%1$s>',
			tag_escape( $tag ),
			esc_attr( $disp ),
			esc_html( $title )
		);
		if ( preg_match( '/(<span[^>]*class="[^"]*eyebrow[^"]*"[^>]*>.*?<\/span>)/is', $body_html ) ) {
			$body_html = preg_replace(
				'/(<span[^>]*class="[^"]*eyebrow[^"]*"[^>]*>.*?<\/span>)/is',
				'$1' . $heading,
				$body_html,
				1
			);
		} else {
			$body_html = $heading . $body_html;
		}
	}
	echo $body_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- balanced through cil_rich_text.
	return;
}
?>
<div class="<?php echo esc_attr( $class ); ?>" data-reveal>
	<?php if ( $title ) : ?>
		<<?php echo tag_escape( $tag ); ?> class="display <?php echo esc_attr( $disp ); ?>"><?php echo esc_html( $title ); ?></<?php echo tag_escape( $tag ); ?>>
	<?php endif; ?>
	<?php if ( $body_html ) : ?>
		<div class="body-text" style="margin-top:12px"><?php echo $body_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- cil_rich_text. ?></div>
	<?php endif; ?>
</div>
