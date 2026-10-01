<?php
/**
 * Homepage-only scroll-reveal wiring.
 *
 * Reuses the theme's existing [data-reveal] + IntersectionObserver system
 * (assets/js/theme.js + base.css). Does not change Gutenberg content; only
 * adds presentation attributes when rendering the front page.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inject data-reveal attributes onto an opening HTML tag if missing.
 *
 * @param string $html  Block HTML.
 * @param int    $delay Reveal delay in ms.
 * @return string
 */
function cil_home_inject_reveal_attr( $html, $delay = 0 ) {
	if ( ! is_string( $html ) || '' === $html ) {
		return $html;
	}
	if ( false !== strpos( $html, 'data-reveal' ) ) {
		return $html;
	}

	$attr = ' data-reveal';
	if ( $delay > 0 ) {
		$attr .= ' data-reveal-delay="' . (int) $delay . '"';
	}

	$updated = preg_replace( '/^<([a-zA-Z0-9]+)\b/', '<$1' . $attr, $html, 1 );
	return is_string( $updated ) ? $updated : $html;
}

/**
 * Whether a block class list contains a token.
 *
 * @param array<string, mixed> $attrs Block attributes.
 * @param string               $token Class token.
 * @return bool
 */
function cil_home_block_has_class( $attrs, $token ) {
	$class = '';
	if ( ! empty( $attrs['className'] ) && is_string( $attrs['className'] ) ) {
		$class = $attrs['className'];
	}
	if ( ! empty( $attrs['class'] ) && is_string( $attrs['class'] ) ) {
		$class .= ' ' . $attrs['class'];
	}
	return (bool) preg_match( '/(^|\s)' . preg_quote( $token, '/' ) . '(\s|$)/', $class );
}

/**
 * Front-page only: attach reveal attributes to new homepage content wrappers.
 *
 * @param string               $block_content Rendered HTML.
 * @param array<string, mixed> $block         Parsed block.
 * @return string
 */
function cil_home_reveal_render_block( $block_content, $block ) {
	if ( is_admin() || wp_is_json_request() || ! is_front_page() ) {
		return $block_content;
	}
	if ( empty( $block['blockName'] ) || ! is_string( $block_content ) || '' === $block_content ) {
		return $block_content;
	}

	$name  = $block['blockName'];
	$attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();

	// Supporting text under medical / methods headings.
	if ( 'core/group' === $name && cil_home_block_has_class( $attrs, 'cil-section-lede' ) ) {
		return cil_home_inject_reveal_attr( $block_content, 100 );
	}

	// Clinic introduction prose wrapper.
	if ( 'core/group' === $name && (
		cil_home_block_has_class( $attrs, 'cil-home-intro' )
		|| false !== strpos( $block_content, 'cil-home-intro' )
	) ) {
		return cil_home_inject_reveal_attr( $block_content, 0 );
	}

	// Clinic introduction checklist.
	if ( 'core/list' === $name && (
		cil_home_block_has_class( $attrs, 'cil-checklist' )
		|| false !== strpos( $block_content, 'cil-checklist' )
	) ) {
		return cil_home_inject_reveal_attr( $block_content, 120 );
	}

	return $block_content;
}
add_filter( 'render_block', 'cil_home_reveal_render_block', 20, 2 );

/**
 * Fallback: ensure the clinic-introduction group receives data-reveal on the front page
 * even if core/group save HTML omitted the custom class token.
 *
 * @param string $content Post content HTML.
 * @return string
 */
function cil_home_reveal_the_content( $content ) {
	if ( is_admin() || ! is_front_page() || ! is_string( $content ) || '' === $content ) {
		return $content;
	}
	if ( false === strpos( $content, 'id="clinic-introduction"' ) ) {
		return $content;
	}
	// Only the first group directly under clinic-introduction wrap.
	$updated = preg_replace(
		'/(id="clinic-introduction"[^>]*>\s*<div class="wrap">\s*<div)(\s+)(?![^>]*data-reveal)/',
		'$1 data-reveal$2',
		$content,
		1
	);
	return is_string( $updated ) ? $updated : $content;
}
add_filter( 'the_content', 'cil_home_reveal_the_content', 20 );
