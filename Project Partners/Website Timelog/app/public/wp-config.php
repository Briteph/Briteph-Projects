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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          '`qkXzyJh;iqe+i8[?bgAx+r5kE#Nr<6gwe#<:J+&OeTnseb&<FF-%1<3jQ^Rv$tl' );
define( 'SECURE_AUTH_KEY',   'NIN4[~.H N*yU*D22<KZn Gq@v/.uZ5my$X2YGFM68xMm}k/Ub-}5PHHcR.!Eo@@' );
define( 'LOGGED_IN_KEY',     '`{#6%9K@P5)wlFue*%od~HxIW{fJ*=)T@),~~P<e$s|VWkb(.iI9g_0A45QB)1&^' );
define( 'NONCE_KEY',         '$y2LUeH+s)#u,|3$[->0;{eN:a@7s/dEStaBZ#ny<^+<HaQ%hC-z& fB!=KB`KJb' );
define( 'AUTH_SALT',         'ho|dnPh#P7rKw~HSBy;qhx=O0lPL_HyL#[fOi}F$@EWnq8`$pS$&N6*`L*|`.Hs&' );
define( 'SECURE_AUTH_SALT',  '}5fusHcfJ:* fjONR)J~&lo@pLjQ,O*e0Z6kd2#9lc@dQ;3D[>Z4PN<`6quzLT@J' );
define( 'LOGGED_IN_SALT',    'g645zGxWUoGch HNt?}O^sW(C%2Pp*QtMH71 hE2PxP6GIOGb{10dV &(t0sU[1:' );
define( 'NONCE_SALT',        'P6(<*YPA;<7)4R ;LsFa7o`H;<PbI7EG3:S 1=t4&48)pVKB{/*Pb2Uv}:ssCQ#4' );
define( 'WP_CACHE_KEY_SALT', 'xnEf.0r%ArI9}F6Q&Wl024dH[--pG>vmWw[8MWdsdl,+x*xlu#R9~7ucHoVc@}{F' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

// define('WP_POST_REVISIONS', 5);

define( 'WP_SENTRY_PHP_DSN', 'https://e2b9f3b40d634b21b657468cc948160b@o1055051.ingest.sentry.io/6040837' );
define( 'WP_SENTRY_BROWSER_DSN', 'https://e2b9f3b40d634b21b657468cc948160b@o1055051.ingest.sentry.io/6040837' );


/* DEFINE SCORO VARIABLES */
define('SCORO_BASE_URL', 'https://projectpartners.scoro.com/api/v2/');
define('SCORO_COMPANY_ID', 'projectpartners');
define('SCORO_API_KEY', 'ScoroAPI_0fb3e0606295d3d');

define('TEST_SCORO_BASE_URL', 'https://pptest3.scoro.com/api/v2/');
define('TEST_SCORO_COMPANY_ID', 'pptest3');
define('TEST_SCORO_API_KEY', 'ScoroAPI_41b2a058132447d');

define('SCORO_CLIENT_WORK_ACTIVITY_ID', 263);
define('SCORO_PLANNED_ACTIVITY_ID', 281);

/* DEFINE ZOHO CREATOR VARIABLES */
define('ZOHO_BASE_URL', 'https://accounts.zoho.com/');
define('ZOHO_BASE_SERVICE_URL', 'https://creator.zoho.com/');

define('CLIENT_ID', '1000.OT0WUDSMI13MZKQBOW6AK92GFNAS1Z');
define('CLIENT_SECRET', 'c25cf69761134b3b9946740e0b8f7496aea1db9c6b');
define('CONSOLE_CODE', '1000.6c24fba013642080b1773c1543a48f04.7261694eefca79e705898b158cdf12e4');

define('TOKEN_URL', ZOHO_BASE_URL . 'oauth/v2/token?grant_type=authorization_code&client_id='.CLIENT_ID.'&client_secret='.CLIENT_SECRET.'&redirect_uri=https://localhost&code='.CONSOLE_CODE);


// Enable WP_DEBUG mode
// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_LOG', true );
// define( 'WP_DEBUG_DISPLAY', true );