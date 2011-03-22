<?php
class Js_Worker extends Worker {
	public function process($params = array()) {
		// $js_files is an array created from $params[0]
		$js_files = count($params) > 0 ? explode(':', $params[0]) : NULL;
		
		if ($js_files != NULL) {
			$js_content = '';
			foreach ($js_files as $js_file) {
				$js_content .= $this->load_template('js/'.$js_file);
			}
			
			$response_data = array(
				'content' => $js_content,
				'type' => 'text/javascript'
			);
			$this->response($response_data);
		}
	}
}

/* End of file: ./application/presenters/js_worker.php */
