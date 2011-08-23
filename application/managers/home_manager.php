<?php
final class Home_Manager extends Manager {
	public function get_index() {
		$layout_data = array(
			'page_title' => 'Home',
			'content' => $this->_render_template('pages/home'),
		);
		
		$response_data = array(
			'content' => $this->_render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->_response($response_data);
	}

}

// End of file: ./application/managers/home_manager.php
