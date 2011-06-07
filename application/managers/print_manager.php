<?php
final class Print_Manager extends Manager {
	public function get_index() {
		$this->load_library('SYS', 'printer/printer_library', 'p'); 

		$layout_data = array(
			'page_title' => 'Printable Version',
			'content' => $this->p->render(),
		);
		
		$response_data = array(
			'content' => $this->render_template('layouts/print_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
}

/* End of file: ./application/managers/print_manager.php */
