<?php
class Home_Manager extends Manager {
    public function __construct() {
        //parent::__construct();
	}
	
	public function get_index() {
		$response_data = array(
			'content' => $this->render_template('pages/home'),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	public function post_index() {
		dump($_GET);
		dump($_POST);
		dump($_FILES);
		

		dump($this->_POST_DATA);
		dump($this->_FILES_DATA);
	}
	
}

// End of file: ./application/managers/home_manager.php
