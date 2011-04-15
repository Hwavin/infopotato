<?php
final class Tutorials_Worker extends Worker {
	public function get($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$layout_data = array(
			'page_title' => 'Tutorials',
			'javascripts' => $name === '_video' ? array('swfobject.js') : NULL,
			'content' => $this->render_template('pages/tutorials'.$name),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/workers/tutorials_worker.php
