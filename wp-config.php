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
define('DB_NAME', 'fulihote_WPOX8');

/** Database username */
define('DB_USER', 'fulihote_WPOX8');

/** Database password */
define('DB_PASSWORD', '%?X*Z4R{:Z_EKqo.m');

/** Database hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY', '5bde2f6957b846693bae68c21c1449207ae2777f6e64d53fea7885ba9db56884');
define('SECURE_AUTH_KEY', '8b1cb02e42174f40b0372e10e141862897484999383e983670ed17eb5ecdc2fe');
define('LOGGED_IN_KEY', 'e1d6c90a511ffcc8a2818b153cbd5a6007cf4e11142465c7729fa05de23c0a75');
define('NONCE_KEY', '20595502ca127bcddd8ccf0059c4f3b6567c9c6a08bec7189221d0380550c012');
define('AUTH_SALT', '94dd3d3ca65e0c661ec90aeea03b90c34cf6476455f5444a944520fdcfb0fd06');
define('SECURE_AUTH_SALT', '1eb3311ea89397381b887f4f8966681460ff79e1cabe6444073c45e4f896597e');
define('LOGGED_IN_SALT', 'ef747622907514ba627b61352588385bc0fec75ceaa53ac608936c35890cc11d');
define('NONCE_SALT', '5810a2e1c066584a595396e6b30ef3994a47eafb80101f6d91387cbefa64785d');

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
$table_prefix = 'WaU_';
define('WP_CRON_LOCK_TIMEOUT', 120);
define('AUTOSAVE_INTERVAL', 300);
define('WP_POST_REVISIONS', 20);
define('EMPTY_TRASH_DAYS', 7);
define('WP_AUTO_UPDATE_CORE', true);

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
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
