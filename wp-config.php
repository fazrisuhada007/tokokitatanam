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
define( 'DB_NAME', 'ekitatanam' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '8[ITtA5eOfkUXo_wAb=V/Gwi%W}I.3}US$FG_*6_O0&hJAKr Zlw&s1h{9+#~4pr' );
define( 'SECURE_AUTH_KEY',  ')xIy^{ppR0v`E=okxjKVF}HA,SMmk|O<IaEp)_9<<G?_DCkW+5t699kim5X7jD@Y' );
define( 'LOGGED_IN_KEY',    'I]3h=RT0oTnb2r=d1=w-%M8RTgUm/?sY[o3CP{:%qo: )1{FMf^;gmZ#?kraP``n' );
define( 'NONCE_KEY',        'CG^J4BF~UkYdk{+~ mLycd=`:yd/g>6c2ubhvwWwToT&TUF7=bW_kfZ=qUAWQb<.' );
define( 'AUTH_SALT',        '84z0y!|=a!FgOt}8kluXKv;Izu,sVk]>Mb?_X:|vgpeW)7dQ@jmNmYQu7`;<bQAJ' );
define( 'SECURE_AUTH_SALT', 'shd^ouF*:7f6,FH| ,mhbtHjIO8zh.q+?wZeDPZBD/`_N(CV3(H>$UN<eKQs):]H' );
define( 'LOGGED_IN_SALT',   '@s2P#+;-pYWk=4gz8|P&!;5t?tT5h -6^Es<tn7E>L=Lbl1ea?/H2qe=o |}Byk*' );
define( 'NONCE_SALT',       '.Ra$kYyb-hdRnE6(zH0A8Fb@|A/2A$;S=<v3},BR)/C_|E&ekxCZ/[[}y+ZcBn)L' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
