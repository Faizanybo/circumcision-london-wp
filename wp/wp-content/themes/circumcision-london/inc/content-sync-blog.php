<?php
/**
 * Blog pack content sync: page + posts + category + featured media + nav.
 *
 * Extends the page-only Content sync system. Git ships fixtures under
 * content-fixtures/{pages/blog.json,posts/*.json,blog/media/*,blog/pack.json}.
 * Explicit admin apply upserts the Blog page, clinic-articles category, posts,
 * featured images, and a Primary-menu Blog link when a menu is assigned.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blog pack JSON path.
 *
 * @return string
 */
function cil_blog_pack_path() {
	return cil_content_fixtures_dir() . '/blog/pack.json';
}

/**
 * Post fixture path.
 *
 * @param string $slug Post slug.
 * @return string
 */
function cil_blog_post_fixture_path( $slug ) {
	$slug = sanitize_title( $slug );
	return cil_content_fixtures_dir() . '/posts/' . $slug . '.json';
}

/**
 * Load blog pack metadata.
 *
 * @return array|WP_Error
 */
function cil_blog_load_pack() {
	$data = cil_content_read_json_file( cil_blog_pack_path() );
	if ( is_wp_error( $data ) ) {
		return $data;
	}
	if ( empty( $data['page'] ) || empty( $data['posts'] ) || ! is_array( $data['posts'] ) ) {
		return new WP_Error( 'cil_blog_pack', 'blog/pack.json must include page and posts.' );
	}
	$data['page']            = sanitize_title( (string) $data['page'] );
	$data['posts']           = array_values( array_map( 'sanitize_title', $data['posts'] ) );
	$data['replace_from']    = isset( $data['replace_from'] ) ? untrailingslashit( (string) $data['replace_from'] ) : '';
	$data['replace_to']      = isset( $data['replace_to'] ) ? untrailingslashit( (string) $data['replace_to'] ) : '';
	$data['ensure_nav_blog'] = ! empty( $data['ensure_nav_blog'] );
	$data['categories']      = isset( $data['categories'] ) && is_array( $data['categories'] ) ? $data['categories'] : array();
	return $data;
}

/**
 * Load one post fixture.
 *
 * @param string $slug Slug.
 * @return array|WP_Error
 */
function cil_blog_load_post_fixture( $slug ) {
	$slug = sanitize_title( $slug );
	$data = cil_content_read_json_file( cil_blog_post_fixture_path( $slug ) );
	if ( is_wp_error( $data ) ) {
		return $data;
	}
	if ( empty( $data['slug'] ) || sanitize_title( $data['slug'] ) !== $slug ) {
		return new WP_Error( 'cil_blog_slug', 'Post fixture slug mismatch for ' . $slug );
	}
	if ( empty( $data['title'] ) || ! isset( $data['content'] ) || ! is_string( $data['content'] ) ) {
		return new WP_Error( 'cil_blog_empty', 'Post fixture missing title/content for ' . $slug );
	}
	if ( empty( $data['source_sha256'] ) || ! hash_equals( $data['source_sha256'], hash( 'sha256', $data['content'] ) ) ) {
		return new WP_Error( 'cil_blog_hash', 'Post fixture source_sha256 mismatch for ' . $slug );
	}
	$no_local = cil_content_assert_no_local_host( $data['content'] . ' ' . ( isset( $data['excerpt'] ) ? $data['excerpt'] : '' ) );
	if ( is_wp_error( $no_local ) ) {
		return $no_local;
	}
	return $data;
}

/**
 * Ensure a category exists by slug.
 *
 * @param array<string, mixed> $cat Cat row.
 * @return int|WP_Error Term ID.
 */
function cil_blog_ensure_category( $cat ) {
	$slug = isset( $cat['slug'] ) ? sanitize_title( $cat['slug'] ) : '';
	$name = isset( $cat['name'] ) ? sanitize_text_field( $cat['name'] ) : $slug;
	if ( '' === $slug ) {
		return new WP_Error( 'cil_blog_cat', 'Category slug missing.' );
	}
	$existing = get_category_by_slug( $slug );
	if ( $existing ) {
		return (int) $existing->term_id;
	}
	$result = wp_insert_term(
		$name,
		'category',
		array(
			'slug'        => $slug,
			'description' => isset( $cat['description'] ) ? (string) $cat['description'] : '',
		)
	);
	if ( is_wp_error( $result ) ) {
		return $result;
	}
	return (int) $result['term_id'];
}

/**
 * Find an attachment previously sideloaded for this fixture key.
 *
 * @param string $sha256 File hash.
 * @return int Attachment ID or 0.
 */
function cil_blog_find_attachment_by_sha( $sha256 ) {
	$sha256 = strtolower( (string) $sha256 );
	if ( '' === $sha256 ) {
		return 0;
	}
	$q = new WP_Query(
		array(
			'post_type'              => 'attachment',
			'post_status'            => 'inherit',
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_key'               => '_cil_fixture_media_sha256',
			'meta_value'             => $sha256,
		)
	);
	return ! empty( $q->posts[0] ) ? (int) $q->posts[0] : 0;
}

/**
 * Sideload a theme-bundled media file into the Media Library.
 *
 * @param array<string, mixed> $media Media meta from post fixture.
 * @return int|WP_Error Attachment ID.
 */
function cil_blog_sideload_media( $media ) {
	if ( empty( $media['file'] ) || empty( $media['sha256'] ) ) {
		return new WP_Error( 'cil_blog_media', 'Featured media file/sha256 missing.' );
	}
	$sha = strtolower( (string) $media['sha256'] );
	$existing = cil_blog_find_attachment_by_sha( $sha );
	if ( $existing ) {
		return $existing;
	}

	$abs = cil_content_fixtures_dir() . '/' . ltrim( str_replace( '\\', '/', (string) $media['file'] ), '/' );
	if ( ! is_file( $abs ) ) {
		return new WP_Error( 'cil_blog_media_missing', 'Media file not found: ' . $media['file'] );
	}
	if ( ! hash_equals( $sha, hash_file( 'sha256', $abs ) ) ) {
		return new WP_Error( 'cil_blog_media_hash', 'Media file hash mismatch: ' . $media['file'] );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$basename = basename( $abs );
	$tmp      = wp_tempnam( $basename );
	if ( ! $tmp || ! copy( $abs, $tmp ) ) {
		return new WP_Error( 'cil_blog_media_tmp', 'Could not stage media temp file.' );
	}

	$file_array = array(
		'name'     => $basename,
		'tmp_name' => $tmp,
		'type'     => isset( $media['mime'] ) ? (string) $media['mime'] : mime_content_type( $abs ),
		'error'    => 0,
		'size'     => filesize( $abs ),
	);

	$attachment_id = media_handle_sideload( $file_array, 0, isset( $media['title'] ) ? (string) $media['title'] : '' );
	if ( is_wp_error( $attachment_id ) ) {
		@unlink( $tmp );
		return $attachment_id;
	}

	update_post_meta( $attachment_id, '_cil_fixture_media_sha256', $sha );
	if ( ! empty( $media['alt'] ) ) {
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $media['alt'] ) );
	}

	return (int) $attachment_id;
}

/**
 * Resolve a post by slug or 0.
 *
 * @param string $slug Slug.
 * @return WP_Post|null
 */
function cil_blog_get_post_by_slug( $slug ) {
	$slug  = sanitize_title( $slug );
	$posts = get_posts(
		array(
			'name'                   => $slug,
			'post_type'              => 'post',
			'post_status'            => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'numberposts'            => 1,
			'suppress_filters'       => true,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);
	return ! empty( $posts[0] ) ? $posts[0] : null;
}

/**
 * Upsert one blog post from fixture.
 *
 * @param array<string, mixed> $fixture Post fixture.
 * @return array<string, mixed>|WP_Error
 */
function cil_blog_upsert_post( $fixture ) {
	$slug = sanitize_title( $fixture['slug'] );
	$existing = cil_blog_get_post_by_slug( $slug );

	$thumb_id = 0;
	if ( ! empty( $fixture['featured_media'] ) && is_array( $fixture['featured_media'] ) ) {
		$thumb = cil_blog_sideload_media( $fixture['featured_media'] );
		if ( is_wp_error( $thumb ) ) {
			// Theme-bundled media still serves the feed/article images; continue without Library thumb.
			$thumb_id = 0;
		} else {
			$thumb_id = (int) $thumb;
		}
	}

	$cat_ids = array();
	foreach ( isset( $fixture['categories'] ) ? (array) $fixture['categories'] : array() as $cat_slug ) {
		$term = get_category_by_slug( sanitize_title( $cat_slug ) );
		if ( $term ) {
			$cat_ids[] = (int) $term->term_id;
		}
	}

	// Hash-check against fixture first, then rewrite upload URLs to theme media for ServerlessWP.
	$content_for_hash = $fixture['content'];
	if ( empty( $fixture['source_sha256'] ) || ! hash_equals( $fixture['source_sha256'], hash( 'sha256', $content_for_hash ) ) ) {
		return new WP_Error( 'cil_blog_hash', 'Post fixture source_sha256 mismatch for ' . $slug );
	}
	$content_to_save = cil_blog_rewrite_content_media_urls( $content_for_hash, $slug );

	$postarr = array(
		'post_type'    => 'post',
		'post_status'  => ! empty( $fixture['status'] ) ? $fixture['status'] : 'publish',
		'post_title'   => $fixture['title'],
		'post_name'    => $slug,
		'post_content' => $content_to_save,
		'post_excerpt' => isset( $fixture['excerpt'] ) ? (string) $fixture['excerpt'] : '',
	);
	if ( ! empty( $fixture['date_gmt'] ) ) {
		$postarr['post_date_gmt'] = $fixture['date_gmt'];
		$postarr['post_date']     = get_date_from_gmt( $fixture['date_gmt'] );
	}
	if ( $cat_ids ) {
		$postarr['post_category'] = $cat_ids;
	}

	kses_remove_filters();
	remove_filter( 'content_save_pre', 'convert_invalid_entities' );
	remove_filter( 'content_save_pre', 'balanceTags', 50 );

	if ( $existing ) {
		$postarr['ID'] = (int) $existing->ID;
		$result          = wp_update_post( wp_slash( $postarr ), true );
	} else {
		$result = wp_insert_post( wp_slash( $postarr ), true );
	}

	add_filter( 'content_save_pre', 'convert_invalid_entities' );
	add_filter( 'content_save_pre', 'balanceTags', 50 );
	kses_init_filters();

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	$post_id = (int) $result;
	if ( $thumb_id ) {
		set_post_thumbnail( $post_id, $thumb_id );
	}
	update_post_meta( $post_id, '_cil_content_fixture_sha256', $fixture['source_sha256'] );
	update_post_meta( $post_id, '_cil_content_fixture_applied_at', gmdate( 'c' ) );
	update_post_meta( $post_id, '_cil_blog_theme_media', 'blog/media/' . basename( (string) cil_blog_theme_media_path( $slug ) ) );

	return array(
		'slug'     => $slug,
		'id'       => $post_id,
		'created'  => ! $existing,
		'thumb_id' => $thumb_id,
	);
}

/**
 * Upsert the Blog page (create if missing).
 *
 * @param array<string, mixed> $fixture Page fixture.
 * @return array<string, mixed>|WP_Error
 */
function cil_blog_upsert_page( $fixture ) {
	$path = isset( $fixture['path'] ) ? $fixture['path'] : '/blog/';
	$page = get_page_by_path( trim( $path, '/' ), OBJECT, 'page' );
	$created = false;

	kses_remove_filters();
	remove_filter( 'content_save_pre', 'convert_invalid_entities' );
	remove_filter( 'content_save_pre', 'balanceTags', 50 );

	if ( ! $page ) {
		if ( empty( $fixture['create_if_missing'] ) ) {
			add_filter( 'content_save_pre', 'convert_invalid_entities' );
			add_filter( 'content_save_pre', 'balanceTags', 50 );
			kses_init_filters();
			return new WP_Error( 'cil_blog_page_missing', 'Blog page does not exist and create_if_missing is false.' );
		}
		$id = wp_insert_post(
			wp_slash(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => isset( $fixture['title'] ) ? $fixture['title'] : 'Blog',
					'post_name'    => 'blog',
					'post_content' => $fixture['content'],
				)
			),
			true
		);
		$created = true;
	} else {
		$id = wp_update_post(
			wp_slash(
				array(
					'ID'           => (int) $page->ID,
					'post_content' => $fixture['content'],
					'post_title'   => isset( $fixture['title'] ) ? $fixture['title'] : $page->post_title,
					'post_status'  => 'publish',
					'post_name'    => 'blog',
				)
			),
			true
		);
	}

	add_filter( 'content_save_pre', 'convert_invalid_entities' );
	add_filter( 'content_save_pre', 'balanceTags', 50 );
	kses_init_filters();

	if ( is_wp_error( $id ) ) {
		return $id;
	}

	$after = get_post( $id );
	if ( ! $after || ! hash_equals( $fixture['source_sha256'], hash( 'sha256', $after->post_content ) ) ) {
		return new WP_Error( 'cil_blog_page_verify', 'Blog page content hash mismatch after upsert.' );
	}

	update_post_meta( $after->ID, '_cil_content_fixture_sha256', $fixture['source_sha256'] );
	update_post_meta( $after->ID, '_cil_content_fixture_applied_at', gmdate( 'c' ) );

	return array(
		'slug'    => 'blog',
		'id'      => (int) $after->ID,
		'created' => $created,
	);
}

/**
 * Ensure Primary menu has a Blog item pointing at /blog/.
 *
 * @return array<string, mixed>
 */
function cil_blog_ensure_nav() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return array(
			'changed' => false,
			'message' => 'No Primary menu assigned; theme PHP fallback already includes Blog → /blog/.',
		);
	}

	$menu_id = (int) $locations['primary'];
	$items   = wp_get_nav_menu_items( $menu_id );
	$blog_url = trailingslashit( home_url( '/blog/' ) );

	if ( is_array( $items ) ) {
		foreach ( $items as $item ) {
			if ( 'Blog' === $item->title || untrailingslashit( $item->url ) === untrailingslashit( $blog_url ) ) {
				if ( untrailingslashit( $item->url ) !== untrailingslashit( $blog_url ) ) {
					wp_update_nav_menu_item(
						$menu_id,
						(int) $item->ID,
						array(
							'menu-item-title'  => 'Blog',
							'menu-item-url'    => $blog_url,
							'menu-item-status' => 'publish',
							'menu-item-type'   => 'custom',
						)
					);
					return array(
						'changed' => true,
						'message' => 'Updated existing Blog menu item URL to /blog/.',
					);
				}
				return array(
					'changed' => false,
					'message' => 'Blog menu item already points to /blog/.',
				);
			}
		}
	}

	// Prefer nesting under Contact Us when that parent exists.
	$parent_id = 0;
	if ( is_array( $items ) ) {
		foreach ( $items as $item ) {
			if ( 0 === (int) $item->menu_item_parent && false !== stripos( $item->title, 'Contact' ) ) {
				$parent_id = (int) $item->ID;
				break;
			}
		}
	}

	wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'     => 'Blog',
			'menu-item-url'       => $blog_url,
			'menu-item-status'    => 'publish',
			'menu-item-type'      => 'custom',
			'menu-item-parent-id' => $parent_id,
		)
	);

	return array(
		'changed' => true,
		'message' => $parent_id ? 'Added Blog under Contact Us menu.' : 'Added Blog to Primary menu.',
	);
}

/**
 * Dry-run inspect for blog pack.
 *
 * @return array<string, mixed>
 */
function cil_blog_inspect_pack() {
	$report = array(
		'ok'         => false,
		'can_apply'  => false,
		'host_match' => false,
		'error'      => '',
		'page'       => null,
		'posts'      => array(),
		'categories' => array(),
	);

	$pack = cil_blog_load_pack();
	if ( is_wp_error( $pack ) ) {
		$report['error'] = $pack->get_error_message();
		return $report;
	}

	$report['host_match'] = ( cil_content_url_host( home_url() ) === cil_content_url_host( $pack['replace_to'] ) );
	if ( ! $report['host_match'] ) {
		$report['error'] = sprintf(
			'This site host (%s) does not match blog pack replace_to host (%s).',
			cil_content_url_host( home_url() ),
			cil_content_url_host( $pack['replace_to'] )
		);
	}

	$page_fixture = cil_content_load_fixture( $pack['page'] );
	if ( is_wp_error( $page_fixture ) ) {
		$report['error'] = $page_fixture->get_error_message();
		return $report;
	}
	$page = get_page_by_path( 'blog', OBJECT, 'page' );
	$report['page'] = array(
		'exists'          => (bool) $page,
		'destination_id'  => $page ? (int) $page->ID : 0,
		'hash_match'      => $page ? hash_equals( $page_fixture['source_sha256'], hash( 'sha256', $page->post_content ) ) : false,
		'create_allowed'  => ! empty( $page_fixture['create_if_missing'] ),
	);

	foreach ( $pack['categories'] as $cat ) {
		$slug = isset( $cat['slug'] ) ? $cat['slug'] : '';
		$report['categories'][] = array(
			'slug'   => $slug,
			'exists' => (bool) get_category_by_slug( $slug ),
		);
	}

	$pending = ! $report['page']['exists'] || ! $report['page']['hash_match'];
	foreach ( $pack['posts'] as $slug ) {
		$fix = cil_blog_load_post_fixture( $slug );
		if ( is_wp_error( $fix ) ) {
			$report['error'] = $fix->get_error_message();
			return $report;
		}
		$existing = cil_blog_get_post_by_slug( $slug );
		$row      = array(
			'slug'       => $slug,
			'exists'     => (bool) $existing,
			'hash_match' => $existing ? hash_equals( $fix['source_sha256'], hash( 'sha256', $existing->post_content ) ) : false,
			'media'      => ! empty( $fix['featured_media']['file'] ) ? $fix['featured_media']['file'] : '',
		);
		if ( ! $row['exists'] || ! $row['hash_match'] ) {
			$pending = true;
		}
		$report['posts'][] = $row;
	}

	$report['ok']        = true;
	$report['can_apply'] = $report['host_match'] && $pending && '' === $report['error'];
	if ( $report['host_match'] && ! $pending ) {
		$report['error'] = 'Blog pack already matches this site.';
	}
	return $report;
}

/**
 * Apply the full blog pack.
 *
 * @return array<string, mixed>|WP_Error
 */
function cil_blog_apply_pack() {
	$inspect = cil_blog_inspect_pack();
	if ( ! empty( $inspect['error'] ) && ! $inspect['can_apply'] && ! $inspect['ok'] ) {
		return new WP_Error( 'cil_blog_inspect', $inspect['error'] );
	}
	if ( ! $inspect['host_match'] ) {
		return new WP_Error( 'cil_blog_host', $inspect['error'] );
	}

	$pack = cil_blog_load_pack();
	if ( is_wp_error( $pack ) ) {
		return $pack;
	}

	$results = array(
		'categories' => array(),
		'posts'      => array(),
		'page'       => null,
		'nav'        => null,
	);

	foreach ( $pack['categories'] as $cat ) {
		$id = cil_blog_ensure_category( $cat );
		if ( is_wp_error( $id ) ) {
			return $id;
		}
		$results['categories'][] = array(
			'slug' => $cat['slug'],
			'id'   => $id,
		);
	}

	foreach ( $pack['posts'] as $slug ) {
		$fixture = cil_blog_load_post_fixture( $slug );
		if ( is_wp_error( $fixture ) ) {
			return $fixture;
		}
		$up = cil_blog_upsert_post( $fixture );
		if ( is_wp_error( $up ) ) {
			return $up;
		}
		$results['posts'][] = $up;
	}

	$page_fixture = cil_content_load_fixture( $pack['page'] );
	if ( is_wp_error( $page_fixture ) ) {
		return $page_fixture;
	}
	$page_up = cil_blog_upsert_page( $page_fixture );
	if ( is_wp_error( $page_up ) ) {
		return $page_up;
	}
	$results['page'] = $page_up;

	if ( ! empty( $pack['ensure_nav_blog'] ) ) {
		$results['nav'] = cil_blog_ensure_nav();
	}

	$results['inspect'] = cil_blog_inspect_pack();
	return $results;
}

/**
 * Absolute filesystem path to theme-bundled blog media for a post slug.
 *
 * @param string $slug Post slug.
 * @return string Empty if missing.
 */
function cil_blog_theme_media_path( $slug ) {
	$slug = sanitize_title( $slug );
	if ( '' === $slug ) {
		return '';
	}
	$dir = trailingslashit( cil_content_fixtures_dir() ) . 'blog/media/';
	foreach ( array( 'jpg', 'jpeg', 'png', 'webp', 'gif' ) as $ext ) {
		$path = $dir . $slug . '.' . $ext;
		if ( is_file( $path ) ) {
			return $path;
		}
	}
	$matches = glob( $dir . $slug . '.*' );
	if ( $matches && is_file( $matches[0] ) ) {
		return $matches[0];
	}
	return '';
}

/**
 * Public URL for theme-bundled blog media (works on ServerlessWP / Vercel).
 *
 * @param string $slug Post slug.
 * @return string
 */
function cil_blog_theme_media_url( $slug ) {
	$path = cil_blog_theme_media_path( $slug );
	if ( '' === $path ) {
		return '';
	}
	return get_template_directory_uri() . '/content-fixtures/blog/media/' . basename( $path );
}

/**
 * Image HTML for a blog feed row — prefers theme fixtures (reliable on Vercel).
 *
 * @param int|WP_Post $post Post.
 * @return string
 */
function cil_blog_feed_image_html( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$url = cil_blog_theme_media_url( $post->post_name );
	$alt = the_title_attribute(
		array(
			'echo' => false,
			'post' => $post,
		)
	);

	if ( $url ) {
		return sprintf(
			'<img src="%1$s" alt="%2$s" width="1100" height="733" loading="lazy" decoding="async">',
			esc_url( $url ),
			esc_attr( $alt )
		);
	}

	$thumb_id = get_post_thumbnail_id( $post );
	if ( $thumb_id ) {
		$html = wp_get_attachment_image(
			$thumb_id,
			'cil-figure',
			false,
			array(
				'loading'  => 'lazy',
				'decoding' => 'async',
				'alt'      => $alt,
			)
		);
		if ( $html ) {
			return $html;
		}
	}

	return '';
}

/**
 * Rewrite in-content article images to theme-bundled media URLs.
 *
 * Blog post fixtures keep Local attachment HTML (uploads/ + wp-image-ID). On
 * ServerlessWP those upload files often 404; theme fixtures are deployed with git.
 *
 * @param string $content Post content.
 * @param string $slug    Post slug.
 * @return string
 */
function cil_blog_rewrite_content_media_urls( $content, $slug ) {
	$url = cil_blog_theme_media_url( $slug );
	if ( '' === $url || '' === $content ) {
		return $content;
	}

	// Prefer the dedicated article figure used by Local blog posts.
	$replaced = preg_replace(
		'#(<figure[^>]*class="[^"]*cil-blog-article-figure[^"]*"[^>]*>\s*<img[^>]+src=")[^"]+#i',
		'$1' . esc_url( $url ),
		$content,
		1,
		$count
	);
	if ( is_string( $replaced ) && $count > 0 ) {
		$content = $replaced;
		$content = preg_replace(
			'#(<figure[^>]*class="[^"]*cil-blog-article-figure[^"]*"[^>]*>\s*<img[^>]+)\s+srcset="[^"]*"#i',
			'$1',
			$content,
			1
		);
		return $content;
	}

	// Fallback: first wp-block-image.
	$replaced = preg_replace(
		'#(<figure[^>]*class="[^"]*wp-block-image[^"]*"[^>]*>\s*<img[^>]+src=")[^"]+#i',
		'$1' . esc_url( $url ),
		$content,
		1,
		$count
	);
	if ( is_string( $replaced ) && $count > 0 ) {
		$content = $replaced;
		$content = preg_replace(
			'#(<figure[^>]*class="[^"]*wp-block-image[^"]*"[^>]*>\s*<img[^>]+)\s+srcset="[^"]*"#i',
			'$1',
			$content,
			1
		);
	}

	return $content;
}

/**
 * Front-end: fix blog article body images that point at missing uploads.
 *
 * @param string $content Content.
 * @return string
 */
function cil_blog_filter_the_content_media( $content ) {
	if ( is_admin() || ! is_singular( 'post' ) ) {
		return $content;
	}
	$post = get_post();
	if ( ! $post ) {
		return $content;
	}
	return cil_blog_rewrite_content_media_urls( $content, $post->post_name );
}
add_filter( 'the_content', 'cil_blog_filter_the_content_media', 12 );

/**
 * When upserting, persist theme-media rewrites into post_content.
 *
 * @param array<string, mixed> $fixture Fixture.
 * @return array<string, mixed>
 */
function cil_blog_prepare_post_content_for_upsert( $fixture ) {
	if ( empty( $fixture['content'] ) || empty( $fixture['slug'] ) ) {
		return $fixture;
	}
	$fixture['content'] = cil_blog_rewrite_content_media_urls( $fixture['content'], $fixture['slug'] );
	// Keep hash verification against original fixture hash of unre-written content —
	// so rewrite only on write after hash check. Caller must hash-check first.
	return $fixture;
}
