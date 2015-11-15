<?php

	define( 'Language', 'en');
    define( 'SUBDIR', '' ); // either empty or has to end with a slash "/"
    //ini_set( "display_errors", true);
    error_reporting(E_ALL);
    date_default_timezone_set( 'Asia/Tehran' ); // set according to php.net/timezones
    ini_set( 'default_charset', 'UTF-8' ); // default charset for database
    header( 'Contfat-type: text/html; charset=UTF-8' ) ;

    define( 'DB_NAME', 'cc' );
    define( 'DB_LOC', 'localhost' );
    define( 'DB_USER', 'root' );
    define( 'DB_PASS', '' );

    define( 'DOC_ROOT', __DIR__);
    define( 'SITE_DOMAIN', 'http://' . getenv('HTTP_HOST') . '/' . SUBDIR); // returns site domain with SUBDIR (if available)
    define( 'CLASS_PATH', DOC_ROOT . '/classes' );
    define( 'INCLUDE_FIX', 'c_contents/themes/ccms/' ); // change 'ccms' to your themes name
    define( 'THEME_BASE', SUBDIR . INCLUDE_FIX );

    // location and prefix for cache files
    define('CACHE_PATH', DOC_ROOT . '/tmp/cache_');
    // how long to keep the cache files (hours)
    define('CACHE_TIME', 12);

    require_once 'includes/f_init.php';