<?php
/**
 * Portable PHP password hashing framework
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link   based on http://www.openwall.com/phpass/ 
 * @version Version 0.3 / genuine
 */
class Password_Hash_Library {
	private $_itoa64;
	
	/**
	 * Base-2 logarithm of the iteration count used for password stretching
	 * 
	 * @var string
	 */
	private $_iteration_count_log2;
	
	/**
	 * Do we require the hashes to be portable to older systems (less secure)?
	 * 
	 * @var boolean
	 */
	private $_portable_hashes;
	
	private $_random_state;
	
	/**
	 * Constructor
	 *
	 * @param integer $config['iteration_count_log2'] -- specifies the "base-2 logarithm of the iteration count used for password stretching"
	 * @param boolean $config['portable_hashes'] -- specifies the use of portable hashes
	 * 
	 * If all your servers are running PHP 5.3 and above, 
	 * it's recommended to turn off portable mode and let PHPass use bcrypt instead.
	 */
	public function __construct(array $config = NULL) { 
		if (count($config) > 0) {
			$this->_itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

			if ($config['iteration_count_log2'] < 4 || $config['iteration_count_log2'] > 31) {
				$config['iteration_count_log2'] = 8;
			}
			$this->_iteration_count_log2 = $config['iteration_count_log2'];

			// Set $config['portable_hashes'] = FALSE to use stronger but system-specific hashes, 
			// with a possible fallback to the weaker portable hashes.
			// If set to TRUE, then force the use of weaker portable hashes.
			$this->_portable_hashes = $config['portable_hashes'];

			$this->_random_state = microtime();
			if (function_exists('getmypid')) {
				$this->_random_state .= getmypid();
			}
		}
	}

	protected function get_random_bytes($count) {
		$output = '';
		if (is_readable('/dev/urandom') && ($fh = @fopen('/dev/urandom', 'rb'))) {
			$output = fread($fh, $count);
			fclose($fh);
		}

		if (strlen($output) < $count) {
			$output = '';
			for ($i = 0; $i < $count; $i += 16) {
				$this->_random_state =
				    md5(microtime() . $this->_random_state);
				$output .=
				    pack('H*', md5($this->_random_state));
			}
			$output = substr($output, 0, $count);
		}

		return $output;
	}

	protected function encode64($input, $count) {
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

	protected function gensalt_private($input) {
		$output = '$P$';
		$output .= $this->_itoa64[min($this->_iteration_count_log2 + 5, 30)];
		$output .= $this->encode64($input, 6);

		return $output;
	}

	protected function crypt_private($password, $setting) {
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

	protected function gensalt_extended($input) {
		$count_log2 = min($this->_iteration_count_log2 + 8, 24);
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

	protected function gensalt_blowfish($input) {
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
		$output .= chr(ord('0') + $this->_iteration_count_log2 / 10);
		$output .= chr(ord('0') + $this->_iteration_count_log2 % 10);
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
	 * @param	string  The password limit should be set to 72 characters to prevent certain DoS attacks. 
	 *
	 * @return	string  The hash can never be less than 20 characters, so if it is then something went wrong during the encryption process.
	 */
	public function hash_password($password) {
		$random = '';

		if (CRYPT_BLOWFISH == 1 && ! $this->_portable_hashes) {
			$random = $this->get_random_bytes(16);
			$hash = crypt($password, $this->gensalt_blowfish($random));
			if (strlen($hash) == 60) {
				return $hash;
			}
		}

		if (CRYPT_EXT_DES == 1 && ! $this->_portable_hashes) {
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
	 * @return	boolean
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