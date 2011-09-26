<?php
final class World_Manager extends Manager {
	public function __construct() {
		parent::__construct();
		$this->load_data('world/world_data', 'w');
	}
	
	public function get_index() {
	    $this->get_example1();
		$this->get_example2();
		$this->get_example3();
		$this->get_example4();
		$this->get_example5();
		$this->get_example6();
	}
	
	public function get_example1() {
		$result = $this->w->example1();
		dump($result);
	}
	
	public function get_example2() {
		$result = $this->w->example2('Pittsburgh');
		dump($result);
	}
	
	public function get_example3() {
		$result = $this->w->example3();
		dump($result);
	}
	
	public function get_example4() {
		$result = $this->w->example4('Pittsburgh');
		dump($result);
	}
	
	public function get_example5() {
		$result = $this->w->example5();
		dump($result);
	}
	
	public function get_example6() {
		$result = $this->w->example6('Pittsburgh');
		dump($result);
	}
	
	public function get_example7() {
		$result = $this->w->example7('Pittsburgh-haha', 4080);
		dump($result);
	}
	
	public function get_example8() {
		$result = $this->w->example8('Philadelphia','USA','Pennsylvania',1517550);
		dump($result);
	}
	
	public function get_example9() {
		$result = $this->w->example9();
		dump($result);
	}
	
	public function get_example10() {
		$result = $this->w->example10();
		dump($result);
	}
	
	public function get_example11() {
		$result = $this->w->example11();
		dump($result);
	}
	
	public function get_example12() {
		$result = $this->w->example12();
		dump($result);
	}
	
	public function get_example13() {
		$result = $this->w->example13();
		dump($result);
	}
	
	public function get_example14() {
		$result = $this->w->example14();
		dump($result);
	}
	
	public function get_example15() {
		$result = $this->w->example15();
		dump($result);
	}
}

// End of file: ./application/managers/world_manager.php