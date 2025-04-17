<?php
define( 'WP_CACHE', true );
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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u512655431_N6wWB' );

/** Database username */
define( 'DB_USER', 'u512655431_T1RK6' );

/** Database password */
define( 'DB_PASSWORD', 'OKlvqpLG9n' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '&Z(w}a}+W-C[paYh[LYD#/V(RSq;vvlQ<9UB$M#BXHlB_$EI|S`a*/3ssZl-%zB_' );
define( 'SECURE_AUTH_KEY',   '%gr:wvc:F^Id>i{z?AX:|,X3_HvdqVoa7/VShi0tSH:S`-rF*6LLlp^B}w(r&u;3' );
define( 'LOGGED_IN_KEY',     '1bc|3CFGQS7G tm`qR0hN6QpYd7(nq}wo8Z:/&Y2s/:h3O*E2lBZzLULx3_^C7P?' );
define( 'NONCE_KEY',         ':CzFSibKkfjNYhc1`6dW9dSCDC~).H<b`44KVdu~ENhRusjBQ?HtM64_CRasptHb' );
define( 'AUTH_SALT',         'p#I06xaIrnah75&W-=&5Uw!dRn)kh++6kizl!UW2/2LHRchav^!=4OhN.U?^q:Mz' );
define( 'SECURE_AUTH_SALT',  'G-<pp5}AwX[iba }aIs9O]k|uq:w5rAFAu+fjp] T(;:Osh@6f6:ZD!Pp;3>D)3j' );
define( 'LOGGED_IN_SALT',    'W,6d+kII(/LD% tRc~i%}6[!6? B{1 7@Fy8(Xev8PV#3xn*C~ztyiU ZY=/8[wW' );
define( 'NONCE_SALT',        '$Vf intu~w;}Yv?9eTp[4ouOQ5U?aYl&~k:M0b.X.=$b`n@s;vmsA$FJqK33W^pJ' );
define( 'WP_CACHE_KEY_SALT', 'E27BR_aVGM{JC=v0`viemg/vT(DsL8!iz]EWH_<$4oz<L`z2R[I@4Rg9`Gx>$%G&' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '41aca94c812ede683929f2a0ba18ea65' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
