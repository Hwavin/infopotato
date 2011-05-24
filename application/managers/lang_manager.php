<?php
final class Lang_Manager extends Manager {
	public function get_index() {
		$layout_data = array(
			'page_title' => __('Hello World'),
			'content' => $this->render_template('pages/lang'),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/lang_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/managers/home_manager.php
