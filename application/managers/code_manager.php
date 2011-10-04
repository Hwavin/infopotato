<?php
final class Code_Manager extends Manager {
	public function get_index() {
		$layout_data = array(
			'page_title' => 'Browse source code',
			'content' => $this->render_template('pages/code'),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	public function get_core(array $params = NULL) {
		$file = count($params) > 0 ? SYS_DIR.'core'.DS.$params[0].'.php' : '';

		$content_data = array(
		    'filename' => $params[0].'.php',
			'highlighted_code' => explode('<br />', str_replace(array('<code>', '</code>'), '', highlight_file($file, true))),
		);
		
		$layout_data = array(
			'page_title' => 'Browse source code',
			'content' => $this->render_template('pages/browse_code', $content_data),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	public function get_library(array $params = NULL) {
		$file = count($params) > 0 ? SYS_DIR.'libraries'.DS.$params[0].DS.$params[0].'_library.php' : '';

		$content_data = array(
		    'filename' => $params[0].'_library.php',
			'highlighted_code' => explode('<br />', str_replace(array('<code>', '</code>'), '', highlight_file($file, true))),
		);
		
		$layout_data = array(
			'page_title' => 'Browse source code',
			'content' => $this->render_template('pages/browse_code', $content_data),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	public function get_function(array $params = NULL) {
		$file = count($params) > 0 ? SYS_DIR.'functions'.DS.$params[0].DS.$params[0].'_function.php' : '';

		$content_data = array(
		    'filename' => $params[0].'_function.php',
			'highlighted_code' => explode('<br />', str_replace(array('<code>', '</code>'), '', highlight_file($file, true))),
		);
		
		$layout_data = array(
			'page_title' => 'Browse source code',
			'content' => $this->render_template('pages/browse_code', $content_data),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

/* End of file: ./application/managers/code_manager.php */
