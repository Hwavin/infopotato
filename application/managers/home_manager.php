<?php
final class Home_Manager extends Manager {
	public function get_index() {
		$layout_data = array(
			'page_title' => 'Home',
			'content' => $this->render_template('pages/home'),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}

	public function get_t() { 
		$this->load_library('SYS', 'output_cache/output_cache_library', 'cache', array('cache_dir'=>APP_DIR.'cache'.DS)); 
		$cached_data = $this->cache->get('home'); 
		
		if ($cached_data === FALSE) {
			$layout_data = array( 
				'page_title' => 'Home', 
				'content' => $this->render_template('pages/home'), 
			);
			$this->cache->set('home', $layout_data['content']); 
		} else {
			$layout_data = array( 
				'page_title' => 'Home', 
				'content' => $cached_data, 
			); 
		}
		
		$response_data = array( 
			'content' => $this->render_template('layouts/default_layout', $layout_data), 
			'type' => 'text/html', 
		); 
		$this->response($response_data);
    }
}

// End of file: ./application/managers/home_manager.php
