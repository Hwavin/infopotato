<?php
 /**
 * User Agent Library
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class User_Agent_Library {

    /**
	 * Contents of the User-Agent: header from the current request, if there is one.
	 * A typical example is: Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586).
	 * 
	 * @var string
	 */
	public $agent = NULL;
	
	/**
	 * If the current request id from a browser
	 * 
	 * @var boolean
	 */
	public $is_browser = FALSE;
	
	/**
	 * If the current request id from a robot
	 * 
	 * @var boolean
	 */
	public $is_robot = FALSE;
	
	/**
	 * If the current request id from a mobile device
	 * 
	 * @var boolean
	 */
	public $is_mobile = FALSE;

	/**
	 * The accepted languages
	 * 
	 * @var array
	 */
	public $languages = array();
	
	/**
	 * The accepted Character Sets
	 * 
	 * @var array
	 */
	public $charsets = array();
	
	/**
	 * User agent platform information
	 * 
	 * @var array
	 */
	public $platforms = array();
	
	/**
	 * User agent browsers definitions
	 * 
	 * @var array
	 */
	public $browsers = array();
	
	/**
	 * User agent mobile definitions
	 * 
	 * @var array
	 */
	public $mobiles	= array();
	
	/**
	 * User agent robots definitions
	 * 
	 * @var integer 
	 */
	public $robots = array();
	
	/**
	 * Platform info
	 * 
	 * @var string
	 */
	public $platform = '';
	
	/**
	 * Browser name
	 * 
	 * @var string
	 */
	public $browser	= '';
	
	/**
	 * Browser version
	 * 
	 * @var string
	 */
	public $version	= '';
	
	/**
	 * Mobile name
	 * 
	 * @var string 
	 */
	public $mobile = '';
	
	/**
	 * Robot name
	 * 
	 * @var string
	 */
	public $robot = '';
	
	
	/**
	 * Constructor
	 *
	 * Sets the User Agent and runs the compilation routine
	 *
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
	 * @return	bool
	 */		
	private function _compile_data() {
		// Set platform info
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
	 * @return	mixed
	 */		
	private function _set_platform() {
		foreach ($this->platforms as $key => $val) {
			if (preg_match("|".preg_quote($key)."|i", $this->agent)) {
				$this->platform = $val;
				return TRUE;
			}
		}
		$this->platform = 'Unknown Platform';
	}
	
	/**
	 * Set the Browser
	 *
	 * @return	bool
	 */		
	private function _set_browser() {
		foreach ($this->browsers as $key => $val) {		
			if (preg_match("|".preg_quote($key).".*?([0-9\.]+)|i", $this->agent, $match)) {
				$this->is_browser = TRUE;
				$this->version = $match[1];
				$this->browser = $val;
				$this->_set_mobile();
				return TRUE;
			}
		}
		return FALSE;
	}

	
	/**
	 * Set the Robot
	 *
	 * @return	bool
	 */		
	private function _set_robot() {
		foreach ($this->robots as $key => $val) {
			if (preg_match("|".preg_quote($key)."|i", $this->agent)) {
				$this->is_robot = TRUE;
				$this->robot = $val;
				return TRUE;
			}
		}
		return FALSE;
	}


	/**
	 * Set the Mobile Device
	 *
	 * @return	bool
	 */		
	private function _set_mobile() {
		foreach ($this->mobiles as $key => $val) {
			if (strpos(strtolower($this->agent), $key) !== FALSE) {
				$this->is_mobile = TRUE;
				$this->mobile = $val;
				return TRUE;
			}
		}	
		return FALSE;
	}
	

	/**
	 * Set the accepted languages
	 *
	 * @return	void
	 */			
	private function _set_languages() {
		if ((count($this->languages) == 0) && isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && $_SERVER['HTTP_ACCEPT_LANGUAGE'] != '') {
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
	 * @return	void
	 */			
	private function _set_charsets() {	
		if ((count($this->charsets) == 0) && isset($_SERVER['HTTP_ACCEPT_CHARSET']) && $_SERVER['HTTP_ACCEPT_CHARSET'] != '') {
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
	 * @return	bool
	 */		
	public function is_browser() {
		return $this->is_browser;
	}


	/**
	 * Is Robot
	 *
	 * @return	bool
	 */		
	public function is_robot() {
		return $this->is_robot;
	}


	/**
	 * Is Mobile
	 *
	 * @return	bool
	 */		
	public function is_mobile() {
		return $this->is_mobile;
	}	


	/**
	 * Is this a referral from another site?
	 *
	 * @return	bool
	 */			
	public function is_referral() {
		return ( ! isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == '') ? FALSE : TRUE;
	}


	/**
	 * Returns a string containing the full user agent string. Typically it will be something like this:
	 * Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.0.4) Gecko/20060613 Camino/1.0.2
	 *
	 * @return	string
	 */			
	public function agent_string() {
		return $this->agent;
	}


	/**
	 * Get Platform
	 *
	 * @return	string
	 */			
	public function platform() {
		return $this->platform;
	}


	/**
	 * Get Browser Name
	 *
	 * @return	string
	 */			
	public function browser() {
		return $this->browser;
	}


	/**
	 * Get the Browser Version
	 *
	 * @return	string
	 */			
	public function version() {
		return $this->version;
	}


	/**
	 * Get The Robot Name
	 *
	 * @return	string
	 */				
	public function robot() {
		return $this->robot;
	}

	/**
	 * Get the Mobile Device
	 *
	 * @return	string
	 */			
	public function mobile() {
		return $this->mobile;
	}
	

	/**
	 * Get the referrer
	 *
	 * @return	bool
	 */			
	public function referrer() {
		return ( ! isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] == '') ? '' : trim($_SERVER['HTTP_REFERER']);
	}


	/**
	 * Get the accepted languages
	 *
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
	 * @return	bool
	 */			
	public function accept_lang($lang = 'en') {
		return (in_array(strtolower($lang), $this->languages(), TRUE)) ? TRUE : FALSE;
	}
	

	/**
	 * Test for a particular character set
	 *
	 * @return	bool
	 */			
	public function accept_charset($charset = 'utf-8') {
		return (in_array(strtolower($charset), $this->charsets(), TRUE)) ? TRUE : FALSE;
	}
		
}


/* End of file: ./system/libraries/user_agent/user_agent_library.php */