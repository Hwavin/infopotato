<?php
final class About_Worker extends Worker {
	public function get($params = array()) {
		$name = count($params) > 0 ? $params[0] : 'motivation';

		$layout_data = array(
			'page_title' => 'About',
			'stylesheets' => array('tabs.css'),
			'content' => $this->load_template('pages/about_'.$name),
		);
		
		$response_data = array(
			'content' => $this->load_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/workers/about_worker.php 
