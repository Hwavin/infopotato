<?php
final class About_Manager extends Manager {
	public function get_index(array $params = NULL) {
		$name = count($params) > 0 ? $params[0] : 'motivation';

		$layout_data = array(
			'page_title' => 'About',
			'stylesheets' => array('tabs.css'),
			'content' => $this->render_template('pages/about_'.$name),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/managers/about_manager.php 
