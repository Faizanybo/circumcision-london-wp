<?php
/**
 * Card wrapping the callback form. Used on age, condition and extra pages.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = isset( $args['eyebrow'] ) && $args['eyebrow'] ? $args['eyebrow'] : __( 'Request a call back', 'circumcision-london' );
$title   = isset( $args['title'] ) && $args['title'] ? $args['title'] : __( 'Ask us first', 'circumcision-london' );
$form_id = isset( $args['id'] ) && $args['id'] ? $args['id'] : 'callback';
$subject = isset( $args['subject'] ) && $args['subject'] ? $args['subject'] : 'general enquiry';
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
<?php if ( ! empty( $args['urgent'] ) ) : ?>
	<div style="margin-top:22px">
		<?php get_template_part( 'template-parts/urgent-note' ); ?>
	</div>
<?php endif; ?>
