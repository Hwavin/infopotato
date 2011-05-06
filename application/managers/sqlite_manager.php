<?php
final class SQLite_Manager extends Manager {
	public function get_index() {
		$this->load_data('sqlite_data', 'u');
		
		//$user_info = $this->u->get_user_info(1);
		$users_info = $this->u->get_users_info();
		
		//$this->u->add_user('dsadas', 'dadsasada');

		Global_Functions::dump(APP_DIR.'bios.xml', 'XMl');
		
		//Global_Functions::dump($user_info);
		Global_Functions::dump($users_info);
		Global_Functions::dump($this->u);
		
		new Dump($this->u);
		
		$variable_test = array(
    "first"=>"1",
    "second",
    "third"=>array(
        "inner third 1",
        "inner third 2"=>"yeah"),
    "fourth");

new Dump($variable_test);

	}
}

// End of file: ./application/managers/sqlite_manager.php
