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
					download($download_dir.'ZhouYuan_Resume.doc');
					break;
				case 'pdf' :
					download($download_dir.'ZhouYuan_Resume.pdf');
					break;	
				case 'text' :
					download($download_dir.'ZhouYuan_Resume.txt');
					break;
				default:
					echo 'File not found';
			}
		} else {
			Global_Functions::load_script('redirect/redirect_script');
			redirect(APP_ENTRY_URI.'about/founder/');
		}

	}
}

// End of file: ./application/workers/resume_worker.php
