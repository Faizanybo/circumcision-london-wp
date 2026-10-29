<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
if (isset($_ENV['DATABASE'])) {
  define( 'DB_NAME', $_ENV['DATABASE'] );
}

/** Database username */
if (isset($_ENV['USERNAME'])) {
  define( 'DB_USER', $_ENV['USERNAME'] );
}

/** Database password */
if (isset($_ENV['PASSWORD'])) {
  define( 'DB_PASSWORD', $_ENV['PASSWORD'] );
}

/** Database hostname */
if (isset($_ENV['HOST'])) {
  define( 'DB_HOST', $_ENV['HOST'] );
}

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
if (isset($_ENV['DB_COLLATE'])) {
  define( 'DB_COLLATE', $_ENV['DB_COLLATE'] );
}
else {
  if (isset($_ENV['HOST']) && str_contains($_ENV['HOST'], 'tidbcloud.com')) {
    define ( 'DB_COLLATE', 'utf8mb4_general_ci');
  }
  else {
    define( 'DB_COLLATE', '' );
  }
}

/**
 * Read an environment value without printing it.
 *
 * @param string $name Variable name.
 * @return string
 */
$cil_env = static function ( $name ) {
	if ( isset( $_ENV[ $name ] ) && '' !== $_ENV[ $name ] ) {
		return (string) $_ENV[ $name ];
	}
	if ( isset( $_SERVER[ $name ] ) && is_string( $_SERVER[ $name ] ) && '' !== $_SERVER[ $name ] ) {
		return (string) $_SERVER[ $name ];
	}
	$value = getenv( $name );
	return ( false !== $value && '' !== $value ) ? (string) $value : '';
};

/**#@+
 * Authentication unique keys and salts.
 *
 * Set AUTH_KEY, SECURE_AUTH_KEY, LOGGED_IN_KEY, NONCE_KEY, AUTH_SALT,
 * SECURE_AUTH_SALT, LOGGED_IN_SALT and NONCE_SALT in the environment.
 * Do not commit unique values. Empty values let WordPress store per-site
 * salts in the database. Changing these values logs everyone out.
 *
 * @since 2.6.0
 */
foreach ( array( 'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT' ) as $cil_salt_name ) {
	if ( ! defined( $cil_salt_name ) ) {
		define( $cil_salt_name, $cil_env( $cil_salt_name ) );
	}
}
unset( $cil_salt_name );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = isset($_ENV['TABLE_PREFIX']) ? $_ENV['TABLE_PREFIX'] : 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */

if (!isset($_ENV['SKIP_MYSQL_SSL'])) {
  define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL );
}

$_SERVER['HTTPS'] = 'on';

/**
 * Whether a hostname may be used for WP_HOME / WP_SITEURL.
 *
 * Prefer WP_HOME / WP_SITEURL from the environment (canonical URL for that
 * environment). If those are empty, only allowlisted hosts are used so
 * LocalWP (*.local, localhost) and Vercel previews (*.vercel.app) keep working
 * without baking the production domain into staging. Set WP_HOME on production
 * after cutover approval; leave it empty on Vercel if unique preview URLs
 * must keep working.
 *
 * @param string $host Hostname, possibly with a port.
 * @return bool
 */
$cil_host_allowed = static function ( $host ) use ( $cil_env ) {
	$host = strtolower( trim( (string) $host ) );
	$host = preg_replace( '/:\d+$/', '', $host );
	$host = rtrim( $host, '.' );
	if ( '' === $host || false !== strpos( $host, '/' ) || false !== strpos( $host, '\\' ) || false !== strpos( $host, "\0" ) ) {
		return false;
	}

	$allowed = array( 'localhost', '127.0.0.1', '::1' );
	$home    = $cil_env( 'WP_HOME' );
	if ( '' !== $home ) {
		$home_host = parse_url( $home, PHP_URL_HOST );
		if ( is_string( $home_host ) && '' !== $home_host ) {
			$allowed[] = strtolower( $home_host );
		}
	}
	$extra = $cil_env( 'WP_ALLOWED_HOSTS' );
	if ( '' !== $extra ) {
		foreach ( explode( ',', $extra ) as $item ) {
			$item = strtolower( trim( $item ) );
			if ( '' !== $item ) {
				$allowed[] = $item;
			}
		}
	}

	foreach ( $allowed as $item ) {
		if ( $item === $host ) {
			return true;
		}
		if ( 0 === strpos( $item, '*.' ) ) {
			$suffix = substr( $item, 1 );
			if ( $suffix && substr( $host, -strlen( $suffix ) ) === $suffix ) {
				return true;
			}
		}
	}

	if ( preg_match( '/^(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+vercel\.app$/', $host ) ) {
		return true;
	}
	if ( preg_match( '/^(?:[a-z0-9-]+\.)+local$/', $host ) ) {
		return true;
	}
	if ( preg_match( '/^(?:[a-z0-9-]+\.)*localhost$/', $host ) ) {
		return true;
	}

	return false;
};

$cil_inject = '';
if ( function_exists( 'getallheaders' ) ) {
	$cil_headers = getallheaders();
	if ( is_array( $cil_headers ) ) {
		foreach ( $cil_headers as $cil_header_name => $cil_header_value ) {
			if ( 'injecthost' === strtolower( (string) $cil_header_name ) ) {
				$cil_inject = (string) $cil_header_value;
				break;
			}
		}
	}
	unset( $cil_headers, $cil_header_name, $cil_header_value );
}

$cil_chosen_host = '';
foreach ( array( $cil_inject, isset( $_SERVER['HTTP_HOST'] ) ? (string) $_SERVER['HTTP_HOST'] : '' ) as $cil_candidate ) {
	if ( $cil_host_allowed( $cil_candidate ) ) {
		$cil_chosen_host = strtolower( preg_replace( '/:\d+$/', '', rtrim( trim( $cil_candidate ), '.' ) ) );
		break;
	}
}
unset( $cil_inject, $cil_candidate );

$cil_home_env = rtrim( $cil_env( 'WP_HOME' ), '/' );
if ( '' !== $cil_home_env ) {
	if ( ! defined( 'WP_HOME' ) ) {
		define( 'WP_HOME', $cil_home_env );
	}
	if ( ! defined( 'WP_SITEURL' ) ) {
		$cil_siteurl_env = rtrim( $cil_env( 'WP_SITEURL' ), '/' );
		define( 'WP_SITEURL', '' !== $cil_siteurl_env ? $cil_siteurl_env : $cil_home_env );
		unset( $cil_siteurl_env );
	}
} elseif ( '' !== $cil_chosen_host ) {
	$_SERVER['HTTP_HOST'] = $cil_chosen_host;
	if ( ! defined( 'WP_HOME' ) ) {
		define( 'WP_HOME', 'https://' . $cil_chosen_host );
	}
	if ( ! defined( 'WP_SITEURL' ) ) {
		define( 'WP_SITEURL', 'https://' . $cil_chosen_host );
	}
}
unset( $cil_home_env, $cil_chosen_host, $cil_host_allowed, $cil_env );

// Optional S3 credentials for file storage.
if (isset($_ENV['S3_KEY_ID']) && isset($_ENV['S3_ACCESS_KEY'])) {
	define( 'AS3CF_SETTINGS', serialize( array(
        'provider' => 'aws',
        'access-key-id' => $_ENV['S3_KEY_ID'],
        'secret-access-key' => $_ENV['S3_ACCESS_KEY'],
) ) );
}

// Disable file modification because the changes won't be persisted.
define('DISALLOW_FILE_EDIT', true );
define('DISALLOW_FILE_MODS', true );

// If using SQLite (S3 or Vercel Blob) instead of MySQL/MariaDB. Node owns this
// decision (util/storage.js) and signals it with the header below, which the
// active plugin strips of any inbound value in preRequest.
if (!empty($_SERVER['HTTP_X_SERVERLESSWP_SQLITE_FILE'])) {
  define('DB_DIR', '/tmp');
  // Per-invocation working file so concurrent requests don't share one.
  define('DB_FILE', basename($_SERVER['HTTP_X_SERVERLESSWP_SQLITE_FILE']));
  define('DB_NAME', 'wp-sqlite');

  // Force the rollback journal mode. The Node sqlite plugin uploads the
  // single .sqlite file to remote storage at the end of each request.
  define('SQLITE_JOURNAL_MODE', 'DELETE');

  // Auto-cron can cause db race conditions on these urls, don't bother with it.
  if (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false || strpos($_SERVER['REQUEST_URI'], 'wp-login') !== false) {
    define('DISABLE_WP_CRON', true);
  }

  // Increase time between cron runs (2 hours) to reduce DB writes.
  define('WP_CRON_LOCK_TIMEOUT', 7200);

  // Limit revisions.
  define('WP_POST_REVISIONS', 3);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
