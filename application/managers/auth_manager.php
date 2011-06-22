<?php
class Auth_Manager extends Manager {
	// Define session prefix
	const SESSION_KEY = 'user::';

	/**
	 * Sample user data
	 * You can also get the user data from database
	 *
	 * @var array
	 */
	public $user = array(
		'id' => 1,
		'fullname' => 'Zhou Yuan',
		'username' => 'zhouy',
		'hash_pass' => '$P$BN7rmT7I1KKipgOKsefSTOUL6QtIBE1'
	);

	protected function _check_auth() {
		if ( ! self::get_user_token()) {
			// Remember the URI requested before the user was redirected to the login page
			self::set_requested_uri('http://' . $_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI']);
			$this->get_login();
			exit;
		}	
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

		if ($username === $this->user['username']) {
			if ($this->pass->check_password($password, $this->user['hash_pass'])) {	
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
	
	/**
	 * This method must be declaired as protected
	 * so that it's not accessible directly from /auth/login/
	 */
	protected function get_login() {
		$layout_data = array(
			'page_title' => 'Login',
			'content' => $this->render_template('pages/login'),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html'
		);

		$this->response($response_data);
	}
	
	/**
	 * This method must be declaired as public
	 * so that it's accessible directly to the login form's post action - /auth/login/
	 * Since it's a POST request, no access directly from the URI, and 
	 * the $_SERVER['HTTP_REFERER'] can be used
	 */
	public function post_login() {
		$username = isset($this->POST_DATA['username']) ? trim($this->POST_DATA['username']) : '';
		$password = isset($this->POST_DATA['password']) ? trim($this->POST_DATA['password']) : '';
		
		if ($this->_identify($username, $password) === TRUE) {
			// Store user data
			Session::set(self::SESSION_KEY.'uid', $this->user['id']);
			Session::set(self::SESSION_KEY.'fullname', $this->user['fullname']);
			
			// Set the user token
			self::set_user_token($this->user['id']);
			
			$this->assign_template_global('user_fullname', Session::get(self::SESSION_KEY.'fullname'));
			
			$this->load_function('SYS', 'redirect/redirect_function');
			redirect_function(self::get_requested_uri(TRUE, APP_BASE_URI.'home/'));
		} else {
			// Data to be displayed in view
			$content_data = array(
				'auth' => FALSE, 
			);
			
			$layout_data = array(
				'page_title' => 'Login',
				'content' => $this->render_template('pages/login', $content_data),
			);
			
			$response_data = array(
				'content' => $this->render_template('layouts/default_layout', $layout_data),
				'type' => 'text/html'
			);
			$this->response($response_data);
		}
	}
	
	
	/**
	 * Logs a user out
	 */
	public function get_logout() {
		// Clear stored user session data
		Session::clear(self::SESSION_KEY); 

		$this->load_function('SYS', 'redirect/redirect_function');
		redirect_function(APP_URI_BASE.'home/');
	}
	
	/**
	 * Sets the restricted URI requested by the user
	 * 
	 * @param  string  $uri  The URI to save as the requested URI
	 * @return void
	 */
	public static function set_requested_uri($uri) {
		Session::set(self::SESSION_KEY.'::requested_uri', $uri);
	}
	
	/**
	 * Returns the URI requested before the user was redirected to the login page
	 * 
	 * @param  boolean $clear        If the requested url should be cleared from the session after it is retrieved
	 * @param  string  $default_uri  The default URI to return if the user was not redirected
	 * @return string  The URI that was requested before they were redirected to the login page
	 */
	public static function get_requested_uri($clear, $default_uri = NULL) {
		$requested_uri = Session::get(self::SESSION_KEY.'::requested_uri', $default_url);
		if ($clear) {
			Session::delete(self::SESSION_KEY. '::requested_uri');
		}
		return $requested_uri;
	}

	/**
	 * Sets some piece of information to use to identify the current user
	 * 
	 * @param  mixed $token  The user's token. This could be a user id, an email address, a user object, etc.
	 * @return void
	 */
	public static function set_user_token($token) {
		Session::set(self::SESSION_KEY.'::user_token', $token);
		Session::regenerate_id();
	}
	
	/**
	 * Gets the value that was set as the user token, `NULL` if no token has been set
	 * 
	 * @return mixed  The user token that had been set, `NULL` if none
	 */
	public static function get_user_token() {
		return Session::get(self::SESSION_KEY.'::user_token', NULL);
	}
	
}

/* End of file: ./application/managers/auth_manager.php */
