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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'gopuyagadi0032_5oXfLh6cQNinCEFrPMVb' );

/** Database username */
define( 'DB_USER', 'gopuyagadi0032_yewecahuja2951' );

/** Database password */
define( 'DB_PASSWORD', 'aqANy7ri9B0sV1wLITpv' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          '$wSX2_{4)vCjLL!5$Y-*i{|29vR7>0G_-]p*y5 ;Z(G?nq|S{{eWL2@egZuTj6o|' );
define( 'SECURE_AUTH_KEY',   '`qok?&1{A:cQA`SXbPQ:Y-Ob=v{mM/2/`d6[vSxt.El*,en@S4Puj]qC~B?_qkN3' );
define( 'LOGGED_IN_KEY',     'SY{0zc/`P>^mogia}Ta>(E/1#T(p8up<Nha?<!U}jnw3e8SIg@xuU%SY$MB66<kE' );
define( 'NONCE_KEY',         '+ssTw$(zlIO..^B^53D:|RU0%_=3X :uA?[^o>r|9V4IK0H~jG^7h]C2yJ,0J)Zn' );
define( 'AUTH_SALT',         '&*2qqgTJ|v&U1V4 Xvkzx,qj3i=3]P3~3{|L#OOka3*ez?b0?GR1~8:<ZDzd3/P0' );
define( 'SECURE_AUTH_SALT',  '<j&&_C^S,,_;aE6xrv/*4X$b:5(~}:f^l49LM>%Rqo[cgvV( SI)/0IBYL.MU%z?' );
define( 'LOGGED_IN_SALT',    'G/phV?(<vh[j Gl2]i+=+O?[9JN+A85(q<)X)i|T/%2ihdNqs;6+W+.F_6!Ihp-u' );
define( 'NONCE_SALT',        'pqXhB`j});}znh)S2V82)5[I&$Je6~VjYJf)*OQRdx5 yZY9_@26CO019|W.].tg' );
define( 'WP_CACHE_KEY_SALT', 'X7]s4b0h<1=4%8tsczbhNUwDTPm5C,BAj@%f+qPu ykbU|>GBzF?Z(nDCZ72f Ji' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'iwpba70_';


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

define( 'WP_AUTO_UPDATE_CORE', false );
define( 'WP_DEBUG_LOG', false );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
