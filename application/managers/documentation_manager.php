<?php
final class Documentation_Manager extends Auth_Manager {
	public function r__construct() {
		parent::__construct();

		if ($this->_check_auth() === FALSE) {
			$this->get_login();
			exit;
		}
	}
	
	public function get_index() {
		$layout_data = array(
			'page_title' => 'Documentation',
			'content' => $this->render_template('pages/documentation'),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	public function get_intro($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$current_name = ($name !== '') ? $params[0] : '';

		$pages = array(
			0 => array(
				'uri' => 'server_requirements', 
				'name' => 'Server Requirements'
			),
			1 => array(
				'uri' => 'installation', 
				'name' => 'Installation Instruction'
			),
			2 => array(
				'uri' => 'environments', 
				'name' => 'The Environment'
			),
			3 => array(
				'uri' => 'structure', 
				'name' => 'The Directory Structure'
			),
			4 => array(
				'uri' => 'alternative_php', 
				'name' => 'Alternative PHP Syntax'
			),
			5 => array(
				'uri' => 'style_guide', 
				'name' => 'Conventions &amp; Style Guide'
			),
			6 => array(
				'uri' => 'testing', 
				'name' => 'Testing'
			),
		);
		
		for ($i = 0; $i < count($pages); $i++) {
			if ($pages[$i]['uri'] === $current_name ) {
				$prev = $i > 0 ? $pages[$i - 1] : NULL;
				$next = $i < count($pages) - 1 ? $pages[$i + 1] : NULL;
				break;
			}
		}
		
		$pager_data = array(
			'pages' => $pages,
			'current_name' => $current_name,
			'prev' => isset($prev) ? $prev : NULL,
			'next' => isset($next) ? $next : NULL,
			'base_uri' => APP_URI_BASE.'documentation/intro/'
		);
		
		//dump($pager_data);
		
		$content_data = array(
			'pager' => $this->render_template('pages/pager', $pager_data)
		);
		
		$layout_data = array(
			'page_title' => 'Documentation - Introduction',
			'stylesheets' => array('syntax.css', 'pager.css'),
			'content' => $this->render_template('pages/documentation_intro'.$name, $content_data),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	public function get_core($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$current_name = ($name !== '') ? $params[0] : '';

		$pages = array(
			0 => array(
				'uri' => 'workflow', 
				'name' => 'Request Processing Workflow'
			),
			1 => array(
				'uri' => 'global', 
				'name' => 'Global Constants and Functions'
			),
			2 => array(
				'uri' => 'uri', 
				'name' => 'URI'
			),
			3 => array(
				'uri' => 'dispatcher', 
				'name' => 'Dispatcher'
			),
			4 => array(
				'uri' => 'manager', 
				'name' => 'Manager'
			),
			5 => array(
				'uri' => 'template', 
				'name' => 'Template'
			),
			6 => array(
				'uri' => 'data', 
				'name' => 'Data Access Object'
			),
			7 => array(
				'uri' => 'sql_adapters', 
				'name' => 'SQL Database Adapters'
			),
			8 => array(
				'uri' => 'i18n', 
				'name' => 'Internationalization'
			),
			9 => array(
				'uri' => 'library', 
				'name' => 'Library'
			),
			10 => array(
				'uri' => 'function', 
				'name' => 'Function'
			),
			11 => array(
				'uri' => 'runtime', 
				'name' => 'System Core Runtime Cache'
			),
			12 => array(
				'uri' => 'dump', 
				'name' => 'Dump Variable'
			),
			13 => array(
				'uri' => 'utf8', 
				'name' => 'UTF-8 Support'
			),
			14 => array(
				'uri' => 'caching', 
				'name' => 'Caching'
			),
			15 => array(
				'uri' => 'cookie', 
				'name' => 'Cookie'
			),
			16 => array(
				'uri' => 'session', 
				'name' => 'Session'
			),
			17 => array(
				'uri' => 'security', 
				'name' => 'Security'
			),
			18 => array(
				'uri' => 'ajax', 
				'name' => 'Ajax Interaction'
			),
		);
		
		for ($i = 0; $i < count($pages); $i++) {
			if ($pages[$i]['uri'] === $current_name ) {
				$prev = $i > 0 ? $pages[$i - 1] : NULL;
				$next = $i < count($pages) - 1 ? $pages[$i + 1] : NULL;
				break;
			}
		}
		
		$pager_data = array(
			'pages' => $pages,
			'current_name' => $current_name,
			'prev' => isset($prev) ? $prev : NULL,
			'next' => isset($next) ? $next : NULL,
			'base_uri' => APP_URI_BASE.'documentation/core/'
		);
		
		//dump($pager_data);
		
		$content_data = array(
			'pager' => $this->render_template('pages/pager', $pager_data)
		);
		
		$layout_data = array(
			'page_title' => 'Documentation - Introduction',
			'stylesheets' => array('syntax.css', 'pager.css'),
			'content' => $this->render_template('pages/documentation_core'.$name, $content_data),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	public function get_library($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$current_name = ($name !== '') ? $params[0] : '';

		$pages = array(
			0 => array(
				'uri' => 'output_cache', 
				'name' => 'Output Cache'
			),
			1 => array(
				'uri' => 'calendar', 
				'name' => 'Calendar'
			),
			2 => array(
				'uri' => 'dir', 
				'name' => 'Dir Info'
			),
			3 => array(
				'uri' => 'feed_writer', 
				'name' => 'Feed Writer'
			),
			4 => array(
				'uri' => 'email', 
				'name' => 'Email'
			),
			5 => array(
				'uri' => 'encypt', 
				'name' => 'Encryption'
			),
			6 => array(
				'uri' => 'upload', 
				'name' => 'File Upload'
			),
			7 => array(
				'uri' => 'form_validation', 
				'name' => 'Form Validation'
			),
			8 => array(
				'uri' => 'ftp', 
				'name' => 'FTP'
			),
			9 => array(
				'uri' => 'pagination', 
				'name' => 'Pagination'
			),
			10 => array(
				'uri' => 'password_hashing', 
				'name' => 'Password Hashing'
			),
			11 => array(
				'uri' => 'printer', 
				'name' => 'Printer'
			),
			12 => array(
				'uri' => 'user_agent', 
				'name' => 'User Agent'
			),
			13 => array(
				'uri' => 'mobile_detect', 
				'name' => 'Mobile Device Detection'
			),
		);
		
		for ($i = 0; $i < count($pages); $i++) {
			if ($pages[$i]['uri'] === $current_name ) {
				$prev = $i > 0 ? $pages[$i - 1] : NULL;
				$next = $i < count($pages) - 1 ? $pages[$i + 1] : NULL;
				break;
			}
		}
		
		$pager_data = array(
			'pages' => $pages,
			'current_name' => $current_name,
			'prev' => isset($prev) ? $prev : NULL,
			'next' => isset($next) ? $next : NULL,
			'base_uri' => APP_URI_BASE.'documentation/library/'
		);
		
		//dump($pager_data);
		
		$content_data = array(
			'pager' => $this->render_template('pages/pager', $pager_data)
		);
		
		$layout_data = array(
			'page_title' => 'Documentation - Library',
			'stylesheets' => array('syntax.css', 'pager.css'),
			'content' => $this->render_template('pages/documentation_library'.$name, $content_data),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	public function get_function($params = array()) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$current_name = ($name !== '') ? $params[0] : '';

		$pages = array(
			0 => array(
				'uri' => 'captcha', 
				'name' => 'CAPTCHA'
			),
			1 => array(
				'uri' => 'download', 
				'name' => 'Download'
			),
			2 => array(
				'uri' => 'redirect', 
				'name' => 'Redirection'
			),
			3 => array(
				'uri' => 'minify_html', 
				'name' => 'Minify HTML'
			),
			4 => array(
				'uri' => 'htmlawed', 
				'name' => 'HtmLawed'
			),
		);
		
		for ($i = 0; $i < count($pages); $i++) {
			if ($pages[$i]['uri'] === $current_name ) {
				$prev = $i > 0 ? $pages[$i - 1] : NULL;
				$next = $i < count($pages) - 1 ? $pages[$i + 1] : NULL;
				break;
			}
		}
		
		$pager_data = array(
			'pages' => $pages,
			'current_name' => $current_name,
			'prev' => isset($prev) ? $prev : NULL,
			'next' => isset($next) ? $next : NULL,
			'base_uri' => APP_URI_BASE.'documentation/function/'
		);
		
		//dump($pager_data);
		
		$content_data = array(
			'pager' => $this->render_template('pages/pager', $pager_data)
		);
		
		$layout_data = array(
			'page_title' => 'Documentation - Function',
			'stylesheets' => array('syntax.css', 'pager.css'),
			'content' => $this->render_template('pages/documentation_function'.$name, $content_data),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/managers/documentation_manager.php 
