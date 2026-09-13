<?php
/**
 * Reusable section rendering, shortcodes and Gutenberg patterns.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template-part slugs that can be rendered as sections.
 *
 * @return string[]
 */
function cil_section_slugs() {
	return array(
		'hero',
		'groups',
		'group-cards',
		'trust-strip',
		'intro',
		'process',
		'steps',
		'callback',
		'callback-form',
		'callback-card',
		'urgent-note',
		'reviews',
		'cta-band',
		'faq',
		'page-head',
		'section-head',
		'spec-list',
		'quotes-grid',
		'quote',
		'callout',
		'price-table',
		'rating-badge',
		'text-section',
		'video-grid',
		'figure',
		'spec-panel',
		'info-cards',
	);
}

/**
 * Render a named section into the current output buffer.
 *
 * @param string               $name Section slug.
 * @param array<string, mixed> $args Template-part args.
 */
function cil_render_section( $name, $args = array() ) {
	if ( ! in_array( $name, cil_section_slugs(), true ) ) {
		return;
	}
	get_template_part( 'template-parts/' . $name, null, $args );
}

/**
 * Capture a section as HTML.
 *
 * @param string               $name Section slug.
 * @param array<string, mixed> $args Template-part args.
 * @return string
 */
function cil_get_section( $name, $args = array() ) {
	ob_start();
	cil_render_section( $name, $args );
	return (string) ob_get_clean();
}

/**
 * [cil_section name="trust-strip"] for Gutenberg shortcode blocks.
 *
 * @param array<string, string> $atts Shortcode attributes.
 * @return string
 */
function cil_section_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'name'    => '',
			'level'   => '',
			'heading' => '',
			'title'   => '',
			'text'    => '',
			'id'      => '',
			'subject' => '',
			'exclude' => '',
		),
		$atts,
		'cil_section'
	);

	$name = sanitize_key( $atts['name'] );
	if ( ! in_array( $name, cil_section_slugs(), true ) ) {
		return '';
	}

	$args = array();
	if ( '' !== $atts['level'] ) {
		$args['level'] = (int) $atts['level'];
	}
	if ( '' !== $atts['heading'] ) {
		$args['heading'] = $atts['heading'];
	}
	if ( '' !== $atts['title'] ) {
		$args['title'] = $atts['title'];
	}
	if ( '' !== $atts['text'] ) {
		$args['text'] = $atts['text'];
	}
	if ( '' !== $atts['id'] ) {
		$args['id'] = $atts['id'];
	}
	if ( '' !== $atts['subject'] ) {
		$args['subject'] = $atts['subject'];
	}
	if ( '' !== $atts['exclude'] ) {
		$args['exclude'] = $atts['exclude'];
	}

	$html = cil_get_section( $name, $args );
	if ( '' === $html ) {
		return '';
	}

	return '<div class="cil-breakout">' . $html . '</div>';
}
add_shortcode( 'cil_section', 'cil_section_shortcode' );

/**
 * Gutenberg pattern category for reusable clinic sections.
 */
function cil_register_pattern_category() {
	register_block_pattern_category(
		'circumcision-london',
		array(
			'label' => __( 'Circumcision London', 'circumcision-london' ),
		)
	);
}
add_action( 'init', 'cil_register_pattern_category' );

/**
 * Register block patterns that insert the reusable sections.
 */
function cil_register_block_patterns() {
	$patterns = array(
		'hero'         => array(
			'title'       => __( 'Hero', 'circumcision-london' ),
			'description' => __( 'Full-bleed clinic hero with video poster, CTAs and facts.', 'circumcision-london' ),
		),
		'groups'       => array(
			'title'       => __( 'Age group cards', 'circumcision-london' ),
			'description' => __( 'Three overlapping age-group cards with prices.', 'circumcision-london' ),
		),
		'trust-strip'  => array(
			'title'       => __( 'Trust strip', 'circumcision-london' ),
			'description' => __( 'Reviews, experience, CQC and languages.', 'circumcision-london' ),
		),
		'intro'        => array(
			'title'       => __( 'Clinic introduction', 'circumcision-london' ),
			'description' => __( 'Welcome copy and Dr Haidar photograph.', 'circumcision-london' ),
		),
		'process'      => array(
			'title'       => __( 'Process steps', 'circumcision-london' ),
			'description' => __( 'Four-step process from enquiry to aftercare.', 'circumcision-london' ),
		),
		'callback'     => array(
			'title'       => __( 'Callback form', 'circumcision-london' ),
			'description' => __( 'Request a call back plus the “when we will tell you not to” note.', 'circumcision-london' ),
		),
		'urgent-note'  => array(
			'title'       => __( 'Urgent A&E note', 'circumcision-london' ),
			'description' => __( 'When to go to A&E instead of waiting for an appointment.', 'circumcision-london' ),
		),
		'reviews'      => array(
			'title'       => __( 'Reviews and testimonials', 'circumcision-london' ),
			'description' => __( 'Google rating, stats and three patient quotes.', 'circumcision-london' ),
		),
		'cta-band'     => array(
			'title'       => __( 'Talk to us band', 'circumcision-london' ),
			'description' => __( 'Closing CTA with phone, booking and WhatsApp card.', 'circumcision-london' ),
		),
		'faq'          => array(
			'title'       => __( 'Homepage FAQ', 'circumcision-london' ),
			'description' => __( 'Accordion of the questions asked most often.', 'circumcision-london' ),
		),
	);

	foreach ( $patterns as $slug => $meta ) {
		$shortcode = '[cil_section name="' . $slug . '"]';
		if ( 'groups' === $slug ) {
			$shortcode = '[cil_section name="groups" level="2"]';
		}
		if ( 'faq' === $slug ) {
			$shortcode = '[cil_section name="faq" heading="The questions we are asked most"]';
		}

		register_block_pattern(
			'circumcision-london/' . $slug,
			array(
				'title'       => $meta['title'],
				'description' => $meta['description'],
				'categories'  => array( 'circumcision-london' ),
				'content'     => '<!-- wp:group {"align":"full","className":"cil-breakout"} -->
<div class="wp-block-group alignfull cil-breakout"><!-- wp:shortcode -->
' . $shortcode . '
<!-- /wp:shortcode --></div>
<!-- /wp:group -->',
			)
		);
	}
}
add_action( 'init', 'cil_register_block_patterns' );
