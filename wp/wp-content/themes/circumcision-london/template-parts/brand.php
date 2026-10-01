<?php
/**
 * Stacked Beverley Clinic wordmark.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$context = ( isset( $args['context'] ) && 'footer' === $args['context'] ) ? 'footer' : 'header';
cil_wordmark( '', $context );
