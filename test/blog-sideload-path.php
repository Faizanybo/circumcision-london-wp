<?php
/**
 * Isolated path-confinement checks for blog fixture media (no WordPress).
 *
 *   php test/blog-sideload-path.php
 */
$root = sys_get_temp_dir() . '/cil-sideload-' . bin2hex( random_bytes( 4 ) );
mkdir( $root, 0777, true );
mkdir( $root . '/blog/media', 0777, true );
file_put_contents( $root . '/blog/media/ok.jpg', 'fixture' );
file_put_contents( $root . '/secret.php', '<?php echo "no";' );
$outside = dirname( $root ) . '/cil-sideload-outside-' . bin2hex( random_bytes( 4 ) ) . '.jpg';
file_put_contents( $outside, 'outside' );

function cil_test_resolve_fixture_media_path( $fixtures_root, $relative ) {
	$allowed  = array( 'jpg', 'jpeg', 'png', 'gif', 'webp' );
	$relative = str_replace( '\\', '/', (string) $relative );
	$relative = ltrim( $relative, '/' );
	if ( '' === $relative || false !== strpos( $relative, "\0" ) || preg_match( '#(?:^|/)\.\.(?:/|$)#', $relative ) ) {
		return false;
	}
	$ext = strtolower( (string) pathinfo( $relative, PATHINFO_EXTENSION ) );
	if ( ! in_array( $ext, $allowed, true ) ) {
		return false;
	}
	$root = realpath( $fixtures_root );
	if ( false === $root ) {
		return false;
	}
	$candidate = $root . '/' . $relative;
	if ( ! is_file( $candidate ) ) {
		return false;
	}
	$abs = realpath( $candidate );
	if ( false === $abs ) {
		return false;
	}
	$root_n = strtolower( str_replace( '\\', '/', $root ) );
	$abs_n  = strtolower( str_replace( '\\', '/', $abs ) );
	if ( $abs_n !== $root_n && 0 !== strpos( $abs_n, $root_n . '/' ) ) {
		return false;
	}
	return $abs;
}

$fail = 0;
$ok   = cil_test_resolve_fixture_media_path( $root, 'blog/media/ok.jpg' );
if ( false === $ok || false === strpos( str_replace( '\\', '/', $ok ), '/blog/media/ok.jpg' ) ) {
	fwrite( STDERR, "FAIL: legitimate jpg should resolve\n" );
	$fail++;
}
if ( false !== cil_test_resolve_fixture_media_path( $root, '../secret.php' ) ) {
	fwrite( STDERR, "FAIL: php traversal must be rejected\n" );
	$fail++;
}
if ( false !== cil_test_resolve_fixture_media_path( $root, 'secret.php' ) ) {
	fwrite( STDERR, "FAIL: php inside fixtures must be rejected by extension\n" );
	$fail++;
}
if ( false !== cil_test_resolve_fixture_media_path( $root, '../../' . basename( $outside ) ) ) {
	fwrite( STDERR, "FAIL: path outside fixtures must be rejected\n" );
	$fail++;
}

@unlink( $root . '/blog/media/ok.jpg' );
@unlink( $root . '/secret.php' );
@rmdir( $root . '/blog/media' );
@rmdir( $root . '/blog' );
@rmdir( $root );
@unlink( $outside );

if ( $fail ) {
	exit( 1 );
}
echo "OK blog-sideload-path\n";
exit( 0 );
