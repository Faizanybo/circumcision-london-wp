<?php
/**
 * Homepage callback form plus the “when we will tell you not to” copy.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$form_id      = isset( $args['id'] ) ? $args['id'] : 'home';
$form_subject = isset( $args['subject'] ) ? $args['subject'] : 'homepage callback';
?>
<section class="section">
	<div class="wrap">
		<div class="split reverse">
			<div data-reveal data-reveal-delay="120">
				<div class="card">
					<span class="caps eyebrow"><?php esc_html_e( 'Request a call back', 'circumcision-london' ); ?></span>
					<h2 class="display d-2" style="margin-bottom:20px"><?php esc_html_e( 'Ask before you book', 'circumcision-london' ); ?></h2>
					<?php
					get_template_part(
						'template-parts/callback-form',
						null,
						array(
							'id'      => $form_id,
							'subject' => $form_subject,
						)
					);
					?>
				</div>
			</div>

			<div data-reveal>
				<span class="caps eyebrow"><?php esc_html_e( 'Being straight with you', 'circumcision-london' ); ?></span>
				<h2 class="display d-1"><?php esc_html_e( 'When we will tell you not to', 'circumcision-london' ); ?></h2>
				<div class="body-text" style="margin-top:24px">
					<p><?php esc_html_e( 'A foreskin that does not pull back in a young boy is normal, and usually sorts itself out well into the teens. If that is the only reason you have booked, we will say so and send you home.', 'circumcision-london' ); ?></p>
					<p><?php echo wp_kses_post( sprintf( /* translators: 1: frenuloplasty URL, 2: preputioplasty URL */ __( 'We will not proceed with a baby who is unwell or jaundiced, or who has a condition such as hypospadias where the foreskin may be needed for later reconstruction. If there is a tight frenulum and nothing else, a <a href="%1$s">frenuloplasty</a> is the smaller and better operation. If you want to keep the foreskin, a <a href="%2$s">preputioplasty</a> may do the job instead.', 'circumcision-london' ), esc_url( home_url( '/procedures/frenuloplasty/' ) ), esc_url( home_url( '/procedures/preputioplasty/' ) ) ) ); ?></p>
					<p><?php esc_html_e( 'Those conversations cost us bookings. They are also the reason people send us their brothers.', 'circumcision-london' ); ?></p>
				</div>
				<div style="margin-top:28px">
					<?php get_template_part( 'template-parts/urgent-note' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>
