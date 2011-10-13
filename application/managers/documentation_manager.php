<?php
final class Documentation_Manager extends Manager {

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
	
	public function get_start() {
		$layout_data = array(
			'page_title' => 'Documentation - Getting Started',
			'content' => $this->render_template('pages/documentation_start'),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	public function get_intro(array $params = NULL) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$current_name = ($name !== '') ? $params[0] : '';

		$pages = array(
			array(
				'uri' => 'server_requirements', 
				'name' => 'Server Requirements'
			),
			array(
				'uri' => 'version_numbering', 
				'name' => 'Version numbering'
			),
			array(
				'uri' => 'installation', 
				'name' => 'Installation Instruction'
			),
			array(
				'uri' => 'environments', 
				'name' => 'The Environment'
			),
			array(
				'uri' => 'structure', 
				'name' => 'The Directory Structure'
			),
			array(
				'uri' => 'alternative_php', 
				'name' => 'Alternative PHP Syntax'
			),
			array(
				'uri' => 'style_guide', 
				'name' => 'Conventions &amp; Style Guide'
			),
			array(
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
	
	public function get_core(array $params = NULL) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$current_name = ($name !== '') ? $params[0] : '';

		$pages = array(
			array(
				'uri' => 'workflow', 
				'name' => 'Request Processing Workflow'
			),
			array(
				'uri' => 'global', 
				'name' => 'Global Constants and Functions'
			),
			array(
				'uri' => 'uri', 
				'name' => 'URI'
			),
			array(
				'uri' => 'bootstrap', 
				'name' => 'Bootstrap'
			),
			array(
				'uri' => 'dispatcher', 
				'name' => 'Dispatcher'
			),
			array(
				'uri' => 'manager', 
				'name' => 'Manager'
			),
			array(
				'uri' => 'template', 
				'name' => 'Template'
			),
			array(
				'uri' => 'data', 
				'name' => 'Data'
			),
			array(
				'uri' => 'sql_dao', 
				'name' => 'SQL Data Access Object'
			),
			array(
				'uri' => 'i18n_l10n', 
				'name' => 'Internationalization &amp; Localization'
			),
			array(
				'uri' => 'library', 
				'name' => 'Library'
			),
			array(
				'uri' => 'function', 
				'name' => 'Function'
			),
			array(
				'uri' => 'runtime', 
				'name' => 'System Core Runtime Cache'
			),
			array(
				'uri' => 'dump', 
				'name' => 'Dump Variable'
			),
			array(
				'uri' => 'utf8', 
				'name' => 'UTF-8 Support'
			),
			array(
				'uri' => 'caching', 
				'name' => 'Caching'
			),
			array(
				'uri' => 'cookie', 
				'name' => 'Cookie'
			),
			array(
				'uri' => 'session', 
				'name' => 'Session'
			),
			array(
				'uri' => 'security', 
				'name' => 'Security'
			),
			array(
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
	
	public function get_library(array $params = NULL) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$current_name = ($name !== '') ? $params[0] : '';

		$pages = array(
			array(
				'uri' => 'output_cache', 
				'name' => 'Output Cache'
			),
			array(
				'uri' => 'calendar', 
				'name' => 'Calendar'
			),
			array(
				'uri' => 'dirinfo', 
				'name' => 'Dir Info'
			),
			array(
				'uri' => 'email', 
				'name' => 'Email'
			),
			array(
				'uri' => 'encypt', 
				'name' => 'Encryption'
			),
			array(
				'uri' => 'upload', 
				'name' => 'File Upload'
			),
			array(
				'uri' => 'form_validation', 
				'name' => 'Form Validation'
			),
			array(
				'uri' => 'image', 
				'name' => 'Image Manipulation'
			),
			array(
				'uri' => 'ftp', 
				'name' => 'FTP'
			),
			array(
				'uri' => 'pagination', 
				'name' => 'Pagination'
			),
			array(
				'uri' => 'password_hashing', 
				'name' => 'Password Hashing'
			),
			array(
				'uri' => 'printer', 
				'name' => 'Printer'
			),
			array(
				'uri' => 'user_agent', 
				'name' => 'User Agent'
			),
			array(
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
	
	public function get_function(array $params = NULL) {
		$name = count($params) > 0 ? '_'.$params[0] : '';
		
		$current_name = ($name !== '') ? $params[0] : '';

		$pages = array(
			array(
				'uri' => 'captcha', 
				'name' => 'CAPTCHA'
			),
			array(
				'uri' => 'download', 
				'name' => 'Download'
			),
			array(
				'uri' => 'redirect', 
				'name' => 'Redirection'
			),
			array(
				'uri' => 'minify_html', 
				'name' => 'Minify HTML'
			),
			array(
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
	
	public function get_tutorial(array $params = NULL) {
		$name = count($params) > 0 ? '_'.$params[0] : '';

		$layout_data = array(
			'page_title' => 'Documentation - Tutorials',
			'stylesheets' => $name === '_form_validation' ? array('syntax.css', 'livevalidation.css') : array('syntax.css'),
			'content' => $this->render_template('pages/documentation_tutorial'.$name),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/managers/documentation_manager.php 
