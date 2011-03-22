<?php
class Download_Worker extends Worker {	
	public function process() {
		$layout_data = array(
			'page_title' => 'Download',
			'content' => $this->load_template('pages/download'),
		);
		
		$response_data = array(
			'content' => $this->load_template('layouts/default_layout', $layout_data),
			'type' => 'text/html',
		);
		$this->response($response_data);
	}
	
	
	
	public function index2($params = array()) {
		// Make sure script execution doesn't time out.
        // Set maximum execution time in seconds (0 means no limit).
		set_time_limit(0);

		// Load force download script
		load_script('download/download_script');
		
		$download_dir = APP_DOWNLOAD_DIR;
		
		$file = count($params) > 0 ? strtolower($params[0]) : '';

		switch ($file) {
            case 'mas' :
                download($download_dir.'Making-America-Smarter.pdf', 'Making-America-Smarter.pdf', 'application/pdf');
				break;

            case 'fate' :
                download($download_dir.'From-Aptitude-to-Effort-A-New-Foundation-for-Our-Schools.pdf', 'From-Aptitude-to-Effort-A-New-Foundation-for-Our-Schools.pdf', 'application/pdf');
				break;
				
            case 'lofser' :
				download($download_dir.'Learning-Organizations-for-Sustainable-Education-Reform.pdf', 'Learning-Organizations-for-Sustainable-Education-Reform.pdf', 'application/pdf');
				break;
			
			case 'lfl' :
				download($download_dir.'Leadership-for-Learning-A-Theory-of-Action-for-Urban-School-Districts.pdf', 'Leadership-for-Learning-A-Theory-of-Action-for-Urban-School-Districts.pdf', 'application/pdf');
				break;
			
			case 'sbra' :
				download($download_dir.'Standards-Based-Reform-and-Accountability-Getting-Back-on-Course.pdf', 'Standards-Based-Reform-and-Accountability-Getting-Back-on-Course.pdf', 'application/pdf');
				break;
			
			case 'msshssadsb' :
				download($download_dir.'Mathematics-and-Science-Specialty-High-Schools-Serving-a-Diverse-Student-Body-What-Different.pdf', 'Mathematics-and-Science-Specialty-High-Schools-Serving-a-Diverse-Student-Body-What-Different.pdf', 'application/pdf');
				break;

			case 'ell' :
				download($download_dir.'Bibliography-ELLs-DL-Design-Principles.pdf', 'Bibliography-ELLs-DL-Design-Principles.pdf', 'application/pdf');
				break;
				
			case 'mcs' :
				download($download_dir.'Metro-Case-Study.pdf', 'Metro-Case-Study.pdf', 'application/pdf');
				break;
			
			case 'ats' :
				download($download_dir.'AT-Sourcebook.pdf', 'AT-Sourcebook.pdf', 'application/pdf');
				break;
				
			case 'pbhp' :
				download($download_dir.'Practice-based_Hiring_Process.pdf', 'Practice-based_Hiring_Process.pdf', 'application/pdf');
				break;
		}
	}
}

// End of file: ./application/presenters/download_presenter.php
