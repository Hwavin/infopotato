<?php
final class SQLite_Manager extends Manager {
	public function get_index() {
		$this->load_data('sqlite_data', 'u');
		
		//$user_info = $this->u->get_user_info(1);
		$users_info = $this->u->get_users_info();
		
		//$this->u->add_user('dsadas', 'dadsasada');

		Global_Functions::dump(APP_DIR.'bios.xml', 'xml', TRUE);
		
		//Global_Functions::dump($user_info);
		//Global_Functions::dump($users_info, '', TRUE);
		//Global_Functions::dump($this->u);


		$this->load_library('SYS', 'snoopy/snoopy_library', 'snoopy');
		$this->snoopy->fetchlinks("http://www.instituteforlearning.org/");
		Global_Functions::dump($this->snoopy->results, '', TRUE);
	}
} 

// End of file: ./application/managers/sqlite_manager.php
