<?php
/**
 * Generic WordPress-to-WordPress page content release.
 *
 * Git ships fixtures. An explicit admin apply writes post_content only.
 * Destination pages are resolved by slug/path, never by ID.
 * This does not run on deploy and does not create pages.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Directory of committed content fixtures inside the theme.
 *
 * @return string
 */
function cil_content_fixtures_dir() {
	return get_template_directory() . '/content-fixtures';
}

/**
 * Manifest path.
 *
 * @return string
 */
function cil_content_manifest_path() {
	return cil_content_fixtures_dir() . '/manifest.json';
}

/**
 * Fixture path for a slug.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cil_content_fixture_path( $slug ) {
	$slug = sanitize_title( $slug );
	return cil_content_fixtures_dir() . '/pages/' . $slug . '.json';
}

/**
 * Decode a JSON file. Rejects UTF-8 BOM and invalid JSON.
 *
 * @param string $path File path.
 * @return array|WP_Error
 */
function cil_content_read_json_file( $path ) {
	if ( ! is_file( $path ) ) {
		return new WP_Error( 'cil_content_missing_file', 'File not found: ' . $path );
	}
	$raw = file_get_contents( $path );
	if ( false === $raw ) {
		return new WP_Error( 'cil_content_unreadable', 'Could not read: ' . $path );
	}
	if ( strncmp( $raw, "\xEF\xBB\xBF", 3 ) === 0 ) {
		return new WP_Error( 'cil_content_bom', 'JSON file has a UTF-8 BOM: ' . $path );
	}
	$decoded = json_decode( $raw, true );
	if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $decoded ) ) {
		return new WP_Error( 'cil_content_json', 'Invalid JSON in ' . $path . ': ' . json_last_error_msg() );
	}
	return $decoded;
}

/**
 * Load the release manifest.
 *
 * @return array|WP_Error
 */
function cil_content_load_manifest() {
	$data = cil_content_read_json_file( cil_content_manifest_path() );
	if ( is_wp_error( $data ) ) {
		return $data;
	}
	if ( empty( $data['pages'] ) || ! is_array( $data['pages'] ) ) {
		return new WP_Error( 'cil_content_manifest', 'manifest.json must contain a pages array.' );
	}
	$data['pages']        = array_values( array_map( 'sanitize_title', $data['pages'] ) );
	$data['replace_from'] = isset( $data['replace_from'] ) ? untrailingslashit( (string) $data['replace_from'] ) : '';
	$data['replace_to']   = isset( $data['replace_to'] ) ? untrailingslashit( (string) $data['replace_to'] ) : '';
	return $data;
}

/**
 * Load one page fixture.
 *
 * @param string $slug Page slug.
 * @return array|WP_Error
 */
function cil_content_load_fixture( $slug ) {
	$slug = sanitize_title( $slug );
	$data = cil_content_read_json_file( cil_content_fixture_path( $slug ) );
	if ( is_wp_error( $data ) ) {
		return $data;
	}
	if ( empty( $data['slug'] ) || sanitize_title( $data['slug'] ) !== $slug ) {
		return new WP_Error( 'cil_content_slug_mismatch', 'Fixture slug does not match filename for ' . $slug );
	}
	if ( ! isset( $data['content'] ) || ! is_string( $data['content'] ) || '' === $data['content'] ) {
		return new WP_Error( 'cil_content_empty', 'Fixture has no content string for ' . $slug );
	}
	if ( empty( $data['path'] ) ) {
		$data['path'] = '/' . $slug . '/';
	}
	if ( empty( $data['source_sha256'] ) || ! hash_equals( $data['source_sha256'], hash( 'sha256', $data['content'] ) ) ) {
		return new WP_Error( 'cil_content_hash', 'Fixture source_sha256 does not match content for ' . $slug );
	}
	return $data;
}

/**
 * Rewrite absolute source-site URLs to the destination site.
 *
 * @param string $content     Raw post_content.
 * @param string $replace_from Source home URL.
 * @param string $replace_to   Destination home URL.
 * @return string
 */
function cil_content_rewrite_site_urls( $content, $replace_from, $replace_to ) {
	$replace_from = untrailingslashit( $replace_from );
	$replace_to   = untrailingslashit( $replace_to );
	$pairs          = array(
		$replace_from => $replace_to,
	);
	if ( 0 === strpos( $replace_from, 'http://' ) ) {
		$pairs[ 'https://' . substr( $replace_from, 7 ) ] = $replace_to;
	} elseif ( 0 === strpos( $replace_from, 'https://' ) ) {
		$pairs[ 'http://' . substr( $replace_from, 8 ) ] = $replace_to;
	}
	return str_replace( array_keys( $pairs ), array_values( $pairs ), $content );
}

/**
 * Fail if any .local host remains in content.
 *
 * @param string $content Content.
 * @return true|WP_Error
 */
function cil_content_assert_no_local_host( $content ) {
	if ( preg_match( '/https?:\/\/[^\/\s"\']+\.local(?:[:\/"\']|$)/i', $content ) ) {
		return new WP_Error( 'cil_content_local_url', 'Content still contains a .local URL.' );
	}
	if ( false !== stripos( $content, '.local' ) && preg_match( '/[a-z0-9.-]+\.local/i', $content ) ) {
		return new WP_Error( 'cil_content_local_url', 'Content still contains a .local host.' );
	}
	return true;
}

/**
 * Resolve exactly one published page by path/slug.
 *
 * @param string $path Path or slug, e.g. book or /book/.
 * @return WP_Post|WP_Error
 */
function cil_content_resolve_published_page( $path ) {
	$path = trim( (string) $path, '/' );
	if ( '' === $path ) {
		return new WP_Error( 'cil_content_path', 'Missing page path.' );
	}

	$page = get_page_by_path( $path, OBJECT, 'page' );
	if ( ! $page ) {
		return new WP_Error(
			'cil_content_resolve',
			sprintf( 'Path "%s" must resolve to exactly one published page (found 0).', $path )
		);
	}

	if ( 'page' !== $page->post_type || 'publish' !== $page->post_status ) {
		return new WP_Error( 'cil_content_resolve', 'Resolved post is not a published page.' );
	}

	$dupes = get_posts(
		array(
			'post_type'              => 'page',
			'post_status'            => 'publish',
			'name'                   => $page->post_name,
			'post_parent'            => (int) $page->post_parent,
			'numberposts'            => 2,
			'suppress_filters'       => true,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	if ( count( $dupes ) > 1 ) {
		return new WP_Error(
			'cil_content_resolve',
			sprintf( 'Path "%s" resolved to more than one published page.', $path )
		);
	}

	return $page;
}

/**
 * Number of stored revisions for a page.
 *
 * @param WP_Post|int $post Page.
 * @return int
 */
function cil_content_revision_count( $post ) {
	$ids = wp_get_post_revisions(
		$post,
		array(
			'check_enabled'  => true,
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	);
	return is_array( $ids ) ? count( $ids ) : 0;
}

/**
 * Snapshot of fields that content sync must not change.
 *
 * @param WP_Post $post Page.
 * @return array<string, mixed>
 */
function cil_content_page_guards( $post ) {
	return array(
		'title'          => $post->post_title,
		'slug'           => $post->post_name,
		'status'         => $post->post_status,
		'author'         => (int) $post->post_author,
		'featured_media' => (int) get_post_thumbnail_id( $post ),
		'template'       => (string) get_page_template_slug( $post ),
	);
}

/**
 * Host of a URL, lowercased.
 *
 * @param string $url URL.
 * @return string
 */
function cil_content_url_host( $url ) {
	$host = wp_parse_url( $url, PHP_URL_HOST );
	return is_string( $host ) ? strtolower( $host ) : '';
}

/**
 * Whether the current site host matches the fixture destination host.
 *
 * @param array<string, mixed> $fixture Fixture.
 * @return bool
 */
function cil_content_current_host_matches_fixture( $fixture ) {
	$to = isset( $fixture['replace_to'] ) ? $fixture['replace_to'] : '';
	return cil_content_url_host( home_url() ) === cil_content_url_host( $to );
}

/**
 * Inspect one manifest slug against this site (dry-run data).
 *
 * @param string $slug Slug.
 * @return array<string, mixed>
 */
function cil_content_inspect_slug( $slug ) {
	$slug   = sanitize_title( $slug );
	$report = array(
		'slug'            => $slug,
		'ok'              => false,
		'can_apply'       => false,
		'error'           => '',
		'local_id'        => null,
		'destination_id'  => null,
		'guards'          => array(),
		'fixture_sha256'  => '',
		'destination_sha256' => '',
		'hash_match'      => false,
		'host_match'      => false,
		'bytes'           => 0,
		'revisions'       => 0,
	);

	$fixture = cil_content_load_fixture( $slug );
	if ( is_wp_error( $fixture ) ) {
		$report['error'] = $fixture->get_error_message();
		return $report;
	}

	$report['local_id']       = isset( $fixture['local_id'] ) ? (int) $fixture['local_id'] : 0;
	$report['fixture_sha256'] = $fixture['source_sha256'];
	$report['bytes']          = strlen( $fixture['content'] );
	$report['host_match']     = cil_content_current_host_matches_fixture( $fixture );

	$no_local = cil_content_assert_no_local_host( $fixture['content'] );
	if ( is_wp_error( $no_local ) ) {
		$report['error'] = $no_local->get_error_message();
		return $report;
	}

	$page = cil_content_resolve_published_page( $fixture['path'] );
	if ( is_wp_error( $page ) ) {
		$report['error'] = $page->get_error_message();
		return $report;
	}

	$report['destination_id']     = (int) $page->ID;
	$report['destination_sha256'] = hash( 'sha256', $page->post_content );
	$report['hash_match']         = hash_equals( $report['fixture_sha256'], $report['destination_sha256'] );
	$report['guards']             = cil_content_page_guards( $page );
	$report['revisions']          = cil_content_revision_count( $page );
	$report['ok']                 = true;
	$report['can_apply']          = $report['host_match'] && ! $report['hash_match'];

	if ( ! $report['host_match'] ) {
		$report['error'] = sprintf(
			'This site host (%s) does not match fixture replace_to host (%s). Dry-run is allowed; apply is blocked here.',
			cil_content_url_host( home_url() ),
			cil_content_url_host( $fixture['replace_to'] )
		);
	}

	return $report;
}

/**
 * Apply one fixture: post_content only.
 *
 * @param string $slug Slug.
 * @return array<string, mixed>|WP_Error
 */
function cil_content_apply_slug( $slug ) {
	$slug     = sanitize_title( $slug );
	$manifest = cil_content_load_manifest();
	if ( is_wp_error( $manifest ) ) {
		return $manifest;
	}
	if ( ! in_array( $slug, $manifest['pages'], true ) ) {
		return new WP_Error( 'cil_content_not_listed', 'Slug is not in the content-fixtures manifest: ' . $slug );
	}

	$inspect = cil_content_inspect_slug( $slug );
	if ( ! $inspect['ok'] && ! $inspect['host_match'] ) {
		return new WP_Error( 'cil_content_host', $inspect['error'] );
	}
	if ( ! $inspect['ok'] ) {
		return new WP_Error( 'cil_content_inspect', $inspect['error'] );
	}
	if ( ! $inspect['host_match'] ) {
		return new WP_Error( 'cil_content_host', $inspect['error'] );
	}

	$fixture = cil_content_load_fixture( $slug );
	if ( is_wp_error( $fixture ) ) {
		return $fixture;
	}

	$page = cil_content_resolve_published_page( $fixture['path'] );
	if ( is_wp_error( $page ) ) {
		return $page;
	}

	$before_guards = cil_content_page_guards( $page );
	$before_seo    = array(
		'_cil_document_title'   => get_post_meta( $page->ID, '_cil_document_title', true ),
		'_cil_meta_description' => get_post_meta( $page->ID, '_cil_meta_description', true ),
	);
	$before_rev    = cil_content_revision_count( $page );

	if ( hash_equals( hash( 'sha256', $page->post_content ), $fixture['source_sha256'] ) ) {
		return array(
			'slug'     => $slug,
			'id'       => (int) $page->ID,
			'changed'  => false,
			'message'  => 'Destination already matches the fixture. No write performed.',
			'inspect'  => cil_content_inspect_slug( $slug ),
		);
	}

	/*
	 * wp_update_post() expects slashed data and then wp_unslash()s it.
	 * Gutenberg stores HTML in block JSON as \u003c / \u0022. Without wp_slash(),
	 * stripslashes() turns those into literal "u003c" and the frontend prints garbage.
	 * kses_remove_filters() does not remove convert_invalid_entities or balanceTags.
	 */
	kses_remove_filters();
	remove_filter( 'content_save_pre', 'convert_invalid_entities' );
	remove_filter( 'content_save_pre', 'balanceTags', 50 );
	$result = wp_update_post(
		wp_slash(
			array(
				'ID'           => (int) $page->ID,
				'post_content' => $fixture['content'],
			)
		),
		true
	);
	add_filter( 'content_save_pre', 'convert_invalid_entities' );
	add_filter( 'content_save_pre', 'balanceTags', 50 );
	kses_init_filters();

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	clean_post_cache( $page->ID );
	$after = get_post( $page->ID );
	if ( ! $after ) {
		return new WP_Error( 'cil_content_missing_after', 'Page disappeared after update.' );
	}

	$after_guards = cil_content_page_guards( $after );
	foreach ( $before_guards as $key => $value ) {
		if ( $after_guards[ $key ] !== $value ) {
			return new WP_Error(
				'cil_content_guard_failed',
				sprintf( 'Guard field "%s" changed during apply. Expected %s, got %s.', $key, wp_json_encode( $value ), wp_json_encode( $after_guards[ $key ] ) )
			);
		}
	}

	foreach ( $before_seo as $meta_key => $value ) {
		if ( get_post_meta( $after->ID, $meta_key, true ) !== $value ) {
			return new WP_Error( 'cil_content_seo_changed', 'SEO metadata changed during apply: ' . $meta_key );
		}
	}

	$after_hash = hash( 'sha256', $after->post_content );
	if ( ! hash_equals( $fixture['source_sha256'], $after_hash ) ) {
		return new WP_Error( 'cil_content_verify', 'Destination post_content hash does not match the fixture after apply.' );
	}

	update_post_meta( $after->ID, '_cil_content_fixture_sha256', $fixture['source_sha256'] );
	update_post_meta( $after->ID, '_cil_content_fixture_applied_at', gmdate( 'c' ) );

	$after_rev = cil_content_revision_count( $after );

	return array(
		'slug'            => $slug,
		'id'              => (int) $after->ID,
		'changed'         => true,
		'fixture_sha256'  => $fixture['source_sha256'],
		'destination_sha256' => $after_hash,
		'revisions_before'=> $before_rev,
		'revisions_after' => $after_rev,
		'guards'          => $after_guards,
		'inspect'         => cil_content_inspect_slug( $slug ),
	);
}

/**
 * Write a fixture JSON file without a BOM.
 *
 * @param string               $slug Slug.
 * @param array<string, mixed> $data Fixture.
 * @return string|WP_Error Path or error.
 */
function cil_content_write_fixture( $slug, $data ) {
	$slug = sanitize_title( $slug );
	$dir  = cil_content_fixtures_dir() . '/pages';
	if ( ! is_dir( $dir ) && ! wp_mkdir_p( $dir ) ) {
		return new WP_Error( 'cil_content_mkdir', 'Could not create ' . $dir );
	}
	$json = wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
	if ( false === $json ) {
		return new WP_Error( 'cil_content_encode', 'JSON encode failed.' );
	}
	$path = cil_content_fixture_path( $slug );
	if ( false === file_put_contents( $path, $json . "\n" ) ) {
		return new WP_Error( 'cil_content_write', 'Could not write ' . $path );
	}
	$written = file_get_contents( $path );
	if ( is_string( $written ) && strncmp( $written, "\xEF\xBB\xBF", 3 ) === 0 ) {
		return new WP_Error( 'cil_content_bom', 'Wrote a BOM unexpectedly: ' . $path );
	}
	return $path;
}

/**
 * Build a fixture array from the live local page.
 *
 * @param string $slug         Slug.
 * @param string $replace_from Source home.
 * @param string $replace_to   Destination home.
 * @return array<string, mixed>|WP_Error
 */
function cil_content_export_slug( $slug, $replace_from, $replace_to ) {
	$slug  = sanitize_title( $slug );
	$page  = cil_content_resolve_published_page( $slug );
	if ( is_wp_error( $page ) ) {
		return $page;
	}

	$content = cil_content_rewrite_site_urls( $page->post_content, $replace_from, $replace_to );
	$check   = cil_content_assert_no_local_host( $content );
	if ( is_wp_error( $check ) ) {
		return $check;
	}
	if ( '' === trim( $content ) ) {
		return new WP_Error( 'cil_content_empty_export', 'Page has empty post_content.' );
	}

	return array(
		'schema'              => 1,
		'slug'                => $page->post_name,
		'path'                => '/' . $slug . '/',
		'local_id'            => (int) $page->ID,
		'source_modified_gmt' => $page->post_modified_gmt,
		'source_sha256'       => hash( 'sha256', $content ),
		'replace_from'        => untrailingslashit( $replace_from ),
		'replace_to'          => untrailingslashit( $replace_to ),
		'content'             => $content,
	);
}

/**
 * Register the Tools → Content sync screen.
 */
function cil_content_sync_admin_menu() {
	add_management_page(
		__( 'Content sync', 'circumcision-london' ),
		__( 'Content sync', 'circumcision-london' ),
		'manage_options',
		'cil-content-sync',
		'cil_content_sync_render_admin'
	);
}
add_action( 'admin_menu', 'cil_content_sync_admin_menu' );

/**
 * Handle explicit apply POST.
 */
function cil_content_sync_handle_post() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( empty( $_POST['cil_content_sync_action'] ) || 'apply' !== $_POST['cil_content_sync_action'] ) {
		return;
	}
	if ( empty( $_GET['page'] ) || 'cil-content-sync' !== $_GET['page'] ) {
		return;
	}

	check_admin_referer( 'cil_content_sync_apply', 'cil_content_sync_nonce' );

	$confirm = isset( $_POST['cil_content_sync_confirm'] ) ? sanitize_text_field( wp_unslash( $_POST['cil_content_sync_confirm'] ) ) : '';
	$slugs   = isset( $_POST['cil_content_sync_slugs'] ) ? (array) wp_unslash( $_POST['cil_content_sync_slugs'] ) : array();
	$slugs   = array_values( array_unique( array_map( 'sanitize_title', $slugs ) ) );

	if ( 'APPLY' !== $confirm ) {
		add_settings_error( 'cil_content_sync', 'confirm', __( 'Apply aborted: type APPLY in the confirmation field.', 'circumcision-london' ), 'error' );
		return;
	}
	if ( ! $slugs ) {
		add_settings_error( 'cil_content_sync', 'slugs', __( 'Apply aborted: no slugs selected.', 'circumcision-london' ), 'error' );
		return;
	}

	foreach ( $slugs as $slug ) {
		$result = cil_content_apply_slug( $slug );
		if ( is_wp_error( $result ) ) {
			add_settings_error( 'cil_content_sync', 'apply-' . $slug, $slug . ': ' . $result->get_error_message(), 'error' );
			continue;
		}
		$message = ! empty( $result['changed'] )
			? sprintf( 'Applied %s to page ID %d. Destination hash matches fixture. Revisions %d → %d.', $slug, $result['id'], $result['revisions_before'], $result['revisions_after'] )
			: sprintf( '%s already matched; no write.', $slug );
		add_settings_error( 'cil_content_sync', 'apply-ok-' . $slug, $message, 'success' );
	}
}
add_action( 'admin_init', 'cil_content_sync_handle_post' );

/**
 * Admin screen: dry-run by default.
 */
function cil_content_sync_render_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	settings_errors( 'cil_content_sync' );

	$manifest = cil_content_load_manifest();
	echo '<div class="wrap">';
	echo '<h1>' . esc_html__( 'Content sync', 'circumcision-london' ) . '</h1>';
	echo '<p>' . esc_html__( 'Dry-run is the default. Git deploy never applies these fixtures. Apply writes post_content only, resolves the destination by slug, and never creates a page.', 'circumcision-london' ) . '</p>';

	if ( is_wp_error( $manifest ) ) {
		echo '<div class="notice notice-error"><p>' . esc_html( $manifest->get_error_message() ) . '</p></div>';
		echo '</div>';
		return;
	}

	echo '<p><strong>' . esc_html__( 'Destination host expected by fixtures:', 'circumcision-london' ) . '</strong> ';
	echo esc_html( $manifest['replace_to'] );
	echo '<br><strong>' . esc_html__( 'This site:', 'circumcision-london' ) . '</strong> ';
	echo esc_html( untrailingslashit( home_url() ) );
	echo '</p>';

	$reports = array();
	foreach ( $manifest['pages'] as $slug ) {
		$reports[ $slug ] = cil_content_inspect_slug( $slug );
	}

	echo '<h2>' . esc_html__( 'Dry-run', 'circumcision-london' ) . '</h2>';
	echo '<table class="widefat striped"><thead><tr>';
	echo '<th>' . esc_html__( 'Slug', 'circumcision-london' ) . '</th>';
	echo '<th>' . esc_html__( 'Local ID (hint)', 'circumcision-london' ) . '</th>';
	echo '<th>' . esc_html__( 'Destination ID', 'circumcision-london' ) . '</th>';
	echo '<th>' . esc_html__( 'Hash', 'circumcision-london' ) . '</th>';
	echo '<th>' . esc_html__( 'Host', 'circumcision-london' ) . '</th>';
	echo '<th>' . esc_html__( 'Status', 'circumcision-london' ) . '</th>';
	echo '</tr></thead><tbody>';

	foreach ( $reports as $slug => $report ) {
		$hash = $report['hash_match'] ? __( 'match', 'circumcision-london' ) : __( 'different', 'circumcision-london' );
		$host = $report['host_match'] ? __( 'match', 'circumcision-london' ) : __( 'mismatch', 'circumcision-london' );
		$state = $report['error'] ? $report['error'] : ( $report['hash_match'] ? __( 'Already applied', 'circumcision-london' ) : __( 'Pending apply', 'circumcision-london' ) );
		echo '<tr>';
		echo '<td><code>' . esc_html( $slug ) . '</code></td>';
		echo '<td>' . esc_html( (string) $report['local_id'] ) . '</td>';
		echo '<td>' . esc_html( $report['destination_id'] ? (string) $report['destination_id'] : '—' ) . '</td>';
		echo '<td>' . esc_html( $hash ) . '</td>';
		echo '<td>' . esc_html( $host ) . '</td>';
		echo '<td>' . esc_html( $state ) . '</td>';
		echo '</tr>';
		if ( ! empty( $report['guards'] ) ) {
			echo '<tr><td colspan="6"><code>';
			echo esc_html( wp_json_encode( $report['guards'] ) );
			echo '</code></td></tr>';
		}
	}
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'Apply', 'circumcision-london' ) . '</h2>';
	echo '<p>' . esc_html__( 'This overwrites Gutenberg post_content for the selected published pages. Title, slug, status, author, featured image, template and SEO meta are left unchanged. Type APPLY to confirm.', 'circumcision-london' ) . '</p>';

	echo '<form method="post" action="' . esc_url( admin_url( 'tools.php?page=cil-content-sync' ) ) . '">';
	wp_nonce_field( 'cil_content_sync_apply', 'cil_content_sync_nonce' );
	echo '<input type="hidden" name="cil_content_sync_action" value="apply">';
	foreach ( $manifest['pages'] as $slug ) {
		$report   = $reports[ $slug ];
		$disabled = empty( $report['can_apply'] ) ? ' disabled' : '';
		echo '<p><label><input type="checkbox" name="cil_content_sync_slugs[]" value="' . esc_attr( $slug ) . '"' . $disabled . '> ';
		echo esc_html( $slug );
		echo '</label></p>';
	}
	echo '<p><label>' . esc_html__( 'Confirmation', 'circumcision-london' ) . ' <input type="text" name="cil_content_sync_confirm" value="" class="regular-text" autocomplete="off"></label></p>';
	submit_button( __( 'Apply selected fixtures', 'circumcision-london' ) );
	echo '</form>';
	echo '</div>';
}
