<?php
final class Maintenance_Manager extends Manager {
	public function get_index() {
		$response_data = array(
			'content' => $this->_render_template('pages/maintenance'),
			'type' => 'text/html',
		);
		$this->_response($response_data);
	}
}

// End of file: ./application/managers/maintenance_manager.php
