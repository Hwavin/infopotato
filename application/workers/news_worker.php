<?php
final class News_Worker extends Worker {
	public function get($params = array()) {
		$name = count($params) > 0 ? $params[0] : '';
		
		$this->load_library('cache/cache_library', 'cache', array('cache_dir'=>APP_CACHE_DIR));
		
		if ($name == '') {
			$layout_data = array(
				'page_title' => 'News',
				'content' => $this->load_template('pages/news'),
			);
			
			$response_data = array(
				'content' => $this->load_template('layouts/default_layout', $layout_data),
				'type' => 'text/html',
			);
			$this->response($response_data);
		} else {
			$layout_data = array(
				'page_title' => 'News',
				'content' => $this->load_template('pages/news_'.$name),
			);
			
			$response_data = array(
				'content' => $this->load_template('layouts/default_layout', $layout_data),
				'type' => 'text/html',
			);
			$this->response($response_data);
		}	
	}
}

// End of file: ./application/workers/news_worker.php
