<?php
final class Error_Manager extends Manager {
	public function get_404($params = array()) {
        $layout_data = array(
			'page_title' => '404 - Not Found',
			'content' => $this->render_template('pages/error_404'),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/managers/error_manager.php
