<?php
/**
 * Provides a consistent cookie API, HTTPOnly compatibility with older PHP versions and default parameters
 * 
 * @copyright  Copyright (c) 2008-2009 Will Bond, others
 * @author     Will Bond [wb] <will@flourishlib.com>
 * @author     Nick Trew [nt]
 * @license    http://flourishlib.com/license
 * 
 * @package    Flourish
 * @link       http://flourishlib.com/fCookie
 * 
 * @version    1.0.0b3
 * @changes    1.0.0b3  Added the ::delete() method [nt+wb, 2009-09-30]
 * @changes    1.0.0b2  Updated for new fCore API [wb, 2009-02-16]
 * @changes    1.0.0b   The initial implementation [wb, 2008-09-01]
 */
class Cookie {
	/**
	 * The default domain to set for cookies
	 * 
	 * @var string
	 */
	private static $_default_domain = NULL;
	
	/**
	 * The default expiration date to set for cookies
	 * 
	 * @var string|integer
	 */
	private static $_default_expires = NULL;
	
	/**
	 * If cookies should default to being http-only
	 * 
	 * @var boolean
	 */
	private static $_default_httponly = FALSE;
	
	/**
	 * The default path to set for cookies
	 * 
	 * @var string
	 */
	private static $_default_path = NULL;
	
	/**
	 * If cookies should default to being secure-only
	 * 
	 * @var boolean
	 */
	private static $_default_secure = FALSE;
	
	/**
	 * Forces use as a static class
	 * 
	 * @return Cookie
	 */
	private function __construct() { }
	
	/**
	 * Deletes a cookie - uses default parameters set by the other set methods of this class
	 * 
	 * @param  string  $name    The cookie name to delete
	 * @param  string  $path    The path of the cookie to delete
	 * @param  string  $domain  The domain of the cookie to delete
	 * @param  boolean $secure  If the cookie is a secure-only cookie
	 * @return void
	 */
	public static function delete($name, $path = NULL, $domain = NULL, $secure = NULL) {
		self::set($name, '', time() - 86400, $path, $domain, $secure);
	}
	
	
	/**
	 * Gets a cookie value from `$_COOKIE`, while allowing a default value to be provided
	 * 
	 * @param  string $name           The name of the cookie to retrieve
	 * @param  mixed  $default_value  If there is no cookie with the name provided, return this value instead
	 * @return mixed  The value
	 */
	public static function get($name, $default_value = NULL) {
		if (isset($_COOKIE[$name])) {
			$value = UTF8::clean($_COOKIE[$name]);
			if (get_magic_quotes_gpc()) {
				$value = stripslashes($value);
			}
			return $value;
		}
		return $default_value;
	}
	
	
	/**
	 * Resets the configuration of the class
	 * 
	 * @internal
	 * 
	 * @return void
	 */
	public static function reset() {
		self::$_default_domain = NULL;
		self::$_default_expires = NULL;
		self::$_default_httponly = FALSE;
		self::$_default_path = NULL;
		self::$_default_secure = FALSE;
	}
	
	
	/**
	 * Sets a cookie to be sent back to the browser - uses default parameters set by the other set methods of this class
	 * 
	 * The following methods allow for setting default parameters for this method:
	 *   
	 *  - ::set_default_expires():  Sets the default for the `$expires` parameter
	 *  - ::set_default_path():     Sets the default for the `$path` parameter
	 *  - ::set_default_domain():   Sets the default for the `$domain` parameter
	 *  - ::set_default_secure():   Sets the default for the `$secure` parameter
	 *  - ::set_default_httponly(): Sets the default for the `$httponly` parameter
	 * 
	 * @param  string         $name      The name of the cookie to set
	 * @param  mixed          $value     The value of the cookie to set
	 * @param  string|integer $expires   A relative string to be interpreted by [http://php.net/strtotime strtotime()] or an integer unix timestamp
	 * @param  string         $path      The path this cookie applies to
	 * @param  string         $domain    The domain this cookie applies to
	 * @param  boolean        $secure    If the cookie should only be transmitted over a secure connection
	 * @param  boolean        $httponly  If the cookie should only be readable by HTTP connection, not javascript
	 * @return void
	 */
	public static function set($name, $value, $expires = NULL, $path = NULL, $domain = NULL, $secure = NULL, $httponly = NULL) {
		if ($expires === NULL && self::$_default_expires !== NULL) {
			$expires = self::$_default_expires;	
		}
		
		if ($path === NULL && self::$_default_path !== NULL) {
			$path = self::$_default_path;	
		}
		
		if ($domain === NULL && self::$_default_domain !== NULL) {
			$domain = self::$_default_domain;	
		}
		
		if ($secure === NULL && self::$_default_secure !== NULL) {
			$secure = self::$_default_secure;	
		}
		
		if ($httponly === NULL && self::$_default_httponly !== NULL) {
			$httponly = self::$_default_httponly;	
		}
		
		if ($expires && ! is_numeric($expires)) {
			$expires = strtotime($expires);	
		}
		
		// httponly added in PHP 5.2.0.
		// When TRUE the cookie will be made accessible only through the HTTP protocol. 
		// This means that the cookie won't be accessible by scripting languages, such as JavaScript.
		if (strlen($value) && $httponly) {
			setcookie($name, $value, $expires, $path, $domain, $secure, TRUE);
			return; 		
		}
		// Defines a cookie to be sent along with the rest of the HTTP headers
		setcookie($name, $value, $expires, $path, $domain, $secure);
	}
	
	
	/**
	 * Sets the default domain to use for cookies
	 * 
	 * This value will be used when the `$domain` parameter of the ::set()
	 * method is not specified or is set to `NULL`.
	 * 
	 * @param  string $domain  The default domain to use for cookies
	 * @return void
	 */
	public static function set_default_domain($domain) {
		self::$_default_domain = $domain;	
	}
	
	
	/**
	 * Sets the default expiration date to use for cookies
	 * 
	 * This value will be used when the `$expires` parameter of the ::set()
	 * method is not specified or is set to `NULL`.
	 * 
	 * @param  string|integer $expires  The default expiration date to use for cookies
	 * @return void
	 */
	public static function set_default_expires($expires) {
		self::$_default_expires = $expires;	
	}
	
	
	/**
	 * Sets the default httponly flag to use for cookies
	 * 
	 * This value will be used when the `$httponly` parameter of the ::set()
	 * method is not specified or is set to `NULL`.
	 * 
	 * @param  boolean $httponly  The default httponly flag to use for cookies
	 * @return void
	 */
	public static function set_default_httponly($httponly) {
		self::$_default_httponly = $httponly;	
	}
	
	
	/**
	 * Sets the default path to use for cookies
	 * 
	 * This value will be used when the `$path` parameter of the ::set()
	 * method is not specified or is set to `NULL`.
	 * 
	 * @param  string $path  The default path to use for cookies
	 * @return void
	 */
	public static function set_default_path($path) {
		self::$_default_path = $path;	
	}
	
	
	/**
	 * Sets the default secure flag to use for cookies
	 * 
	 * This value will be used when the `$secure` parameter of the ::set()
	 * method is not specified or is set to `NULL`.
	 * 
	 * @param  boolean $secure  The default secure flag to use for cookies
	 * @return void
	 */
	public static function set_default_secure($secure) {
		self::$_default_secure = $secure;	
	}
	
}

/* End of file: ./system/core/cookie.php */