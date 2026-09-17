<?php
/**
 * Create missing core service pages from prototype content.
 *
 * Existing pages are never overwritten. Gutenberg edits, slugs, IDs and
 * seeded metadata persist across theme updates and version bumps.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Seed snapshot written onto newly created service pages only.
 *
 * This identifies which catalog version created a page. It is not a rewrite
 * trigger: bumping it must not replace existing Gutenberg content.
 */
define( 'CIL_SERVICE_PAGES_VERSION', '0.12.0' );

/**
 * Pretty permalinks so /babies /children /adults resolve.
 */
function cil_ensure_pretty_permalinks() {
	if ( get_option( 'permalink_structure' ) ) {
		return;
	}
	update_option( 'permalink_structure', '/%postname%/' );
	flush_rewrite_rules( false );
}

/**
 * All theme-managed prototype pages.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_managed_pages() {
	$pages = cil_age_pages();
	if ( function_exists( 'cil_extra_pages' ) ) {
		$pages = array_merge( $pages, cil_extra_pages() );
	}
	if ( function_exists( 'cil_condition_pages' ) ) {
		$pages = array_merge( $pages, cil_condition_pages() );
	}
	if ( function_exists( 'cil_procedure_pages' ) ) {
		$pages = array_merge( $pages, cil_procedure_pages() );
	}
	if ( function_exists( 'cil_misc_pages' ) ) {
		$pages = array_merge( $pages, cil_misc_pages() );
	}
	if ( function_exists( 'cil_about_pages' ) ) {
		$pages = array_merge( $pages, cil_about_pages() );
	}
	if ( function_exists( 'cil_visit_pages' ) ) {
		$pages = array_merge( $pages, cil_visit_pages() );
	}
	if ( function_exists( 'cil_legal_pages' ) ) {
		$pages = array_merge( $pages, cil_legal_pages() );
	}
	return $pages;
}

/**
 * Block markup for a managed page slug.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cil_managed_page_blocks( $slug ) {
	if ( isset( cil_age_pages()[ $slug ] ) ) {
		return cil_age_page_blocks( $slug );
	}
	if ( function_exists( 'cil_condition_pages' ) && isset( cil_condition_pages()[ $slug ] ) ) {
		return cil_condition_page_blocks( $slug );
	}
	if ( function_exists( 'cil_procedure_pages' ) && isset( cil_procedure_pages()[ $slug ] ) ) {
		return cil_procedure_page_blocks( $slug );
	}
	if ( function_exists( 'cil_misc_pages' ) && isset( cil_misc_pages()[ $slug ] ) ) {
		return cil_misc_page_blocks( $slug );
	}
	if ( function_exists( 'cil_about_pages' ) && isset( cil_about_pages()[ $slug ] ) ) {
		return cil_about_page_blocks( $slug );
	}
	if ( function_exists( 'cil_visit_pages' ) && isset( cil_visit_pages()[ $slug ] ) ) {
		return cil_visit_page_blocks( $slug );
	}
	if ( function_exists( 'cil_legal_pages' ) && isset( cil_legal_pages()[ $slug ] ) ) {
		return cil_legal_page_blocks( $slug );
	}
	if ( function_exists( 'cil_extra_page_blocks' ) ) {
		return cil_extra_page_blocks( $slug );
	}
	return '';
}

/**
 * Lookup path for a managed page (nested paths use the prototype URL).
 *
 * @param string               $slug Page slug.
 * @param array<string, mixed> $page Page data.
 * @return string
 */
function cil_managed_page_path( $slug, $page ) {
	if ( ! empty( $page['path'] ) ) {
		return trim( $page['path'], '/' );
	}
	return $slug;
}

/**
 * Ensure a private parent page exists so children can live at nested URLs.
 *
 * The parent is not a public landing page. Prototype has no /conditions or /procedures index.
 *
 * @param string $slug  Parent slug.
 * @param string $title Admin title.
 * @return int Parent page ID, or 0 on failure.
 */
function cil_ensure_parent_page( $slug, $title ) {
	$existing = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $existing ) {
		return (int) $existing->ID;
	}

	kses_remove_filters();
	$id = wp_insert_post(
		wp_slash(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'private',
				'post_type'    => 'page',
				'post_content' => '',
			)
		),
		true
	);
	kses_init_filters();

	if ( is_wp_error( $id ) || ! $id ) {
		return 0;
	}

	update_post_meta( (int) $id, '_cil_managed', '1' );
	update_post_meta( (int) $id, '_cil_parent_placeholder', '1' );

	return (int) $id;
}

/**
 * Insert missing managed service pages. Never rewrite an existing page.
 *
 * A) Missing path: create the page, mark it managed, write seed metadata.
 * B) Existing path: leave ID, slug, content, Gutenberg blocks and meta alone,
 *    even if CIL_SERVICE_PAGES_VERSION or cil_service_pages_version differ.
 *
 * Opt-in exception: filter `cil_reseed_existing_page` returning true for a
 * given slug is the only way to replace an existing page from seed data.
 */
function cil_ensure_service_pages() {
	if ( wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return;
	}

	cil_ensure_pretty_permalinks();

	$seed_version = CIL_SERVICE_PAGES_VERSION;
	$parent_ids   = array();
	$need_flush   = false;

	foreach ( cil_managed_pages() as $slug => $page ) {
		if ( empty( $page['parent'] ) ) {
			continue;
		}
		$parent_slug = $page['parent'];
		if ( isset( $parent_ids[ $parent_slug ] ) ) {
			continue;
		}
		$parent_title = ! empty( $page['parent_title'] ) ? $page['parent_title'] : 'Reasons';
		$had_parent   = (bool) get_page_by_path( $parent_slug, OBJECT, 'page' );
		$parent_ids[ $parent_slug ] = cil_ensure_parent_page( $parent_slug, $parent_title );
		if ( ! $had_parent && $parent_ids[ $parent_slug ] ) {
			$need_flush = true;
		}
	}

	foreach ( cil_managed_pages() as $slug => $page ) {
		$lookup   = cil_managed_page_path( $slug, $page );
		$existing = get_page_by_path( $lookup, OBJECT, 'page' );
		$parent   = 0;
		if ( ! empty( $page['parent'] ) && ! empty( $parent_ids[ $page['parent'] ] ) ) {
			$parent = (int) $parent_ids[ $page['parent'] ];
		}

		if ( $existing ) {
			/**
			 * Whether to replace one existing service page from theme seed data.
			 *
			 * Default false. Returning true restores the old overwrite path for
			 * that slug only (ID is preserved; content, title, excerpt and seed
			 * meta are replaced). Do not key this off the theme version.
			 *
			 * @param bool    $reseed   Whether to reseed this page.
			 * @param string  $slug     Catalog slug.
			 * @param WP_Post $existing Existing page.
			 */
			$reseed = apply_filters( 'cil_reseed_existing_page', false, $slug, $existing );
			if ( ! $reseed ) {
				continue;
			}
		}

		if ( ! empty( $page['parent'] ) && ! $parent ) {
			continue;
		}

		$content = cil_managed_page_blocks( $slug );
		$payload = array(
			'post_title'   => $page['h1'],
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_excerpt' => $page['description'],
			'post_content' => $content,
		);
		if ( $parent ) {
			$payload['post_parent'] = $parent;
		}

		kses_remove_filters();

		if ( ! $existing ) {
			$id = wp_insert_post( wp_slash( $payload ), true );
			kses_init_filters();
			if ( is_wp_error( $id ) || ! $id ) {
				continue;
			}
			cil_mark_managed_page( (int) $id, $page, $seed_version );
			$need_flush = true;
			continue;
		}

		$payload['ID'] = $existing->ID;
		wp_update_post( wp_slash( $payload ) );
		kses_init_filters();
		cil_mark_managed_page( (int) $existing->ID, $page, $seed_version );
	}

	update_option( 'cil_service_pages_version', $seed_version );

	if ( $need_flush ) {
		flush_rewrite_rules( false );
	}
}

/**
 * Store SEO meta used by the document-title and description filters.
 *
 * @param int                  $id      Page ID.
 * @param array<string, mixed> $page    Page data.
 * @param string               $version Seed version.
 */
function cil_mark_managed_page( $id, $page, $version ) {
	update_post_meta( $id, '_cil_managed', '1' );
	update_post_meta( $id, '_cil_source_version', $version );
	update_post_meta( $id, '_cil_document_title', $page['title'] );
	update_post_meta( $id, '_cil_meta_description', $page['description'] );
	if ( ! empty( $page['schema_name'] ) ) {
		update_post_meta( $id, '_cil_schema_name', $page['schema_name'] );
	}
	if ( ! empty( $page['offer'] ) ) {
		update_post_meta( $id, '_cil_offer_price', $page['offer'] );
	}
}

add_action( 'init', 'cil_ensure_service_pages', 40 );
