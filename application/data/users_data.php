<?php
class Users_Data extends Data {
	public function __construct() {
		// Use default database connection config
		parent::__construct('default');
	}
	
	public function user_exists($username) { 
		$sql = $this->db->prepare("SELECT * FROM users WHERE username=?", array($username));
		return $this->db->get_row($sql, ARRAY_A);
	}

	public function get_user_info($id) { 
		$sql = $this->db->prepare("SELECT * FROM users WHERE id=?", array($id));
		return $this->db->get_row($sql, ARRAY_A);
	}
}

/* End of file: ./application/data/users_data.php */
