<?php
/**
 * Wraps the session control functions and the $_SESSION superglobal for a more consistent and safer API
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
     * Standard messages produced by the class. Can be modified for il8n
     * @var array
     */
    private static $messages = array(
        'dir_permission_denied' => 'Permission denied for creating the writable APP_SESSION_DIR directory!'
    );
    
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
        // Default session directories will usually evaluate to your system's temp directory
        $dir = APP_SESSION_DIR;
        
        // Create the log directory if not exists
        if ( ! file_exists($dir)) {
            // The thrid parameter TRUE allows the creation of nested directories specified in the path
            if ( ! mkdir($dir, 0777, TRUE)) {
                // Output error message and terminate the current script
                Common::halt('An Error Was Encountered', self::$messages['dir_permission_denied'], 'sys_error');
            }
        }

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
        
        // If the session has existed for too long upon active request and not been 
        // garbage collected (since session garbage collector runs with a probability)
        // InfoPotato will create a new session and delete the ond one
        if (isset($_SESSION['SESSION::expires']) && ($_SESSION['SESSION::expires'] < $_SERVER['REQUEST_TIME'])) {
            // Erase data from current session
            $_SESSION = array();
            // Create a new session, deleting the previous associated session file
            self::regenerate_id();
        }

        // Get the default timespan (1440 seconds, 24 mins) from php.ini
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
        if (self::$open) { 
            session_write_close();
            unset($_SESSION);
            self::$open = FALSE; 
        }
    }
    
    
    /**
     * Sets data to the $_SESSION superglobal
     * 
     * @param string $key The name to save the value under
     * @param mixed $value The value to store
     * @return void
     */
    public static function set($key, $value) {
        self::open();

        $_SESSION[$key] = $value;
    }


    /**
     * Gets data from the $_SESSION superglobal
     * 
     * @param string $key The name to get the value for
     * @param mixed  $default_value The default value to use if the requested key is not set
     * @return mixed The data element requested
     */
    public static function get($key, $default_value = NULL) {
        self::open();

        return (isset($_SESSION[$key])) ? $_SESSION[$key] : $default_value;
    }

        
    /**
     * Deletes a value from the session
     * 
     * @param string $key The key of the value to delete
     * @param mixed  $default_value The value to return if the key is not set
     * @return mixed The value of the key that was deleted
     */
    public static function delete($key, $default_value = NULL) {
        self::open();
        
        $value = $default_value;

        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
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

        // Erase all the session data on server side
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
            self::open();
            
            // Create a new session, deleting the previous associated session file
            session_regenerate_id(TRUE);
            self::$regenerated = TRUE;
        }
    }


}

/* End of file: ./system/core/session.php */