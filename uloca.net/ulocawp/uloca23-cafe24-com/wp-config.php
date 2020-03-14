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
define('DB_NAME', 'uloca22');

/** MySQL database username */
define('DB_USER', 'uloca22');

/** MySQL database password */
define('DB_PASSWORD', 'w3m69p21!@');

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
/* define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'b%JLBv*wU[eBm$_v1b;q9ZgfvI-kjP|oQ!FGl9/:V:v@OPv1at>&JAWCanV6Ek;?');
define('LOGGED_IN_KEY',    'ZC}1jCX.#m8zxX6E^%pOCYAr=fk5J!vS]>/L4rt@19,{LD_L<.F_^NMAzvgu %vo');
define('NONCE_KEY',        'P{Bz?UtC#9$5R_5G5xY>IsHra.*WzZuWn1Q+,0P-b`_TS{Z%~:x({sLudYce$&R!');
define('AUTH_SALT',        'wvv&GAMx??(4gEMSBxjF04@eYrYdK$X*L[}l<QMlMm5m/ISz;MSkE(!;!-ZOHFjA');
define('SECURE_AUTH_SALT', '#cOA~CJ/T)#av)o*@Ip3695ijpNioh8r.GX3&KlN3W-pl_ONx [:l@Iv5v?;VUvY');
define('LOGGED_IN_SALT',   'Ssk<v~)[W$3_%)}i%;Fr1`w)}IvI?M`@#jX}6-BwiZ!|?@]a(J6lcC&?w9-lh~Ff');
define('NONCE_SALT',       'Mc>wmH_hU=Cn|)+x:++Tl7*tIP9b3|RAt$&WA2MlUdRw&kijw4_on1)sXr9MrL@I');
*/

define('AUTH_KEY',         'TYBQBhI,aQ2ug]KeE+mDa[i/R *T:{xq;A_@+#4HlX n+X 9_jz^^h~VyK!/3gic');
define('SECURE_AUTH_KEY',  '');
define('LOGGED_IN_KEY',    '');
define('NONCE_KEY',        '');
define('AUTH_SALT',        '');
define('SECURE_AUTH_SALT', '');
define('LOGGED_IN_SALT',   '');
define('NONCE_SALT',       '');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpstg0_'; // Changed by WP Staging

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
define('UPLOADS', 'wp-content/uploads'); 
if ( ! defined( 'ABSPATH' ) )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
