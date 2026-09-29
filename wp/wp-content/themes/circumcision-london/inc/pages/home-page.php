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

	$intro_html  = '<p>Our clinic is in North-West London, about fifteen minutes from Wembley Stadium, outside the congestion charge, with free street parking all around it. We circumcise babies, infants, children, teenagers and adult men, day in and day out. It is what this clinic does.</p>';
	$intro_html .= '<p>Our two practitioners have more than forty years of combined experience between them, in the UK and abroad, and have carried out thousands of circumcisions across every age group. They also <a href="' . $courses . '">train doctors in the UK and internationally</a> in how to circumcise safely.</p>';
	$intro_html .= '<p>Whether you are here for religious, medical, cultural or personal reasons, the standard does not change: local anaesthetic every time, tested and shown to be working before we begin, and aftercare we explain to you and then give you in writing.</p>';

	$callback_html  = '<p><strong>Who is suitable for circumcision?</strong></p>';
	$callback_html .= '<p>We circumcise males of all ages and backgrounds, so whether it is for religious, cultural or medical reasons, we have you covered. Let us know when booking what your main reason for circumcising and we will accommodate you accordingly.</p>';
	$callback_html .= '<p>Our 2 practitioners are Muslim and we treat a big portion of the Muslim community in London and from other parts of the UK. We also get patients from the Jewish community coming in for religious circumcisions.</p>';
	$callback_html .= '<p>Some patients get circumcised to meet cultural requirements such as Filipinos, certain parts of Africa, Fiji and other countries where circumcision is an important part of their culture.</p>';
	$callback_html .= '<p>We also get a big portion of patients who have a medical reason for getting circumcised, most common is a tight foreskin (phimosis and paraphimosis), inflammation of the head of the penis (balanitis) as well as short and scarred frenulum.</p>';

	$callback_card_html  = '<p><strong>Want to ask before you book?</strong></p>';
	$callback_card_html .= '<p>We often call you back within a few minutes, but always within the same day even during busy times. Monday – Saturday 09:00–17:00.</p>';
	$callback_card_html .= '<p><strong>Name, phone number</strong></p>';
	$callback_card_html .= '<p><strong>Patient DOB</strong></p>';
	$callback_card_html .= '<p><strong>Anything you would like us to know (optional)</strong></p>';

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
			'cardHtml'    => $callback_card_html,
			'eyebrow'     => '',
			'heading'     => 'When we may postpone',
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
