<?php
final class Tutorials_Manager extends Auth_Manager {
	public function __construct() {
		parent::__construct();
		
		if ($this->_check_auth() === FALSE) {
			$this->get_login();
			exit;
		}
	}
	
	public function get_index($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$layout_data = array(
			'page_title' => 'Tutorials',
			'javascripts' => $name === '_video' ? array('swfobject.js') : NULL,
			'stylesheets' => array('syntax.css'),
			'content' => $this->render_template('pages/tutorials'.$name),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/managers/tutorials_manager.php
