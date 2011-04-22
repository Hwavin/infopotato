<?php
/**
 * This file acts as the "Single Point of Entry" to your application, all requests are sent to index.php.
 * This file is responsible for initializing the framework: loading files, reading configuration data, 
 * parsing the URI into actionable information, and populating the objects that encapsulate the request. 
 */

if (version_compare(PHP_VERSION, '5.2.0', '<')) {
	exit('InfoPotato requires PHP version 5.2.0 or newer');
}
 
/**
 * Define the application environment
 * Usage: development or production
 */
define('ENVIRONMENT', 'development');
 
/**
 * Set the PHP error reporting level. If you set this in php.ini, you remove this.
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In production environments, it is typically desirable to disable PHP's error reporting
 * by setting the internal error_reporting flag to a value of 0.
 */
switch (ENVIRONMENT) {
	case 'development':
		error_reporting(E_ALL | E_STRICT);
	break;

	case 'production':
		error_reporting(0);
	break;

	default:
		exit('The application environment is not set correctly.');
}

/**
 * Define InfoPotato Version
 */
define('INFOPOTATO_VERSION', '1.0.0');

/**
 * Set default time zone used by all date/time functions
 */
date_default_timezone_set('America/New_York');

/**
 * There are two types of requests, static requests and application requests.
 * APP_URI is used for static requests to access static assets (images) access, sometimes CDN is used
 * APP_ENTRY_URI is used for application requests to access resources that need InfoPotato to further process
 * Must end with the trailing slash '/'
 */
define('STATIC_URI_BASE', 'http://localhost/infopotato/application/');
define('APP_URI_BASE', 'http://localhost/infopotato/index.php/');

/**
 * Shorthand directory separator constant, '\' for Windows, '/' for Unix
 * DIRECTORY_SEPARATOR is not necessary, only the forward slash, '/' should be fine
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Framework system/application directories
 */
define('SYS_DIR', dirname(__FILE__).DS.'system'.DS);
define('APP_DIR', dirname(__FILE__).DS.'application'.DS);
define('APP_DATA_DIR', APP_DIR.'data'.DS);
define('APP_CONFIG_DIR', APP_DIR.'configs'.DS);
define('APP_MANAGER_DIR', APP_DIR.'managers'.DS);
define('APP_LIBRARY_DIR', APP_DIR.'libraries'.DS);
define('APP_FUNCTION_DIR', APP_DIR.'functions'.DS);
define('APP_TEMPLATE_DIR', APP_DIR.'templates'.DS);

/**
 * Default manager if none is given in the URL, case-sensetive 
 */
define('DEFAULT_MANAGER', 'home');


/**
 * Default allowed URL Characters (UTF-8 encoded characters)
 *
 * By default only these are allowed: a-z 0-9~%.:_-
 * Leave blank to allow all characters
 */
define('PERMITTED_URI_CHARS', 'a-z 0-9~%.:_-');

/**
 * User-defined constants go here
 */
//define('APP_CACHE_DIR', APP_DIR.'cache'.DS);
//define('APP_UPLOAD_DIR', APP_DIR.'upload'.DS);
define('APP_DOWNLOAD_DIR', APP_DIR.'downloads'.DS);
//define('APP_SESSION_DIR', APP_DIR.'session'.DS);

/**
 * Dispatch, prepare, response 
 */
require(SYS_DIR.'infopotato_app.php');
InfoPotato_App::run();

// End of file: ./index.php 

