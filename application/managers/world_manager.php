<?php
final class World_Manager extends Manager {
	public function __construct() {
		parent::__construct();
		$this->load_data('world/world_data', 'w');
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
		$result = $this->w->example7('Pittsburgh-haha', 1);
		dump($result);
	}
}

// End of file: ./application/managers/world_manager.php