<?php
class Ajax_Manager extends Manager {
	public function get_index($params = array()) {
		$param1 = isset($params[0]) ? $params[0] : '';
		$param2 = isset($params[1]) ? $params[1] : '';
		
		$this->_disable_cache();
		echo 'This is an Ajax GET request with '.$param1. ' and '.$param2;
	}
	
	public function post_index() {
		$this->_disable_cache();
		echo '<pre>';
		print_r($this->_POST_DATA);
		echo '</pre>';
	}
	
	private function _disable_cache() {
        header('Pragma: no-cache');        
        header('Cache-control: no-cache');        
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	}
}

// End of file: ./application/managers/ajax_manager.php
