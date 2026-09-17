<?php
/**
 * Gutenberg blocks, styles and editor script for reusable clinic patterns.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Editor category for clinic blocks.
 *
 * @param array<int, array<string, mixed>> $categories Categories.
 * @return array<int, array<string, mixed>>
 */
function cil_block_categories( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'circumcision-london',
			'title' => __( 'Circumcision London', 'circumcision-london' ),
		)
	);
	return $categories;
}
add_filter( 'block_categories_all', 'cil_block_categories' );

/**
 * Editor script: block registrations, styles and Inspector controls.
 */
function cil_enqueue_editor_assets() {
	wp_enqueue_media();
	wp_enqueue_script(
		'cil-editor',
		get_template_directory_uri() . '/assets/js/editor.js',
		array(
			'wp-blocks',
			'wp-element',
			'wp-block-editor',
			'wp-components',
			'wp-i18n',
			'wp-data',
			'wp-server-side-render',
		),
		cil_asset_version( 'assets/js/editor.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'cil_enqueue_editor_assets' );

/**
 * Capture a template part as HTML for block render callbacks.
 *
 * @param string               $slug Template-part slug.
 * @param array<string, mixed> $args Args.
 * @return string
 */
function cil_render_part( $slug, $args = array() ) {
	ob_start();
	get_template_part( 'template-parts/' . $slug, null, $args );
	return (string) ob_get_clean();
}

/**
 * Decode a JSON attribute that may already be an array.
 *
 * @param mixed $value Raw attribute.
 * @return array
 */
function cil_block_list( $value ) {
	if ( is_array( $value ) ) {
		return $value;
	}
	if ( is_string( $value ) && '' !== $value ) {
		$decoded = json_decode( $value, true );
		if ( is_array( $decoded ) ) {
			return $decoded;
		}
	}
	return array();
}

/**
 * Register dynamic (PHP-rendered) blocks. Static blocks live in editor.js.
 */
function cil_register_dynamic_blocks() {
	$common = array(
		'api_version' => 3,
		'category'    => 'circumcision-london',
		'supports'    => array(
			'html'  => false,
			'align' => array( 'wide', 'full' ),
		),
	);

	register_block_type(
		'cil/trust-strip',
		array_merge(
			$common,
			array(
				'title'           => __( 'Trust strip', 'circumcision-london' ),
				'description'     => __( 'Reviews, experience, CQC and languages.', 'circumcision-london' ),
				'render_callback' => function () {
					return '<div class="cil-breakout">' . cil_render_part( 'trust-strip' ) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/group-cards',
		array_merge(
			$common,
			array(
				'title'           => __( 'Age group cards', 'circumcision-london' ),
				'description'     => __( 'Three age-group service cards with prices.', 'circumcision-london' ),
				'attributes'      => array(
					'level'   => array(
						'type'    => 'number',
						'default' => 3,
					),
					'exclude' => array(
						'type'    => 'string',
						'default' => '',
					),
					'banded'  => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
				'render_callback' => function ( $attrs ) {
					$exclude = isset( $attrs['exclude'] ) ? $attrs['exclude'] : '';
					if ( ! $exclude && is_singular() ) {
						$exclude = get_permalink();
					}
					$cards = cil_render_part(
						'group-cards',
						array(
							'level'   => isset( $attrs['level'] ) ? (int) $attrs['level'] : 3,
							'exclude' => $exclude,
						)
					);
					if ( ! empty( $attrs['banded'] ) ) {
						return '<div class="cil-breakout"><section class="section bg-warm"><div class="wrap">' . $cards . '</div></section></div>';
					}
					return '<div class="cil-breakout">' . $cards . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/hero',
		array_merge(
			$common,
			array(
				'title'           => __( 'Homepage hero', 'circumcision-london' ),
				'description'     => __( 'Full-bleed clinic hero with video poster, CTAs and facts.', 'circumcision-london' ),
				'attributes'      => array(
					'eyebrow'     => array(
						'type'    => 'string',
						'default' => 'CQC registered · Edgware, North-West London',
					),
					'title'       => array(
						'type'    => 'string',
						'default' => 'A dedicated circumcision clinic in North-West London',
					),
					'sub'         => array(
						'type'    => 'string',
						'default' => 'Qualified practitioners, local anaesthetic every time, and we show you that no pain is felt before we begin.',
					),
					'posterId'    => array(
						'type'    => 'number',
						'default' => 0,
					),
					'videoMp4Id'  => array(
						'type'    => 'number',
						'default' => 0,
					),
					'videoWebmId' => array(
						'type'    => 'number',
						'default' => 0,
					),
				),
				'render_callback' => function ( $attrs ) {
					return '<div class="cil-breakout">' . cil_render_part(
						'hero',
						array(
							'eyebrow'       => isset( $attrs['eyebrow'] ) ? $attrs['eyebrow'] : '',
							'title'         => isset( $attrs['title'] ) ? $attrs['title'] : '',
							'sub'           => isset( $attrs['sub'] ) ? $attrs['sub'] : '',
							'poster_id'     => isset( $attrs['posterId'] ) ? $attrs['posterId'] : 0,
							'video_mp4_id'  => isset( $attrs['videoMp4Id'] ) ? $attrs['videoMp4Id'] : 0,
							'video_webm_id' => isset( $attrs['videoWebmId'] ) ? $attrs['videoWebmId'] : 0,
						)
					) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/groups',
		array_merge(
			$common,
			array(
				'title'           => __( 'Homepage age groups', 'circumcision-london' ),
				'description'     => __( 'Overlapping age-group cards used under the homepage hero.', 'circumcision-london' ),
				'attributes'      => array(
					'level' => array(
						'type'    => 'number',
						'default' => 2,
					),
				),
				'render_callback' => function ( $attrs ) {
					return '<div class="cil-breakout">' . cil_render_part(
						'groups',
						array(
							'level' => isset( $attrs['level'] ) ? (int) $attrs['level'] : 2,
						)
					) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/intro',
		array_merge(
			$common,
			array(
				'title'           => __( 'Homepage introduction', 'circumcision-london' ),
				'description'     => __( 'Welcome copy and Dr Haidar photograph.', 'circumcision-london' ),
				'attributes'      => array(
					'eyebrow' => array(
						'type'    => 'string',
						'default' => 'Welcome to the clinic',
					),
					'heading' => array(
						'type'    => 'string',
						'default' => 'Choosing circumcision is an important decision. We will talk you through all of it.',
					),
					'html'    => array(
						'type'    => 'string',
						'default' => '',
					),
					'imageId' => array(
						'type'    => 'number',
						'default' => 0,
					),
				),
				'render_callback' => function ( $attrs ) {
					return '<div class="cil-breakout">' . cil_render_part(
						'intro',
						array(
							'eyebrow'  => isset( $attrs['eyebrow'] ) ? $attrs['eyebrow'] : '',
							'heading'  => isset( $attrs['heading'] ) ? $attrs['heading'] : '',
							'html'     => isset( $attrs['html'] ) ? $attrs['html'] : '',
							'image_id' => isset( $attrs['imageId'] ) ? $attrs['imageId'] : 0,
						)
					) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/process',
		array_merge(
			$common,
			array(
				'title'           => __( 'Homepage process', 'circumcision-london' ),
				'description'     => __( 'Four-step process from enquiry to aftercare.', 'circumcision-london' ),
				'attributes'      => array(
					'eyebrow' => array(
						'type'    => 'string',
						'default' => 'What happens',
					),
					'heading' => array(
						'type'    => 'string',
						'default' => 'Four steps, and no surprises in any of them',
					),
					'lede'    => array(
						'type'    => 'string',
						'default' => 'This is the whole process. If anything on the day differs from what is written here, we will have told you why before it happens.',
					),
					'items'   => array(
						'type'    => 'array',
						'default' => array(),
					),
				),
				'render_callback' => function ( $attrs ) {
					$items = cil_block_list( isset( $attrs['items'] ) ? $attrs['items'] : array() );
					$args  = array(
						'eyebrow' => isset( $attrs['eyebrow'] ) ? $attrs['eyebrow'] : '',
						'heading' => isset( $attrs['heading'] ) ? $attrs['heading'] : '',
						'lede'    => isset( $attrs['lede'] ) ? $attrs['lede'] : '',
					);
					if ( $items ) {
						$args['items'] = $items;
					}
					return '<div class="cil-breakout">' . cil_render_part( 'process', $args ) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/home-callback',
		array_merge(
			$common,
			array(
				'title'           => __( 'Homepage callback', 'circumcision-london' ),
				'description'     => __( 'Request a call back plus the “when we will tell you not to” note.', 'circumcision-london' ),
				'attributes'      => array(
					'formId'       => array(
						'type'    => 'string',
						'default' => 'home',
					),
					'subject'      => array(
						'type'    => 'string',
						'default' => 'homepage callback',
					),
					'cardEyebrow'  => array(
						'type'    => 'string',
						'default' => 'Request a call back',
					),
					'cardTitle'    => array(
						'type'    => 'string',
						'default' => 'Ask before you book',
					),
					'eyebrow'      => array(
						'type'    => 'string',
						'default' => 'Being straight with you',
					),
					'heading'      => array(
						'type'    => 'string',
						'default' => 'When we will tell you not to',
					),
					'html'         => array(
						'type'    => 'string',
						'default' => '',
					),
				),
				'render_callback' => function ( $attrs ) {
					return '<div class="cil-breakout">' . cil_render_part(
						'callback',
						array(
							'id'           => isset( $attrs['formId'] ) ? $attrs['formId'] : 'home',
							'subject'      => isset( $attrs['subject'] ) ? $attrs['subject'] : 'homepage callback',
							'card_eyebrow' => isset( $attrs['cardEyebrow'] ) ? $attrs['cardEyebrow'] : '',
							'card_title'   => isset( $attrs['cardTitle'] ) ? $attrs['cardTitle'] : '',
							'eyebrow'      => isset( $attrs['eyebrow'] ) ? $attrs['eyebrow'] : '',
							'heading'      => isset( $attrs['heading'] ) ? $attrs['heading'] : '',
							'html'         => isset( $attrs['html'] ) ? $attrs['html'] : '',
						)
					) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/reviews',
		array_merge(
			$common,
			array(
				'title'           => __( 'Homepage reviews', 'circumcision-london' ),
				'description'     => __( 'Google rating, stats and three patient quotes.', 'circumcision-london' ),
				'attributes'      => array(
					'body'   => array(
						'type'    => 'string',
						'default' => '',
					),
					'quotes' => array(
						'type'    => 'array',
						'default' => array(),
					),
				),
				'render_callback' => function ( $attrs ) {
					$quotes = cil_block_list( isset( $attrs['quotes'] ) ? $attrs['quotes'] : array() );
					$args   = array(
						'body' => isset( $attrs['body'] ) ? $attrs['body'] : '',
					);
					if ( $quotes ) {
						$args['quotes'] = $quotes;
					}
					return '<div class="cil-breakout">' . cil_render_part( 'reviews', $args ) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/urgent-note',
		array_merge(
			$common,
			array(
				'title'           => __( 'Urgent A&E note', 'circumcision-london' ),
				'description'     => __( 'Fixed clinical copy: when to go to A&E.', 'circumcision-london' ),
				'render_callback' => function () {
					return cil_render_part( 'urgent-note' );
				},
			)
		)
	);

	register_block_type(
		'cil/callback-form',
		array_merge(
			$common,
			array(
				'title'           => __( 'Callback form', 'circumcision-london' ),
				'description'     => __( 'Request-a-call-back fields. UI only.', 'circumcision-london' ),
				'attributes'      => array(
					'formId'  => array(
						'type'    => 'string',
						'default' => 'callback',
					),
					'subject' => array(
						'type'    => 'string',
						'default' => 'general enquiry',
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'callback-form',
						array(
							'id'      => isset( $attrs['formId'] ) ? $attrs['formId'] : 'callback',
							'subject' => isset( $attrs['subject'] ) ? $attrs['subject'] : 'general enquiry',
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/callback-card',
		array_merge(
			$common,
			array(
				'title'           => __( 'Callback card', 'circumcision-london' ),
				'description'     => __( 'Form inside the prototype card, with editable heading.', 'circumcision-london' ),
				'attributes'      => array(
					'eyebrow' => array(
						'type'    => 'string',
						'default' => 'Request a call back',
					),
					'title'   => array(
						'type'    => 'string',
						'default' => 'Ask us first',
					),
					'formId'  => array(
						'type'    => 'string',
						'default' => 'callback',
					),
					'subject' => array(
						'type'    => 'string',
						'default' => 'general enquiry',
					),
					'urgent'  => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'callback-card',
						array(
							'eyebrow' => isset( $attrs['eyebrow'] ) ? $attrs['eyebrow'] : '',
							'title'   => isset( $attrs['title'] ) ? $attrs['title'] : '',
							'id'      => isset( $attrs['formId'] ) ? $attrs['formId'] : 'callback',
							'subject' => isset( $attrs['subject'] ) ? $attrs['subject'] : 'general enquiry',
							'urgent'  => ! empty( $attrs['urgent'] ),
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/cliniko-bookings',
		array_merge(
			$common,
			array(
				'title'           => __( 'Cliniko bookings', 'circumcision-london' ),
				'description'     => __( 'London Cliniko booking diary. Do not use for other clinic locations.', 'circumcision-london' ),
				'render_callback' => function () {
					return '<div class="cil-breakout">' . cil_render_part( 'cliniko-bookings' ) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/cta-band',
		array_merge(
			$common,
			array(
				'title'           => __( 'Talk to us band', 'circumcision-london' ),
				'description'     => __( 'Closing CTA with booking, phone and WhatsApp card.', 'circumcision-london' ),
				'attributes'      => array(
					'title' => array(
						'type'    => 'string',
						'default' => 'Ask us anything before you decide',
					),
					'text'  => array(
						'type'    => 'string',
						'default' => 'Most people call with a question rather than to book. That is what the phone is for, and nothing is booked until you say so.',
					),
				),
				'render_callback' => function ( $attrs ) {
					return '<div class="cil-breakout">' . cil_render_part(
						'cta-band',
						array(
							'title' => isset( $attrs['title'] ) ? $attrs['title'] : '',
							'text'  => isset( $attrs['text'] ) ? $attrs['text'] : '',
						)
					) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/quotes-grid',
		array_merge(
			$common,
			array(
				'title'           => __( 'Testimonial grid', 'circumcision-london' ),
				'description'     => __( 'Three prototype testimonials, or custom quotes.', 'circumcision-london' ),
				'attributes'      => array(
					'quotes' => array(
						'type'    => 'array',
						'default' => array(),
					),
				),
				'render_callback' => function ( $attrs ) {
					$quotes = cil_block_list( isset( $attrs['quotes'] ) ? $attrs['quotes'] : array() );
					$args   = array();
					if ( $quotes ) {
						$args['quotes'] = $quotes;
					}
					return cil_render_part( 'quotes-grid', $args );
				},
			)
		)
	);

	register_block_type(
		'cil/spec-list',
		array_merge(
			$common,
			array(
				'title'           => __( 'Specification list', 'circumcision-london' ),
				'description'     => __( 'Key/value rows used on age, team and extra pages.', 'circumcision-london' ),
				'attributes'      => array(
					'rows' => array(
						'type'    => 'array',
						'default' => array(
							array(
								'k' => 'Best age',
								'v' => 'Under one month old',
							),
							array(
								'k' => 'Price',
								'v' => 'From £200',
							),
						),
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'spec-list',
						array(
							'rows' => cil_block_list( isset( $attrs['rows'] ) ? $attrs['rows'] : array() ),
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/steps',
		array_merge(
			$common,
			array(
				'title'           => __( 'Process steps', 'circumcision-london' ),
				'description'     => __( 'Numbered steps list.', 'circumcision-london' ),
				'attributes'      => array(
					'items' => array(
						'type'    => 'array',
						'default' => array(
							array(
								'h' => 'You call, or ask us to call you',
								'p' => 'We answer the questions you have now and agree a consultation time.',
							),
							array(
								'h' => 'Consultation and examination',
								'p' => 'You meet the practitioner who would carry out the procedure.',
							),
						),
					),
				),
				'render_callback' => function ( $attrs ) {
					$items = cil_block_list( isset( $attrs['items'] ) ? $attrs['items'] : array() );
					return cil_render_part( 'steps', array( 'items' => $items ) );
				},
			)
		)
	);

	register_block_type(
		'cil/page-head',
		array_merge(
			$common,
			array(
				'title'           => __( 'Page heading', 'circumcision-london' ),
				'description'     => __( 'Inner-page hero: breadcrumbs, eyebrow, H1 and lede.', 'circumcision-london' ),
				'attributes'      => array(
					'eyebrow'     => array(
						'type'    => 'string',
						'default' => '',
					),
					'title'       => array(
						'type'    => 'string',
						'default' => '',
					),
					'lede'        => array(
						'type'    => 'string',
						'default' => '',
					),
					'reviewedBy'  => array(
						'type'    => 'string',
						'default' => '',
					),
					'crumbs'      => array(
						'type'    => 'array',
						'default' => array(),
					),
				),
				'render_callback' => function ( $attrs ) {
					return '<div class="cil-breakout">' . cil_render_part(
						'page-head',
						array(
							'eyebrow'     => isset( $attrs['eyebrow'] ) ? $attrs['eyebrow'] : '',
							'title'       => isset( $attrs['title'] ) ? $attrs['title'] : '',
							'lede'        => isset( $attrs['lede'] ) ? $attrs['lede'] : '',
							'reviewed_by' => isset( $attrs['reviewedBy'] ) ? $attrs['reviewedBy'] : '',
							'crumbs'      => cil_block_list( isset( $attrs['crumbs'] ) ? $attrs['crumbs'] : array() ),
						)
					) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/section-head',
		array_merge(
			$common,
			array(
				'title'           => __( 'Section heading', 'circumcision-london' ),
				'attributes'      => array(
					'eyebrow' => array(
						'type'    => 'string',
						'default' => '',
					),
					'heading' => array(
						'type'    => 'string',
						'default' => '',
					),
					'lede'    => array(
						'type'    => 'string',
						'default' => '',
					),
					'display' => array(
						'type'    => 'string',
						'default' => 'd-2',
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'section-head',
						array(
							'eyebrow' => isset( $attrs['eyebrow'] ) ? $attrs['eyebrow'] : '',
							'heading' => isset( $attrs['heading'] ) ? $attrs['heading'] : '',
							'lede'    => isset( $attrs['lede'] ) ? $attrs['lede'] : '',
							'display' => isset( $attrs['display'] ) ? $attrs['display'] : 'd-2',
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/callout',
		array_merge(
			$common,
			array(
				'title'           => __( 'Callout', 'circumcision-london' ),
				'attributes'      => array(
					'title'  => array(
						'type'    => 'string',
						'default' => '',
					),
					'body'   => array(
						'type'    => 'string',
						'default' => '',
					),
					'urgent' => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'level'  => array(
						'type'    => 'number',
						'default' => 2,
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'callout',
						array(
							'title'  => isset( $attrs['title'] ) ? $attrs['title'] : '',
							'body'   => isset( $attrs['body'] ) ? $attrs['body'] : '',
							'urgent' => ! empty( $attrs['urgent'] ),
							'level'  => isset( $attrs['level'] ) ? (int) $attrs['level'] : 2,
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/quote',
		array_merge(
			$common,
			array(
				'title'           => __( 'Testimonial card', 'circumcision-london' ),
				'attributes'      => array(
					'name'    => array(
						'type'    => 'string',
						'default' => '',
					),
					'context' => array(
						'type'    => 'string',
						'default' => '',
					),
					'quote'   => array(
						'type'    => 'string',
						'default' => '',
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'quote',
						array(
							'name'    => isset( $attrs['name'] ) ? $attrs['name'] : '',
							'context' => isset( $attrs['context'] ) ? $attrs['context'] : '',
							'quote'   => isset( $attrs['quote'] ) ? $attrs['quote'] : '',
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/price-table',
		array_merge(
			$common,
			array(
				'title'           => __( 'Price table', 'circumcision-london' ),
				'attributes'      => array(
					'rows' => array(
						'type'    => 'array',
						'default' => array(
							array(
								'name'  => '0 – 2 months',
								'desc'  => '',
								'price' => '£200',
								'href'  => '',
								'url'   => '',
								'cta'   => 'Book this',
							),
						),
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'price-table',
						array(
							'rows' => cil_block_list( isset( $attrs['rows'] ) ? $attrs['rows'] : array() ),
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/faq',
		array_merge(
			$common,
			array(
				'title'           => __( 'FAQ accordion', 'circumcision-london' ),
				'attributes'      => array(
					'heading' => array(
						'type'    => 'string',
						'default' => 'Questions people ask us',
					),
					'items'   => array(
						'type'    => 'array',
						'default' => array(),
					),
				),
				'render_callback' => function ( $attrs ) {
					$items = cil_block_list( isset( $attrs['items'] ) ? $attrs['items'] : array() );
					$args  = array(
						'heading' => isset( $attrs['heading'] ) ? $attrs['heading'] : '',
					);
					if ( $items ) {
						$args['items'] = $items;
					}
					return '<div class="cil-breakout">' . cil_render_part( 'faq', $args ) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/rating-badge',
		array_merge(
			$common,
			array(
				'title'           => __( 'Rating badge', 'circumcision-london' ),
				'attributes'      => array(
					'score'      => array(
						'type'    => 'string',
						'default' => '4.9',
					),
					'strong'     => array(
						'type'    => 'string',
						'default' => 'Read them on Google',
					),
					'sub'        => array(
						'type'    => 'string',
						'default' => '2,092 reviews, new tab',
					),
					'href'       => array(
						'type'    => 'string',
						'default' => '',
					),
					'track'      => array(
						'type'    => 'string',
						'default' => 'reviews-read',
					),
					'smallScore' => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
				'render_callback' => function ( $attrs ) {
					$clinic = cil_clinic();
					$href   = isset( $attrs['href'] ) && $attrs['href'] ? $attrs['href'] : $clinic['reviews']['read_url'];
					return cil_render_part(
						'rating-badge',
						array(
							'score'       => isset( $attrs['score'] ) ? $attrs['score'] : '',
							'strong'      => isset( $attrs['strong'] ) ? $attrs['strong'] : '',
							'sub'         => isset( $attrs['sub'] ) ? $attrs['sub'] : '',
							'href'        => $href,
							'track'       => isset( $attrs['track'] ) ? $attrs['track'] : 'reviews-read',
							'small_score' => ! empty( $attrs['smallScore'] ),
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/video-grid',
		array_merge(
			$common,
			array(
				'title'           => __( 'Video testimonials', 'circumcision-london' ),
				'description'     => __( 'Renders nothing until videos are supplied. Matches the prototype empty state.', 'circumcision-london' ),
				'attributes'      => array(
					'items' => array(
						'type'    => 'array',
						'default' => array(),
					),
				),
				'render_callback' => function ( $attrs ) {
					$items = array();
					foreach ( cil_block_list( isset( $attrs['items'] ) ? $attrs['items'] : array() ) as $item ) {
						$item = cil_resolve_video_item( $item );
						if ( empty( $item['mp4'] ) && empty( $item['webm'] ) ) {
							continue;
						}
						$items[] = $item;
					}
					return cil_render_part(
						'video-grid',
						array(
							'items' => $items,
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/spec-panel',
		array_merge(
			$common,
			array(
				'title'           => __( 'At-a-glance spec', 'circumcision-london' ),
				'description'     => __( 'Eyebrow, specification list and optional price note.', 'circumcision-london' ),
				'attributes'      => array(
					'eyebrow' => array(
						'type'    => 'string',
						'default' => 'At a glance',
					),
					'rows'    => array(
						'type'    => 'array',
						'default' => array(),
					),
					'note'    => array(
						'type'    => 'string',
						'default' => '',
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'spec-panel',
						array(
							'eyebrow' => isset( $attrs['eyebrow'] ) ? $attrs['eyebrow'] : '',
							'rows'    => cil_block_list( isset( $attrs['rows'] ) ? $attrs['rows'] : array() ),
							'note'    => isset( $attrs['note'] ) ? $attrs['note'] : '',
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/text-section',
		array_merge(
			$common,
			array(
				'title'           => __( 'Text section', 'circumcision-london' ),
				'description'     => __( 'Eyebrow, heading and body used on the striped inner pages.', 'circumcision-london' ),
				'attributes'      => array(
					'eyebrow' => array(
						'type'    => 'string',
						'default' => '',
					),
					'heading' => array(
						'type'    => 'string',
						'default' => '',
					),
					'html'    => array(
						'type'    => 'string',
						'default' => '',
					),
					'narrow'  => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'banded'  => array(
						'type'    => 'boolean',
						'default' => false,
					),
					'display' => array(
						'type'    => 'string',
						'default' => 'd-2',
					),
				),
				'render_callback' => function ( $attrs ) {
					return '<div class="cil-breakout">' . cil_render_part(
						'text-section',
						array(
							'eyebrow' => isset( $attrs['eyebrow'] ) ? $attrs['eyebrow'] : '',
							'heading' => isset( $attrs['heading'] ) ? $attrs['heading'] : '',
							'html'    => isset( $attrs['html'] ) ? $attrs['html'] : '',
							'narrow'  => isset( $attrs['narrow'] ) ? (bool) $attrs['narrow'] : true,
							'banded'  => ! empty( $attrs['banded'] ),
							'display' => isset( $attrs['display'] ) ? $attrs['display'] : 'd-2',
						)
					) . '</div>';
				},
			)
		)
	);

	register_block_type(
		'cil/figure',
		array_merge(
			$common,
			array(
				'title'           => __( 'Clinic figure', 'circumcision-london' ),
				'description'     => __( 'Responsive picture with prototype srcset, ratio and caption.', 'circumcision-london' ),
				'attributes'      => array(
					'name'    => array(
						'type'    => 'string',
						'default' => '',
					),
					'src'     => array(
						'type'    => 'string',
						'default' => '',
					),
					'webp'    => array(
						'type'    => 'string',
						'default' => '',
					),
					'alt'     => array(
						'type'    => 'string',
						'default' => '',
					),
					'caption' => array(
						'type'    => 'string',
						'default' => '',
					),
					'ratio'   => array(
						'type'    => 'string',
						'default' => '4-3',
					),
					'width'   => array(
						'type'    => 'number',
						'default' => 1920,
					),
					'height'  => array(
						'type'    => 'number',
						'default' => 1080,
					),
					'imageId'          => array(
						'type'    => 'number',
						'default' => 0,
					),
					'useFeaturedImage' => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
				'uses_context'    => array( 'postId' ),
				'render_callback' => function ( $attrs, $content, $block ) {
					$post_id = 0;
					if ( isset( $block->context['postId'] ) ) {
						$post_id = (int) $block->context['postId'];
					} elseif ( get_the_ID() ) {
						$post_id = (int) get_the_ID();
					}
					return cil_render_part(
						'figure',
						array(
							'name'     => isset( $attrs['name'] ) ? $attrs['name'] : '',
							'src'      => isset( $attrs['src'] ) ? $attrs['src'] : '',
							'webp'     => isset( $attrs['webp'] ) ? $attrs['webp'] : '',
							'alt'      => isset( $attrs['alt'] ) ? $attrs['alt'] : '',
							'caption'  => isset( $attrs['caption'] ) ? $attrs['caption'] : '',
							'ratio'    => isset( $attrs['ratio'] ) ? $attrs['ratio'] : '4-3',
							'width'    => isset( $attrs['width'] ) ? (int) $attrs['width'] : 1920,
							'height'   => isset( $attrs['height'] ) ? (int) $attrs['height'] : 1080,
							'image_id' => cil_figure_attachment_id( $attrs, $post_id ),
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/info-cards',
		array_merge(
			$common,
			array(
				'title'           => __( 'Info cards', 'circumcision-london' ),
				'description'     => __( 'Three-up card grid used on religious, courses and similar pages.', 'circumcision-london' ),
				'attributes'      => array(
					'items' => array(
						'type'    => 'array',
						'default' => array(),
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'info-cards',
						array(
							'items' => cil_block_list( isset( $attrs['items'] ) ? $attrs['items'] : array() ),
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/section',
		array(
			'api_version' => 3,
			'title'       => __( 'Section', 'circumcision-london' ),
			'category'    => 'circumcision-london',
			'supports'    => array(
				'html'   => false,
				'align'  => array( 'wide', 'full' ),
				'anchor' => true,
			),
			'attributes'  => array(
				'size'   => array(
					'type'    => 'string',
					'default' => 'section-sm',
				),
				'band'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'wrap'   => array(
					'type'    => 'string',
					'default' => 'wrap',
				),
				'anchor' => array(
					'type'    => 'string',
					'default' => '',
				),
			),
		)
	);

	register_block_type(
		'cil/file-link',
		array_merge(
			$common,
			array(
				'title'           => __( 'Document link', 'circumcision-london' ),
				'description'     => __( 'Link to a Media Library PDF, with an optional external URL fallback.', 'circumcision-london' ),
				'attributes'      => array(
					'fileId' => array(
						'type'    => 'number',
						'default' => 0,
					),
					'text'   => array(
						'type'    => 'string',
						'default' => '',
					),
					'url'    => array(
						'type'    => 'string',
						'default' => '',
					),
				),
				'render_callback' => function ( $attrs ) {
					return cil_render_part(
						'file-link',
						array(
							'file_id' => isset( $attrs['fileId'] ) ? $attrs['fileId'] : 0,
							'text'    => isset( $attrs['text'] ) ? $attrs['text'] : '',
							'url'     => isset( $attrs['url'] ) ? $attrs['url'] : '',
						)
					);
				},
			)
		)
	);

	register_block_type(
		'cil/split',
		array(
			'api_version' => 3,
			'title'       => __( 'Split', 'circumcision-london' ),
			'category'    => 'circumcision-london',
			'supports'    => array(
				'html'  => false,
				'align' => array( 'wide', 'full' ),
			),
			'attributes'  => array(
				'reverse' => array(
					'type'    => 'boolean',
					'default' => false,
				),
			),
		)
	);
}
add_action( 'init', 'cil_register_dynamic_blocks' );
