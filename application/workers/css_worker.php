<?php
final class Css_Worker extends Worker {
	public function get($params = array()) {
		// $css_files is an array created from $params[0]
		$css_files = count($params) > 0 ? explode(':', $params[0]) : NULL;
		
		if ($css_files !== NULL) {
			$css_content = '';
			foreach ($css_files as $css_file) {
				$css_content .= $this->load_template('css/'.$css_file);
			}

			$response_data = array(
				'content' => $css_content,
				'type' => 'text/css'
			);
			$this->response($response_data);
		}
	}
}

/* End of file: ./application/workers/css_worker.php */
