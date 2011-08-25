<?php
final class Maintenance_Manager extends Manager {
	public function get_index() {
		$response_data = array(
			'content' => $this->render_template('pages/maintenance'),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

// End of file: ./application/managers/maintenance_manager.php
