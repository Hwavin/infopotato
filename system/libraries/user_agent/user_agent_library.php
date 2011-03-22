<?php
/**
 * User Agent Class
 *
 * Identifies the platform, browser, robot, or mobile devise of the browsing agent
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	User Agent
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/user_agent.html
 */
class User_Agent_Library {

	public $agent = NULL;
	
	public $is_browser = FALSE;
	public $is_robot = FALSE;
	public $is_mobile = FALSE;

	public $languages = array();
	public $charsets = array();
	
	public $platforms = array();
	public $browsers = array();
	public $mobiles	= array();
	public $robots = array();
	
	public $platform = '';
	public $browser	= '';
	public $version	= '';
	public $mobile = '';
	public $robot = '';
	
	/**
	 * Constructor
	 *
	 * Sets the User Agent and runs the compilation routine
	 *
	 * @access	public
	 * @return	void
	 */		
	public function __construct() {
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$this->agent = trim($_SERVER['HTTP_USER_AGENT']);
		}
		
		if ( ! is_null($this->agent)) {
			$this->platforms = array (
				'windows nt 6.0' => 'Windows Longhorn',
				'windows nt 5.2' => 'Windows 2003',
				'windows nt 5.0' => 'Windows 2000',
				'windows nt 5.1' => 'Windows XP',
				'windows nt 4.0' => 'Windows NT 4.0',
				'winnt4.0' => 'Windows NT 4.0',
				'winnt 4.0' => 'Windows NT',
				'winnt'	=> 'Windows NT',
				'windows 98' => 'Windows 98',
				'win98' => 'Windows 98',
				'windows 95' => 'Windows 95',
				'win95' => 'Windows 95',
				'windows' => 'Unknown Windows OS',
				'os x' => 'Mac OS X',
				'ppc mac' => 'Power PC Mac',
				'freebsd' => 'FreeBSD',
				'ppc' => 'Macintosh',
				'linux'	=> 'Linux',
				'debian' => 'Debian',
				'sunos'	=> 'Sun Solaris',
				'beos' => 'BeOS',
				'apachebench' => 'ApacheBench',
				'aix' => 'AIX',
				'irix' => 'Irix',
				'osf' => 'DEC OSF',
				'hp-ux' => 'HP-UX',
				'netbsd' => 'NetBSD',
				'bsdi' => 'BSDi',
				'openbsd' => 'OpenBSD',
				'gnu' => 'GNU/Linux',
				'unix' => 'Unknown Unix OS'
			);


			// The order of this array should NOT be changed. Many browsers return
			// multiple browser types so we want to identify the sub-type first.
			$this->browsers = array(
				'Opera' => 'Opera',
				'MSIE' => 'Internet Explorer',
				'Internet Explorer'	=> 'Internet Explorer',
				'Shiira' => 'Shiira',
				'Firefox' => 'Firefox',
				'Chimera' => 'Chimera',
				'Phoenix' => 'Phoenix',
				'Firebird' => 'Firebird',
				'Camino' => 'Camino',
				'Netscape' => 'Netscape',
				'OmniWeb' => 'OmniWeb',
				'Safari' => 'Safari',
				'Mozilla' => 'Mozilla',
				'Konqueror'	=> 'Konqueror',
				'icab' => 'iCab',
				'Lynx' => 'Lynx',
				'Links' => 'Links',
				'hotjava' => 'HotJava',
				'amaya'	=> 'Amaya',
				'IBrowse' => 'IBrowse'
			);

			$this->mobiles = array(
				// legacy array, old values commented out
				'mobileexplorer' => 'Mobile Explorer',
				// 'openwave' => 'Open Wave',
				// 'opera mini' => 'Opera Mini',
				// 'operamini' => 'Opera Mini',
				// 'elaine' => 'Palm',
				'palmsource' => 'Palm',
				// 'digital paths' => 'Palm',
				// 'avantgo' => 'Avantgo',
				// 'xiino' => 'Xiino',
				'palmscape' => 'Palmscape',
				// 'nokia' => 'Nokia',
				// 'ericsson' => 'Ericsson',
				// 'blackberry' => 'BlackBerry',
				// 'motorola' => 'Motorola'

				// Phones and Manufacturers
				'motorola' => "Motorola",
				'nokia' => "Nokia",
				'palm' => "Palm",
				'iphone' => "Apple iPhone",
				'ipod' => "Apple iPod Touch",
				'sony' => "Sony Ericsson",
				'ericsson' => "Sony Ericsson",
				'blackberry' => "BlackBerry",
				'cocoon' => "O2 Cocoon",
				'blazer' => "Treo",
				'lg' => "LG",
				'amoi' => "Amoi",
				'xda' => "XDA",
				'mda' => "MDA",
				'vario'	=> "Vario",
				'htc' => "HTC",
				'samsung' => "Samsung",
				'sharp' => "Sharp",
				'sie-' => "Siemens",
				'alcatel' => "Alcatel",
				'benq' => "BenQ",
				'ipaq' => "HP iPaq",
				'mot-' => "Motorola",
				'playstation portable' => "PlayStation Portable",
				'hiptop' => "Danger Hiptop",
				'nec-' => "NEC",
				'panasonic'	=> "Panasonic",
				'philips' => "Philips",
				'sagem' => "Sagem",
				'sanyo'	=> "Sanyo",
				'spv' => "SPV",
				'zte' => "ZTE",
				'sendo' => "Sendo",

				// Operating Systems
				'symbian' => "Symbian",
				'SymbianOS'	=> "SymbianOS", 
				'elaine' => "Palm",
				'palm' => "Palm",
				'series60' => "Symbian S60",
				'windows ce' => "Windows CE",

				// Browsers
				'obigo' => "Obigo",
				'netfront' => "Netfront Browser",
				'openwave' => "Openwave Browser",
				'mobilexplorer' => "Mobile Explorer",
				'operamini' => "Opera Mini",
				'opera mini' => "Opera Mini",

				// Other
				'digital paths' => "Digital Paths",
				'avantgo' => "AvantGo",
				'xiino' => "Xiino",
				'novarra' => "Novarra Transcoder",
				'vodafone' => "Vodafone",
				'docomo' => "NTT DoCoMo",
				'o2' => "O2",

				// Fallback
				'mobile' => "Generic Mobile",
				'wireless' => "Generic Mobile",
				'j2me' => "Generic Mobile",
				'midp' => "Generic Mobile",
				'cldc' => "Generic Mobile",
				'up.link' => "Generic Mobile",
				'up.browser' => "Generic Mobile",
				'smartphone' => "Generic Mobile",
				'cellphone'	=> "Generic Mobile"
			);

			// There are hundreds of bots but these are the most common.
			$this->robots = array(
				'googlebot' => 'Googlebot',
				'msnbot' => 'MSNBot',
				'slurp' => 'Inktomi Slurp',
				'yahoo' => 'Yahoo',
				'askjeeves'	=> 'AskJeeves',
				'fastcrawler' => 'FastCrawler',
				'infoseek' => 'InfoSeek Robot 1.0',
				'lycos' => 'Lycos'
			);

			$this->_compile_data();
		}
	}
	

	/**
	 * Compile the User Agent Data
	 *
	 * @access	private
	 * @return	bool
	 */		
	private function _compile_data() {
		$this->_set_platform();
	
		foreach (array('_set_browser', '_set_robot', '_set_mobile') as $function) {
			if ($this->$function() === TRUE) {
				break;
			}
		}	
	}
	

	/**
	 * Set the Platform
	 *
	 * @access	private
	 * @return	mixed
	 */		
	private function _set_platform() {
		if (is_array($this->platforms) AND count($this->platforms) > 0) {
			foreach ($this->platforms as $key => $val) {
				if (preg_match("|".preg_quote($key)."|i", $this->agent)) {
					$this->platform = $val;
					return TRUE;
				}
			}
		}
		$this->platform = 'Unknown Platform';
	}


	/**
	 * Set the Browser
	 *
	 * @access	private
	 * @return	bool
	 */		
	private function _set_browser() {
		if (is_array($this->browsers) AND count($this->browsers) > 0) {
			foreach ($this->browsers as $key => $val) {		
				if (preg_match("|".preg_quote($key).".*?([0-9\.]+)|i", $this->agent, $match)) {
					$this->is_browser = TRUE;
					$this->version = $match[1];
					$this->browser = $val;
					$this->_set_mobile();
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	
	/**
	 * Set the Robot
	 *
	 * @access	private
	 * @return	bool
	 */		
	private function _set_robot() {
		if (is_array($this->robots) AND count($this->robots) > 0) {		
			foreach ($this->robots as $key => $val) {
				if (preg_match("|".preg_quote($key)."|i", $this->agent)) {
					$this->is_robot = TRUE;
					$this->robot = $val;
					return TRUE;
				}
			}
		}
		return FALSE;
	}


	/**
	 * Set the Mobile Device
	 *
	 * @access	private
	 * @return	bool
	 */		
	private function _set_mobile() {
		if (is_array($this->mobiles) AND count($this->mobiles) > 0) {		
			foreach ($this->mobiles as $key => $val) {
				if (strpos(strtolower($this->agent), $key) !== FALSE) {
					$this->is_mobile = TRUE;
					$this->mobile = $val;
					return TRUE;
				}
			}
		}	
		return FALSE;
	}
	

	/**
	 * Set the accepted languages
	 *
	 * @access	private
	 * @return	void
	 */			
	private function _set_languages() {
		if ((count($this->languages) == 0) AND isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) AND $_SERVER['HTTP_ACCEPT_LANGUAGE'] != '') {
			$languages = preg_replace('/(;q=[0-9\.]+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE'])));
			
			$this->languages = explode(',', $languages);
		}
		
		if (count($this->languages) == 0) {
			$this->languages = array('Undefined');
		}	
	}
	

	/**
	 * Set the accepted character sets
	 *
	 * @access	private
	 * @return	void
	 */			
	private function _set_charsets() {	
		if ((count($this->charsets) == 0) AND isset($_SERVER['HTTP_ACCEPT_CHARSET']) AND $_SERVER['HTTP_ACCEPT_CHARSET'] != '') {
			$charsets = preg_replace('/(;q=.+)/i', '', strtolower(trim($_SERVER['HTTP_ACCEPT_CHARSET'])));
			
			$this->charsets = explode(',', $charsets);
		}
		
		if (count($this->charsets) == 0) {
			$this->charsets = array('Undefined');
		}	
	}


	/**
	 * Is Browser
	 *
	 * @access	public
	 * @return	bool
	 */		
	public function is_browser() {
		return $this->is_browser;
	}


	/**
	 * Is Robot
	 *
	 * @access	public
	 * @return	bool
	 */		
	public function is_robot() {
		return $this->is_robot;
	}


	/**
	 * Is Mobile
	 *
	 * @access	public
	 * @return	bool
	 */		
	public function is_mobile() {
		return $this->is_mobile;
	}	


	/**
	 * Is this a referral from another site?
	 *
	 * @access	public
	 * @return	bool
	 */			
	public function is_referral() {
		return ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? FALSE : TRUE;
	}


	/**
	 * Returns a string containing the full user agent string. Typically it will be something like this:
	 * Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.0.4) Gecko/20060613 Camino/1.0.2
	 *
	 * @access	public
	 * @return	string
	 */			
	public function agent_string() {
		return $this->agent;
	}


	/**
	 * Get Platform
	 *
	 * @access	public
	 * @return	string
	 */			
	public function platform() {
		return $this->platform;
	}


	/**
	 * Get Browser Name
	 *
	 * @access	public
	 * @return	string
	 */			
	public function browser() {
		return $this->browser;
	}


	/**
	 * Get the Browser Version
	 *
	 * @access	public
	 * @return	string
	 */			
	public function version() {
		return $this->version;
	}


	/**
	 * Get The Robot Name
	 *
	 * @access	public
	 * @return	string
	 */				
	public function robot() {
		return $this->robot;
	}

	/**
	 * Get the Mobile Device
	 *
	 * @access	public
	 * @return	string
	 */			
	public function mobile() {
		return $this->mobile;
	}
	

	/**
	 * Get the referrer
	 *
	 * @access	public
	 * @return	bool
	 */			
	public function referrer() {
		return ( ! isset($_SERVER['HTTP_REFERER']) OR $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
	}


	/**
	 * Get the accepted languages
	 *
	 * @access	public
	 * @return	array
	 */			
	public function languages() {
		if (count($this->languages) == 0) {
			$this->_set_languages();
		}
	
		return $this->languages;
	}


	/**
	 * Get the accepted Character Sets
	 *
	 * @access	public
	 * @return	array
	 */			
	public function charsets() {
		if (count($this->charsets) == 0) {
			$this->_set_charsets();
		}
	
		return $this->charsets;
	}
	

	/**
	 * Test for a particular language
	 *
	 * @access	public
	 * @return	bool
	 */			
	public function accept_lang($lang = 'en') {
		return (in_array(strtolower($lang), $this->languages(), TRUE)) ? TRUE : FALSE;
	}
	

	/**
	 * Test for a particular character set
	 *
	 * @access	public
	 * @return	bool
	 */			
	public function accept_charset($charset = 'utf-8') {
		return (in_array(strtolower($charset), $this->charsets(), TRUE)) ? TRUE : FALSE;
	}
		
}


/* End of file: ./system/libraries/user_agent/user_agent_library.php */