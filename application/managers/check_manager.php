<?php
final class Home_Manager extends Manager {
	public function get_index() {
		$layout_data = array(
			'page_title' => 'Home',
			'content' => $this->render_template('pages/home'),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}

	public function get_e() {
	    $this->load_function('SYS', 'redirect/redirect_function'); 
		redirect_function(APP_URI_BASE.'about/'); 
	}
}

// End of file: ./application/managers/home_manager.php