<?php
final class Lang_Manager extends Manager {
	public function get_index($params = array()) {
		$lang = (count($params[0]) > 0) ? $params[0].'/'.$params[1] : I18n::$lang;
		
		Session::set('lang', $lang);
		
		$this->_load_function('SYS', 'redirect/redirect_function');	
		redirect_function($_SERVER['HTTP_REFERER']);
	}
}

// End of file: ./application/managers/lang_manager.php
