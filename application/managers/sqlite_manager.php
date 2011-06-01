<?php
final class SQLite_Manager extends Manager {
	public function get_index() {
		$this->load_data('sqlite_data', 'u');
		
		//$user_info = $this->u->get_user_info(1);
		$users_info = $this->u->get_users_info();
		
		//$this->u->add_user('dsadas', 'dadsasada');

		dump(APP_DIR.'bios.xml', 'xml', TRUE);
		
		//dump($user_info);
		//dump($users_info, '', TRUE);
		//dump($this->u);


		$this->load_library('SYS', 'snoopy/snoopy_library', 'snoopy');
		$this->snoopy->fetchlinks("http://www.instituteforlearning.org/");
		dump($this->snoopy->results, '', TRUE);
	}
} 

// End of file: ./application/managers/sqlite_manager.php
