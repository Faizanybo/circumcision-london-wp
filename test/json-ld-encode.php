<?php
/**
 * Unit-level check that JSON-LD encoding escapes </script>.
 *
 *   php test/json-ld-encode.php
 */
if ( ! function_exists( 'add_filter' ) ) {
	function add_filter() {}
	function add_action() {}
	function remove_action() {}
}
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', true );
}
require __DIR__ . '/../wp/wp-content/themes/circumcision-london/inc/security.php';

$json = cil_json_ld_encode( array( 'x' => '</script><script>alert(1)</script>' ) );

if ( false === $json ) {
	fwrite( STDERR, "json_encode failed\n" );
	exit( 1 );
}
if ( false !== strpos( $json, '</script>' ) ) {
	fwrite( STDERR, "FAIL: encoded JSON still contains </script>\n" );
	exit( 1 );
}
if ( false === strpos( $json, '\\u003C' ) && false === strpos( $json, '\\u003c' ) ) {
	fwrite( STDERR, "FAIL: expected JSON_HEX_TAG encoding of <\n" );
	exit( 1 );
}

echo "OK json-ld-encode\n";
exit( 0 );
