<?php
/**
 * Homepage callback form plus optional supporting copy.
 *
 * Empty heading/html/card title/urgent fields stay empty (no theme fallbacks),
 * so editors can keep the form alone without the old postpone copy.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$form_id      = isset( $args['id'] ) ? $args['id'] : 'home';
$form_subject = isset( $args['subject'] ) && $args['subject'] ? $args['subject'] : 'homepage callback';
$card_eyebrow = ( isset( $args['card_eyebrow'] ) && '' !== $args['card_eyebrow'] ) ? $args['card_eyebrow'] : __( 'Request a call back', 'circumcision-london' );
$card_title   = isset( $args['card_title'] ) ? (string) $args['card_title'] : '';
$card_html    = ( isset( $args['card_html'] ) && '' !== $args['card_html'] ) ? $args['card_html'] : '';
$eyebrow      = array_key_exists( 'eyebrow', $args ) ? (string) $args['eyebrow'] : '';
$heading      = array_key_exists( 'heading', $args ) ? (string) $args['heading'] : '';
$html         = array_key_exists( 'html', $args ) ? (string) $args['html'] : '';
$urgent_title = isset( $args['urgent_title'] ) ? (string) $args['urgent_title'] : '';
$urgent_body  = isset( $args['urgent_body'] ) ? (string) $args['urgent_body'] : '';

$show_copy = ( '' !== $eyebrow || '' !== $heading || '' !== $html || '' !== $urgent_title || '' !== $urgent_body );
// card_html key present (even empty) means "no default form note" — intro lives in the card box when set.
$hide_note = array_key_exists( 'card_html', $args );
?>
<section class="section">
	<div class="wrap">
		<div class="<?php echo $show_copy ? 'split reverse' : 'cil-callback-form-only'; ?>">
			<div data-reveal data-reveal-delay="120">
				<div class="card">
					<span class="caps eyebrow"><?php echo esc_html( $card_eyebrow ); ?></span>
					<?php if ( '' !== $card_title ) : ?>
					<h2 class="display d-2" style="margin-bottom:20px"><?php echo esc_html( $card_title ); ?></h2>
					<?php endif; ?>
					<?php if ( $card_html ) : ?>
					<div class="body-text callback-card-intro" style="margin-bottom:20px">
						<?php echo cil_rich_text( $card_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allowlisted in cil_rich_text(). ?>
					</div>
					<?php endif; ?>
					<?php
					get_template_part(
						'template-parts/callback-form',
						null,
						array(
							'id'        => $form_id,
							'subject'   => $form_subject,
							'hide_note' => $hide_note,
						)
					);
					?>
				</div>
			</div>

			<?php if ( $show_copy ) : ?>
			<div data-reveal>
				<?php if ( $eyebrow ) : ?>
				<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<?php endif; ?>
				<?php if ( '' !== $heading ) : ?>
				<h2 class="display d-1"><?php echo esc_html( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( '' !== $html ) : ?>
				<div class="body-text" style="margin-top:24px">
					<?php echo cil_rich_text( $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allowlisted in cil_rich_text(). ?>
				</div>
				<?php endif; ?>
				<?php if ( $urgent_title || $urgent_body ) : ?>
				<div style="margin-top:28px">
					<?php
					get_template_part(
						'template-parts/callout',
						null,
						array(
							'title'  => $urgent_title,
							'body'   => $urgent_body,
							'urgent' => true,
							'level'  => 3,
						)
					);
					?>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
