<?php
/**
 * Callback request form. UI and mailto fallback only; no production backend.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id      = isset( $args['id'] ) && $args['id'] ? $args['id'] : 'callback';
$subject = isset( $args['subject'] ) && $args['subject'] ? $args['subject'] : 'general enquiry';
$id      = sanitize_html_class( $id );
?>
<form class="form-grid" data-form="<?php echo esc_attr( $subject ); ?>" id="<?php echo esc_attr( $id ); ?>" novalidate>
	<p class="form-note" style="margin-bottom:4px">
		<?php esc_html_e( 'We call back within two working hours, Monday to Friday 09:00 to 17:00 and Saturday 10:00 to 15:00. Nothing is booked until you say so.', 'circumcision-london' ); ?>
	</p>
	<div class="field">
		<label for="<?php echo esc_attr( $id ); ?>-name"><?php esc_html_e( 'Your name', 'circumcision-london' ); ?></label>
		<input id="<?php echo esc_attr( $id ); ?>-name" name="name" type="text" autocomplete="name" required>
	</div>
	<div class="field">
		<label for="<?php echo esc_attr( $id ); ?>-phone"><?php esc_html_e( 'Phone number', 'circumcision-london' ); ?></label>
		<input id="<?php echo esc_attr( $id ); ?>-phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" required>
	</div>
	<div class="field full">
		<label for="<?php echo esc_attr( $id ); ?>-who"><?php esc_html_e( 'Who is the appointment for?', 'circumcision-london' ); ?></label>
		<select id="<?php echo esc_attr( $id ); ?>-who" name="who">
			<option><?php esc_html_e( 'A baby under one year', 'circumcision-london' ); ?></option>
			<option><?php esc_html_e( 'A boy or teenager', 'circumcision-london' ); ?></option>
			<option><?php esc_html_e( 'An adult', 'circumcision-london' ); ?></option>
			<option><?php esc_html_e( 'I am not sure yet, I would like advice', 'circumcision-london' ); ?></option>
		</select>
	</div>
	<div class="field full">
		<label for="<?php echo esc_attr( $id ); ?>-message"><?php esc_html_e( 'Anything you would like us to know', 'circumcision-london' ); ?> <span style="font-weight:400;color:var(--muted)"><?php esc_html_e( '(optional)', 'circumcision-london' ); ?></span></label>
		<textarea id="<?php echo esc_attr( $id ); ?>-message" name="message" rows="4"></textarea>
	</div>
	<label class="consent" for="<?php echo esc_attr( $id ); ?>-consent">
		<input id="<?php echo esc_attr( $id ); ?>-consent" name="consent" type="checkbox" required>
		<span><?php echo wp_kses_post( sprintf( /* translators: %s: privacy notice URL */ __( 'I am happy for the clinic to contact me about this enquiry. We do not add you to a mailing list. See our <a href="%s" style="color:var(--blue-deep)">privacy notice</a>.', 'circumcision-london' ), esc_url( home_url( '/privacy-notice/' ) ) ) ); ?></span>
	</label>
	<div class="form-status" role="status" aria-live="polite"></div>
	<div class="field full"><button class="btn" type="submit"><?php esc_html_e( 'Request a call back', 'circumcision-london' ); ?></button></div>
</form>
