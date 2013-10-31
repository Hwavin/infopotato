<?php
/**
 * Portable PHP password hashing framework
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link   based on http://www.openwall.com/phpass/ 
 * @version Version 0.3 / genuine
 */

namespace InfoPotato\libraries\password_hash;

class Password_Hash_Library {
    private $_itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    
    /**
     * Base-2 logarithm of the iteration count used for password stretching
     * 
     * @var string
     */
    private $iteration_count_log2 = 8;
    
    /**
     * Do we require the hashes to be portable to older systems (less secure)?
     *
     * Set FALSE to use stronger but system-specific hashes, 
     * with a possible fallback to the weaker portable hashes.
     * If set to TRUE, then force the use of weaker portable hashes.
     *
     * If all your servers are running PHP 5.3 and above, 
     * it's recommended to turn off portable mode and let PHPass use bcrypt instead.
     *
     * @var bool
     */
    private $portable_hashes = FALSE;
    
    /**
     * Constructor
     *
     * The constructor can be passed an array of config values
     */
    public function __construct(array $config = NULL) {
        if (count($config) > 0) {
            foreach ($config as $key => $val) {
                // Using isset() requires $this->$key not to be NULL in property definition
                // property_exists() allows empty property
                if (property_exists($this, $key)) {
                    $method = 'initialize_'.$key;
                    
                    if (method_exists($this, $method)) {
                        $this->$method($val);
                    }
                } else {
                    exit("'".$key."' is not an acceptable config argument!");
                }
            }
        }
    }
    
    /**
     * Validate and set $iteration_count_log2
     *
     * @param  $val int
     * @return    void
     */
    private function initialize_iteration_count_log2($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('iteration_count_log2');
        }
        if ($val < 4 || $val > 31) {
            $val = 8;
        }
        $this->iteration_count_log2 = $val;
    }
    
    /**
     * Validate and set $portable_hashes
     *
     * @param  $val bool
     * @return    void
     */
    private function initialize_portable_hashes($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('portable_hashes');
        }
        $this->portable_hashes = $val;
    }
    
    /**
     * Output the error message for invalid argument value
     *
     * @return void
     */
    private function invalid_argument_value($arg) {
        exit("In your config array, the provided argument value of "."'".$arg."'"." is invalid.");
    }
    
    private function get_random_bytes($count) {
        $output = '';
        if (is_readable('/dev/urandom') && ($fh = @fopen('/dev/urandom', 'rb'))) {
            $output = fread($fh, $count);
            fclose($fh);
        }
        
        if (strlen($output) < $count) {
            $output = '';
            
            $random_state = microtime();
            if (function_exists('getmypid')) {
                $random_state .= getmypid();
            }
            
            for ($i = 0; $i < $count; $i += 16) {
                $random_state = md5(microtime() . $random_state);
                $output .= pack('H*', md5($random_state));
            }
            $output = substr($output, 0, $count);
        }
        
        return $output;
    }
    
    private function encode64($input, $count) {
        $output = '';
        $i = 0;
        do {
            $value = ord($input[$i++]);
            $output .= $this->_itoa64[$value & 0x3f];
            if ($i < $count) {
                $value |= ord($input[$i]) << 8;
            }
            $output .= $this->_itoa64[($value >> 6) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            if ($i < $count) {
                $value |= ord($input[$i]) << 16;
            }
            $output .= $this->_itoa64[($value >> 12) & 0x3f];
            if ($i++ >= $count) {
                break;
            }
            $output .= $this->_itoa64[($value >> 18) & 0x3f];
        } while ($i < $count);
        
        return $output;
    }
    
    private function gensalt_private($input) {
        $output = '$P$';
        $output .= $this->_itoa64[min($this->iteration_count_log2 + 5, 30)];
        $output .= $this->encode64($input, 6);
        
        return $output;
    }
    
    private function crypt_private($password, $setting) {
        $output = '*0';
        if (substr($setting, 0, 2) == $output) {
            $output = '*1';
        }
        $id = substr($setting, 0, 3);
        // We use '$P$', phpBB3 uses '$H$' for the same thing
        if ($id != '$P$' && $id != '$H$') {
            return $output;
        }
        $count_log2 = strpos($this->_itoa64, $setting[3]);
        if ($count_log2 < 7 || $count_log2 > 30) {
            return $output;
        }
        $count = 1 << $count_log2;
        
        $salt = substr($setting, 4, 8);
        if (strlen($salt) != 8) {
            return $output;
        }
        // We're kind of forced to use MD5 here since it's the only
        // cryptographic primitive available in all versions of PHP
        // currently in use (We only use PHP5).  To implement our own low-level crypto
        // in PHP would result in much worse performance and
        // consequently in lower iteration counts and hashes that are
        // quicker to crack (by non-PHP code).
        $hash = md5($salt . $password, TRUE);
        do {
            $hash = md5($hash . $password, TRUE);
        } while (--$count);
        
        $output = substr($setting, 0, 12);
        $output .= $this->encode64($hash, 16);
        
        return $output;
    }
    
    private function gensalt_extended($input) {
        $count_log2 = min($this->iteration_count_log2 + 8, 24);
        // This should be odd to not reveal weak DES keys, and the
        // maximum valid value is (2**24 - 1) which is odd anyway.
        $count = (1 << $count_log2) - 1;
        
        $output = '_';
        $output .= $this->_itoa64[$count & 0x3f];
        $output .= $this->_itoa64[($count >> 6) & 0x3f];
        $output .= $this->_itoa64[($count >> 12) & 0x3f];
        $output .= $this->_itoa64[($count >> 18) & 0x3f];
        
        $output .= $this->encode64($input, 3);
        
        return $output;
    }
    
    private function gensalt_blowfish($input) {
        // This one needs to use a different order of characters and a
        // different encoding scheme from the one in encode64() above.
        // We care because the last character in our encoded string will
        // only represent 2 bits.  While two known implementations of
        // bcrypt will happily accept and correct a salt string which
        // has the 4 unused bits set to non-zero, we do not want to take
        // chances and we also do not want to waste an additional byte
        // of entropy.
        $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        
        $output = '$2a$';
        $output .= chr(ord('0') + $this->iteration_count_log2 / 10);
        $output .= chr(ord('0') + $this->iteration_count_log2 % 10);
        $output .= '$';
        
        $i = 0;
        do {
            $c1 = ord($input[$i++]);
            $output .= $itoa64[$c1 >> 2];
            $c1 = ($c1 & 0x03) << 4;
            if ($i >= 16) {
                $output .= $itoa64[$c1];
                break;
            }
            
            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 4;
            $output .= $itoa64[$c1];
            $c1 = ($c2 & 0x0f) << 2;
            
            $c2 = ord($input[$i++]);
            $c1 |= $c2 >> 6;
            $output .= $itoa64[$c1];
            $output .= $itoa64[$c2 & 0x3f];
        } while (1);
        
        return $output;
    }
    
    /**
     * Generate the hashed password
     *
     * @param    string  The password limit should be set to 72 characters to prevent certain DoS attacks. 
     *
     * @return    string  The hash can never be less than 20 characters, so if it is then something went wrong during the encryption process.
     */
    public function hash_password($password) {
        $random = '';
        
        if (CRYPT_BLOWFISH == 1 && ! $this->portable_hashes) {
            $random = $this->get_random_bytes(16);
            $hash = crypt($password, $this->gensalt_blowfish($random));
            if (strlen($hash) == 60) {
                return $hash;
            }
        }
        
        if (CRYPT_EXT_DES == 1 && ! $this->portable_hashes) {
            if (strlen($random) < 3) {
                $random = $this->get_random_bytes(3);
            }
            $hash = crypt($password, $this->gensalt_extended($random));
            if (strlen($hash) == 20) {
                return $hash;
            }
        }
        
        if (strlen($random) < 6) {
            $random = $this->get_random_bytes(6);
        }
        $hash = $this->crypt_private($password, $this->gensalt_private($random));
        if (strlen($hash) == 34) {
            return $hash;
        }
        // Returning '*' on error is safe here, but would _not_ be safe
        // in a crypt(3)-like function used _both_ for generating new
        // hashes and for validating passwords against existing hashes.
        return '*';
    }
    
    /**
     * Check the supplied password against the hash
     *
     * @return    boolean
     */
    public function check_password($password, $stored_hash) {
        $hash = $this->crypt_private($password, $stored_hash);
        if ($hash[0] == '*') {
            $hash = crypt($password, $stored_hash);
        }
        return $hash == $stored_hash;
    }
}

/* End of file: ./system/libraries/password_hash/password_hash_library.php */