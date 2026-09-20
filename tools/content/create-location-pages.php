<?php
/**
 * One-shot: create empty published regional clinic pages if they are missing.
 *
 * Does not write post_content. Content Sync fixtures populate Gutenberg later.
 * Does not modify Book, Prices, homepage, Cliniko, or any existing page.
 *
 * Idempotent: if a published/draft page already exists at the slug, skip it.
 *
 * Usage (do not run until ready):
 *   php create-location-pages.php
 *
 * Expects WordPress at ../../wp/wp-load.php from this file, or WP already loaded.
 */
if ( ! defined( 'ABSPATH' ) ) {
	$wp_load = dirname( __DIR__, 2 ) . '/wp/wp-load.php';
	if ( ! is_file( $wp_load ) ) {
		fwrite( STDERR, "wp-load.php not found: {$wp_load}\n" );
		exit( 1 );
	}
	require $wp_load;
}

$pages = array(
	array(
		'slug'  => 'luton-circumcision-clinic',
		'title' => 'Circumcision Clinic in Luton',
	),
	array(
		'slug'  => 'southampton-circumcision-clinic',
		'title' => 'Circumcision Clinic in Southampton',
	),
	array(
		'slug'  => 'birmingham-circumcision-clinic',
		'title' => 'Circumcision Clinic in Birmingham',
	),
);

$created = 0;
$skipped = 0;

foreach ( $pages as $page ) {
	$existing = get_page_by_path( $page['slug'], OBJECT, 'page' );
	if ( $existing ) {
		echo $page['slug'] . ' exists ID=' . (int) $existing->ID . " skipped\n";
		$skipped++;
		continue;
	}

	$id = wp_insert_post(
		array(
			'post_title'   => $page['title'],
			'post_name'    => $page['slug'],
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
		),
		true
	);

	if ( is_wp_error( $id ) ) {
		fwrite( STDERR, $page['slug'] . ' ' . $id->get_error_message() . "\n" );
		exit( 1 );
	}

	echo $page['slug'] . ' created ID=' . (int) $id . "\n";
	$created++;
}

echo "created={$created} skipped={$skipped}\n";
