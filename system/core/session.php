<?php
/**
 * Wraps the session control functions and the `$_SESSION` superglobal for a more consistent and safer API
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\core;

class Session {
    /**
     * If the session is open
     * 
     * @var bool 
     */
    private static $open = FALSE;

    /**
     * If the session ID was regenerated during this script
     * 
     * @var bool
     */
    private static $regenerated = FALSE;

    /**
     * Private contructor prevents direct object creation
     * 
     * @return Session
     */
    private function __construct() {}

    
    /**
     * Opens the session for writing, is automatically called by ::get() and ::set()
     * 
     * A `Cannot send session cache limiter` warning will be triggered if 
     * ::delete(), ::get() or ::set() is called after output
     * has been sent to the browser. To prevent such a warning, explicitly call
     * this method before generating any output.
     * 
     * @return void
     */
    private static function open() {
        // Flag check
        if (self::$open) { 
            return; 
        }
        
        // Initialize the session settings

        // The session dir should always be set to a non-standard directory to ensure that 
        // another site on the server doesn't garbage collect the session files for this site
        // Standard session directories usually include '/tmp' and '/var/tmp'
        $dir = APP_SESSION_DIR;
        // Sets the path to store session files in
        session_save_path($dir);

        // Marks the cookie as accessible only through the HTTP protocol. 
        // This means that the cookie won't be accessible by scripting languages, such as JavaScript. 
        // This setting can effectively help to reduce identity theft through XSS attacks 
        // (although it is not supported by all browsers).
        // http://www.browserscope.org/?category=security
        ini_set('session.cookie_httponly', 1);

        // Forces to use cookies to store the session id on the client side
        ini_set('session.use_cookies', 1);
        
        // Prevents attacks involved passing session ids in URLs, defaults to 1 (enabled) since PHP 5.3.0.
        ini_set('session.use_only_cookies', 1);

        // FLag
        self::$open = TRUE;
        
        // If the session is already open, we just piggy-back without setting options
        if ( ! isset($_SESSION)) {
            session_start();
        }
        
        // If the session has existed for too long and not been garbage collected, reset it
        if (isset($_SESSION['SESSION::expires']) && ($_SESSION['SESSION::expires'] < $_SERVER['REQUEST_TIME'])) {
            // Reset the current session array
            $_SESSION = array();
            // Create a new session, deleting the previous associated session file
            self::regenerate_id();
        }

        // Get the default timespan (1440 seconds) from php.ini
        $timespan = ini_get('session.gc_maxlifetime');
        // Extends the expiration time upon active request
        $_SESSION['SESSION::expires'] = $_SERVER['REQUEST_TIME'] + $timespan;
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
     * Sets data to the `$_SESSION` superglobal
     * 
     * @param string $key The name to save the value under - array elements can be modified via `[sub-key]` syntax, and thus `[` and `]` can not be used in key names
     * @param mixed $value The value to store
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
     * Gets data from the `$_SESSION` superglobal
     * 
     * @param string $key The name to get the value for - array elements can be accessed via `[sub-key]` syntax, and thus `[` and `]` can not be used in key names
     * @param mixed  $default_value The default value to use if the requested key is not set
     * @return mixed The data element requested
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
     * Deletes a value from the session
     * 
     * @param string $key The key of the value to delete - array elements can be modified via `[sub-key]` syntax, and thus `[` and `]` can not be used in key names
     * @param mixed  $default_value The value to return if the `$key` is not set
     * @return mixed The value of the `$key` that was deleted
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
                    Common::halt('A System Error Was Encountered', "Session::delete() was called for an element, {$original_key}, which is not an array", 'sys_error');
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
     * Destroys all the session data and session cookie
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
     * Regenerates the session ID, but only once per script execution
     * Any time a user has a change in privilege (gaining or losing access rights within a system) 
     * be sure to regenerate the session ID
     * 
     * @return void
     */
    public static function regenerate_id() {
        if ( ! self::$regenerated) {
            // Create a new session, deleting the previous associated session file
            session_regenerate_id(TRUE);
            self::$regenerated = TRUE;
        }
    }


    
}

/* End of file: ./system/core/session.php */