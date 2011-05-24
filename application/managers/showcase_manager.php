<?php
final class Showcase_Manager extends Manager {
	public function get_index($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$page = isset($params[0]) ? (int) $params[0] : 1;

		$config = array(
			'base_uri' => 'http://localhost/infopotato/application/public/index.php/showcase/index/',
			'items_total' => 200, 
			'current_page' => 3,
			'current_page_class' => 'current_page',
			'mid_range' => 7
		);
		$this->load_library('SYS', 'pagination/pagination_library', 'page', $config);
		
		$content_data = array(
			'pagination' => $this->page->build_pagination(),
		);
		
		$layout_data = array(
			'page_title' => 'Showcase',
			'stylesheets' => array('pagination.css'),
			'content' => $this->render_template('pages/showcase'.$name, $content_data),
		);
		

		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
		
		//Global_Functions::dump($this->page->get_pagination_data());
	}
}

// End of file: ./application/managers/showcase_manager.php
