<?php
/**
 * Callback request form. UI and mailto fallback only; no production backend.
 *
 * Optional $args['copy'] overrides the visible wording (keys: note, name, phone,
 * dob, message, optional, consent, submit, invalid, sent). Empty keys keep the defaults.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id      = isset( $args['id'] ) && $args['id'] ? $args['id'] : 'callback';
$subject = isset( $args['subject'] ) && $args['subject'] ? $args['subject'] : 'general enquiry';
$id      = sanitize_html_class( $id );
$hide_note = ! empty( $args['hide_note'] );
$copy      = ( isset( $args['copy'] ) && is_array( $args['copy'] ) ) ? array_filter( array_map( 'strval', $args['copy'] ), 'strlen' ) : array();
?>
<form class="form-grid" data-form="<?php echo esc_attr( $subject ); ?>" id="<?php echo esc_attr( $id ); ?>"<?php echo isset( $copy['invalid'] ) ? ' data-msg-invalid="' . esc_attr( $copy['invalid'] ) . '"' : ''; ?><?php echo isset( $copy['sent'] ) ? ' data-msg-sent="' . esc_attr( $copy['sent'] ) . '"' : ''; ?> novalidate>
	<?php if ( ! $hide_note ) : ?>
	<div class="form-note" style="margin-bottom:4px">
<?php if ( isset( $copy['note'] ) ) : ?>
		<?php echo cil_rich_text( $copy['note'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allowlisted in cil_rich_text(). ?>
<?php else : ?>
		<p><?php esc_html_e( "Have a question or need some advice? We're here to help and would be happy to give you a call.", 'circumcision-london' ); ?></p>
		<p><?php echo wp_kses( __( 'Our opening hours are <em>Monday to Saturday, 9:00am–5:00pm</em>.', 'circumcision-london' ), array( 'em' => array() ) ); ?></p>
		<p><?php echo wp_kses( __( "We aim to call you back <em>within 1 hour during opening hours</em>, and always on the same working day. If you contact us outside these hours, we'll get back to you the next working day.", 'circumcision-london' ), array( 'em' => array() ) ); ?></p>
		<p><?php echo wp_kses( __( "<em>There's no pressure or obligation to book</em> — we're simply here to answer your questions and help you make the right decision.", 'circumcision-london' ), array( 'em' => array() ) ); ?></p>
<?php endif; ?>
	</div>
	<?php endif; ?>
	<div class="field">
		<label for="<?php echo esc_attr( $id ); ?>-name"><?php echo esc_html( isset( $copy['name'] ) ? $copy['name'] : __( 'Your name', 'circumcision-london' ) ); ?></label>
		<input id="<?php echo esc_attr( $id ); ?>-name" name="name" type="text" autocomplete="name" required>
	</div>
	<div class="field">
		<label for="<?php echo esc_attr( $id ); ?>-phone"><?php echo esc_html( isset( $copy['phone'] ) ? $copy['phone'] : __( 'Phone number', 'circumcision-london' ) ); ?></label>
		<input id="<?php echo esc_attr( $id ); ?>-phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" required>
	</div>
	<div class="field full">
		<label for="<?php echo esc_attr( $id ); ?>-dob"><?php echo esc_html( isset( $copy['dob'] ) ? $copy['dob'] : __( 'Patient DOB', 'circumcision-london' ) ); ?></label>
		<input id="<?php echo esc_attr( $id ); ?>-dob" name="dob" type="date" max="<?php echo esc_attr( wp_date( 'Y-m-d' ) ); ?>" autocomplete="off" required>
	</div>
	<div class="field full">
		<label for="<?php echo esc_attr( $id ); ?>-message"><?php echo esc_html( isset( $copy['message'] ) ? $copy['message'] : __( 'Anything you would like us to know', 'circumcision-london' ) ); ?> <span style="font-weight:400;color:var(--muted)"><?php echo esc_html( isset( $copy['optional'] ) ? $copy['optional'] : __( '(optional)', 'circumcision-london' ) ); ?></span></label>
		<textarea id="<?php echo esc_attr( $id ); ?>-message" name="message" rows="4"></textarea>
	</div>
	<label class="consent" for="<?php echo esc_attr( $id ); ?>-consent">
		<input id="<?php echo esc_attr( $id ); ?>-consent" name="consent" type="checkbox" required>
<?php if ( isset( $copy['consent'] ) ) : ?>
		<span><?php echo wp_kses_post( $copy['consent'] ); ?></span>
<?php else : ?>
		<span><?php echo wp_kses_post( sprintf( /* translators: %s: privacy notice URL */ __( 'I am happy for the clinic to contact me about this enquiry. We do not add you to a mailing list. See our <a href="%s" style="color:var(--blue-deep)">privacy notice</a>.', 'circumcision-london' ), esc_url( home_url( '/privacy-notice/' ) ) ) ); ?></span>
<?php endif; ?>
	</label>
	<div class="form-status" role="status" aria-live="polite"></div>
	<div class="field full"><button class="btn" type="submit"><?php echo esc_html( isset( $copy['submit'] ) ? $copy['submit'] : __( 'Request a call back', 'circumcision-london' ) ); ?></button></div>
</form>
