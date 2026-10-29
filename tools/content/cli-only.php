<?php
/**
 * Abort unless this content script is invoked from the command line.
 */
if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
	fwrite( STDERR, "This script can only run from the command line.\n" );
	exit( 1 );
}
