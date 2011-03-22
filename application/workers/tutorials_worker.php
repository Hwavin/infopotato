<?php
class Tutorials_Worker extends Worker {
	public function process($params = array()) {
		$name = count($params) > 0 ? $params[0] : '';

		if ($name == '') {
			$layout_data = array(
				'page_title' => 'Tutorials',
				'content' => $this->load_template('pages/tutorials'),
			);
			
			$response_data = array(
				'content' => $this->load_template('layouts/default_layout', $layout_data),
				'type' => 'text/html',
			);
			$this->response($response_data);
		} else {
			$layout_data = array(
				'page_title' => 'Tutorials',
				'content' => $this->load_template('pages/tutorials_'.$name),
			);
			
			$response_data = array(
				'content' => $this->load_template('layouts/default_layout', $layout_data),
				'type' => 'text/html',
			);
			$this->response($response_data);
		}	
	}
}

// End of file: ./application/presenters/tutorials_worker.php
