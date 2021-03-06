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
define('DB_NAME', 'qv84');

/** MySQL database username */
define('DB_USER', 'root');

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
define('AUTH_KEY',         '5mCkG^Wl7Ez)kbxk0CH9~i?i!# t<.QP`_D#}vQ/W_LKC|#99/8ThE$Mcwu:[e$q');
define('SECURE_AUTH_KEY',  '5|/,vf]NRE?[LV-SlrrtKS13UJ+Z=bgLZAN*J2: yz?xL:YIB,NXk@Na|[(m#+we');
define('LOGGED_IN_KEY',    ' j4Y`[,cP(F{F?*+81,*y7 G6dhpBK;HBNJy+*[5GTpvW!VZ;XJU!S@y]RmO1-oP');
define('NONCE_KEY',        'H}H^TaO b-X{ 9pE&O}(`c >b8OgL%oh`M83sA4yt`Enelx>U2AtBP>##oj#Yub|');
define('AUTH_SALT',        'UM>/f.Q3543a45%q%v]uDpJ^.h$;f,EFAIv8Q4V>=~;PH%0<pH&Wl3Prhzeg2>OC');
define('SECURE_AUTH_SALT', 'q@zJ[b|G=M2iiH%2 HDtXgXnnSCXFh,Rux8FRw05vV>c:(My )j0]wS]1_I)o{;1');
define('LOGGED_IN_SALT',   'EztfD:}}9U<LL1hg>}H6VkPdC!iZfTgQtcTjx(D7.aE#<Tdg?uIpzyWy)jIGwPp1');
define('NONCE_SALT',       'lzQ+`s>v|DbBQ/jLEz7_/;lw:Ub{6%F{HbH1?9Y92~#sG$6>bMv8S$%f0BbS4a&6');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'qv84_';

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

/*
	//in the beginning of the page
	global $wpdb; $wpdb->flush(); 

	 //in the end of the page or where the query happening.
	var_dump($wpdb->queries);
*/
define( 'SAVEQUERIES', true );
 
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
