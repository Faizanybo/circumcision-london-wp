<?php
/**
 * Homepage. Renders the static Home page Gutenberg content.
 *
 * Header, footer and chrome stay in PHP. Visible homepage sections come from
 * the Home page in the block editor.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	echo '<div class="entry-content">';
	the_content();
	echo '</div>';
}

get_footer();
