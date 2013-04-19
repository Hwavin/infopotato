<?php
/**
 * Provides a consistent cookie API, HTTPOnly compatibility with older PHP versions and default parameters
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link    based on   http://flourishlib.com/fCookie
 */
class Cookie {
	/**
	 * The default domain to set for cookies
	 * 
	 * @var string
	 */
	private static $default_domain = NULL;
	
	/**
	 * The default expiration date to set for cookies
	 * 
	 * @var string|integer
	 */
	private static $default_expires = NULL;
	
	/**
	 * If cookies should default to being http-only
	 * Set the HTTPOnly flag on your session cookie and any custom cookies to prevent XSS
	 * 
	 * @var bool
	 */
	private static $default_httponly = TRUE;
	
	/**
	 * The default path to set for cookies
	 * 
	 * @var string
	 */
	private static $default_path = NULL;
	
	/**
	 * If cookies should default to being secure-only
	 * 
	 * @var bool
	 */
	private static $default_secure = FALSE;
	
	/**
	 * Prevent direct object creation
	 * 
	 * @return Cookie
	 */
	private function __construct() {}
	
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
		self::$default_domain = NULL;
		self::$default_expires = NULL;
		self::$default_httponly = FALSE;
		self::$default_path = NULL;
		self::$default_secure = FALSE;
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
	 
	 /**
	 * Set cookie
	 *
	 * Sets a cookie to be sent back to the browser - uses default parameters set by the other set methods of this class
	 * 
	 *
	 * @param	string	$name		The name of the cookie to set
	 * @param	string	$value		Cookie value to be stored on the clients computer
	 * @param	int		$expire		A relative string to be interpreted by [http://php.net/strtotime strtotime()] or an integer unix timestamp
	 * @param	string	$domain		Cookie domain (e.g.: '.yourdomain.com')
	 * @param	string	$path		Cookie path (default: '/')
	 * @param	bool	$secure		If the cookie should only be transmitted over a secure connection SSL
	 * @param	bool	$httponly	If the cookie should only be readable by HTTP connection, not javascript
	 * @return	void
	 */
	public static function set($name, $value, $expires = NULL, $path = NULL, $domain = NULL, $secure = NULL, $httponly = NULL) {
		if ($expires === NULL && self::$default_expires !== NULL) {
			$expires = self::$default_expires;	
		}
		
		if ($path === NULL && self::$default_path !== NULL) {
			$path = self::$default_path;	
		}
		
		if ($domain === NULL && self::$default_domain !== NULL) {
			$domain = self::$default_domain;	
		}
		
		if ($secure === NULL && self::$default_secure !== NULL) {
			$secure = self::$default_secure;	
		}
		
		if ($httponly === NULL && self::$default_httponly !== NULL) {
			$httponly = self::$default_httponly;	
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
		// Like other headers, cookies must be sent before any output from your script
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
		self::$default_domain = $domain;	
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
		self::$default_expires = $expires;	
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
		self::$default_httponly = $httponly;	
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
		self::$default_path = $path;	
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
		self::$default_secure = $secure;	
	}
	
}

/* End of file: ./system/core/cookie.php */