<?php
/**
 * Circumcision London theme setup.
 *
 * Hybrid classic PHP templates + Gutenberg. Clinic details, nav and prices
 * match the approved prototype (src/site.js, src/layout.js).
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CIL_VERSION', '0.13.28' );

/**
 * Clinic facts from the prototype. Do not invent replacements here.
 *
 * @return array<string, mixed>
 */
function cil_clinic() {
	static $clinic = null;

	if ( null !== $clinic ) {
		return $clinic;
	}

	$clinic = array(
		'name'       => 'Circumcision in London',
		'legal_name' => 'Beverley Clinic',
		'parent'     => 'Beverley Clinic',
		'parent_url' => 'https://www.beverleyclinic.co.uk',
		'address'    => array(
			'street'   => '78 Beverley Drive',
			'locality' => 'Edgware',
			'region'   => 'London',
			'postcode' => 'HA8 5NE',
			'country'  => 'GB',
		),
		'phone'      => array(
			'display' => '020 8951 3794',
			'href'    => 'tel:+442089513794',
		),
		'mobile'     => array(
			'display' => '07886 779958',
			'href'    => 'tel:+447886779958',
		),
		'whatsapp'   => array(
			'display' => '07886 779958',
			'href'    => 'https://wa.me/447886779958',
		),
		'email'      => 'info@circumcisioninlondon.co.uk',
		'hours'      => array(
			array(
				'days'  => 'Monday to Saturday',
				'open'  => '09:00',
				'close' => '17:00',
			),
		),
		'hours_note' => 'Sundays/public holidays/bank holidays: phone lines closed; some pre-booked appointments may still take place.',
		// Verified on cqc.org.uk (October 2026): LMCS Limited, 78 Beverley Drive, overall Good.
		'cqc_url'    => 'https://www.cqc.org.uk/location/1-470883883',
		'cqc_location_id' => '1-470883883',
		'cqc_rating' => 'Good',
		'reviews'    => array(
			'rating'         => '4.9',
			'count'          => 2092,
			'count_display'  => '2,092',
			'verified'       => '2 September 2026',
			// Verified Google Maps business listing (opens the listing, not the review form).
			'read_url'       => 'https://www.google.com/maps?cid=3913365297956493985',
		),
		'languages'  => 'English, Arabic, Urdu, Hindi, Punjabi and Turkish',
		// Editable in Appearance > Customize > Social links; empty values are not shown.
		'social'     => array(
			'instagram' => get_theme_mod( 'cil_social_instagram', 'https://www.instagram.com/circumcision.in.london/' ),
			'youtube'   => get_theme_mod( 'cil_social_youtube', '' ),
		),
		'next_available' => 'Usually within 7 days',
		'book_path'  => '/book/',
	);

	/**
	 * Filter clinic details used by the theme chrome.
	 *
	 * @param array<string, mixed> $clinic Clinic details.
	 */
	$clinic = apply_filters( 'cil_clinic', $clinic );

	return $clinic;
}

/**
 * Theme setup: Gutenberg, menus, logo, HTML5.
 */
function cil_setup() {
	load_theme_textdomain( 'circumcision-london', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 128,
			'width'       => 440,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array( 'site-title', 'site-description' ),
		)
	);

	add_image_size( 'cil-hero', 1800, 0, false );
	add_image_size( 'cil-figure', 1100, 0, false );
	add_image_size( 'cil-card', 700, 0, false );

	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_editor_style(
		array(
			'assets/css/fonts.css',
			'assets/css/base.css',
			'assets/css/components.css',
			'assets/css/gutenberg.css',
		)
	);

	register_nav_menus(
		array(
			'primary'         => __( 'Primary', 'circumcision-london' ),
			'footer-who'      => __( 'Footer: Who we see', 'circumcision-london' ),
			'footer-reasons'  => __( 'Footer: Reasons', 'circumcision-london' ),
			'footer-clinics'  => __( 'Footer: Our locations', 'circumcision-london' ),
			'footer-legal'    => __( 'Footer: Legal', 'circumcision-london' ),
		)
	);
}
add_action( 'after_setup_theme', 'cil_setup' );

/**
 * Content width matches the prototype wrap (1220px).
 */
function cil_content_width() {
	$GLOBALS['content_width'] = 1220;
}
add_action( 'after_setup_theme', 'cil_content_width', 0 );

/**
 * Cache-bust a theme file from its mtime.
 *
 * @param string $relative Path relative to the theme root.
 * @return string
 */
function cil_asset_version( $relative ) {
	$path = get_template_directory() . '/' . ltrim( $relative, '/' );
	if ( file_exists( $path ) ) {
		return (string) filemtime( $path );
	}
	return CIL_VERSION;
}

/**
 * Front-end assets: fonts, design system, header, footer, Gutenberg.
 */
function cil_enqueue_assets() {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'cil-fonts', $uri . '/assets/css/fonts.css', array(), cil_asset_version( 'assets/css/fonts.css' ) );
	wp_enqueue_style( 'cil-base', $uri . '/assets/css/base.css', array( 'cil-fonts' ), cil_asset_version( 'assets/css/base.css' ) );
	wp_enqueue_style( 'cil-header', $uri . '/assets/css/header.css', array( 'cil-base' ), cil_asset_version( 'assets/css/header.css' ) );
	wp_enqueue_style( 'cil-footer', $uri . '/assets/css/footer.css', array( 'cil-base' ), cil_asset_version( 'assets/css/footer.css' ) );
	wp_enqueue_style( 'cil-components', $uri . '/assets/css/components.css', array( 'cil-base' ), cil_asset_version( 'assets/css/components.css' ) );
	wp_enqueue_style( 'cil-gutenberg', $uri . '/assets/css/gutenberg.css', array( 'cil-base', 'cil-components' ), cil_asset_version( 'assets/css/gutenberg.css' ) );
	// Visual layer: photo cards, colour bands, icons, trust indicators, homepage composition.
	wp_enqueue_style( 'cil-visual', $uri . '/assets/css/visual.css', array( 'cil-components', 'cil-gutenberg' ), cil_asset_version( 'assets/css/visual.css' ) );
	if ( is_page( 'babies' ) ) {
		wp_enqueue_style( 'cil-babies', $uri . '/assets/css/babies.css', array( 'cil-visual' ), cil_asset_version( 'assets/css/babies.css' ) );
	}

	wp_enqueue_script(
		'cil-theme',
		$uri . '/assets/js/theme.js',
		array(),
		cil_asset_version( 'assets/js/theme.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'cil_enqueue_assets' );

/**
 * Cliniko postMessage handler only when the London bookings block is present.
 */
function cil_enqueue_cliniko_bookings() {
	if ( is_admin() || ! is_singular() || ! has_block( 'cil/cliniko-bookings' ) ) {
		return;
	}

	$cfg = cil_cliniko_bookings_config();
	$uri = get_template_directory_uri();

	wp_enqueue_script(
		'cil-cliniko-bookings',
		$uri . '/assets/js/cliniko-bookings.js',
		array(),
		cil_asset_version( 'assets/js/cliniko-bookings.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_localize_script(
		'cil-cliniko-bookings',
		'cilClinikoBookings',
		array(
			'iframeId' => $cfg['iframe_id'],
			'origin'   => $cfg['origin'],
		)
	);
}
add_action( 'wp_enqueue_scripts', 'cil_enqueue_cliniko_bookings' );

/**
 * Preload the two latin variable fonts the first paint needs.
 */
function cil_preload_fonts() {
	$uri = get_template_directory_uri();
	echo '<link rel="preload" as="font" type="font/woff2" href="' . esc_url( $uri . '/assets/fonts/newsreader-normal-latin.woff2' ) . '" crossorigin>' . "\n";
	echo '<link rel="preload" as="font" type="font/woff2" href="' . esc_url( $uri . '/assets/fonts/karla-normal-latin.woff2' ) . '" crossorigin>' . "\n";
}
add_action( 'wp_head', 'cil_preload_fonts', 2 );

/**
 * Theme colour for browser chrome. Prototype --paper, not the older README value.
 */
function cil_head_meta() {
	echo '<meta name="theme-color" content="#f4f7fa">' . "\n";
	echo '<script>document.documentElement.classList.remove("no-js");</script>' . "\n";
}
add_action( 'wp_head', 'cil_head_meta', 1 );

/**
 * Body classes.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function cil_body_classes( $classes ) {
	$classes[] = 'cil-theme';
	if ( is_front_page() ) {
		$classes[] = 'cil-home';
	}
	if ( is_404() ) {
		$classes[] = 'page-404';
	}
	/* Ensure page-{slug} exists (WP core sometimes omits it; CSS must not rely on page-id-N). */
	if ( is_singular( 'page' ) ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post && $post->post_name ) {
			$slug_class = sanitize_html_class( 'page-' . $post->post_name );
			if ( $slug_class && ! in_array( $slug_class, $classes, true ) ) {
				$classes[] = $slug_class;
			}
		}
	}
	return $classes;
}
add_filter( 'body_class', 'cil_body_classes' );

/**
 * SVG icons from the prototype layout.
 *
 * @param string $name Icon key.
 * @return string
 */
function cil_icon( $name ) {
	$icons = array(
		'phone'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.9.6 2.8a2 2 0 0 1-.4 2.1L8 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.5 2.8.6a2 2 0 0 1 1.8 2z"/></svg>',
		'email'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 7 9-7"/></svg>',
		'whatsapp' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.2-1.7-.9-2-1-.3-.1-.5-.1-.7.1-.2.3-.7 1-.9 1.2-.2.2-.3.2-.6.1a8 8 0 0 1-2.4-1.5 9 9 0 0 1-1.6-2c-.2-.3 0-.5.1-.6l.5-.5.3-.5v-.5l-.9-2.2c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.2.2 2.1 3.3 5.1 4.5.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.3-.6-.4zM12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.4A10 10 0 1 0 12 2zm0 18.3a8.3 8.3 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.3 8.3 0 1 1 12 20.3z"/></svg>',
		'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>',
		'arrow-up' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5M6 11l6-6 6 6"/></svg>',
		'shield'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2 4 5.5v6c0 5 3.4 9.3 8 10.5 4.6-1.2 8-5.5 8-10.5v-6z"/><path d="m9 12 2 2 4-4"/></svg>',
		'star'     => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.3 6.8.8-5 4.7 1.3 6.8L12 17.3 6 20.6l1.3-6.8-5-4.7 6.8-.8z"/></svg>',
		'pin'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>',
		'clock'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>',
		'users'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/></svg>',
		'award'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="9" r="6"/><path d="m8.2 13.8-1.4 7.7L12 18.6l5.2 2.9-1.4-7.7"/></svg>',
		'heart'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg>',
		'refresh'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 0 1-15.5 6.2L3 16M3 12a9 9 0 0 1 15.5-6.2L21 8"/><path d="M21 3v5h-5M3 21v-5h5"/></svg>',
		'map'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 3 3 5.5v15.5l6-2.5 6 2.5 6-2.5V3l-6 2.5z"/><path d="M9 3v15.5M15 5.5V21"/></svg>',
		'globe'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg>',
		'check'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>',
		'arrow-right' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
		// Google "G" mark in its standard colours, beside Google review figures.
		'google'   => '<svg class="icon-google" viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>',
		// Trustpilot star mark (brand green).
		'trustpilot' => '<svg class="icon-trustpilot" viewBox="0 0 24 24" aria-hidden="true"><path fill="#00B67A" d="M12 1.5l3.09 7.36 7.91.6-6.03 5.15 1.86 7.72L12 18.16 5.17 22.33l1.86-7.72L1 9.46l7.91-.6z"/></svg>',
		// Yell mark (brand yellow tile with Y), matching the Testimonials page buttons.
		'yell' => '<svg class="icon-yell" viewBox="0 0 24 24" aria-hidden="true"><rect width="24" height="24" rx="5" fill="#FFD100"/><text x="12" y="17.5" text-anchor="middle" font-family="Arial,Helvetica,sans-serif" font-weight="700" font-size="15" fill="#111">Y</text></svg>',
	);

	return isset( $icons[ $name ] ) ? $icons[ $name ] : '';
}

/**
 * Booking URL. Prototype path /book.
 *
 * @return string
 */
function cil_book_url() {
	$clinic = cil_clinic();
	return home_url( $clinic['book_path'] );
}

/**
 * London Cliniko booking embed. Do not reuse for Luton, Southampton or Birmingham.
 *
 * @return array{iframe_id: string, src: string, origin: string}
 */
function cil_cliniko_bookings_config() {
	return array(
		'iframe_id' => 'cliniko-64894483',
		'src'       => 'https://beverley-clinic.au1.cliniko.com/bookings?embedded=true',
		'origin'    => 'https://beverley-clinic.au1.cliniko.com',
	);
}

/**
 * Default primary navigation from the prototype.
 *
 * @return array<int, array<string, mixed>>
 */
function cil_default_nav() {
	return array(
		array(
			'label' => __( 'Home', 'circumcision-london' ),
			'url'   => home_url( '/' ),
		),
		array(
			'label'    => __( 'Who we see', 'circumcision-london' ),
			'url'      => home_url( '/babies/' ),
			'children' => array(
				array(
					'label' => __( 'Babies and toddlers', 'circumcision-london' ),
					'url'   => home_url( '/babies/' ),
					'note'  => __( 'From £200', 'circumcision-london' ),
				),
				array(
					'label' => __( 'Children and teenagers', 'circumcision-london' ),
					'url'   => home_url( '/children/' ),
					'note'  => __( 'From £300', 'circumcision-london' ),
				),
				array(
					'label' => __( 'Adult men', 'circumcision-london' ),
					'url'   => home_url( '/adults/' ),
					'note'  => __( 'From £680', 'circumcision-london' ),
				),
				array(
					'label' => __( 'Buried penis', 'circumcision-london' ),
					'url'   => home_url( '/buried-penis/' ),
				),
			),
		),
		array(
			'label'    => __( 'Reasons', 'circumcision-london' ),
			'url'      => home_url( '/conditions/phimosis/' ),
			'children' => array(
				array(
					'label' => __( 'Phimosis (tight foreskin)', 'circumcision-london' ),
					'url'   => home_url( '/conditions/phimosis/' ),
				),
				array(
					'label' => __( 'Balanitis', 'circumcision-london' ),
					'url'   => home_url( '/conditions/balanitis/' ),
				),
				array(
					'label' => __( 'BXO (lichen sclerosus)', 'circumcision-london' ),
					'url'   => home_url( '/conditions/bxo/' ),
				),
				array(
					'label' => __( 'Paraphimosis', 'circumcision-london' ),
					'url'   => home_url( '/conditions/paraphimosis/' ),
				),
				array(
					'label' => __( 'Religious and cultural', 'circumcision-london' ),
					'url'   => home_url( '/religious/' ),
				),
				array(
					'label' => __( 'Re-circumcision', 'circumcision-london' ),
					'url'   => home_url( '/re-circumcision/' ),
				),
				array(
					'label' => __( 'Frenulum removal', 'circumcision-london' ),
					'url'   => home_url( '/procedures/frenuloplasty/' ),
				),
			),
		),
		array(
			'label' => __( 'Testimonials', 'circumcision-london' ),
			'url'   => home_url( '/testimonials/' ),
		),
		array(
			'label' => __( 'About', 'circumcision-london' ),
			'url'   => home_url( '/about/' ),
		),
		array(
			'label' => __( 'Prices', 'circumcision-london' ),
			'url'   => home_url( '/prices/' ),
		),
		array(
			'label' => __( 'For doctors', 'circumcision-london' ),
			'url'   => home_url( '/courses/' ),
		),
		array(
			'label'    => __( 'Contact Us', 'circumcision-london' ),
			'url'      => home_url( '/contact/' ),
			'children' => array(
				array(
					'label' => __( 'Aftercare', 'circumcision-london' ),
					'url'   => home_url( '/aftercare/' ),
				),
				array(
					'label'   => __( 'Our locations', 'circumcision-london' ),
					'heading' => true,
				),
				array(
					'label' => __( 'London — Edgware', 'circumcision-london' ),
					'url'   => home_url( '/contact/' ),
				),
				array(
					'label' => __( 'Luton', 'circumcision-london' ),
					'url'   => home_url( '/luton-circumcision-clinic/' ),
				),
				array(
					'label' => __( 'Southampton', 'circumcision-london' ),
					'url'   => home_url( '/southampton-circumcision-clinic/' ),
				),
				array(
					'label' => __( 'Birmingham', 'circumcision-london' ),
					'url'   => home_url( '/birmingham-circumcision-clinic/' ),
				),
				array(
					'label' => __( 'Blog', 'circumcision-london' ),
					'url'   => home_url( '/blog/' ),
				),
			),
		),
	);
}

/**
 * Default footer "Who we see" links from the prototype.
 *
 * @return array<int, array<string, string>>
 */
function cil_default_footer_who() {
	return array(
		array( 'label' => __( 'Babies & Toddlers', 'circumcision-london' ), 'url' => home_url( '/babies/' ) ),
		array( 'label' => __( 'Children & Teenagers', 'circumcision-london' ), 'url' => home_url( '/children/' ) ),
		array( 'label' => __( 'Adults', 'circumcision-london' ), 'url' => home_url( '/adults/' ) ),
		array( 'label' => __( 'Buried Penis', 'circumcision-london' ), 'url' => home_url( '/buried-penis/' ) ),
		array( 'label' => __( 'Prices', 'circumcision-london' ), 'url' => home_url( '/prices/' ) ),
		array( 'label' => __( 'Testimonials', 'circumcision-london' ), 'url' => home_url( '/testimonials/' ) ),
		array( 'label' => __( 'Aftercare', 'circumcision-london' ), 'url' => home_url( '/aftercare/' ) ),
		array( 'label' => __( 'Our Practitioners', 'circumcision-london' ), 'url' => home_url( '/team/' ) ),
		array( 'label' => __( 'For Doctors', 'circumcision-london' ), 'url' => home_url( '/courses/' ) ),
		array( 'label' => __( 'Visit / Contact', 'circumcision-london' ), 'url' => home_url( '/contact/' ) ),
	);
}

/**
 * Default footer "Reasons" links from the prototype.
 *
 * @return array<int, array<string, string>>
 */
function cil_default_footer_reasons() {
	return array(
		array( 'label' => __( 'Phimosis', 'circumcision-london' ), 'url' => home_url( '/conditions/phimosis/' ) ),
		array( 'label' => __( 'Balanitis', 'circumcision-london' ), 'url' => home_url( '/conditions/balanitis/' ) ),
		array( 'label' => __( 'BXO', 'circumcision-london' ), 'url' => home_url( '/conditions/bxo/' ) ),
		array( 'label' => __( 'Paraphimosis', 'circumcision-london' ), 'url' => home_url( '/conditions/paraphimosis/' ) ),
		array( 'label' => __( 'Religious & Cultural', 'circumcision-london' ), 'url' => home_url( '/religious/' ) ),
		array( 'label' => __( 'Re-circumcision', 'circumcision-london' ), 'url' => home_url( '/re-circumcision/' ) ),
		array( 'label' => __( 'Frenuloplasty', 'circumcision-london' ), 'url' => home_url( '/procedures/frenuloplasty/' ) ),
		array( 'label' => __( 'Preputioplasty', 'circumcision-london' ), 'url' => home_url( '/procedures/preputioplasty/' ) ),
	);
}

/**
 * Default footer "Our locations" links.
 *
 * @return array<int, array<string, string>>
 */
function cil_default_footer_clinics() {
	return array(
		array( 'label' => __( 'London — Edgware', 'circumcision-london' ), 'url' => home_url( '/contact/' ) ),
		array( 'label' => __( 'Luton', 'circumcision-london' ), 'url' => home_url( '/luton-circumcision-clinic/' ) ),
		array( 'label' => __( 'Southampton', 'circumcision-london' ), 'url' => home_url( '/southampton-circumcision-clinic/' ) ),
		array( 'label' => __( 'Birmingham', 'circumcision-london' ), 'url' => home_url( '/birmingham-circumcision-clinic/' ) ),
	);
}

/**
 * Default legal footer links from the prototype.
 *
 * @return array<int, array<string, string>>
 */
function cil_default_footer_legal() {
	$clinic = cil_clinic();

	return array(
		array(
			'label'  => $clinic['parent'],
			'url'    => $clinic['parent_url'],
			'rel'    => 'noopener',
			'target' => '_blank',
		),
		array(
			'label'  => __( 'CQC report', 'circumcision-london' ),
			'url'    => $clinic['cqc_url'],
			'rel'    => 'noopener',
			'target' => '_blank',
		),
		array(
			'label' => __( 'Privacy notice', 'circumcision-london' ),
			'url'   => home_url( '/privacy-notice/' ),
		),
		array(
			'label' => __( 'Accessibility', 'circumcision-london' ),
			'url'   => home_url( '/accessibility/' ),
		),
	);
}

/**
 * Age-group cards used on the prototype 404 and later landing pages.
 *
 * @return array<int, array<string, string>>
 */
function cil_groups() {
	return array(
		array(
			'href'  => home_url( '/babies/' ),
			'title' => __( 'Babies and toddlers', 'circumcision-london' ),
			'age'   => __( 'Best under one month old', 'circumcision-london' ),
			'price' => __( 'From £200', 'circumcision-london' ),
			'cta'   => __( 'Baby circumcision, from £200', 'circumcision-london' ),
			'blurb' => __( 'The ring method, Plastibell or Circumplast, under local anaesthetic. About ten minutes, no stitches, and you take him home straight afterwards. Bath him straight away.', 'circumcision-london' ),
		),
		array(
			'href'  => home_url( '/children/' ),
			'title' => __( 'Children and teenagers', 'circumcision-london' ),
			'age'   => __( '3-17 years old', 'circumcision-london' ),
			'price' => __( 'From £300', 'circumcision-london' ),
			'cta'   => __( 'Circumcision for boys, from £300', 'circumcision-london' ),
			'blurb' => __( 'The forceps guided method with thermal cautery, under local anaesthetic. School holiday appointments book up quickly.', 'circumcision-london' ),
		),
		array(
			'href'  => home_url( '/adults/' ),
			'title' => __( 'Adult men', 'circumcision-london' ),
			'age'   => __( 'Eighteen and over', 'circumcision-london' ),
			'price' => __( 'From £680', 'circumcision-london' ),
			'cta'   => __( 'Adult circumcision, from £680', 'circumcision-london' ),
			'blurb' => __( 'Forceps guided, under local anaesthetic. You stay awake and can talk to the doctor, watch television or listen to music throughout.', 'circumcision-london' ),
		),
	);
}

/**
 * Build a nav tree from a WordPress menu location, or return the prototype fallback.
 *
 * @param string $location Menu location.
 * @param array  $fallback Fallback tree.
 * @return array<int, array<string, mixed>>
 */
function cil_menu_tree( $location, $fallback ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return $fallback;
	}

	$items = wp_get_nav_menu_items( $locations[ $location ] );
	if ( empty( $items ) ) {
		return $fallback;
	}

	$by_parent = array();
	foreach ( $items as $item ) {
		$parent = (int) $item->menu_item_parent;
		if ( ! isset( $by_parent[ $parent ] ) ) {
			$by_parent[ $parent ] = array();
		}
		$by_parent[ $parent ][] = $item;
	}

	$build = function ( $parent_id ) use ( &$build, $by_parent ) {
		$branch = array();
		if ( empty( $by_parent[ $parent_id ] ) ) {
			return $branch;
		}
		foreach ( $by_parent[ $parent_id ] as $item ) {
			$classes = array_filter( (array) $item->classes );
			$node    = array(
				'label' => $item->title,
				'url'   => $item->url,
			);
			if ( in_array( 'nav-heading', $classes, true ) ) {
				$node['heading'] = true;
				$node['url']     = '';
			}
			if ( ! empty( $item->description ) ) {
				$node['note'] = $item->description;
			}
			if ( ! empty( $item->target ) ) {
				$node['target'] = $item->target;
			}
			if ( ! empty( $item->xfn ) ) {
				$node['rel'] = $item->xfn;
			}
			$children = $build( (int) $item->ID );
			if ( $children ) {
				$node['children'] = $children;
			}
			$branch[] = $node;
		}
		return $branch;
	};

	return $build( 0 );
}

/**
 * Whether a nav URL is the current request.
 *
 * @param string $url Absolute or home-relative URL.
 * @return bool
 */
function cil_is_current_url( $url ) {
	$item_path = (string) wp_parse_url( $url, PHP_URL_PATH );
	$here_path = (string) wp_parse_url( home_url( add_query_arg( array() ) ), PHP_URL_PATH );
	$home_path = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );

	$item_path = untrailingslashit( $item_path );
	$here_path = untrailingslashit( $here_path );
	$home_path = untrailingslashit( $home_path );

	if ( $item_path === $here_path ) {
		return true;
	}

	if ( $item_path === $home_path ) {
		return is_front_page();
	}

	return $item_path && ( 0 === strpos( $here_path . '/', $item_path . '/' ) );
}

/**
 * aria-current attribute when the URL matches.
 *
 * @param string $url URL.
 * @return string
 */
function cil_current_attr( $url ) {
	return cil_is_current_url( $url ) ? ' aria-current="page"' : '';
}

/**
 * Extra attributes for external footer links.
 *
 * @param array<string, string> $item Link item.
 * @return string
 */
function cil_link_extra_attrs( $item ) {
	$attr = '';
	if ( ! empty( $item['rel'] ) ) {
		$attr .= ' rel="' . esc_attr( $item['rel'] ) . '"';
	}
	if ( ! empty( $item['target'] ) ) {
		$attr .= ' target="' . esc_attr( $item['target'] ) . '"';
	}
	return $attr;
}

/**
 * Logo markup: Customizer, then context-specific CIL theme PNG, then text.
 *
 * @param array<string, mixed> $clinic  Clinic details.
 * @param string               $context 'header' or 'footer'.
 * @return string
 */
function cil_logo_html( $clinic, $context = 'header' ) {
	$context = ( 'footer' === $context ) ? 'footer' : 'header';
	$attrs   = array(
		'alt'    => $clinic['name'],
		'width'  => 550,
		'height' => 602,
	);

	if ( 'header' === $context ) {
		$attrs['fetchpriority'] = 'high';
	}

	if ( has_custom_logo() ) {
		return wp_get_attachment_image( (int) get_theme_mod( 'custom_logo' ), 'full', false, $attrs );
	}

	// Client-supplied header/footer files first; older theme PNGs remain as fallbacks.
	// Header uses the transparent lockup (no white box) — same artwork family as the named header file.
	$candidates = ( 'footer' === $context )
		? array(
			// White-outline footer lockup for navy footer (client file).
			'assets/images/Circumcision in London - footer - white outline.png',
			'assets/images/circumcision-in-london-footer-logo.png',
			'assets/images/Circumcision in London - footer.png',
			'assets/images/circumcision-in-london-logo.png',
			'assets/images/logo.png',
		)
		: array(
			// Transparent horizontal lockup only — never fall back to white-plate JPGs/PNGs.
			'assets/images/circumcision-in-london-header-logo.png',
			'assets/images/circumcision-in-london-logo-wordmark.png',
			'assets/images/Circumcision in London Logo - transparent background.png',
			'assets/images/circumcision-in-london-logo.png',
			'assets/images/logo.png',
		);

	foreach ( $candidates as $relative ) {
		$path = get_template_directory() . '/' . $relative;
		if ( ! file_exists( $path ) ) {
			continue;
		}
		$size = @getimagesize( $path );
		$w    = ( $size && ! empty( $size[0] ) ) ? (int) $size[0] : (int) $attrs['width'];
		$h    = ( $size && ! empty( $size[1] ) ) ? (int) $size[1] : (int) $attrs['height'];
		$prio = ( 'header' === $context ) ? ' fetchpriority="high"' : '';
		$ver  = (string) filemtime( $path );
		$src  = get_template_directory_uri() . '/' . $relative . '?v=' . rawurlencode( $ver );
		return sprintf(
			'<img src="%1$s" alt="%2$s" width="%3$d" height="%4$d"%5$s>',
			esc_url( $src ),
			esc_attr( $clinic['name'] ),
			$w,
			$h,
			$prio
		);
	}

	return '<span class="mark">' . esc_html( $clinic['name'] ) . '</span>';
}

/**
 * Site wordmark. Prototype stacked lockup: logo plus "Circumcision in London".
 *
 * @param string $extra_class Optional extra class on the anchor.
 * @param string $context     'header' or 'footer' — selects the matching logo asset.
 */
function cil_wordmark( $extra_class = '', $context = 'header' ) {
	$clinic  = cil_clinic();
	$context = ( 'footer' === $context ) ? 'footer' : 'header';
	$class   = 'brand' . ( $extra_class ? ' ' . $extra_class : '' );
	/*
	 * Visual lockup stays logo + trading name. Full handover phrase is in aria-label
	 * so we do not duplicate "at Beverley Clinic" against the logo lockup.
	 */
	$brand_line = sprintf(
		/* translators: 1: trading name, 2: legal clinic name */
		__( '%1$s at %2$s', 'circumcision-london' ),
		$clinic['name'],
		$clinic['legal_name']
	);
	$label = sprintf(
		/* translators: %s: brand line */
		__( '%s, home page', 'circumcision-london' ),
		$brand_line
	);
	?>
	<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $label ); ?>">
		<?php echo cil_logo_html( $clinic, $context ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts. ?>
		<span class="sub"><?php echo esc_html( $clinic['name'] ); ?></span>
	</a>
	<?php
}

/**
 * Desktop primary navigation markup.
 */
function cil_primary_nav() {
	// Theme owns primary nav structure (Appearance > Menus primary location is ignored).
	$items = cil_default_nav();
	echo '<ul class="nav">';
	foreach ( $items as $item ) {
		$has_children = ! empty( $item['children'] );
		$li_class     = 'nav-item' . ( $has_children ? ' has-menu' : '' );
		$url          = ! empty( $item['url'] ) ? $item['url'] : '';
		if ( $has_children && ! $url ) {
			foreach ( $item['children'] as $child ) {
				if ( empty( $child['heading'] ) && ! empty( $child['url'] ) ) {
					$url = $child['url'];
					break;
				}
			}
		}
		$cur = $has_children ? '' : cil_current_attr( $url );
		echo '<li class="' . esc_attr( $li_class ) . '">';
		echo '<a class="nav-link" href="' . esc_url( $url ) . '"' . $cur . '>' . esc_html( $item['label'] ) . '</a>';
		if ( $has_children ) {
			echo '<ul class="submenu">';
			foreach ( $item['children'] as $child ) {
				if ( ! empty( $child['heading'] ) ) {
					echo '<li class="submenu-heading"><span class="submenu-label">' . esc_html( $child['label'] ) . '</span></li>';
					continue;
				}
				$note = '';
				if ( ! empty( $child['note'] ) ) {
					$note = '<span class="note">' . esc_html( $child['note'] ) . '</span>';
				}
				echo '<li><a href="' . esc_url( $child['url'] ) . '"' . cil_current_attr( $child['url'] ) . '>' . esc_html( $child['label'] ) . $note . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}

/**
 * Mobile overlay navigation markup.
 */
function cil_mobile_nav() {
	// Keep mobile nav in sync with desktop (theme-owned structure).
	$items = cil_default_nav();
	foreach ( $items as $item ) {
		if ( empty( $item['children'] ) ) {
			echo '<a href="' . esc_url( $item['url'] ) . '"' . cil_current_attr( $item['url'] ) . '>' . esc_html( $item['label'] ) . '</a>';
			continue;
		}
		echo '<span class="caps group-label">' . esc_html( $item['label'] ) . '</span>';
		echo '<ul class="sub">';
		foreach ( $item['children'] as $child ) {
			if ( ! empty( $child['heading'] ) ) {
				echo '<li class="submenu-heading"><span class="submenu-label">' . esc_html( $child['label'] ) . '</span></li>';
				continue;
			}
			$note = '';
			if ( ! empty( $child['note'] ) ) {
				$note = '<span class="note">' . esc_html( $child['note'] ) . '</span>';
			}
			echo '<li><a href="' . esc_url( $child['url'] ) . '"' . cil_current_attr( $child['url'] ) . '>' . esc_html( $child['label'] ) . $note . '</a></li>';
		}
		echo '</ul>';
	}
}

/**
 * Simple link list from a menu location or fallback.
 *
 * @param string $location Menu location.
 * @param array  $fallback Fallback links.
 */
function cil_link_list( $location, $fallback ) {
	$items = cil_menu_tree( $location, $fallback );
	echo '<ul>';
	foreach ( $items as $item ) {
		if ( ! empty( $item['heading'] ) ) {
			continue;
		}
		echo '<li><a href="' . esc_url( $item['url'] ) . '"' . cil_link_extra_attrs( $item ) . '>' . esc_html( $item['label'] ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Prototype page-head block used by inner templates.
 *
 * @param string                         $title        Heading text.
 * @param string                         $lede         Optional lede.
 * @param string                         $eyebrow      Optional eyebrow.
 * @param array<int, array<string, string>> $crumbs    Optional crumbs after Home.
 * @param string                         $reviewed_by  Optional reviewer HTML.
 */
function cil_page_head( $title, $lede = '', $eyebrow = '', $crumbs = array(), $reviewed_by = '' ) {
	get_template_part(
		'template-parts/page-head',
		null,
		array(
			'title'       => $title,
			'lede'        => $lede,
			'eyebrow'     => $eyebrow,
			'crumbs'      => $crumbs,
			'reviewed_by' => $reviewed_by,
		)
	);
}

/**
 * Whether the current post already includes a page heading block or markup.
 *
 * @return bool
 */
function cil_has_page_head() {
	if ( has_block( 'cil/page-head' ) ) {
		return true;
	}
	$content = get_post_field( 'post_content', get_the_ID() );
	return is_string( $content ) && false !== strpos( $content, 'class="page-head' );
}

/**
 * Prototype homepage document title, with no site-name suffix.
 *
 * @param array<string, string> $parts Title parts.
 * @return array<string, string>
 */
function cil_document_title_parts( $parts ) {
	if ( is_404() ) {
		return array(
			'title' => __( 'Page Not Found | Circumcision Clinic in London', 'circumcision-london' ),
		);
	}
	if ( is_front_page() ) {
		return array(
			'title' => __( 'Circumcision Clinic in London | CQC Registered | Edgware', 'circumcision-london' ),
		);
	}
	if ( is_singular() ) {
		$custom = get_post_meta( get_the_ID(), '_cil_document_title', true );
		if ( is_string( $custom ) && $custom ) {
			return array(
				'title' => $custom,
			);
		}
	}
	return $parts;
}
add_filter( 'document_title_parts', 'cil_document_title_parts' );

/**
 * Whether this request is served on a Vercel deployment host (*.vercel.app).
 *
 * Staging runs as Vercel's "production" deployment, so VERCEL_ENV cannot tell
 * staging apart. The client's own domain never ends in .vercel.app.
 *
 * @param string|null $host Host to test. Defaults to the current request host.
 * @return bool
 */
function cil_is_vercel_app_host( $host = null ) {
	if ( null === $host ) {
		$host = isset( $_SERVER['HTTP_HOST'] ) ? (string) $_SERVER['HTTP_HOST'] : '';
	}
	$host = strtolower( trim( (string) $host ) );
	$host = preg_replace( '/:\d+$/', '', $host );
	$host = rtrim( $host, '.' );
	return (bool) preg_match( '/^(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+vercel\.app$/', $host );
}

/**
 * 404s stay out of the index, matching the prototype noindex flag.
 * Vercel staging hosts are never indexed or followed.
 *
 * @param array<string, bool|string> $robots Robots directives.
 * @return array<string, bool|string>
 */
function cil_robots( $robots ) {
	if ( is_404() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['index'] );
	}
	if ( cil_is_vercel_app_host() ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
		unset( $robots['index'], $robots['follow'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'cil_robots' );

/**
 * X-Robots-Tag on Vercel staging hosts, so non-HTML WordPress responses
 * (feeds, sitemaps) carry the same noindex as the robots meta tag.
 */
function cil_staging_robots_header() {
	if ( headers_sent() || ! cil_is_vercel_app_host() ) {
		return;
	}
	header( 'X-Robots-Tag: noindex, nofollow', true );
}
add_action( 'send_headers', 'cil_staging_robots_header' );

/**
 * Prototype 404 meta description.
 */
function cil_404_head() {
	if ( ! is_404() ) {
		return;
	}
	echo '<meta name="description" content="' . esc_attr( 'That page does not exist on this website. Here are the pages for babies, boys and adults, along with prices, aftercare and contact details.' ) . '">' . "\n";
}
add_action( 'wp_head', 'cil_404_head', 3 );

/**
 * Homepage meta description and hero image preload from the prototype.
 */
function cil_front_page_head() {
	if ( ! is_front_page() ) {
		return;
	}

	$description = 'A dedicated circumcision clinic in Edgware, North-West London. Babies, children, teenagers and adult men, under local anaesthetic. From £200. CQC registered.';
	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";

	$poster_id = function_exists( 'cil_front_hero_poster_id' ) ? cil_front_hero_poster_id() : 0;
	if ( $poster_id && cil_attachment_is_image( $poster_id ) ) {
		$src = wp_get_attachment_image_src( $poster_id, 'cil-hero' );
		if ( $src ) {
			$srcset = wp_get_attachment_image_srcset( $poster_id, 'cil-hero' );
			printf(
				'<link rel="preload" as="image" href="%1$s"%2$s imagesizes="100vw" fetchpriority="high">' . "\n",
				esc_url( $src[0] ),
				$srcset ? ' imagesrcset="' . esc_attr( $srcset ) . '"' : ''
			);
		}
	} else {
		$img = cil_asset( 'images/' );
		printf(
			'<link rel="preload" as="image" href="%1$s" imagesrcset="%2$s" imagesizes="100vw" fetchpriority="high">' . "\n",
			esc_url( $img . 'hero-poster-1200.webp' ),
			esc_attr(
				$img . 'hero-poster-800.webp 800w, ' .
				$img . 'hero-poster-1200.webp 1200w, ' .
				$img . 'hero-poster-1800.webp 1800w'
			)
		);
	}
}
add_action( 'wp_head', 'cil_front_page_head', 3 );

/**
 * Inner-page meta description and JSON-LD from the prototype.
 */
function cil_inner_page_head() {
	if ( is_front_page() || ! is_singular() ) {
		return;
	}

	$description = get_post_meta( get_the_ID(), '_cil_meta_description', true );
	if ( is_string( $description ) && $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}

	$slug  = get_post_field( 'post_name', get_the_ID() );
	$pages = function_exists( 'cil_managed_pages' ) ? cil_managed_pages() : array();
	if ( empty( $pages[ $slug ] ) ) {
		return;
	}

	$page   = $pages[ $slug ];
	$origin = untrailingslashit( home_url() );
	$path   = ! empty( $page['path'] ) ? '/' . trim( $page['path'], '/' ) : '/' . $slug;
	$url    = $origin . $path . '/';

	$crumb_items = array(
		array(
			'@type'    => 'ListItem',
			'position' => 1,
			'name'     => 'Home',
			'item'     => $origin . '/',
		),
	);
	$position = 2;
	if ( ! empty( $page['crumbs'] ) && is_array( $page['crumbs'] ) ) {
		foreach ( $page['crumbs'] as $crumb ) {
			$item_url = ! empty( $crumb['href'] ) ? $crumb['href'] : $url;
			$crumb_items[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'name'     => $crumb['label'],
				'item'     => $item_url,
			);
			$position++;
		}
	} elseif ( ! empty( $page['name'] ) ) {
		$crumb_items[] = array(
			'@type'    => 'ListItem',
			'position' => 2,
			'name'     => 'Who we see',
			'item'     => $origin . '/babies/',
		);
		$crumb_items[] = array(
			'@type'    => 'ListItem',
			'position' => 3,
			'name'     => $page['name'],
			'item'     => $url,
		);
	}

	$graph = array(
		array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $crumb_items,
		),
	);

	if ( ! empty( $page['faqs'] ) ) {
		$graph[] = array(
			'@type'      => 'FAQPage',
			'mainEntity' => array_map(
				static function ( $faq ) {
					return array(
						'@type'          => 'Question',
						'name'           => $faq['q'],
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => wp_strip_all_tags( $faq['a'] ),
						),
					);
				},
				$page['faqs']
			),
		);
	}

	if ( ! empty( $page['schema'] ) && is_array( $page['schema'] ) ) {
		if ( isset( $page['schema']['@type'] ) ) {
			$schema        = $page['schema'];
			$schema['url'] = $url;
			if ( empty( $schema['name'] ) && ! empty( $page['name'] ) ) {
				$schema['name'] = $page['name'];
			}
			$graph[] = $schema;
		} else {
			foreach ( $page['schema'] as $schema_item ) {
				if ( is_array( $schema_item ) ) {
					$graph[] = $schema_item;
				}
			}
		}
	} elseif ( ! empty( $page['schema_name'] ) ) {
		$graph[] = array(
			'@type'         => 'MedicalProcedure',
			'name'          => $page['schema_name'],
			'url'           => $url,
			'procedureType' => 'https://schema.org/SurgicalProcedure',
			'followup'      => 'A phone call the day after, and free follow-up appointments until healing is complete.',
			'offers'        => array(
				'@type'           => 'Offer',
				'price'           => isset( $page['offer'] ) ? $page['offer'] : '',
				'priceCurrency'   => 'GBP',
				'priceValidUntil' => '2027-09-06',
				'availability'    => 'https://schema.org/InStock',
				'url'             => $origin . '/prices/',
			),
		);
	}

	echo '<script type="application/ld+json">' . wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
	) . '</script>' . "\n";
}
add_action( 'wp_head', 'cil_inner_page_head', 3 );

require get_template_directory() . '/inc/content.php';
require get_template_directory() . '/inc/media.php';
require get_template_directory() . '/inc/sections.php';
require get_template_directory() . '/inc/blocks.php';
require get_template_directory() . '/inc/pages/age-groups.php';
require get_template_directory() . '/inc/gutenberg-migrate.php';
require get_template_directory() . '/inc/pages/extra-pages.php';
require get_template_directory() . '/inc/pages/conditions-pages.php';
require get_template_directory() . '/inc/pages/procedures-pages.php';
require get_template_directory() . '/inc/pages/misc-pages.php';
require get_template_directory() . '/inc/pages/about-pages.php';
require get_template_directory() . '/inc/pages/visit-pages.php';
require get_template_directory() . '/inc/pages/legal-pages.php';
require get_template_directory() . '/inc/pages/home-page.php';
require get_template_directory() . '/inc/pages/aftercare-videos.php';
require get_template_directory() . '/inc/pages/about-clinic.php';
require get_template_directory() . '/inc/pages/diary-page.php';
require get_template_directory() . '/inc/home-reveal.php';
require get_template_directory() . '/inc/setup-pages.php';
require get_template_directory() . '/inc/content-sync.php';
require get_template_directory() . '/inc/content-sync-blog.php';

/**
 * Social profile links (Appearance > Customize > Social links). Shown in the
 * footer bottom bar on all screen sizes; a link is hidden while its URL is empty.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function cil_customize_social( $wp_customize ) {
	$wp_customize->add_section(
		'cil_social',
		array(
			'title'       => __( 'Social links', 'circumcision-london' ),
			'description' => __( 'Full profile URLs. Leave a field empty to hide that icon.', 'circumcision-london' ),
			'priority'    => 160,
		)
	);
	$fields = array(
		'cil_social_instagram' => array( __( 'Instagram profile URL', 'circumcision-london' ), 'https://www.instagram.com/circumcision.in.london/' ),
		'cil_social_youtube'   => array( __( 'YouTube channel URL', 'circumcision-london' ), '' ),
	);
	foreach ( $fields as $id => $field ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $field[1],
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $field[0],
				'section' => 'cil_social',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'cil_customize_social' );

/**
 * Optional Google Maps Embed API key (Appearance → Customize → Maps).
 *
 * When set, location embeds use Maps Embed API v1 (place). When empty, the
 * theme uses Google's standard maps embed URL for the clinic address.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function cil_customize_maps( $wp_customize ) {
	$wp_customize->add_section(
		'cil_maps',
		array(
			'title'       => __( 'Maps', 'circumcision-london' ),
			'description' => __( 'Optional Google Maps Embed API key for the homepage Location map. Create a key in Google Cloud with Maps Embed API enabled. Leave empty to use Google’s standard embed URL for the clinic address.', 'circumcision-london' ),
			'priority'    => 165,
		)
	);
	$wp_customize->add_setting(
		'cil_google_maps_embed_key',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'cil_google_maps_embed_key',
		array(
			'label'       => __( 'Google Maps Embed API key', 'circumcision-london' ),
			'description' => __( 'Used only in iframe embeds. Never scrapes map tiles.', 'circumcision-london' ),
			'section'     => 'cil_maps',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'cil_customize_maps' );
