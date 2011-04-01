<?php
final class SQLite_Worker extends Worker {
	public function get() {
		$this->load_data('sqlite_data', 'u');
		
		//$user_info = $this->u->get_user_info(1);
		$users_info = $this->u->get_users_info();
		
		//$this->u->add_user('dsadas', 'dadsasada');
		
		//Global_Functions::dump($user_info);
		Global_Functions::dump($users_info);
	}
}

// End of file: ./application/workers/sqlite_worker.php
