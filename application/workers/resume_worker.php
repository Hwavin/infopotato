<?php
final class Resume_Worker extends Worker {
	public function get($params = array()) {
		// Make sure script execution doesn't time out.
        // Set maximum execution time in seconds (0 means no limit).
		set_time_limit(0);
		
		$file = count($params) > 0 ? strtolower($params[0]) : '';
		
		if ($file !== '') {
			Global_Functions::load_script('download/download_script');
			$download_dir = APP_DOWNLOAD_DIR;
			
			switch ($file) {
				case 'word' :
					download($download_dir.'ZhouYuan_Resume.doc', 'ZhouYuan_Resume.doc', 'application/msword');
					break;
				case 'pdf' :
					download($download_dir.'ZhouYuan_Resume.pdf', 'ZhouYuan_Resume.pdf', 'application/pdf');
					break;	
				case 'text' :
					download($download_dir.'ZhouYuan_Resume.txt', 'ZhouYuan_Resume.txt', 'text/plain');
					break;
			}
		} else {
			Global_Functions::load_script('redirect/redirect_script');
			redirect(BASE_URI.'about/founder/');
		}

	}
}

// End of file: ./application/workers/resume_worker.php
