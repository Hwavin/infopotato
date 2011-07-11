<?php
class Ajax_Manager extends Manager {
	public function get_index($params = array()) {
		$param1 = isset($params[0]) ? $params[0] : '';
		$param2 = isset($params[1]) ? $params[1] : '';
		
		echo 'This is an Ajax GET request with '.$param1. ' and '.$param2;
	}
	
	public function post_index() {
		echo '<pre>';
		print_r($this->POST_DATA);
		echo '</pre>';
	}
}

// End of file: ./application/managers/ajax_manager.php
