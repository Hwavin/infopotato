<?php
/**
 * This file acts as the "Bootstrap" to your application, all requests are sent to index.php.
 * This file is responsible for initializing the framework: loading files, reading configuration data, 
 * parsing the URI into actionable information, and populating the objects that encapsulate the request. 
 */

/**
 * Define the application environment
 * Usage: development or production
 */
define('ENVIRONMENT', 'development');

/**
 * Set default time zone used by all date/time functions
 */
date_default_timezone_set('America/New_York');

/**
 * There are two types of requests, static requests and application requests.
 * STATIC_URI_BASE is used for static requests to access static assets (images) access, sometimes CDN is used
 * APP_URI_BASE is used for application requests to access resources that need InfoPotato to further process
 * Must end with the trailing slash '/'
 */
define('STATIC_URI_BASE', 'http://localhost/infopotato/application/public/');
define('APP_URI_BASE', 'http://localhost/infopotato/application/public/dev.php/');

/**
 * Shorthand directory separator constant
 * On Windows, both slash (/) and backslash (\) are used as directory separator character. 
 * In other platforms, it is the forward slash (/).
 * DIRECTORY_SEPARATOR is not necessary, only the forward slash, '/' should be fine ???
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Framework system/application directories
 */
define('SYS_DIR', dirname(dirname(dirname(__FILE__).DS)).DS.'system'.DS);
define('SYS_CORE_DIR', SYS_DIR.'core'.DS);
define('SYS_RUNTIME_DIR', SYS_DIR.'runtime'.DS);
define('SYS_LIBRARY_DIR', SYS_DIR.'libraries'.DS);
define('SYS_FUNCTION_DIR', SYS_DIR.'functions'.DS);

define('APP_DIR', dirname(dirname(__FILE__).DS).DS);
define('APP_I18N_DIR', APP_DIR.'i18n'.DS);
define('APP_DATA_DIR', APP_DIR.'data'.DS);
define('APP_CONFIG_DIR', APP_DIR.'configs'.DS);
define('APP_MANAGER_DIR', APP_DIR.'managers'.DS);
define('APP_LIBRARY_DIR', APP_DIR.'libraries'.DS);
define('APP_FUNCTION_DIR', APP_DIR.'functions'.DS);
define('APP_TEMPLATE_DIR', APP_DIR.'templates'.DS);

/**
 * Default manager/method to use if none is given in the URL, lowercase
 */
define('APP_DEFAULT_MANAGER', 'home');
define('APP_DEFAULT_MANAGER_METHOD', 'index');

/**
 * If cache the system core components to runtime files
 */
define('SYS_RUNTIME_CACHE', FALSE);

/**
 * User-defined constants go here
 */
//define('APP_CACHE_DIR', APP_DIR.'cache'.DS);
//define('APP_UPLOAD_DIR', APP_DIR.'upload'.DS);
define('APP_DOWNLOAD_DIR', APP_DIR.'downloads'.DS);
define('APP_SESSION_DIR', APP_DIR.'session'.DS);

// Components for init, debug, autoloading, internationalization function
 if (SYS_RUNTIME_CACHE === TRUE) {
	// SYS_RUNTIME_DIR must be writable
	if ( ! is_writable(SYS_RUNTIME_DIR)) {
	    exit('SYS_RUNTIME_DIR must be writable');
	}
	$file = SYS_RUNTIME_DIR.'~init.php';
	if ( ! file_exists($file)) {
	    // Return source with stripped comments and whitespace
		file_put_contents($file, php_strip_whitespace(SYS_CORE_DIR.'init.php'));
	}
} else {
	$file = SYS_CORE_DIR.'init.php';
}
require_once $file;

// Set session directory, must be writable
// Set session normal length as 30 mins
Session::init(APP_SESSION_DIR, '30 minutes');

// Dispatch the incoming request
dispatch();

// End of file: ./dev.php 

