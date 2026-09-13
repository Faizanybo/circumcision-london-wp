<?php
/**
 * Single post template.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	if ( ! cil_has_page_head() ) {
		cil_page_head( get_the_title() );
	}

	echo '<div class="entry-content">';
	the_content();
	echo '</div>';

	wp_link_pages();
}

get_footer();
