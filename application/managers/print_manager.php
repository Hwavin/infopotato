<?php
final class Print_Manager extends Auth_Manager {
	public function get_index($params = array()) {
		// Login required
		$this->_check_auth();
		
		$uri = count($params) > 0 ? APP_URI_BASE.base64_decode($params[0]) : '';

		$this->load_library('SYS', 'printer/printer_library', 'p'); 

		$layout_data = array(
			'page_title' => 'Printable Version',
			'content' => $this->p->render(file_get_contents($uri)),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/print_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

/* End of file: ./application/managers/print_manager.php */
