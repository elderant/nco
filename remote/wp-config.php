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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'ncoacces_d62' );

/** MySQL database username */
define( 'DB_USER', 'ncoacces_d62' );

/** MySQL database password */
define( 'DB_PASSWORD', 'BAF632ED1rd0u7k4i8l9b5' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '$::|`;!J31yRB019LS_I(ug|z8=EGd@%)R@xvTkE>E6Wm&FKXS.M2(dSJ/4KB6I3' );
define( 'SECURE_AUTH_KEY',   'HKaE_ }$Zi&90]];h}[Sv?.t,-SVdX[f7-9t(Q?_>g-s0SvV_6aiIIW8?xN2J^=D' );
define( 'LOGGED_IN_KEY',     'x{K$LzduX _)[5vByO*v/8<`-GuzR%+GDQe dVY^ Dqe$Q_`L&6M0Dtt6@M3l@#T' );
define( 'NONCE_KEY',         '?.]+(]wsi|#;mlvZe)9`bs|l,/%BFpf-Iz_.Nc_.z.ydQFO.qE[;BcnGNbgIJ}oM' );
define( 'AUTH_SALT',         'gb1LQ:qaMX5XdFI!-lbRtAi~7/1,HG2_}0?RC|AbTN0xT-jEL{ndegw)gf;P0{<a' );
define( 'SECURE_AUTH_SALT',  'N:gQ#f7r*NU/OVgXpn9zIfd%d_&`kJrl)ZPWkw8-acjCp23ehrVk/o/:Fmv[m|X;' );
define( 'LOGGED_IN_SALT',    'LcvS@VX!AV{RTq{7jxMgAmbe`DbuKpOeMoz,Co,U8BB`slW^LWB2e[c&`!G36MEL' );
define( 'NONCE_SALT',        'fxape/3HyzXv#.3%p<dftZYji;}h>2FHY =T@Rp}S9eeJg[/ bW>gGW:,JGQ~pB6' );
define( 'WP_CACHE_KEY_SALT', '6N?#qV+;9D7pB_Jl~2qPAxhmMfbnlm`a*KKC9U1#]>{I~k]IxaH9x3+1mHPjHXIa' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'd62_';



define( 'AUTOSAVE_INTERVAL',    300  );
define( 'WP_POST_REVISIONS',    5    );
define( 'EMPTY_TRASH_DAYS',     7    );
define( 'WP_AUTO_UPDATE_CORE',  true );
define( 'WP_CRON_LOCK_TIMEOUT', 120  );


// define('WP_DEBUG', false);
define('WP_DEBUG', true );
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', true);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
