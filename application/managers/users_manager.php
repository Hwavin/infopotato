<?php
final class Users_Manager extends Manager {
	public function get_index() {
		$this->load_data('users/users_data', 'u');
		
		//$user_info = $this->u->get_user_info(1);
		$users_info = $this->u->get_users_info();

		//Global_Functions::dump($user_info);
		Global_Functions::dump($users_info);

}

// End of file: ./application/managers/users_manager.php
