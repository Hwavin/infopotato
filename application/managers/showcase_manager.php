<?php
final class Showcase_Manager extends Manager {
	public function get_index($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$layout_data = array(
			'page_title' => 'Showcase',
			'content' => $this->render_template('pages/showcase'.$name),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/managers/showcase_manager.php
