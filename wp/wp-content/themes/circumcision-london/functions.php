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

define( 'CIL_VERSION', '0.12.0' );

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
		'hours_note' => 'Sundays, public holidays and bank holidays: phone lines are closed, but some pre-booked appointments still go ahead.',
		'cqc_url'    => 'https://www.cqc.org.uk/location/1-2192226003',
		'cqc_rating' => 'Good',
		'reviews'    => array(
			'rating'         => '4.9',
			'count'          => 2092,
			'count_display'  => '2,092',
			'verified'       => '2 September 2026',
			'read_url'       => 'https://www.google.com/maps/search/?api=1&query=Beverley+Clinic+Edgware',
		),
		'languages'  => 'English, Arabic, Urdu, Hindi, Punjabi and Turkish',
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
			'primary'        => __( 'Primary', 'circumcision-london' ),
			'footer-who'     => __( 'Footer: Who we see', 'circumcision-london' ),
			'footer-reasons' => __( 'Footer: Reasons', 'circumcision-london' ),
			'footer-legal'   => __( 'Footer: Legal', 'circumcision-london' ),
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
		'whatsapp' => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.2-1.7-.9-2-1-.3-.1-.5-.1-.7.1-.2.3-.7 1-.9 1.2-.2.2-.3.2-.6.1a8 8 0 0 1-2.4-1.5 9 9 0 0 1-1.6-2c-.2-.3 0-.5.1-.6l.5-.5.3-.5v-.5l-.9-2.2c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.2.2 2.1 3.3 5.1 4.5.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.3-.6-.4zM12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.4A10 10 0 1 0 12 2zm0 18.3a8.3 8.3 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.3 8.3 0 1 1 12 20.3z"/></svg>',
		'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>',
		'arrow-up' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5M6 11l6-6 6 6"/></svg>',
		'shield'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2 4 5.5v6c0 5 3.4 9.3 8 10.5 4.6-1.2 8-5.5 8-10.5v-6z"/><path d="m9 12 2 2 4-4"/></svg>',
		'star'     => '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.9 6.3 6.8.8-5 4.7 1.3 6.8L12 17.3 6 20.6l1.3-6.8-5-4.7 6.8-.8z"/></svg>',
		'pin'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>',
		'clock'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg>',
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
					'note'  => __( 'From £280', 'circumcision-london' ),
				),
				array(
					'label' => __( 'Adult men', 'circumcision-london' ),
					'url'   => home_url( '/adults/' ),
					'note'  => __( 'From £680', 'circumcision-london' ),
				),
				array(
					'label' => __( 'Religious and cultural', 'circumcision-london' ),
					'url'   => home_url( '/religious/' ),
				),
				array(
					'label' => __( 'Re-circumcision', 'circumcision-london' ),
					'url'   => home_url( '/re-circumcision/' ),
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
					'label' => __( 'Buried penis', 'circumcision-london' ),
					'url'   => home_url( '/buried-penis/' ),
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
			'label' => __( 'Aftercare', 'circumcision-london' ),
			'url'   => home_url( '/aftercare/' ),
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
			'label' => __( 'Visit us', 'circumcision-london' ),
			'url'   => home_url( '/contact/' ),
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
		array( 'label' => __( 'Babies and infants', 'circumcision-london' ), 'url' => home_url( '/babies/' ) ),
		array( 'label' => __( 'Boys and teenagers', 'circumcision-london' ), 'url' => home_url( '/children/' ) ),
		array( 'label' => __( 'Adult men', 'circumcision-london' ), 'url' => home_url( '/adults/' ) ),
		array( 'label' => __( 'Religious and cultural', 'circumcision-london' ), 'url' => home_url( '/religious/' ) ),
		array( 'label' => __( 'Prices', 'circumcision-london' ), 'url' => home_url( '/prices/' ) ),
		array( 'label' => __( 'Testimonials', 'circumcision-london' ), 'url' => home_url( '/testimonials/' ) ),
		array( 'label' => __( 'Aftercare', 'circumcision-london' ), 'url' => home_url( '/aftercare/' ) ),
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
		array( 'label' => __( 'Frenuloplasty', 'circumcision-london' ), 'url' => home_url( '/procedures/frenuloplasty/' ) ),
		array( 'label' => __( 'Preputioplasty', 'circumcision-london' ), 'url' => home_url( '/procedures/preputioplasty/' ) ),
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
			'blurb' => __( 'The ring method, Plastibell or Circumplast, under local anaesthetic. About ten minutes, no stitches, and you take him home straight afterwards.', 'circumcision-london' ),
		),
		array(
			'href'  => home_url( '/children/' ),
			'title' => __( 'Children and teenagers', 'circumcision-london' ),
			'age'   => __( 'One to seventeen years', 'circumcision-london' ),
			'price' => __( 'From £280', 'circumcision-london' ),
			'cta'   => __( 'Circumcision for boys, from £280', 'circumcision-london' ),
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
			$node = array(
				'label' => $item->title,
				'url'   => $item->url,
			);
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
 * Logo markup: Customizer, then the prototype PNG in the theme, then text.
 *
 * @param array<string, mixed> $clinic Clinic details.
 * @return string
 */
function cil_logo_html( $clinic ) {
	$attrs = array(
		'alt'           => $clinic['legal_name'],
		'width'         => 440,
		'height'        => 128,
		'fetchpriority' => 'high',
	);

	if ( has_custom_logo() ) {
		return wp_get_attachment_image( (int) get_theme_mod( 'custom_logo' ), 'full', false, $attrs );
	}

	$theme_logo = get_template_directory() . '/assets/images/logo.png';
	if ( file_exists( $theme_logo ) ) {
		return sprintf(
			'<img src="%1$s" alt="%2$s" width="440" height="128" fetchpriority="high">',
			esc_url( get_template_directory_uri() . '/assets/images/logo.png' ),
			esc_attr( $clinic['legal_name'] )
		);
	}

	return '<span class="mark">' . esc_html( $clinic['legal_name'] ) . '</span>';
}

/**
 * Site wordmark. Prototype stacked lockup: logo plus "Circumcision in London".
 *
 * @param string $extra_class Optional extra class on the anchor.
 */
function cil_wordmark( $extra_class = '' ) {
	$clinic = cil_clinic();
	$class  = 'brand' . ( $extra_class ? ' ' . $extra_class : '' );
	$label  = sprintf(
		/* translators: %s: clinic legal name */
		__( 'Circumcision in London at %s, home page', 'circumcision-london' ),
		$clinic['legal_name']
	);
	?>
	<a class="<?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $label ); ?>">
		<?php echo cil_logo_html( $clinic ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts. ?>
		<span class="sub"><?php echo esc_html( $clinic['name'] ); ?></span>
	</a>
	<?php
}

/**
 * Desktop primary navigation markup.
 */
function cil_primary_nav() {
	$items = cil_menu_tree( 'primary', cil_default_nav() );
	echo '<ul class="nav">';
	foreach ( $items as $item ) {
		$has_children = ! empty( $item['children'] );
		$li_class     = 'nav-item' . ( $has_children ? ' has-menu' : '' );
		/* Prototype: dropdown parents link to the first child, with no aria-current. */
		$url = $has_children ? $item['children'][0]['url'] : $item['url'];
		$cur = $has_children ? '' : cil_current_attr( $url );
		echo '<li class="' . esc_attr( $li_class ) . '">';
		echo '<a class="nav-link" href="' . esc_url( $url ) . '"' . $cur . '>' . esc_html( $item['label'] ) . '</a>';
		if ( $has_children ) {
			echo '<ul class="submenu">';
			foreach ( $item['children'] as $child ) {
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
	$items = cil_menu_tree( 'primary', cil_default_nav() );
	foreach ( $items as $item ) {
		if ( empty( $item['children'] ) ) {
			echo '<a href="' . esc_url( $item['url'] ) . '"' . cil_current_attr( $item['url'] ) . '>' . esc_html( $item['label'] ) . '</a>';
			continue;
		}
		echo '<span class="caps group-label">' . esc_html( $item['label'] ) . '</span>';
		echo '<ul class="sub">';
		foreach ( $item['children'] as $child ) {
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
 * 404s stay out of the index, matching the prototype noindex flag.
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
	return $robots;
}
add_filter( 'wp_robots', 'cil_robots' );

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
require get_template_directory() . '/inc/sections.php';
require get_template_directory() . '/inc/blocks.php';
require get_template_directory() . '/inc/pages/age-groups.php';
require get_template_directory() . '/inc/pages/extra-pages.php';
require get_template_directory() . '/inc/pages/conditions-pages.php';
require get_template_directory() . '/inc/pages/procedures-pages.php';
require get_template_directory() . '/inc/pages/misc-pages.php';
require get_template_directory() . '/inc/pages/about-pages.php';
require get_template_directory() . '/inc/pages/visit-pages.php';
require get_template_directory() . '/inc/pages/legal-pages.php';
require get_template_directory() . '/inc/pages/home-page.php';
require get_template_directory() . '/inc/setup-pages.php';
