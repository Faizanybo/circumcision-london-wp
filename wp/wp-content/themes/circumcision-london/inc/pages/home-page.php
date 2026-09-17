<?php
/**
 * Homepage Gutenberg block markup matching the current PHP homepage.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Current homepage copy as Gutenberg blocks.
 *
 * @return string
 */
function cil_homepage_blocks() {
	$courses = esc_url( home_url( '/courses/' ) );
	$fren    = esc_url( home_url( '/procedures/frenuloplasty/' ) );
	$prep    = esc_url( home_url( '/procedures/preputioplasty/' ) );

	$intro_html  = '<p>Our clinic is in North-West London, about fifteen minutes from Wembley Stadium, outside the congestion charge, with free street parking all around it. We circumcise babies, infants, children, teenagers and adult men, day in and day out. It is what this clinic does.</p>';
	$intro_html .= '<p>Our two practitioners have more than forty years of combined experience between them, in the UK and abroad, and have carried out thousands of circumcisions across every age group. They also <a href="' . $courses . '">train doctors in the UK and internationally</a> in how to circumcise safely.</p>';
	$intro_html .= '<p>Whether you are here for religious, medical, cultural or personal reasons, the standard does not change: local anaesthetic every time, tested and shown to be working before we begin, and aftercare we explain to you and then give you in writing.</p>';

	$callback_html  = '<p>A foreskin that does not pull back in a young boy is normal, and usually sorts itself out well into the teens. If that is the only reason you have booked, we will say so and send you home.</p>';
	$callback_html .= '<p>We will not proceed with a baby who is unwell or jaundiced, or who has a condition such as hypospadias where the foreskin may be needed for later reconstruction. If there is a tight frenulum and nothing else, a <a href="' . $fren . '">frenuloplasty</a> is the smaller and better operation. If you want to keep the foreskin, a <a href="' . $prep . '">preputioplasty</a> may do the job instead.</p>';
	$callback_html .= '<p>Those conversations cost us bookings. They are also the reason people send us their brothers.</p>';

	$blocks   = array();
	$blocks[] = cil_dyn_block(
		'cil/hero',
		array(
			'eyebrow' => 'CQC registered · Edgware, North-West London',
			'title'   => 'A dedicated circumcision clinic in North-West London',
			'sub'     => 'Qualified practitioners, local anaesthetic every time, and we show you that no pain is felt before we begin.',
		)
	);
	$blocks[] = cil_dyn_block(
		'cil/groups',
		array(
			'level' => 2,
		)
	);
	$blocks[] = cil_dyn_block( 'cil/trust-strip', array() );
	$blocks[] = cil_dyn_block(
		'cil/intro',
		array(
			'eyebrow' => 'Welcome to the clinic',
			'heading' => 'Choosing circumcision is an important decision. We will talk you through all of it.',
			'html'    => $intro_html,
		)
	);
	$blocks[] = cil_dyn_block(
		'cil/process',
		array(
			'eyebrow' => 'What happens',
			'heading' => 'Four steps, and no surprises in any of them',
			'lede'    => 'This is the whole process. If anything on the day differs from what is written here, we will have told you why before it happens.',
			'items'   => cil_home_steps(),
		)
	);
	$blocks[] = cil_dyn_block(
		'cil/home-callback',
		array(
			'formId'      => 'home',
			'subject'     => 'homepage callback',
			'cardEyebrow' => 'Request a call back',
			'cardTitle'   => 'Ask before you book',
			'eyebrow'     => 'Being straight with you',
			'heading'     => 'When we will tell you not to',
			'html'        => $callback_html,
		)
	);
	$blocks[] = cil_dyn_block(
		'cil/reviews',
		array(
			'body'   => 'Most of our patients arrive because somebody they trust sent them. The families who come back are the part we are proudest of: some return after many years whenever there is a new son, others bring a brother, then a nephew, then a cousin. We have families we have now seen across three children and the better part of a decade.',
			'quotes' => cil_testimonials(),
		)
	);
	$blocks[] = cil_dyn_block(
		'cil/cta-band',
		array(
			'title' => 'Ask us anything before you decide',
			'text'  => 'Most people call with a question rather than to book. That is what the phone is for, and nothing is booked until you say so.',
		)
	);
	$blocks[] = cil_dyn_block(
		'cil/faq',
		array(
			'heading' => 'The questions we are asked most',
			'items'   => cil_home_faqs(),
		)
	);

	return cil_serialize_blocks( $blocks );
}

/**
 * Create the static Home page if missing and seed Gutenberg blocks once.
 *
 * Does not overwrite existing Gutenberg homepage content. Does not mark the
 * page as _cil_managed, so the service-page seeder will not touch it.
 */
function cil_ensure_homepage() {
	if ( wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return;
	}

	$front_id = (int) get_option( 'page_on_front' );

	if ( ! $front_id ) {
		$existing = get_page_by_path( 'home', OBJECT, 'page' );
		if ( $existing ) {
			$front_id = (int) $existing->ID;
		} else {
			kses_remove_filters();
			$created = wp_insert_post(
				wp_slash(
					array(
						'post_title'   => 'Home',
						'post_name'    => 'home',
						'post_status'  => 'publish',
						'post_type'    => 'page',
						'post_content' => cil_homepage_blocks(),
					)
				),
				true
			);
			kses_init_filters();
			if ( is_wp_error( $created ) || ! $created ) {
				return;
			}
			$front_id = (int) $created;
		}

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_id );
		flush_rewrite_rules( false );
	} elseif ( 'page' !== get_option( 'show_on_front' ) ) {
		update_option( 'show_on_front', 'page' );
	}

	$post = get_post( $front_id );
	if ( ! $post || 'page' !== $post->post_type ) {
		return;
	}

	if ( false !== strpos( $post->post_content, 'wp:cil/hero' ) ) {
		return;
	}

	if ( trim( $post->post_content ) !== '' ) {
		return;
	}

	kses_remove_filters();
	wp_update_post(
		wp_slash(
			array(
				'ID'           => $front_id,
				'post_content' => cil_homepage_blocks(),
			)
		)
	);
	kses_init_filters();
}
add_action( 'init', 'cil_ensure_homepage', 41 );
