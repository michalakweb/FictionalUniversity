<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
if (file_exists(dirname(__FILE__) . '/local.php')) {
	//local settings
	define( 'DB_NAME', 'local' );
	define( 'DB_USER', 'root' );
	define( 'DB_PASSWORD', 'root' );
	define( 'DB_HOST', 'localhost' );
}

else {
	//live settings
	define( 'DB_NAME', 'mateuszm_universityData' );
	define( 'DB_USER', 'mateuszm_mateusz' );
	define( 'DB_PASSWORD', 'Filemonek11' );
	define( 'DB_HOST', 'localhost' );
}



/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'GGpoHBdssGZktwCLR8cnKWb5YjCIu6FI5RXUdlrmTtaNs9BMY8s+kqbp4s8M2wx8txgvD9HlEidNnAHQWT3dGQ==');
define('SECURE_AUTH_KEY',  'wSk0cHAg9mSbmaGsz/CYMrSmKrpfcemVIkRaFspf3J8YSKIWUYKiz/hS3Mhw3OecWJCxX46CywPkT2kOC7n8SA==');
define('LOGGED_IN_KEY',    'lKly6gYOX6rJVhGVarsXoL3Wc1De9EfrVwhrqd582VYbgMi+g2cpz4QROwj0y8G4mVsR7xCrBt02q5E+h59pHg==');
define('NONCE_KEY',        '8otnOh3ofPlgGz48xyq2FewqiNZ3J277rB3kMMv+yW/emKO+bvmWk9BNymrK3IcrGFzJ7EQNSWNFEhsqapNSuQ==');
define('AUTH_SALT',        'ZD2i7yyCaETZrljrbLQEoGvgJiY4CcIej5LC8tx7DAA/UoKSylQB/YqlebqD+DW2eckWoggZRgGaYYHliebhaQ==');
define('SECURE_AUTH_SALT', 'JUa7Z3aaeh9QUlj2X2yXRT1rI1CXcgNlSe0VcIbV3U97Qslb2p7Nm8hCOeZRFuN4uiW7L7OVOH45TFupmf8SNw==');
define('LOGGED_IN_SALT',   'n4mQoWDfXbq4livkH/VHMNMGgOSnNy1KpGMsXsR+lcv/mGyzOleGLYlDMxO4vNk7bhY+gJU9v7uwwo2SWd5cbw==');
define('NONCE_SALT',       'QRKh+14gkwPKaWRi0R14o2SRY+/xw/e72C0f91amBsPqUajyrGUruG22UCdr4zUEjZfiLQ1jRe4ZEhnHzrXQnQ==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';





/* Inserted by Local by Flywheel. See: http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy */
if ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
	$_SERVER['HTTPS'] = 'on';
}

/* Inserted by Local by Flywheel. Fixes $is_nginx global for rewrites. */
if ( ! empty( $_SERVER['SERVER_SOFTWARE'] ) && strpos( $_SERVER['SERVER_SOFTWARE'], 'Flywheel/' ) !== false ) {
	$_SERVER['SERVER_SOFTWARE'] = 'nginx/1.10.1';
}
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
