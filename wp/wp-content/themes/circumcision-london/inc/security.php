<?php
/**
 * Front-end hardening: XML-RPC, REST user listing, author archives, headers.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Encode JSON-LD so `</script>` cannot break out of the containing tag.
 *
 * @param mixed $data Data to encode.
 * @return string
 */
function cil_json_ld_encode( $data ) {
	$flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
	if ( function_exists( 'wp_json_encode' ) ) {
		return wp_json_encode( $data, $flags );
	}
	return json_encode( $data, $flags );
}

/**
 * JSON_HEX_* flags used by cil_json_ld_encode(), for tests that cannot load WordPress.
 *
 * @return int
 */
function cil_json_ld_encode_flags() {
	return JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
}

add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', '__return_empty_array' );

/**
 * Gutenberg still needs /wp/v2/users for logged-in editors. Anonymous listing is blocked.
 *
 * @param array $endpoints Registered REST endpoints.
 * @return array
 */
function cil_restrict_users_rest( $endpoints ) {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}

	$routes = array(
		'/wp/v2/users',
		'/wp/v2/users/(?P<id>[\d]+)',
	);
	foreach ( $routes as $route ) {
		if ( empty( $endpoints[ $route ] ) || ! is_array( $endpoints[ $route ] ) ) {
			continue;
		}
		foreach ( $endpoints[ $route ] as $i => $endpoint ) {
			$endpoints[ $route ][ $i ]['permission_callback'] = static function () {
				return current_user_can( 'list_users' );
			};
		}
	}

	return $endpoints;
}
add_filter( 'rest_endpoints', 'cil_restrict_users_rest' );

/**
 * Redirect public author archives and ?author=N user discovery.
 */
function cil_block_author_enumeration() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}
	if ( is_user_logged_in() ) {
		return;
	}
	if ( isset( $_GET['author'] ) && '' !== (string) $_GET['author'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- public GET probe, redirect only.
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
	if ( is_author() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'cil_block_author_enumeration', 0 );

remove_action( 'wp_head', 'wp_generator' );

/**
 * Do not advertise the WordPress version in feeds or the generator meta.
 *
 * @return string
 */
function cil_hide_generator() {
	return '';
}
add_filter( 'the_generator', 'cil_hide_generator' );

/**
 * Security headers that hosting configs may also send. Harmless if duplicated.
 */
function cil_security_headers() {
	if ( headers_sent() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff', false );
	header( 'Referrer-Policy: strict-origin-when-cross-origin', false );
	header( 'X-Frame-Options: SAMEORIGIN', false );
	if ( ! cil_headers_list_has( 'Content-Security-Policy-Report-Only' ) ) {
		header( 'Content-Security-Policy-Report-Only: ' . cil_csp_report_only(), false );
	}
}
add_action( 'send_headers', 'cil_security_headers', 1 );

/**
 * @param string $name Header name.
 * @return bool
 */
function cil_headers_list_has( $name ) {
	$want = strtolower( $name );
	foreach ( headers_list() as $header ) {
		$parts = explode( ':', $header, 2 );
		if ( isset( $parts[0] ) && strtolower( trim( $parts[0] ) ) === $want ) {
			return true;
		}
	}
	return false;
}

/**
 * Report-only CSP covering current public embeds. Do not enforce until verified.
 *
 * @return string
 */
function cil_csp_report_only() {
	$directives = array(
		"default-src 'self'",
		"script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com",
		"style-src 'self' 'unsafe-inline'",
		"img-src 'self' data: blob: https://zveq6ift0ivhorct.public.blob.vercel-storage.com https://maps.gstatic.com https://maps.googleapis.com https://*.google.com https://*.googleapis.com https://secure.gravatar.com https://*.wp.com",
		"font-src 'self' data:",
		"frame-src 'self' https://beverley-clinic.au1.cliniko.com https://www.google.com https://maps.google.com",
		"media-src 'self' blob: https://zveq6ift0ivhorct.public.blob.vercel-storage.com",
		"connect-src 'self' https://www.google-analytics.com https://www.googletagmanager.com",
		"form-action 'self' mailto:",
		"base-uri 'self'",
		"object-src 'none'",
		"frame-ancestors 'self'",
	);
	return implode( '; ', $directives );
}
