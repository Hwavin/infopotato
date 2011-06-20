<?php
class Auth {
	/**
	 * Settings for this object.
	 *
	 * - `fields` The fields to use to identify a user by.
	 * - `userModel` The model name of the User, defaults to User.
	 * - `scope` Additional conditions to use when looking up and authenticating users,
	 *    i.e. `array('User.is_active' => 1).`
	 *
	 * @var array
	 */
	public $settings = array(
		'fields' => array(
			'username' => 'username',
			'password' => 'password'
		),
		'userModel' => 'User',
		'scope' => array()
	);
	
	/**
	 * Maintains current user login state.
	 *
	 * @var boolean
	 */
	private $_logged_in = FALSE;
	
	/**
	 * Manager methods for which user validation is not required.
	 *
	 * @var array
	 */
	public $allowed_methods = array();
	
	/**
	 * The session key name where the record of the current user is stored.  If
	 * unspecified, it will be "Auth.{$userModel name}".
	 *
	 * @var string
	 */
	public $session_key = NULL;
	
	/**
	 * Method list for bound manager
	 *
	 * @var array
	 * @access protected
	 */
	protected $_methods = array();
	
	/**
	 * Form data from Controller::$data
	 *
	 * @var array
	 */
	public $data = array();
	
	/**
	 * Constructor
	 *
	 * @param ComponentCollection $collection The Component collection used on this request.
	 * @param array $settings Array of settings to use.
	 */
	public function __construct($settings = array()) {
		$this->settings = array_merge($this->settings, $settings);
	}
	
	protected function _check_auth() {
		if ($this->_logged_in === TRUE) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}

	/**
	 * Identifies a user based on specific criteria.
	 *
	 * @param mixed $user Optional. The identity of the user to be validated.
	 *              Uses the current user session if none specified.
	 * @param array $conditions Optional. Additional conditions to a find.
	 * @return array User record data, or null, if the user could not be identified.
	 * @access public
	 */
	private function _identify($data) {
		$user = array();
		
		if (isset($user) && $user !== NULL) {
			

			
		}else {
			return FALSE;
		}
	}
	
	
	/**
	 * Manually log-in a user with the given parameter data.  The $data provided can be any data
	 * structure used to identify a user in AuthComponent::identify().  If $data is empty or not
	 * specified, POST data from Controller::$data will be used automatically.
	 *
	 * After (if) login is successful, the user record is written to the session key specified in
	 * AuthComponent::$sessionKey.
	 *
	 * @param mixed $data User object
	 * @return boolean True on login success, false on failure
	 * @access public
	 * @link http://book.cakephp.org/view/1261/login
	 */
	public function login() {
		$this->_logged_in = FALSE;

		if (empty($data)) {
			$data = $this->data;
		}

		if ($this->_identify($data) === TRUE) {
			$this->_logged_in = TRUE;
		}
		
		return $this->_logged_in;
	}
	
	/**
	 * Logs a user out
	 */
	public function logout() {
		Session::delete($this->session_key);
		$this->_logged_in = FALSE;
	}
	
	/**
	 * Takes a list of actions in the current controller for which authentication is not required, or
	 * no parameters to allow all actions.
	 *
	 * @param mixed $action Controller action name or array of actions
	 * @param string $action Controller action name
	 * @param string ... etc.
	 * @return void
	 * @access public
	 * @link http://book.cakephp.org/view/1257/allow
	 */
	public function allow() {
		$args = func_get_args();
		if (empty($args) || $args == array('*')) {
			$this->allowed_methods = $this->_methods;
		} else {
			if (isset($args[0]) && is_array($args[0])) {
				$args = $args[0];
			}
			$this->allowed_methods = array_merge($this->allowed_methods, array_map('strtolower', $args));
		}
	}

}

/* End of file: ./system/core/auth.php */
