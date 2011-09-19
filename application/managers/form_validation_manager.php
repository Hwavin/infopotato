<?php
final class Form_Validation_Manager extends Manager {
	public function post_index() {
		// Load Form Validation library and assign post data
		$this->load_library('SYS', 'form_validation/form_validation_library', 'fv', array('post' => $this->_POST_DATA));

		$this->fv->set_rules('field1', 'Email', 'trim|valid_email');
		$this->fv->set_rules('field2', 'Message', 'trim|required');
		
		$result = $this->fv->run();

		// Further process the input data with htmlawed function
		$field1 = $this->fv->set_value('field1');
		$field2 = $this->fv->set_value('field2');

		if ($result == FALSE) {
			$data = array(
				'field1' => $field1,
				'field2' => $field2,
				'field1_error' => $this->fv->field_error('field1', '<span class="red">', '</span>'),
				'field2_error' => $this->fv->field_error('field2', '<span class="red">', '</span>'),
			); 
			
			$layout_data = array(
				'page_title' => 'Form Validation',
				'stylesheets' => array('syntax.css'),
				'content' => $this->render_template('pages/documentation_tutorial_form_validation', $data),
			);
		} else {	
			$data = array(
				'sent' => TRUE, 
			);	
			
			$layout_data = array(
				'page_title' => 'Form Validation',
				'content' => $this->render_template('pages/form_validation_response', $data),
			);
		}

		$response_data = array(
			'content' => $this->render_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
}

// End of file: ./application/managers/form_validation_manager.php 
