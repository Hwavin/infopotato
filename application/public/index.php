<?php
/**
 * This file acts as the "Bootstrap" to your application, all requests are sent to index.php.
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
define('ENVIRONMENT', 'production');

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
 * STATIC_URI_BASE is used for static requests to access static assets (images) access, sometimes CDN is used
 * APP_URI_BASE is used for application requests to access resources that need InfoPotato to further process
 * Must end with the trailing slash '/'
 */
define('STATIC_URI_BASE', 'http://localhost/infopotato/application/public/');
define('APP_URI_BASE', 'http://localhost/infopotato/application/public/index.php/');

/**
 * Shorthand directory separator constant
 * On Windows, both slash (/) and backslash (\) are used as directory separator character. 
 * In other platforms, it is the forward slash (/).
 * DIRECTORY_SEPARATOR is not necessary, only the forward slash, '/' should be fine ???
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Framework system/application directories
 * You can specify the absolute DIR path
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
define('DEFAULT_MANAGER', 'home');
define('DEFAULT_MANAGER_METHOD', 'index');

/**
 * If cache the system core components to runtime files
 */
define('SYS_RUNTIME_CACHE', TRUE);

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
require_once SYS_DIR.'app_dispatcher.php';
App_Dispatcher::run();

// End of file: ./index.php 

