<?php
/**
 * “At a glance” column: eyebrow, spec list, optional muted note.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$rows    = ( isset( $args['rows'] ) && is_array( $args['rows'] ) ) ? $args['rows'] : array();
$note    = isset( $args['note'] ) ? $args['note'] : '';
?>
<div data-reveal data-reveal-delay="120">
	<?php if ( $eyebrow ) : ?>
		<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
	<?php endif; ?>
	<?php
	get_template_part(
		'template-parts/spec-list',
		null,
		array(
			'rows' => $rows,
		)
	);
	?>
	<?php if ( $note ) : ?>
		<p class="muted" style="margin-top:16px;font-size:15px"><?php echo cil_rich_text( $note ); ?></p>
	<?php endif; ?>
</div>
