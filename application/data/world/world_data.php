<?php
class World_Data extends Data {
	public function __construct() {
		parent::__construct('mysql_dao:world');
	}

	public function example1() { 
		$sql = "SELECT * FROM city";
		return $this->db->get_results($sql, ARRAY_A);
	}
	
	public function example2($name) { 
		$sql = $this->db->prepare("SELECT * FROM city WHERE Name=?", array($name));
		return $this->db->get_row($sql, ARRAY_A);
	}
	
	public function example3() { 
		$sql = "SELECT * FROM city";
		return $this->db->get_row($sql, ARRAY_A, 2);
	}
	
	public function example4($name) { 
		$sql = $this->db->prepare("SELECT * FROM city WHERE Name=?", array($name));
		return $this->db->get_col($sql, 3);
	}
	
	public function example5() { 
		$sql = "SELECT * FROM city";
		return $this->db->get_var($sql, 1, 4);
	}
	
	public function example6($name) { 
		$sql = $this->db->prepare("SELECT * FROM city WHERE Name=?", array($name));
		return $this->db->get_var($sql);
	}
	
	public function example7($name, $id) { 
		$sql = $this->db->prepare("UPDATE city SET Name=? WHERE ID=?", array($name, $id));
		$this->db->vardump($this->db->query($sql));
		return $this->db->query($sql);
	}
}

/* End of file: ./application/data/users_data.php */
