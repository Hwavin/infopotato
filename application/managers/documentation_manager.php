<?php
final class Documentation_Manager extends Manager {
	public function get_index($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$current_name = $name !== '' ? $params[0] : '';
		
		$page = array(
			'server_requirements' => 'Server Requirements',
			'installation' => 'Installation Instruction',
			'environments' => 'The Environment',
			'structure' => 'The Directory Structure',
			'alternative_php' => 'Alternative PHP Syntax',
			'style_guide' => 'Conventions & Style Guide',
		);
		
		$pager_data = array(
			'page' => $page,
			'current_name' => $current_name
		);
		
		$content_data = array(
			'pager' => $this->render_template('pages/pager', $pager_data)
		);
		
		$layout_data = array(
			'page_title' => 'Documentation',
			'stylesheets' => array('syntax.css', 'pager.css'),
			'content' => $this->render_template('pages/documentation'.$name, $content_data),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/managers/documentation_manager.php 
