<?php
class Auth_Manager extends Manager {
	// Define session prefix
	const SESSION_KEY = 'user::';

	/**
	 * User data
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
		if (Session::get(self::SESSION_KEY.'fullname')) {
			$this->assign_template_global('user_fullname', Session::get(self::SESSION_KEY.'fullname'));
			return TRUE;
		} else {
			return FALSE;
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
	 * so that it can be extended by the actual manager, and not accessible directly from /auth/login/
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
			// Store user data in Session
			Session::set(self::SESSION_KEY.'uid', $this->user['id']);
			Session::set(self::SESSION_KEY.'fullname', $this->user['fullname']);
			Session::set(self::SESSION_KEY.'username', $this->user['username']);
			
			$this->load_function('SYS', 'redirect/redirect_function');
			redirect_function(APP_URI_BASE.'home/');
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
		Session::clear(self::SESSION_KEY);   
		
		$this->get_login();
	}

}

/* End of file: ./application/managers/auth_manager.php */
