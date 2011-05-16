<?php

/**
 * Mobile Detect
 *
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link       http://code.google.com/p/php-mobile-detect/issues/detail?id=11
 * @version    SVN: $Id: Mobile_Detect.php 3 2009-05-21 13:06:28Z vic.stanciu $
 */

class Mobile_Detect_Library {
    
    protected $accept;
    protected $user_agent;
    
    protected $is_mobile     = FALSE;
    protected $is_android    = NULL;
    protected $is_blackberry = NULL;
    protected $is_opera      = NULL;
    protected $is_palm       = NULL;
    protected $is_windows    = NULL;
    protected $is_generic    = NULL;
	protected $is_iphone     = NULL;
    protected $is_ipad       = NULL;

    protected $devices = array(
        'android'       => 'android',
        'blackberry'    => 'blackberry',
        'iphone'        => '(iphone|ipod)',
		'ipad'          => 'ipad',
        'opera'         => '(opera mini|opera mobi)',
        'palm'          => '(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)',
        'windows'       => 'windows ce; (iemobile|ppc|smartphone)',
        'generic'       => '(kindle|mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap|nokia|samsung|SonyEricsson)'
    );


    public function __construct() {
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $this->accept     = $_SERVER['HTTP_ACCEPT'];

        if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            $this->is_mobile = TRUE;
        } elseif (strpos($this->accept, 'text/vnd.wap.wml') > 0 || strpos($accept, 'application/vnd.wap.xhtml+xml') > 0) {
            $this->is_mobile = TRUE;
        } else {
            foreach ($this->devices as $device => $regexp) {
                if ($this->_is_device($device)) {
                    $this->is_mobile = TRUE;
                }
            }
        }
    }


    /**
     * Overloads is_android() | is_blackberry() | is_opera() | is_palm() | is_windows() | is_generic() through _is_device()
     *
     * @param string $name
     * @param array $arguments
     * @return bool
     */
    public function __call($name, $arguments) {
		$device = strtolower(substr($name, 2));
        if ($name == 'is_' . strtolower($device)) {
            return $this->_is_device($device);
        } else {
            trigger_error("Method $name not defined", E_USER_ERROR);
        }
    }


    /**
     * To be used if you are only interested in checking to see if the user is using a mobile device, 
	 * without caring for specific platform
	 * 
	 * Returns true if any type of mobile device detected, including special ones
     * @return bool
     */
    public function is_mobile() {
        return $this->is_mobile;
    }


    protected function _is_device($device) {
        $var    = 'is_' . strtolower($device);
        $return = $this->$var === NULL ? (bool) preg_match("/" . $this->devices[$device] . "/i", $this->user_agent) : $this->$var;

        if (($device != 'generic' && $return == TRUE) || $device == 'ipad') {
            $this->is_generic = FALSE;
        }

        return $return;
    }
}

/* End of file: ./system/libraries/mobile_detect/mobile_detect_library.php */