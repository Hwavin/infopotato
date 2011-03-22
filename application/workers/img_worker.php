<?php
class Img_Worker extends Worker {
	public function process($params = array()) {
		$img_file = count($params) > 0 ? implode('/', $params) : '';

		// Is the img_file in a sub-folder? If so, parse out the filename and path.
		if (strpos($img_file, '/') === FALSE) {
			$path = '';
		} else {
			$x = explode('/', $img_file);
			$img_file = end($x);	
			unset($x[count($x)-1]);
			$path = implode(DS, $x).DS;
		}

		$img_file_path = APP_TEMPLATE_DIR.'images'.DS.strtolower($path.$img_file);

		$img_mime_types = array(	
			'png' => 'image/png',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'ico' => 'image/vnd.microsoft.icon',
		);
		
		// Find File Extension
		$arr = explode('.', $img_file);
		$img_extension = end($arr);
		
		$response_data = array(
			'content' => file_get_contents($img_file_path),
			'type' => array_key_exists($img_extension, $img_mime_types) ? $img_mime_types[$img_extension] : 'image/jpeg',
		);
		$this->response($response_data);
	}
}

/* End of file: ./application/presenters/img_worker.php */
