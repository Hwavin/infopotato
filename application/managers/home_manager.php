<?php
class Home_Manager extends Manager {
	public function get_index() {
		$this->load_library('SYS', 'http_client/http_client_library', 'hc');
		$vars = array();
		$this->hc->submit('http://www.infopotato.com/contact', $vars);
		print_r($this->hc->results);
	}

}

// End of file: ./application/managers/home_manager.php
