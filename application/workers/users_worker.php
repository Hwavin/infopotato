<?php
final class Users_Worker extends Worker {
	public function get() {
		$this->load_data('users/users_data', 'u');
		
		//$user_info = $this->u->get_user_info(1);
		$users_info = $this->u->get_users_info();

		//Global_Functions::dump($user_info);
		Global_Functions::dump($users_info);
	}
}

// End of file: ./application/workers/users_worker.php
