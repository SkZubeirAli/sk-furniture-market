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
define( 'DB_NAME', 'sk_furniture' );

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
define( 'AUTH_KEY',         'qefU;uB.%;=mNiL3?KI2$V-F:V9$RN4 o%R:$m}1[[ )=Y]M?X#5NeXX3T.hfpHN' );
define( 'SECURE_AUTH_KEY',  ' &1?R4R}{zPmc#ddN^l3R6ue&dXQ*jS`HduRX!-3.gGe&bd+2<p8zGtW7kML^<Bh' );
define( 'LOGGED_IN_KEY',    'x6L!E.|&o}Co}EGmd2R-s>5>v -|3WX&YJ8t?TSbI{wYz1Z$Q)oY>>cOaARMq0]y' );
define( 'NONCE_KEY',        '^KIPzx%rJ@<!vf.X|=#* ?>+PHrOkNMa=4<v4^T]1i*!Q?$B*P9#R>/ X0jG2ylC' );
define( 'AUTH_SALT',        'S5mQ2Bub}y#q1a)foZ9f%y:>#5JyUd=#Y;)XL6,Wgp+Y}`<>J6yt/22}2LPGD#m0' );
define( 'SECURE_AUTH_SALT', 'YB1Og*~`l95RqW9~N:l`QFM[=`N_jn$z~!Ih4nar^f/Er{BNY)<YvmgY Bcc}Tpd' );
define( 'LOGGED_IN_SALT',   '8RUB,}xxryDd]tV@7ic~wHR!4Kh=,Q8#PO`mo%:q3N{(UqjOea6]Icp&>C%@hQlE' );
define( 'NONCE_SALT',       'O-:~KAYDyf;@u,cKf1t.16Mr/V@ay,#_#gC{8e2x`s0@]7-:9bQ>J EmmgwJe |Q' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'sk_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
