<?php
/**
 * Fallback template (home, archives, search).
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) {
	if ( is_home() && ! is_front_page() ) {
		cil_page_head( get_the_title( get_option( 'page_for_posts' ) ) );
	} elseif ( is_archive() ) {
		cil_page_head( get_the_archive_title() );
	} elseif ( is_search() ) {
		cil_page_head(
			sprintf(
				/* translators: %s: search query */
				__( 'Search: %s', 'circumcision-london' ),
				get_search_query()
			)
		);
	}

	echo '<section class="section-sm"><div class="wrap"><ul class="posts-list">';
	while ( have_posts() ) {
		the_post();
		echo '<li class="card">';
		echo '<a class="card-link" href="' . esc_url( get_permalink() ) . '">';
		echo '<h2 class="display d-3">' . esc_html( get_the_title() ) . '</h2>';
		if ( has_excerpt() ) {
			echo '<p>' . esc_html( get_the_excerpt() ) . '</p>';
		}
		echo '</a></li>';
	}
	echo '</ul>';
	the_posts_pagination();
	echo '</div></section>';
} else {
	cil_page_head(
		__( 'Nothing found', 'circumcision-london' ),
		__( 'There is nothing to show here yet.', 'circumcision-london' )
	);
}

get_footer();
