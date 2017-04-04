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
define('DB_NAME', 'lwp');

/** MySQL database username */
define('DB_USER', 'wpuser');

/** MySQL database password */
define('DB_PASSWORD', 'Password1');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ']9sb1 TSF,rU<)uu+eBiym}G)NWDNy%@2<&B=ZB)h;X9jYp*?^tFnF]< Uhp~pqC');
define('SECURE_AUTH_KEY',  'rH)q3xTICBDin^jp_qApN,H,7eY*&REhs_46%JrV5>z><YkG>u{dV# ^N>L4}%*W');
define('LOGGED_IN_KEY',    '9:_q<&pV?]sN4heueB&p(7R}0dCz^yZP}igSWtb4$pD+~|?g^uW?w_D&1`YZe(}n');
define('NONCE_KEY',        '6DcG^Wq7]iJ0fu/[/qMjEA(;&l9%j;R*x(vx|{{HBz-LK`wkK4bjk5}j_.UYM818');
define('AUTH_SALT',        'oT>HuV;F.>J5beP{4ZLm^-63eF}N}D|:X70Qp9bm}pT3dnnO0Kx(x+<wFm_rjA8f');
define('SECURE_AUTH_SALT', '2sgUkWH*?=L2l{G:T:W8 6@f<a.?p)wm6ydw3!rZR XmpJSyw9{7R-/BHuX`@-JX');
define('LOGGED_IN_SALT',   '38$<nb;GA[Z!1$W(UwvCPJb92P9I^2:TLbeAYcX6Kcu+*s-`au9We-hc>Jz>l.x{');
define('NONCE_SALT',       '>k?TSK/Q4=!+!0OBg6C1`*t`Bdy9%S9se3#_xM`1Bot1M7tl4*W|&)dku&6A|Ug3');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'lwp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
// Enable WP_DEBUG mode
define ( 'WP_DEBUG', true );

// Enable Debug logging to the /wp-content/debug.log file
define ( 'WP_DEBUG_LOG', true );

// Disable display of errors and warnings
define ( 'WP_DEBUG_DISPLAY', false );
@ini_set ( 'display_errors', 0 );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
