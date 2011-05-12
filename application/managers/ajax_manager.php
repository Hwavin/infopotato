<?php
final class Ajax_Manager extends Manager {
	public function get_index($params = array()) {
		$name = count($params) > 0 ? $params[0] : '';
	}
	
	public function post_index($params = array()) {
		
		Global_Functions::dump($this->POST_DATA);
		
	}
}

// End of file: ./application/managers/ajax_manager.php
