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
?>
<div class="<?php echo esc_attr( $class ); ?>" data-reveal>
	<?php if ( $title ) : ?>
		<<?php echo tag_escape( $tag ); ?> class="display <?php echo esc_attr( $disp ); ?>"><?php echo esc_html( $title ); ?></<?php echo tag_escape( $tag ); ?>>
	<?php endif; ?>
	<?php if ( $body ) : ?>
		<div class="body-text" style="margin-top:12px"><?php echo cil_rich_text( $body ); ?></div>
	<?php endif; ?>
</div>
