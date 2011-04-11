<?php
final class Download_Worker extends Worker {	
	public function get() {
		$layout_data = array(
			'page_title' => 'Download',
			'content' => $this->render_template('pages/download'),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}

}

// End of file: ./application/workers/download_worker.php
