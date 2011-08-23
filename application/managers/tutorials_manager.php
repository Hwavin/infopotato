<?php
final class Tutorials_Manager extends Manager {
	public function get_index($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$layout_data = array(
			'page_title' => 'Tutorials',
			'javascripts' => $name === '_video' ? array('swfobject.js') : NULL,
			'stylesheets' => array('syntax.css'),
			'content' => $this->_render_template('pages/tutorials'.$name),
		);
		
		$response_data = array(
			'content' => $this->_render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->_response($response_data);
	}
}

// End of file: ./application/managers/tutorials_manager.php
