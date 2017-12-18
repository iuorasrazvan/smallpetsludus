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
define('DB_NAME', 'smallpets');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         '@&[t-26P+ 5xqH4&ANqGuC*;I~`uy#6J+;obn[Wt}kLRjR192N6+?ax|6fa!OD*N');
define('SECURE_AUTH_KEY',  ';U32R[ 5q)S*QZz$$:2s-`UE$CQ*y$#.8q{>qkFWiZ?%{<z0+MO~2-yu2oQ};Ww5');
define('LOGGED_IN_KEY',    '~WBuvfpqcctn;l6rN0$}}}z2)oZFa~]M^]E7tn1INyv)jZR+$6z^?UbNwvU??h9`');
define('NONCE_KEY',        ']1$q~m$M?iIr6TTRRV7:bX51[2~s.,O}rTsPtb_# ]]%[REb*Ufx>UQ_-;*h9/mt');
define('AUTH_SALT',        '){<V.[bF|;vxG953eWY5jM/L-q^KEbmti)/Z%}#W?|&F?M!_rICM[ZRCuv.G_Byj');
define('SECURE_AUTH_SALT', 'b@B#W0y}qpqWNKgmaY`ZX`7rd$N?Sgrq<&8oY3h%om]U!#$b:4i8a$TYw8MbL}A%');
define('LOGGED_IN_SALT',   '-Yk%sdvmSgR(CYU_wA.=o^PeI)V>!SL1{3S*VE3p&]2dmyM&c~Z@5lHe:a ig>M#');
define('NONCE_SALT',       'N&Adp;<F%X{IOCI5E[]1J7z5)[atH0:qPp_e8-*|y{LQ3]Ka<EwQgr7ruwsA?E;+');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
