<?php
/**
 * Provides a consistent cookie API
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Cookie {
	/**
	 * Prevent direct object creation
	 * 
	 * @return Cookie
	 */
	private function __construct() {}

	/**
	 * Deletes a cookie
	 * 
	 * @param  string  $name    The cookie name to delete
	 * @param  string  $path    The path of the cookie to delete
	 * @param  string  $domain  The domain of the cookie to delete
	 * @param  boolean $secure  If the cookie is a secure-only cookie
	 * @return void
	 */
	public static function delete($name, $path = NULL, $domain = NULL, $secure = NULL) {
		self::set($name, '', time() - 43200, $path, $domain, $secure);
	}

	/**
	 * Gets a cookie value from $_COOKIE, while allowing a default value to be provided
	 * 
	 * @param  string $name           The name of the cookie to retrieve
	 * @param  mixed  $default_value  If there is no cookie with the name provided, return this value instead
	 * @return mixed  The value
	 */
	public static function get($name, $default_value = NULL) {
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default_value;
	}

	/**
	 * Set cookie
	 *
	 * Sets a cookie to be sent back to the browser
	 * 
	 * @param	string	$name		The name of the cookie to set
	 * @param	string	$value		Cookie value to be stored on the clients computer
	 * @param	int|string	$expire	A relative string to be interpreted by [http://php.net/strtotime strtotime()] or an integer unix timestamp
	 * @param	string	$domain		Cookie domain (e.g.: '.yourdomain.com')
	 * @param	string	$path		Cookie path (default: '/')
	 * @param	bool	$secure		If the cookie should only be transmitted over a secure connection SSL
	 * @param	bool	$httponly	If the cookie should only be readable by HTTP connection, not javascript
	 * @return	void
	 */
	public static function set($name = '', $value = '', $expires = NULL, $path = '', $domain = '', $secure = FALSE, $httponly = TRUE) {
        if ( ! is_int($expires)) {
            $expires = strtotime($expires);
        }
		
		// Use HTTP only cookie by default
		if ($httponly !== TRUE) {
			// Defines a cookie to be sent along with the rest of the HTTP headers
		    // Like other headers, cookies must be sent before any output from your script
			setcookie($name, $value, $expires, $path, $domain, $secure);
		} else {
		    // httponly added in PHP 5.2.0.
			// When TRUE the cookie will be made accessible only through the HTTP protocol. 
			// This means that the cookie won't be accessible by scripting languages, such as JavaScript.
			setcookie($name, $value, $expires, $path, $domain, $secure, $httponly);
		}
	}

}

/* End of file: ./system/core/cookie.php */