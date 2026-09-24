<?php
/**
 * Card wrapping the callback form. Used on age, condition and extra pages.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow      = isset( $args['eyebrow'] ) && $args['eyebrow'] ? $args['eyebrow'] : __( 'Request a call back', 'circumcision-london' );
$title        = isset( $args['title'] ) && $args['title'] ? $args['title'] : __( 'Ask us first', 'circumcision-london' );
$form_id      = isset( $args['id'] ) && $args['id'] ? $args['id'] : 'callback';
$subject      = isset( $args['subject'] ) && $args['subject'] ? $args['subject'] : 'general enquiry';
$urgent_title = isset( $args['urgent_title'] ) ? (string) $args['urgent_title'] : '';
$urgent_body  = isset( $args['urgent_body'] ) ? (string) $args['urgent_body'] : '';
$has_urgent   = ( '' !== $urgent_title || '' !== $urgent_body || ! empty( $args['urgent'] ) );

/*
 * Inside cil/split (CSS grid), card + urgent must be ONE column. Emitting them as
 * siblings made the urgent note a third grid cell and left a blank column.
 */
if ( $has_urgent ) {
	echo '<div class="split-aside">';
}
?>
<div class="card">
	<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
	<h2 class="display d-2" style="margin-bottom:20px"><?php echo esc_html( $title ); ?></h2>
	<?php
	get_template_part(
		'template-parts/callback-form',
		null,
		array(
			'id'      => $form_id,
			'subject' => $subject,
		)
	);
	?>
</div>
<?php if ( $urgent_title || $urgent_body ) : ?>
	<div style="margin-top:22px">
		<div class="callout urgent" data-reveal>
			<?php if ( $urgent_title ) : ?>
				<h2 class="display d-3"><?php echo esc_html( $urgent_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $urgent_body ) : ?>
				<?php echo cil_rich_text( $urgent_body ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allowlisted in cil_rich_text(). ?>
			<?php endif; ?>
		</div>
	</div>
<?php elseif ( ! empty( $args['urgent'] ) ) : ?>
	<div style="margin-top:22px">
		<?php get_template_part( 'template-parts/urgent-note' ); ?>
	</div>
<?php endif; ?>
<?php
if ( $has_urgent ) {
	echo '</div>';
}
