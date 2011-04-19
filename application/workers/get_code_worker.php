<?php
final class Get_Code_Worker extends Worker {
	public function get($params = array()) {
		// Make sure script execution doesn't time out.
        // Set maximum execution time in seconds (0 means no limit).
		set_time_limit(0);
		
		$file = count($params) > 0 ? strtolower($params[0]) : '';

		if ($file !== '') {
			// Download counter
			$counter_file = APP_DIR.'download_counter.txt';
			$f = fopen($counter_file, 'r');
			$n = fread($f, filesize($counter_file));
			fclose($f);
			
			$f = fopen($counter_file, 'w');
			fwrite($f, $n+1);
			
			// Popup save file window
			$this->load_function('SYS', 'download/download_function');
			$download_dir = APP_DOWNLOAD_DIR;
			switch ($file) {
				case 'infopotato' :
					download_function($download_dir.'InfoPotato_1.0.0.zip');
					break;
				default:
					echo 'File not found';
			}
		} else {
			$this->load_function('SYS', 'redirect/redirect_function');
			redirect_function(APP_URI_BASE.'download/');
		}

	}
}

// End of file: ./application/workers/get_code_worker.php
