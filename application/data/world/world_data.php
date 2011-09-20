<?php
class World_Data extends Data {
	public function __construct() {
		parent::__construct('mysqli_dao:world');
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
		return $this->db->exec_query($sql);
	}
	
	public function example8($name, $country_code, $district, $population) { 
		$sql = $this->db->prepare("INSERT INTO city(ID, Name, CountryCode, District, Population) 
		                           VALUES(?, ?, ?, ?, ?)", 
		                           array(4080, $name, $country_code, $district, $population));
		return $this->db->exec_query($sql);
	}
	
	public function example9() { 
		//$sql = $this->db->prepare("DELETE FROM city WHERE ID=?", array(4080));
		
		$sql = "CREATE TABLE tbl_1(id INT)";
		return $this->db->exec_query($sql);
	}
	
	public function example10() { 
		$sql = "SELECT COUNT(*) FROM city";
		return $this->db->get_var($sql);
	}
	
	public function example11() { 
		$sql = "SHOW TABLES";
		return $this->db->get_results($sql);
	}
	
	public function example12() { 
		$sql = "DESCRIBE City";
		return $this->db->get_results($sql);
	}
	
	public function example13() { 
		$sql = "EXPLAIN City";
		return $this->db->get_results($sql);
	}
	
	public function example14() { 
		$sql = "SELECT * FROM city";
		return $this->db->get_row($sql);
	}
}

/* End of file: ./application/data/users_data.php */
