<?php
/**
 * Homepage. Markup and copy from the approved prototype src/pages/home.js.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/groups', null, array( 'level' => 2 ) );
get_template_part( 'template-parts/trust-strip' );
get_template_part( 'template-parts/intro' );
get_template_part( 'template-parts/process' );
get_template_part( 'template-parts/callback' );
get_template_part( 'template-parts/reviews' );
get_template_part( 'template-parts/cta-band' );
get_template_part(
	'template-parts/faq',
	null,
	array(
		'heading' => __( 'The questions we are asked most', 'circumcision-london' ),
	)
);

get_footer();
