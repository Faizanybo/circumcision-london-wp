<?php
/**
 * Closing CTA band with WhatsApp card. Defaults match the prototype.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();
$title  = isset( $args['title'] ) && $args['title'] ? $args['title'] : __( 'Ask us anything before you decide', 'circumcision-london' );
$text   = isset( $args['text'] ) && $args['text'] ? $args['text'] : __( 'Most people call with a question rather than to book. That is what the phone is for, and nothing is booked until you say so.', 'circumcision-london' );
$eyebrow     = ( isset( $args['eyebrow'] ) && '' !== $args['eyebrow'] ) ? $args['eyebrow'] : __( 'Talk to us', 'circumcision-london' );
$cta_label   = ( isset( $args['cta_label'] ) && '' !== $args['cta_label'] ) ? $args['cta_label'] : __( 'Book a consultation', 'circumcision-london' );
$cta_url     = ( isset( $args['cta_url'] ) && '' !== $args['cta_url'] ) ? $args['cta_url'] : cil_book_url();
$phone_label = ( isset( $args['phone_label'] ) && '' !== $args['phone_label'] ) ? $args['phone_label'] : sprintf( /* translators: %s: phone number */ __( 'Call %s', 'circumcision-london' ), $clinic['phone']['display'] );
$phone_url   = ( isset( $args['phone_url'] ) && '' !== $args['phone_url'] ) ? $args['phone_url'] : $clinic['phone']['href'];
$card_title  = ( isset( $args['card_title'] ) && '' !== $args['card_title'] ) ? $args['card_title'] : __( 'Would you rather write than speak?', 'circumcision-london' );
$card_html   = ( isset( $args['card_html'] ) && '' !== $args['card_html'] ) ? $args['card_html'] : '';
?>
<section class="cta-band section-sm">
	<div class="wrap">
		<div class="split">
			<div data-reveal>
				<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
				<h2 class="display d-2"><?php echo esc_html( $title ); ?></h2>
				<p class="lede" style="margin-top:18px"><?php echo esc_html( $text ); ?></p>
				<div class="btn-row" style="margin-top:28px">
					<a class="btn" href="<?php echo esc_url( $cta_url ); ?>" data-track="book-cta"><?php echo esc_html( $cta_label ); ?></a>
					<a class="btn btn-ghost" href="<?php echo esc_url( $phone_url ); ?>" data-track="call-cta"><?php echo esc_html( $phone_label ); ?></a>
				</div>
			</div>
			<div class="card" data-reveal data-reveal-delay="120">
				<h3 class="display d-3"><?php echo esc_html( $card_title ); ?></h3>
				<?php if ( $card_html ) : ?>
					<?php echo cil_rich_text( $card_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allowlisted in cil_rich_text(). ?>
				<?php else : ?>
				<p style="margin-top:10px"><?php echo wp_kses_post( sprintf( /* translators: %s: WhatsApp URL */ __( 'A phone call asks you to be fluent and composed in the moment. <a href="%s" rel="noopener" target="_blank" data-track="whatsapp-cta" style="color:var(--blue-deep)">WhatsApp</a> does not. You can take your time, translate, and forward the answer to whoever else in the family needs to see it.', 'circumcision-london' ), esc_url( $clinic['whatsapp']['href'] ) ) ); ?></p>
				<p style="margin-top:12px"><?php esc_html_e( 'We reply during opening hours, and we are used to the questions people feel awkward asking out loud.', 'circumcision-london' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
