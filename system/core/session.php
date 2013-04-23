<?php
/**
 * Wraps the session control functions and the `$_SESSION` superglobal for a more consistent and safer API
 *
 * A `Cannot send session cache limiter` warning will be triggered if 
 * ::add(), ::clear(), ::delete(), ::get() or ::set() is called after output has
 * been sent to the browser. 
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link   based on    http://flourishlib.com/fSession
 */
class Session {

	/**
	 * The length for a normal session
	 * 
	 * @var integer 
	 */
	private static $normal_timespan = NULL;
	
	/**
	 * If the session is open
	 * 
	 * @var boolean 
	 */
	private static $open = FALSE;
	
	/**
	 * The length for a persistent session cookie - one that survives browser restarts
	 * 
	 * @var integer 
	 */
	private static $persistent_timespan = NULL;
	
	/**
	 * If the session ID was regenerated during this script
	 * 
	 * @var boolean 
	 */
	private static $regenerated = FALSE;
	
	/**
	 * Prevent direct object creation
	 * 
	 * @return Session
	 */
	private function __construct() {}
	
	/**
	 * Adds a value to an already-existing array value, or to a new array value
	 *
	 * @param  string  $key        The name to access the array under - array elements can be modified via `[sub-key]` syntax, and thus `[` and `]` can not be used in key names
	 * @param  mixed   $value      The value to add to the array
	 * @param  boolean $beginning  If the value should be added to the beginning
	 * @return void
	 */
	public static function add($key, $value, $beginning = FALSE) {
		self::open();
		$tip =& $_SESSION;
		
		if ($bracket_pos = strpos($key, '[')) {
			$original_key = $key;
			$array_dereference = substr($key, $bracket_pos);
			$key = substr($key, 0, $bracket_pos);
			
			preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER);
			// The current() callback function returns the value of the array element that's currently being pointed to by the internal pointer.
			$array_keys = array_map('current', $array_keys);
			array_unshift($array_keys, $key);
			
			foreach (array_slice($array_keys, 0, -1) as $array_key) {
				if ( ! isset($tip[$array_key])) {
					$tip[$array_key] = array();
				} elseif ( ! is_array($tip[$array_key])) {
					halt('A System Error Was Encountered', "Session::add() was called for the key, {$original_key}, which is not an array", 'sys_error');
				}
				$tip =& $tip[$array_key];
			}
			$key = end($array_keys);
		}
		
		
		if ( ! isset($tip[$key])) {
			$tip[$key] = array();
		} elseif ( ! is_array($tip[$key])) {			
			halt('A System Error Was Encountered', "Session::add() was called for the key, {$key}, which is not an array", 'sys_error');
		}
		
		if ($beginning) {
			array_unshift($tip[$key], $value);
		} else {
			$tip[$key][] = $value;
		}
	}
	
	
	/**
	 * Removes all session values with the provided prefix
	 * 
	 * This method will not remove session variables used by this class, which
	 * are prefixed with `SESSION::`.
	 * 
	 * @param  string $prefix  The prefix to clear all session values for
	 * @return void
	 */
	public static function clear($prefix = NULL) {
		self::open();
		
		$session_type = $_SESSION['SESSION::type'];
		$session_expires = $_SESSION['SESSION::expires'];
		
		if ($prefix) {
			foreach ($_SESSION as $key => $value) {
				if (strpos($key, $prefix) === 0) {
					unset($_SESSION[$key]);
				}
			}
		} else {
			$_SESSION = array();		
		}
		
		$_SESSION['SESSION::type'] = $session_type;
		$_SESSION['SESSION::expires'] = $session_expires;
	}
	
	
	/**
	 * Closes the session for writing, allowing other pages to open the session
	 * 
	 * @return void
	 */
	public static function close() {
		if ( ! self::$open) { 
			return; 
		}
		
		session_write_close();
		unset($_SESSION);
		self::$open = FALSE;
	}
	
	
	/**
	 * Deletes a value from the session
	 * 
	 * @param  string $key            The key of the value to delete - array elements can be modified via `[sub-key]` syntax, and thus `[` and `]` can not be used in key names
	 * @param  mixed  $default_value  The value to return if the `$key` is not set
	 * @return mixed  The value of the `$key` that was deleted
	 */
	public static function delete($key, $default_value = NULL) {
		self::open();
		
		$value = $default_value;
		
		if ($bracket_pos = strpos($key, '[')) {
			$original_key = $key;
			$array_dereference = substr($key, $bracket_pos);
			$key = substr($key, 0, $bracket_pos);
			
			if ( ! isset($_SESSION[$key])) {
				return $value;
			}
			
			preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER);
			$array_keys = array_map('current', $array_keys);
			
			$tip =& $_SESSION[$key];
			
			foreach (array_slice($array_keys, 0, -1) as $array_key) {
				if ( ! isset($tip[$array_key])) {
					return $value;
				} elseif ( ! is_array($tip[$array_key])) {
					halt('A System Error Was Encountered', "Session::delete() was called for an element, {$original_key}, which is not an array", 'sys_error');
				}
				$tip =& $tip[$array_key];
			}
			
			$key = end($array_keys);
		} else {
			$tip =& $_SESSION;
		}
		
		if (isset($tip[$key])) {
			$value = $tip[$key];
			unset($tip[$key]);
		}
		
		return $value;
	}
	
	
	/**
	 * Destroys the session, removing all values
	 * 
	 * @return void
	 */
	public static function destroy() {
		self::open();
		// Unset all the session variables on server side
		$_SESSION = array();
		unset($_SESSION);
		// Delete the session cookie
		// PHPSESSID is the default session_name
		if (isset($_COOKIE[session_name()])) {
			$params = session_get_cookie_params();
			// If it's desired to kill the session, also delete the session cookie
			// In fact no need to destroy the session cookie because the ID in the cookie should be invalid.
			// It would still be smart to remove the session cookie in order to increase cacheability 
			// of anonymous content with caches such as Varnish.
			// 43200 = 60*60*12 means12 hours ahead of current timestamp
			setcookie(session_name(), '', time() - 43200, $params['path'], $params['domain'], $params['secure']);
		}
		// Destroy all the data associated with the current session
		// This does not unset the session cookie
		session_destroy();
	}
	
	
	/**
	 * Changed the session to use a time-based cookie instead of a session-based cookie
	 * 
	 * The length of the time-based cookie is controlled by ::init(). When
	 * this method is called, a time-based cookie is used to store the session
	 * ID. This means the session can persist browser restarts. Normally, a
	 * session-based cookie is used, which is wiped when a browser is closed.
	 * 
	 * This method should be called during the login process and will normally
	 * be controlled by a checkbox or similar where the user can indicate if
	 * they want to stay logged in for an extended period of time.
	 * 
	 * @return void
	 */
	public static function enable_persistence() {
		if (self::$persistent_timespan === NULL) {
			halt('A System Error Was Encountered', "The method Session::init() must be called with the '$persistent_timespan' parameter before calling Session::enable_persistence()", 'sys_error');
		}
		
		$current_params = session_get_cookie_params();

		// This sets the lifetime of the session cookie to self::$persistent_timespan
		// session.cookie_lifetime (defaults to 0 that means "until the browser is closed.") 
		// is set by calling session_set_cookie_params() with the first parameter 
		// to specify the lifetime of the cookie in seconds which is sent to the browser.
		// This keeps the user logged in after browser restarts
		session_set_cookie_params( 
			self::$persistent_timespan,
			$current_params['path'],
			$current_params['domain'],
			$current_params['secure']
		);

		self::open();
		
		$_SESSION['SESSION::type'] = 'persistent';
		
		session_regenerate_id();
		self::$regenerated = TRUE;
	}
	
	
	/**
	 * Gets data from the `$_SESSION` superglobal
	 * 
	 * @param  string $key            The name to get the value for - array elements can be accessed via `[sub-key]` syntax, and thus `[` and `]` can not be used in key names
	 * @param  mixed  $default_value  The default value to use if the requested key is not set
	 * @return mixed  The data element requested
	 */
	public static function get($key, $default_value = NULL) {
		self::open();
		
		$array_dereference = NULL;
		if ($bracket_pos = strpos($key, '[')) {
			$array_dereference = substr($key, $bracket_pos);
			$key = substr($key, 0, $bracket_pos);
		}
		
		if ( ! isset($_SESSION[$key])) {
			return $default_value;
		}
		$value = $_SESSION[$key];
		
		if ($array_dereference) {
			preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER);
			$array_keys = array_map('current', $array_keys);
			foreach ($array_keys as $array_key) {
				if ( ! is_array($value) || ! isset($value[$array_key])) {
					$value = $default_value;
					break;
				}
				$value = $value[$array_key];
			}
		}
		
		return $value;
	}
	
	
	/**
	 * Sets the session to run on the main domain, not just the specific subdomain currently being accessed
	 * By default PHP will only allow access to the $_SESSION superglobal values by pages on the same subdomain, 
	 * such that www.example.com could access the session, but example.com could not. 
	 * Calling ignore_subdomain() removes that restriction and allows access to any subdomain.
	 * 
	 * This method should be called after any calls to
	 * [http://php.net/session_set_cookie_params `session_set_cookie_params()`].
	 * 
	 * @return void
	 */
	public static function ignore_subdomain() {
		if (self::$open || isset($_SESSION)) {
			halt('A System Error Was Encountered', "Session::ignore_subdomain() must be called before any of Session::add(), Session::clear(), Session::enable_persistence(), Session::get(), Session::set(), session_start()", 'sys_error');
		}
		
		$current_params = session_get_cookie_params();
		
		if (isset($_SERVER['SERVER_NAME'])) {
			$domain = $_SERVER['SERVER_NAME'];
		} elseif (isset($_SERVER['HTTP_HOST'])) {
			$domain = $_SERVER['HTTP_HOST'];
		} else {
			halt('A System Error Was Encountered', "The domain name could not be found in ['SERVER_NAME'] or ['HTTP_HOST']. Please set one of these keys to use Session::ignore_subdomain().", 'sys_error');
		}

		session_set_cookie_params( 
			$current_params['lifetime'],
			$current_params['path'],
			preg_replace('#.*?([a-z0-9\\-]+\.[a-z]+)$#iD', '.\1', $domain),
			$current_params['secure']
		);
	}
	
	
	/**
	 * Opens the session for writing, is automatically called by ::clear(), ::get() and ::set()
	 * 
	 * A `Cannot send session cache limiter` warning will be triggered if 
	 * ::add(), ::clear(), ::delete(), ::get() or ::set() is called after output
	 * has been sent to the browser. To prevent such a warning, explicitly call
	 * this method before generating any output.
	 * 
	 * @return void
	 */
	private static function open() {
		if (self::$open) { 
			return; 
		}
		
		self::$open = TRUE;
		
		if (self::$normal_timespan === NULL) {
			// The number of seconds after which data will be seen as 'garbage' and potentially cleaned up.
			self::$normal_timespan = ini_get('session.gc_maxlifetime');	
		}
		
		// If the session is already open, we just piggy-back without setting options
		if ( ! isset($_SESSION)) {
			// Forces to use cookies to store the session id on the client side
			ini_set('session.use_cookies', 1);
			// Prevents attacks involved passing session ids in URLs, defaults to 1 (enabled) since PHP 5.3.0.
			ini_set('session.use_only_cookies', 1);
			session_start();
		}
		
		// If the session has existed for too long, reset it
		if (isset($_SESSION['SESSION::expires']) && $_SESSION['SESSION::expires'] < $_SERVER['REQUEST_TIME']) {
			$_SESSION = array();
			self::regenerate_id();
		}
		
		if ( ! isset($_SESSION['SESSION::type'])) {
			$_SESSION['SESSION::type'] = 'normal';	
		}
		
		// We store the expiration time for a session to allow for both normal and persistent sessions
		$timespan = ($_SESSION['SESSION::type'] === 'persistent' && self::$persistent_timespan) 
		            ? self::$persistent_timespan
					: self::$normal_timespan;
		$_SESSION['SESSION::expires'] = $_SERVER['REQUEST_TIME'] + $timespan;
	}
	
	
	/**
	 * Regenerates the session ID, but only once per script execution
	 * 
	 * @return void
	 */
	public static function regenerate_id() {
		if ( ! self::$regenerated){
			session_regenerate_id();
			self::$regenerated = TRUE;
		}
	}
	
	
	/**
	 * Removes and returns the value from the end of an array value
	 *
	 * @param  string  $key        The name of the element to remove the value from - array elements can be modified via `[sub-key]` syntax, and thus `[` and `]` can not be used in key names
	 * @param  boolean $beginning  If the value should be removed to the beginning
	 * @return mixed  The value that was removed
	 */
	public static function remove($key, $beginning = FALSE) {
		self::open();
		$tip =& $_SESSION;
		
		if ($bracket_pos = strpos($key, '[')) {
			$original_key = $key;
			$array_dereference = substr($key, $bracket_pos);
			$key = substr($key, 0, $bracket_pos);
			
			preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER);
			$array_keys = array_map('current', $array_keys);
			array_unshift($array_keys, $key);
			
			foreach (array_slice($array_keys, 0, -1) as $array_key) {
				if ( ! isset($tip[$array_key])) {
					return NULL;
				} elseif ( ! is_array($tip[$array_key])) {
					halt('A System Error Was Encountered', "Session::remove() was called for the key, {$original_key}, which is not an array", 'sys_error');
				}
				$tip =& $tip[$array_key];
			}
			$key = end($array_keys);
		}
		
		if ( ! isset($tip[$key])) {
			return NULL;
		} elseif ( ! is_array($tip[$key])) {
			halt('A System Error Was Encountered', "Session::remove() was called for the key, {$key}, which is not an array", 'sys_error');
		}
		
		if ($beginning) {
			return array_shift($tip[$key]);
		}
		
		return array_pop($tip[$key]);
	}	
	
	
	/**
	 * Resets the configuration of the class
	 *  
	 * @return void
	 */
	public static function reset() {
		self::$normal_timespan = NULL;
		self::$persistent_timespan = NULL;
		self::$regenerated = FALSE;
		self::$destroy();
		self::$close();	
	}
	
	
	/**
	 * Sets data to the `$_SESSION` superglobal
	 * 
	 * @param  string $key     The name to save the value under - array elements can be modified via `[sub-key]` syntax, and thus `[` and `]` can not be used in key names
	 * @param  mixed  $value   The value to store
	 * @return void
	 */
	public static function set($key, $value) {
		self::open();
		$tip =& $_SESSION;
		
		if ($bracket_pos = strpos($key, '[')) {
			$array_dereference = substr($key, $bracket_pos);
			$key = substr($key, 0, $bracket_pos);
			
			preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER);
			$array_keys = array_map('current', $array_keys);
			array_unshift($array_keys, $key);
			
			foreach (array_slice($array_keys, 0, -1) as $array_key) {
				if ( ! isset($tip[$array_key]) || ! is_array($tip[$array_key])) {
					$tip[$array_key] = array();
				}
				$tip =& $tip[$array_key];
			}
			$tip[end($array_keys)] = $value;		
		} else {
			$tip[$key] = $value;
		}
	}

	/**
	 * Sets the path to store session files in and 
	 * Sets the minimum length of a session
	 * (PHP might not clean up the session data right away once this timespan has elapsed)
	 * 
	 * You should always be called with a non-standard directory to ensure that 
	 * another site on the server doesn't garbage collect the session files for this site.
	 * 
	 * Standard session directories usually include '/tmp' and '/var/tmp'. 
	 * 
	 * Both of the $normal_timespan and $persistent_timespan can accept either a integer timespan in seconds,
	 * or an english description of a timespan (e.g. '30 minutes', '1 hour', '1 day 2 hours').
	 * 
	 * To enable a user to stay logged in for the whole $persistent_timespan and to stay logged in 
	 * across browser restarts, the static method ::enable_persistence() must be called when they log in.
	 * 
	 * @param  string $dir  The directory to store session files in
	 * @param  string|integer $normal_timespan      The normal (minimum), session-based cookie, length for the session
	 * @param  string|integer $persistent_timespan  The persistent, timed-based cookie, length for the session - this is enabled by calling ::enabled_persistence() during login
	 * @return void
	 */
	public static function init($dir, $normal_timespan, $persistent_timespan = NULL) {
		if (self::$open || isset($_SESSION)) {
			halt('A System Error Was Encountered', "Session::init() must be called before any of Session::add(), Session::clear(), Session::enable_persistence(), Session::get(), Session::set(), session_start()", 'sys_error');
		}

		// Set the path of the current directory used to save session data.
		session_save_path($dir);

		$seconds = ( ! is_numeric($normal_timespan)) ? strtotime($normal_timespan) - time() : $normal_timespan;
		self::$normal_timespan = $seconds;
		
		if ($persistent_timespan !== NULL) {
			$seconds = ( ! is_numeric($persistent_timespan)) ? strtotime($persistent_timespan) - time() : $persistent_timespan;	
			self::$persistent_timespan = $seconds;
		}
		
		// If $persistent_timespan specified, it has to be longer than the $normal_timespan
		ini_set('session.gc_maxlifetime', $seconds);
		
		// Marks the cookie as accessible only through the HTTP protocol. 
		// This means that the cookie won't be accessible by scripting languages, such as JavaScript. 
		// This setting can effectively help to reduce identity theft through XSS attacks 
		// (although it is not supported by all browsers).
		ini_set('session.cookie_httponly', 1);
	}
	
}

/* End of file: ./system/core/session.php */