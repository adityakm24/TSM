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
define( 'DB_NAME', 'tsm' );

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
define( 'AUTH_KEY',         '^K)czz7;*9Ij=yFWlGuC)thP;NV.=qL}C:&?ehk-%VD|*t1xJ>(bMwf;JxI&2H)@' );
define( 'SECURE_AUTH_KEY',  '}OBSk)Gn/[%OqGJb(3KA}ean]-Hf,IRw_|54yW,TCaCglt[azx;CeJVHPq(r>9OT' );
define( 'LOGGED_IN_KEY',    'W3Z!$>iWidKF?HpaU.5 WX$&`xf54uex^F /Y9q*o=kW^ZGWd|nnmR,]vMn@AIx!' );
define( 'NONCE_KEY',        'VAz=K:t_IidL.-ZVPSBlUyWTiRm Cm<&2p(#GuoetFNNk+8RBYpOvy5-23D3en=]' );
define( 'AUTH_SALT',        '5/$&-#{[7o_I_(}fN&372r2Ct>_:BO|0kkiMuC7d4Ait|9}nV$h<kYsW[?JwOZ}_' );
define( 'SECURE_AUTH_SALT', '2pd`VGqa(Jl}<&(lh:fv/Z_*)E4yDhsS>)tVLPu}2$}&`/w.c=[?-CjJ~n h]i(;' );
define( 'LOGGED_IN_SALT',   'xkC8GeITFQMA#:v+TIti$q)xnv4IjR`D0&**RQr42nrt_UXxvDo,9k>L grz%r}J' );
define( 'NONCE_SALT',       '@$+2=Xp{V&y@>a7k]QL?mgwwO=nX=4cK/:x?ci+P9fCP5w4W>E[]IySW&Lof~9&C' );

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
