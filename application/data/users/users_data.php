<?php
class Users_Data extends Data {
	public function __construct() {
		// Use default database connection config
		parent::__construct('mysql_dao:default');
	}

	public function get_users_info() { 
		$sql = $this->db->prepare("SELECT * FROM users");
		//$this->db->vardump($this->db->get_results($sql, FETCH_ASSOC));
		//return $this->db->get_results($sql, FETCH_ASSOC);
		return $this->db->get_row($sql, FETCH_ASSOC, 3);
	}
	
	public function get_user_info($username) { 
		$sql = $this->db->prepare("SELECT * FROM users WHERE username=?", array($username));
		//$this->db->vardump($this->db->get_row($sql, FETCH_ASSOC));

		return $this->db->get_row($sql, FETCH_ASSOC);
		//return $this->db->get_col($sql, 4);
	}
}

/* End of file: ./application/data/users_data.php */
