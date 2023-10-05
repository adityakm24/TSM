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
define( 'DB_NAME', 'test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '#}8Bc5~^(,PWBRqd[=)EeAuJpSWnp{k@~2.^Z/@[6rpsx)qF38Z~@@aLm3!!9I;>' );
define( 'SECURE_AUTH_KEY',  '4 t9~zqTXc@4h7.<=v{0aklgBwvq8PA.Gy=BL:c6iI4MJOUvP#Re#yHdt6VM4c}J' );
define( 'LOGGED_IN_KEY',    'FV,t3fW2<k,Hh+h^h=2<0Wx_7.8M_mjF*+pxEVnLoP<zX9gP9zC||BRPKMzLoHmd' );
define( 'NONCE_KEY',        'mr/Uq<qMd0YK)q1h`CK&1w~9{%x#,O8^=hbvU!?k$5;~),1f72kxYGr)$U/*UHP-' );
define( 'AUTH_SALT',        'a:7msdkZ]Ws#1T/BJTiX%5nPr1W`~BpU9`*]n?,?z-.RBU8M,1A5<E?3D0sXJ>Z/' );
define( 'SECURE_AUTH_SALT', 'mKo~ke)O5Ry4yW3Qiz6DZK9g!{O:(<y(o/Qs<$9Wqj<oxo9;@B.j,iFh#^aPBtqX' );
define( 'LOGGED_IN_SALT',   'EVefg972Y>NITE~a<iLQmGiu<r89qSZ`a`$u3C1Hl-*jHj<M$&.:.+yR/A4MNyzu' );
define( 'NONCE_SALT',       'rGn3~EwpGY-ykj}{j0;)SC*9|1(LMLL{e=tn^WPcFO(VR iYUbJd4HN*lL7w?; x' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'tsm_';

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



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
