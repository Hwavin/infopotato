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
 * Set public accessible web root, ending with the trailing slash '/'
 * This BASE_URI constant will be available globally 
 */
define('BASE_URI', 'http://localhost/infopotato/index.php/');

/**
 * '\' for Windows, '/' for Unix
 * DIRECTORY_SEPARATOR is not necessary, only the forward slash, '/' should be fine
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Framework system/application directories
 */
define('SYS_DIR', dirname(__FILE__).DS.'system'.DS);
define('APP_DIR', dirname(__FILE__).DS.'application'.DS);
define('APP_TEMPLATE_DIR', APP_DIR.'templates'.DS);
define('APP_DATA_DIR', APP_DIR.'data'.DS);
define('APP_CONFIG_DIR', APP_DIR.'configs'.DS);
define('APP_WORKER_DIR', APP_DIR.'workers'.DS);
define('APP_LIBRARY_DIR', APP_DIR.'libraries'.DS);

/**
 * Default presenter/action if none is given in the URL, case-sensetive 
 */
define('DEFAULT_WORKER', 'home');


/**
 * Default allowed URL Characters (UTF-8 encoded characters)
 *
 * By default only these are allowed: a-z 0-9~%.:_-
 * Leave blank to allow all characters
 */
define('PERMITTED_URI_CHARS', 'a-z 0-9~%@.:_\-');

/**
 * User-defined constants go here
 */
define('APP_CACHE_DIR', APP_DIR.'cache'.DS);
//define('APP_UPLOAD_DIR', APP_DIR.'upload'.DS);
//define('APP_DOWNLOAD_DIR', APP_DIR.'download'.DS);
//define('APP_SESSION_DIR', APP_DIR.'session'.DS);

/**
 * Dispatch, prepare, response 
 */
require_once(SYS_DIR.'infopotato_app.php');
$app = new InfoPotato_App;
$app->run();

// End of file: ./index.php 

