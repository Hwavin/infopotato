<?php
/**
 * Wraps the session control functions and the `$_SESSION` superglobal for a more consistent and safer API
 * 
 * A `Cannot send session cache limiter` warning will be triggered if ::open(),
 * ::add(), ::clear(), ::delete(), ::get() or ::set() is called after output has
 * been sent to the browser. To prevent such a warning, explicitly call ::open()
 * before generating any output.
 * 
 * @copyright  Copyright (c) 2007-2010 Will Bond, others
 * @author     Will Bond [wb] <will@flourishlib.com>
 * @author     Alex Leeds [al] <alex@kingleeds.com>
 * @license    http://flourishlib.com/license
 * 
 * @package    Flourish
 * @link       http://flourishlib.com/fSession
 */
class Session {

	/**
	 * The length for a normal session
	 * 
	 * @var integer 
	 */
	private static $_normal_timespan = NULL;
	
	/**
	 * If the session is open
	 * 
	 * @var boolean 
	 */
	private static $_open = FALSE;
	
	/**
	 * The length for a persistent session cookie - one that survives browser restarts
	 * 
	 * @var integer 
	 */
	private static $_persistent_timespan = NULL;
	
	/**
	 * If the session ID was regenerated during this script
	 * 
	 * @var boolean 
	 */
	private static $_regenerated = FALSE;
	
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
					printf(
						'%1$s was called for the key, %2$s, which is not an array',
						__CLASS__ . '::add()',
						$original_key
					);
				}
				$tip =& $tip[$array_key];
			}
			$key = end($array_keys);
		}
		
		
		if ( ! isset($tip[$key])) {
			$tip[$key] = array();
		} elseif ( ! is_array($tip[$key])) {
			printf(
				'%1$s was called for the key, %2$s, which is not an array',
				__CLASS__ . '::add()',
				$key
			);
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
		if ( ! self::$_open) { 
			return; 
		}
		
		session_write_close();
		unset($_SESSION);
		self::$_open = FALSE;
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
					printf(
						'%1$s was called for an element, %2$s, which is not an array',
						__CLASS__ . '::delete()',
						$original_key
					);
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
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 43200, $params['path'], $params['domain'], $params['secure']);
		}
		session_destroy();
		self::regenerate_id();
	}
	
	
	/**
	 * Changed the session to use a time-based cookie instead of a session-based cookie
	 * 
	 * The length of the time-based cookie is controlled by ::set_length(). When
	 * this method is called, a time-based cookie is used to store the session
	 * ID. This means the session can persist browser restarts. Normally, a
	 * session-based cookie is used, which is wiped when a browser restart
	 * occurs.
	 * 
	 * This method should be called during the login process and will normally
	 * be controlled by a checkbox or similar where the user can indicate if
	 * they want to stay logged in for an extended period of time.
	 * 
	 * @return void
	 */
	public static function enable_persistence() {
		if (self::$_persistent_timespan === NULL) {
			printf(
				'The method %1$s must be called with the %2$s parameter before calling %3$s',
				__CLASS__ . '::set_length()',
				'$_persistent_timespan',
				__CLASS__ . '::enable_persistence()'
			);	
		}
		
		$current_params = session_get_cookie_params();
		
		$params = array(
			self::$_persistent_timespan,
			$current_params['path'],
			$current_params['domain'],
			$current_params['secure']
		);
		
		call_user_func_array('session_set_cookie_params', $params);
		
		self::open();
		
		$_SESSION['SESSION::type'] = 'persistent';
		
		session_regenerate_id();
		self::$_regenerated = TRUE;
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
	 * 
	 * This method should be called after any calls to
	 * [http://php.net/session_set_cookie_params `session_set_cookie_params()`].
	 * 
	 * @return void
	 */
	public static function ignore_subdomain() {
		if (self::$_open || isset($_SESSION)) {
			printf(
				'%1$s must be called before any of %2$s, %3$s, %4$s, %5$s, %6$s, %7$s or %8$s',
				__CLASS__ . '::ignore_subdomain()',
				__CLASS__ . '::add()',
				__CLASS__ . '::clear()',
				__CLASS__ . '::enable_persistence()',
				__CLASS__ . '::get()',
				__CLASS__ . '::open()',
				__CLASS__ . '::set()',
				'session_start()'
			);
		}
		
		$current_params = session_get_cookie_params();
		
		if (isset($_SERVER['SERVER_NAME'])) {
			$domain = $_SERVER['SERVER_NAME'];
		} elseif (isset($_SERVER['HTTP_HOST'])) {
			$domain = $_SERVER['HTTP_HOST'];
		} else {
			printf(
				'The domain name could not be found in %1$s or %2$s. Please set one of these keys to use %3$s.',
				'$_SERVER[\'SERVER_NAME\']',
				'$_SERVER[\'HTTP_HOST\']',
				__CLASS__ . '::ignore_subdomain()'
			);
		}
		
		$params = array(
			$current_params['lifetime'],
			$current_params['path'],
			preg_replace('#.*?([a-z0-9\\-]+\.[a-z]+)$#iD', '.\1', $domain),
			$current_params['secure']
		);
		
		call_user_func_array('session_set_cookie_params', $params);
	}
	
	
	/**
	 * Opens the session for writing, is automatically called by ::clear(), ::get() and ::set()
	 * 
	 * A `Cannot send session cache limiter` warning will be triggered if this,
	 * ::add(), ::clear(), ::delete(), ::get() or ::set() is called after output
	 * has been sent to the browser. To prevent such a warning, explicitly call
	 * this method before generating any output.
	 * 
	 * @param  boolean $cookie_only_session_id  If the session id should only be allowed via cookie - this is a security issue and should only be set to `FALSE` when absolutely necessary 
	 * @return void
	 */
	public static function open($cookie_only_session_id = TRUE) {
		if (self::$_open) { 
			return; 
		}
		
		self::$_open = TRUE;
		
		if (self::$_normal_timespan === NULL) {
			self::$_normal_timespan = ini_get('session.gc_maxlifetime');	
		}
		
		// If the session is already open, we just piggy-back without setting options
		if ( ! isset($_SESSION)) {
			if ($cookie_only_session_id) {
				ini_set('session.use_cookies', 1);
				ini_set('session.use_only_cookies', 1);
			}
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
		if ($_SESSION['SESSION::type'] == 'persistent' && self::$_persistent_timespan) {
			$_SESSION['SESSION::expires'] = $_SERVER['REQUEST_TIME'] + self::$_persistent_timespan;
		} else {
			$_SESSION['SESSION::expires'] = $_SERVER['REQUEST_TIME'] + self::$_normal_timespan;	
		}
	}
	
	
	/**
	 * Regenerates the session ID, but only once per script execution
	 * 
	 * @internal
	 * 
	 * @return void
	 */
	public static function regenerate_id() {
		if ( ! self::$_regenerated){
			session_regenerate_id();
			self::$_regenerated = TRUE;
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
					printf(
						'%1$s was called for the key, %2$s, which is not an array',
						__CLASS__ . '::remove()',
						$original_key
					);
				}
				$tip =& $tip[$array_key];
			}
			$key = end($array_keys);
		}
		
		if ( ! isset($tip[$key])) {
			return NULL;
		} elseif ( ! is_array($tip[$key])) {
			printf(
				'%1$s was called for the key, %2$s, which is not an array',
				__CLASS__ . '::remove()',
				$key
			);
		}
		
		if ($beginning) {
			return array_shift($tip[$key]);
		}
		
		return array_pop($tip[$key]);
	}	
	
	
	/**
	 * Resets the configuration of the class
	 * 
	 * @internal
	 * 
	 * @return void
	 */
	public static function reset() {
		self::$_normal_timespan = NULL;
		self::$_persistent_timespan = NULL;
		self::$_regenerated = FALSE;
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
	 * Sets the minimum length of a session - PHP might not clean up the session data right away once this timespan has elapsed
	 * 
	 * Please be sure to set a custom session path via ::set_path() to ensure
	 * another site on the server does not garbage collect the session files
	 * from this site!
	 * 
	 * Both of the timespan can accept either a integer timespan in seconds,
	 * or an english description of a timespan (e.g. `'30 minutes'`, `'1 hour'`,
	 * `'1 day 2 hours'`).
	 * 
	 * @param  string|integer $normal_timespan      The normal, session-based cookie, length for the session
	 * @param  string|integer $persistent_timespan  The persistent, timed-based cookie, length for the session - this is enabled by calling ::enabled_persistence() during login
	 * @return void
	 */
	public static function set_length($normal_timespan, $persistent_timespan = NULL) {
		if (self::$_open || isset($_SESSION)) {
			printf(
				'%1$s must be called before any of %2$s, %3$s, %4$s, %5$s, %6$s, %7$s or %8$s',
				__CLASS__ . '::set_length()',
				__CLASS__ . '::add()',
				__CLASS__ . '::clear()',
				__CLASS__ . '::enable_persistence()',
				__CLASS__ . '::get()',
				__CLASS__ . '::open()',
				__CLASS__ . '::set()',
				'session_start()'
			);
		}
		
		$seconds = ( ! is_numeric($normal_timespan)) ? strtotime($normal_timespan) - time() : $normal_timespan;
		self::$_normal_timespan = $seconds;
		
		if ($persistent_timespan) {
			$seconds = ( ! is_numeric($persistent_timespan)) ? strtotime($persistent_timespan) - time() : $persistent_timespan;	
			self::$_persistent_timespan = $seconds;
		}
		
		ini_set('session.gc_maxlifetime', $seconds);
	}
	
	
	/**
	 * Sets the path to store session files in
	 * 
	 * This method should always be called with a non-standard directory
	 * whenever ::set_length() is called to ensure that another site on the
	 * server does not garbage collect the session files for this site.
	 * 
	 * Standard session directories usually include `/tmp` and `/var/tmp`. 
	 * 
	 * @param  string $directory  The directory to store session files in
	 * @return void
	 */
	public static function set_path($directory) {
		if (self::$_open || isset($_SESSION)) {
			printf(
				'%1$s must be called before any of %2$s, %3$s, %4$s, %5$s, %6$s, %7$s or %8$s',
				__CLASS__ . '::set_path()',
				__CLASS__ . '::add()',
				__CLASS__ . '::clear()',
				__CLASS__ . '::enable_persistence()',
				__CLASS__ . '::get()',
				__CLASS__ . '::open()',
				__CLASS__ . '::set()',
				'session_start()'
			);
		}

		if ( ! is_writable($directory)) {
			echo 'The directory specified is not writable';
		}
		
		session_save_path($directory);
	}
	
}

/* End of file: ./system/core/session.php */