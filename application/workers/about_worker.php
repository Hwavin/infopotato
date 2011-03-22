<?php
class About_Worker extends Worker {
	public function process($params = array()) {
		$name = count($params) > 0 ? $params[0] : '';

		if ($name === '') {
			$this->_motivation();
		} else {
			$this->{'_'.$name}();
		}	
	}
	
	private function _motivation() {
		$layout_data = array(
			'page_title' => 'Motivation',
			'stylesheets' => array('tabs.css'),
			'content' => $this->load_template('pages/about_motivation'),
		);
		
		$response_data = array(
			'content' => $this->load_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);

		$this->response($response_data);
	}
	
	private function _facts() {
		$layout_data = array(
			'page_title' => 'Facts',
			'stylesheets' => array('tabs.css'),
			'content' => $this->load_template('pages/about_facts'),
		);
		
		$response_data = array(
			'content' => $this->load_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	private function _license() {
		$layout_data = array(
			'page_title' => 'License',
			'stylesheets' => array('tabs.css'),
			'content' => $this->load_template('pages/about_license'),
		);
		
		$response_data = array(
			'content' => $this->load_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	private function _founder() {
		$layout_data = array(
			'page_title' => 'Founder',
			'stylesheets' => array('tabs.css'),
			'content' => $this->load_template('pages/about_founder'),
		);
		
		$response_data = array(
			'content' => $this->load_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
}

// End of file: ./application/presenters/about_worker.php 
