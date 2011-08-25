<?php
class SQLite_Data extends Data {
	public function __construct() {
		// Use default database connection config
		parent::__construct('sqlite_dao:test');
	}

	public function get_user_info($id) { 
		$sql = $this->_db->prepare("SELECT * FROM t1 WHERE id=?", array($id));
		//$this->_db->vardump($this->_db->get_row($sql, ARRAY_A));

		return $this->_db->get_row($sql, ARRAY_A);
	}
	
	public function get_users_info() { 
		$sql = $this->_db->prepare("SELECT * FROM t1");
		//$this->db->vardump($this->_db->get_results($sql, ARRAY_A));
		return $this->_db->get_results($sql, ARRAY_A);
	}
	
	public function add_user($short, $long) {
		$return_val = TRUE;
		
		$sql = $this->_db->prepare("INSERT INTO t1 (short, long) 
								   VALUES (?, ?)", array($short, $long));
		if ($this->_db->query($sql) === FALSE) {
			$return_val = FALSE;
		}	
		//$this->_db->vardump($this->_db->query($sql));
		return $return_val;
	}
}

/* End of file: ./application/data/sqlite_data.php */
