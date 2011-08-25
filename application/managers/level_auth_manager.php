<?php
class Level_Auth_Manager extends Manager {
    // Define session prefix
    const SESSION_KEY = 'user::';

    /**
     * The valid authorization levels
     * 
     * @var array
     */
    private $_levels;

    /**
     * The logged in user fullname to be displayed in layout template
     *
     * @var string
     */
    protected $_user_fullname;
	
    /**
     * User data
     *
     * @var array
     */
     private $_user;

    /**
     * Constructor
     */
    public function __construct() {
        // A call to parent::__construct() within the child constructor is required
        // to make the $this->POST_DATA available
        parent::__construct();
		
        // Define the valid authorization levels
        $this->_levels = array(
            'admin' => 100,
            'user' => 50,
            'guest' => 25
        );
	
        // Get the layout variable
        $this->_user_fullname = Session::get(self::SESSION_KEY.'fullname');
    }

    /**
     * Checks if a user has the desired auth level
     *
     * @param  string $level  The level to check against the logged in user's level
     */
    protected function _require_auth_level($level = NULL) {
        if ($level === NULL) {
            exit('Please specify the required authorization level for this resource.');
        } 
		
        if ($this->_check_auth_level($level) === FALSE) {
            // Remember the URI requested before the user was redirected to the login page
            $this->_set_requested_uri('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
            $this->_get_login();
            exit;
        }
    }

    /**
     * This method must be declaired as private to make it inaccessible directly from /auth/login/
     */
    private function _get_login() {
        $layout_data = array(
            'page_title' => 'Login',
            'content' => $this->render_template('pages/login'),
        );
		
        $response_data = array(
            'content' => $this->render_template('layouts/login', $layout_data),
            'type' => 'text/html'
        );

        $this->response($response_data);
    }
	
    /**
     * This method must be declaired as public
     * so that it's accessible directly to the login form's post action - /auth/login/
     * Since it's a POST request, no access directly from the URI
     */
    public function post_login() {
        $username = isset($this->POST_DATA['username']) ? trim($this->POST_DATA['username']) : '';
        $password = isset($this->POST_DATA['password']) ? trim($this->POST_DATA['password']) : '';
        $auto_login = isset($this->POST_DATA['auto_login']) ? $this->POST_DATA['auto_login'] : 0;
		
        if ($this->_identify($username, $password) === TRUE) {
            // Store some user data in Session
            Session::set(self::SESSION_KEY.'uid', $this->_user['id']);
			
            // Set the user fullname of logged in user to be used in layout template
            Session::set(self::SESSION_KEY.'fullname', $this->_user['fullname']);

            // Set the user token
            $this->_set_user_token($this->_user['id']);
			
            // Set the user's auth level
            $this->_set_user_auth_level($this->_user['auth_level']);
            
            // Keep me logged in
            if ($auto_login === '1') {
                Session::enable_persistence();
            }

            // Sometimes when a user visits the login page, they will have entered the URI manually, 
            // or will have followed a link. In this sort of situation you will need a default page 
            // to redirect them to. The rest of the time users will usually get directed to the login page 
            // because they tried to access a restricted page.
            $this->load_function('SYS', 'redirect/redirect_function');
            redirect_function($this->_get_requested_uri(TRUE, APP_URI_BASE.'agreement/'));
        } else {
            $content_data = array(
                'auth' => FALSE, 
            );
			
            $layout_data = array(
                'page_title' => 'Login',
                'content' => $this->render_template('pages/login', $content_data),
            );
			
            $response_data = array(
                'content' => $this->render_template('layouts/login', $layout_data),
                'type' => 'text/html'
            );
            $this->response($response_data);
        }
		
		
    }
	
    /**
     * Logout and destroy user's session data
     */
    public function get_logout() {
        // Clear stored user session data
        Session::clear(self::SESSION_KEY); 

        $this->load_function('SYS', 'redirect/redirect_function');
        redirect_function(APP_URI_BASE.'home/');
    }
	
    /**
     * Identifies a user based on username/password
     */
    private function _identify($username, $password) {
        $config = array(
            'iteration_count_log2' => 8, 
            'portable_hashes' => FALSE
        );
        $this->load_library('SYS', 'password_hash/password_hash_library', 'pass', $config);

        // Load users data
        $this->load_data('level_auth_users_data', 'u');
        $this->_user = $this->u->user_exists($username);
		
        if ($this->_user !== NULL) {
            if ($this->pass->check_password($password, $this->_user['hash_pass'])) {	
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
	
    /**
     * Sets the restricted URI requested by the user
     * 
     * @param  string  $uri  The URI to save as the requested URI
     * @return void
     */
    private function _set_requested_uri($uri) {
        Session::set(self::SESSION_KEY.'requested_uri', $uri);
    }
	
    /**
     * Returns the URI requested before the user was redirected to the login page
     * 
     * @param  boolean $clear        If the requested url should be cleared from the session after it is retrieved
     * @param  string  $default_uri  The default URI to return if the user was not redirected
     * @return string  The URI that was requested before they were redirected to the login page
     */
    private function _get_requested_uri($clear, $default_uri = NULL) {
        $requested_uri = Session::get(self::SESSION_KEY.'requested_uri', $default_uri);
        if ($clear) {
            Session::delete(self::SESSION_KEY. 'requested_uri');
        }
        return $requested_uri;
    }

    /**
     * Sets some piece of information to use to identify the current user
     * 
     * @param  mixed $token  The user's token. This could be a user id, an email address, a user object, etc.
     * @return void
     */
    private function _set_user_token($token) {
        Session::set(self::SESSION_KEY.'user_token', $token);
        Session::regenerate_id();
    }
	
    /**
     * Gets the value that was set as the user token, `NULL` if no token has been set
     * 
     * @return mixed  The user token that had been set, `NULL` if none
     */
    private function _get_user_token() {
        return Session::get(self::SESSION_KEY.'user_token', NULL);
    }
	
    /**
     * Checks to see if the logged in user has the specified auth level
     * 
     * @param  string $level  The level to check against the logged in user's level
     * @return boolean  If the user has the required auth level
     */
    private function _check_auth_level($level) {
        if ($this->_get_user_auth_level()) {
            $this->_validate_auth_level($this->_get_user_auth_level());
            $this->_validate_auth_level($level);
			
            $user_auth_level_number = $this->_levels[$this->_get_user_auth_level()];
            $required_auth_level_number = $this->_levels[$level];
			
            if ($user_auth_level_number >= $required_auth_level_number) {
                return TRUE;
            }
        }
		
        return FALSE;
    }
	
    /**
     * Gets the authorization level for the logged in user
     * 
     * @return string  The logged in user's auth level
     */
    private function _get_user_auth_level() {
        return Session::get(self::SESSION_KEY.'user_auth_level', NULL);
    }
	
    /**
     * Sets the authorization level for the logged in user
     * 
     * @param  string $level  The logged in user's auth level
     * @return void
     */
    private function _set_user_auth_level($level) {
        $this->_validate_auth_level($level);
        Session::set(self::SESSION_KEY.'user_auth_level', $level);
        Session::regenerate_id();
    }
	
    /**
     * Makes sure auth levels have been set, and that the specified auth level is valid
     * 
     * @param  string $level  The level to validate
     * @return void
     */
    private function _validate_auth_level($level = NULL) {
        if ($this->_levels === NULL) {
            echo 'No authorization levels have been set';
        }
		
        if ($level !== NULL && ! isset($this->_levels[$level])) {
            printf(
                'The authorization level specified, %1$s, is invalid. Must be one of: %2$s.',
                $level,
                join(', ', array_keys($this->_levels))
            );
        }
    }
	
}

/* End of file: ./application/managers/level_auth_manager.php */
