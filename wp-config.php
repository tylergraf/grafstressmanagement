<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
 define("DB_NAME", $_SERVER["DB_NAME"]);
 define("DB_USER", $_SERVER["DB_USER"]);
 define("DB_PASSWORD", $_SERVER["DB_PASS"]);
 define("DB_HOST", $_SERVER["DB_HOST"]);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'Bm,SntWJxF-`V~xbWrmQS3232323523gg23gNhVhklVY@Q0sx92Y6gV%SX!)S:>N $.$qQ  Le5&69m');
define('SECURE_AUTH_KEY',  '*yq-~?lo#5XE ;&PC&2B23g23g23g23gv5nV>{*C}X[s%fNQY|d&0<^4PP,w<+uf9?>PUIYyU$0P');
define('LOGGED_IN_KEY',    'NO6IBEdB[@ZPSR6x5U/w2g23g23g23geSJ[)$0}:<MR-Dsr}gFHQh,MUUf6_Dg[{LaJ?Kj6kJRE');
define('NONCE_KEY',        '|rOD;;,UjGF#VuZr#XKUe23g23g23g23gg4pu8G:.ouK:C3s!WF+N:l@H[O=.p[c]X3]_NHK^cf>ZT');
define('AUTH_SALT',        'r|0|P^XND;g5xgcD+Zewg3w3gr4w3:bsfP0or0UIC`G*p`&TDbKSOi5YvEqYSj2L?M6rz`Ph:');
define('SECURE_AUTH_SALT', '4c<OPQe6G&@DCv7:G)D3gsg42Wg,xNU:]|$}G0#d?oq(&aLemwU7=XrOS;BaoJoIaJN8Wd');
define('LOGGED_IN_SALT',   'sx*8B*|]dc!i<qj^8-Jgwg3rsdg2;bpgWn7+.{CX<GggiPb;7aUJ,NMo@WNb$6$LkD7kPp?[:');
define('NONCE_SALT',       'reiY2?T&@rn)_j[`_R6|%23g24gsg09jwgoinef^c=1z8}2(G>8{D[: wI[Jqh_7B=$b;b|<+2#qX;L3aB');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
