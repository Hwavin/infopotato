<?php
final class Documentation_Worker extends Worker {
	public function get($params = array()) {
		$name = count($params) > 0 ? $params[0] : '';

		if ($name == '') {
			$layout_data = array(
				'page_title' => 'Documentation',
				'content' => $this->load_template('pages/documentation'),
			);
			
			$response_data = array(
				'content' => $this->load_template('layouts/default_layout', $layout_data),
				'type' => 'text/html',
			);
			$this->response($response_data);
		} else {
			$layout_data = array(
				'page_title' => 'Documentation',
				'content' => $this->load_template('pages/documentation_'.$name),
			);
			
			$response_data = array(
				'content' => $this->load_template('layouts/default_layout', $layout_data),
				'type' => 'text/html',
			);
			$this->response($response_data);
		}	
	}
}

// End of file: ./application/workers/documentation_worker.php 
