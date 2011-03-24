<?php
final class Users_Worker extends Worker {
	public function get() {
		$this->load_data('users_data', 'u');
		
		$user_info = $this->u->get_user_info(1);
		$users_info = $this->u->get_users_info();

		dump($user_info);
		dump($users_info);
	}
}

// End of file: ./application/workers/users_worker.php
