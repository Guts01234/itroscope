<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'roscope_bd' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '/>y4.ELv8?hDMUna~hM.ql;FxdNB%hL3.J(s%jBpO/q1K~K`#IAMI+qZXULoFwNp' );
define( 'SECURE_AUTH_KEY',  '* nY^(~r5Aoj>o,*oDAJCa^zBCnr-t>j%l8j9&&r tol8-8lGd,9t/AA^+9k}Mn5' );
define( 'LOGGED_IN_KEY',    'wJx9Vj>,%R^paWKTGiul29z JKB8B;)OX|?f=h{NkgP:,l#uyq[cXI4ju m7IV#=' );
define( 'NONCE_KEY',        '[n(Ta#My`2wvBN*v0M*d`::osU?<Ndg|W(;DsEBKy3:i=lV;K$_Ez}1KS?.970Hb' );
define( 'AUTH_SALT',        ':`b*P-5 }TZz+X9PESo7=OjLRZ4Gw29;R3dp3{,.21:rKPlZg2H^&.co$e9W$=k6' );
define( 'SECURE_AUTH_SALT', 'q dEg&E_wKKMkrVLDUOl2^ERQ/*FS:FT_K4}Ldscvu=m][4l62-S/=zQh2 h/0hQ' );
define( 'LOGGED_IN_SALT',   ']}Np%g4,Ptn`nT^_pnm+xQAq*ys`NE?zZFCjn7*)jAQ**F9y$yZDI/F{-7DksdJd' );
define( 'NONCE_SALT',       ',W+,7fdd[`00!Cx(XzHY5 |Jp#O{i$h/C|qI?X# ayE9_(<2MA;`;$Q0y,<hcX*_' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
