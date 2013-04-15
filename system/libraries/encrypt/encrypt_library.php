<?php
/**
 * Encryption Library
 * Provides two-way keyed encoding using XOR Hashing and Mcrypt
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Encrypt_Library {
    /**
	 * Reference to the user's encryption key
	 * To take maximum advantage of the encryption algorithm, 
	 * your key should be 32 characters in length (128 bits). 
	 *
	 * @var string
	 */
	protected $encryption_key = 'your key should be 32 characters';
	
	/**
	 * Type of hash operation
	 *
	 * @var string
	 */
	private $_hash_type	= 'sha1';

	/**
	 * Flag for the existance of mcrypt
	 *
	 * @var bool
	 */
	private $_mcrypt_exists	= FALSE;
	
	/**
	 * Current cipher to be used with mcrypt
	 *
	 * @var string
	 */
	private $_mcrypt_cipher;
	
	/**
	 * Method for encrypting/decrypting data
	 *
	 * @var int
	 */
	private $_mcrypt_mode;


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
					$method = 'set_'.$key;

					if (method_exists($this, $method)) {
						$this->$method($val);
					}
				} else {
				    exit("'".$key."' is not an acceptable config argument!");
				}
			}
		}
		
		// Flag for the existance of mcrypt extension
		$this->_mcrypt_exists = function_exists('mcrypt_encrypt');
	}
	
	
	/**
	 * Set $encryption_key
	 *
	 * @param  $val string
	 * @return	void
	 */
	protected function set_encryption_key($val) {
		if ( ! is_string($val) || empty($val)) {
		    $this->_invalid_argument_value('encryption_key');
		}
		$this->encryption_key = $val;
	}
	
	/**
     * Output the error message for invalid argument value
	 *
	 * @return void
     */
	private function _invalid_argument_value($arg) {
	    exit("In your config array, the provided argument value of "."'".$arg."'"." is invalid.");
	}

	/**
	 * Fetch the encryption key
	 *
	 * Returns it as MD5 in order to have an exact-length 128 bit key.
	 * Mcrypt is sensitive to keys that are not in the correct length
	 *
	 * @param	string
	 * @return	string
	 */
	public function get_key($key = '') {
		if ($key === '') {
			// encryption_key won't be empty
			return $this->encryption_key;
		}
        // md5() returns the hash as a 32-character hexadecimal number
		return md5($key);
	}
	
	
	/**
	 * Encode
	 *
	 * Encodes the message string using bitwise XOR encoding.
	 * The key is combined with a random hash, and then it
	 * too gets converted using XOR. The whole thing is then run
	 * through mcrypt (if supported) using the randomized key.
	 * The end result is a double-encrypted message string
	 * that is randomized with each call to this function,
	 * even if the supplied message and key are the same.
	 *
	 * @param	string	the string to encode
	 * @param	string	the key  (optional)
	 * @return	string
	 */
	public function encode($string, $key = '') {
		$method = ($this->_mcrypt_exists === TRUE) ? 'mcrypt_encode' : '_xor_encode';
		return base64_encode($this->$method($string, $this->get_key($key)));
	}


    /**
	 * Decode
	 *
	 * Reverses the above process
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	public function decode($string, $key = '') {
		if (preg_match('/[^a-zA-Z0-9\/\+=]/', $string) || base64_encode(base64_decode($string)) !== $string) {
			return FALSE;
		}

		$method = ($this->_mcrypt_exists === TRUE) ? 'mcrypt_decode' : '_xor_decode';
		return $this->$method(base64_decode($string), $this->get_key($key));
	}
	
	
	/**
	 * XOR Encode
	 *
	 * Takes a plain-text string and key as input and generates an
	 * encoded bit-string using XOR
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	private function _xor_encode($string, $key) {
		$rand = '';
		do {
			$rand .= mt_rand();
		} while (strlen($rand) < 32);

		$rand = $this->hash($rand);

		$enc = '';
		for ($i = 0, $ls = strlen($string), $lr = strlen($rand); $i < $ls; $i++) {
			$enc .= $rand[($i % $lr)].($rand[($i % $lr)] ^ $string[$i]);
		}

		return $this->_xor_merge($enc, $key);
	}

	/**
	 * XOR Decode
	 *
	 * Takes an encoded string and key as input and generates the
	 * plain-text original message
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	private function _xor_decode($string, $key) {
		$string = $this->_xor_merge($string, $key);

		$dec = '';
		for ($i = 0, $l = strlen($string); $i < $l; $i++) {
			$dec .= ($string[$i++] ^ $string[$i]);
		}

		return $dec;
	}
	
	/**
	 * XOR key + string Combiner
	 *
	 * Takes a string and key as input and computes the difference using XOR
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	private function _xor_merge($string, $key) {
		$hash = $this->hash($key);
		$str = '';
		for ($i = 0, $ls = strlen($string), $lh = strlen($hash); $i < $ls; $i++) {
			$str .= $string[$i] ^ $hash[($i % $lh)];
		}

		return $str;
	}

    /**
	 * Encrypt using Mcrypt
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	public function mcrypt_encode($data, $key) {
		$init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());
		$init_vect = mcrypt_create_iv($init_size, MCRYPT_RAND);
		return $this->_add_cipher_noise($init_vect.mcrypt_encrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), $key);
	}

	/**
	 * Decrypt using Mcrypt
	 *
	 * @param	string
	 * @param	string (optional)
	 * @return	string
	 */
	public function mcrypt_decode($data, $key) {
		$data = $this->_remove_cipher_noise($data, $key);
		$init_size = mcrypt_get_iv_size($this->_get_cipher(), $this->_get_mode());

		if ($init_size > strlen($data)) {
			return FALSE;
		}

		$init_vect = substr($data, 0, $init_size);
		$data = substr($data, $init_size);
		return rtrim(mcrypt_decrypt($this->_get_cipher(), $key, $data, $this->_get_mode(), $init_vect), "\0");
	}

	/**
	 * Adds permuted noise to the IV + encrypted data to protect
	 * against Man-in-the-middle attacks on CBC mode ciphers
	 * http://www.ciphersbyritter.com/GLOSSARY.HTM#IV
	 *
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	private function _add_cipher_noise($data, $key) {
		$key = $this->hash($key);
		$str = '';

		for ($i = 0, $j = 0, $ld = strlen($data), $lk = strlen($key); $i < $ld; ++$i, ++$j) {
			if ($j >= $lk) {
				$j = 0;
			}

			$str .= chr((ord($data[$i]) + ord($key[$j])) % 256);
		}

		return $str;
	}

    /**
	 * Removes permuted noise from the IV + encrypted data, reversing
	 * _add_cipher_noise()
	 *
	 * Function description
	 *
	 * @param	string	$data
	 * @param	string	$key
	 * @return	string
	 */
	private function _remove_cipher_noise($data, $key) {
		$key = $this->hash($key);
		$str = '';

		for ($i = 0, $j = 0, $ld = strlen($data), $lk = strlen($key); $i < $ld; ++$i, ++$j) {
			if ($j >= $lk) {
				$j = 0;
			}

			$temp = ord($data[$i]) - ord($key[$j]);

			if ($temp < 0) {
				$temp += 256;
			}

			$str .= chr($temp);
		}

		return $str;
	}

	/**
	 * Set the Mcrypt Cipher
	 *
	 * @param	int
	 * @return	Encrypt_Library
	 */
	public function set_cipher($cipher) {
		$this->_mcrypt_cipher = $cipher;
		return $this;
	}

	/**
	 * Set the Mcrypt Mode
	 *
	 * @param	int
	 * @return	Encrypt_Library
	 */
	public function set_mode($mode) {
		$this->_mcrypt_mode = $mode;
		return $this;
	}

	/**
	 * Get Mcrypt cipher Value
	 *
	 * @return	string
	 */
	private function _get_cipher() {
		if ($this->_mcrypt_cipher === NULL) {
			return $this->_mcrypt_cipher = MCRYPT_RIJNDAEL_256;
		}

		return $this->_mcrypt_cipher;
	}

	/**
	 * Get Mcrypt Mode Value
	 *
	 * @return	int
	 */
	private function _get_mode() {
		if ($this->_mcrypt_mode === NULL) {
			return $this->_mcrypt_mode = MCRYPT_MODE_CBC;
		}

		return $this->_mcrypt_mode;
	}

	/**
	 * Set the Hash type
	 *
	 * @param	string
	 * @return	string
	 */
	public function set_hash($type = 'sha1') {
		// hash_algos() returns a numerically indexed array containing the list of supported hashing algorithms
		$this->_hash_type = in_array($type, hash_algos()) ? $type : 'sha1';
	}


	/**
	 * Hash encode a string
	 *
	 * @param	string
	 * @return	string
	 */	
	public function hash($str) {
		return hash($this->_hash_type, $str);
	}

}


/* End of file: ./system/libraries/encrypt/encrypt_library.php */