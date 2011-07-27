<?php
final class Resume_Manager extends Manager {
	public function get_index($params = array()) {
		// Make sure script execution doesn't time out.
        // Set maximum execution time in seconds (0 means no limit).
		set_time_limit(0);
		
		$file = count($params) > 0 ? strtolower($params[0]) : '';

		if ($file !== '') {
			$this->load_function('SYS', 'download/download_function');
			$download_dir = APP_DOWNLOAD_DIR;
			switch ($file) {
				case 'word' :
					download_function($download_dir.'ZhouYuan_Resume.doc');
					break;
				case 'pdf' :
					download_function($download_dir.'ZhouYuan_Resume.pdf');
					break;	
				default:
					echo 'File not found';
			}
		} else {
			$this->load_function('SYS', 'redirect/redirect_function');
			redirect_function(APP_URI_BASE.'about/founder/');
		}

	}
}

// End of file: ./application/managers/resume_manager.php
