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
	 *
	 * @var string
	 */
	protected $encryption_key = '';
	
	/**
	 * Type of hash operation
	 *
	 * @var string
	 */
	private $_hash_type	= 'sha1';

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
	}
	
	
	/**
	 * Set $encryption_key
	 *
	 * @param  $val string
	 * @return	void
	 */
	protected function set_encryption_key($val) {
		if ( ! is_string($val)) {
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
	 * @return	string
	 */
	public function get_key() {
		if ($this->encryption_key !== '') {
			return $this->encryption_key;
		}
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
	 * @param	string	the key
	 * @return	string
	 */
	public function encode($string) {
		$key = $this->get_key();
		$enc = $this->_xor_encode($string, $key);
		
		if (function_exists('mcrypt_encrypt')){
			$enc = $this->mcrypt_encode($enc, $key);
		}
		return base64_encode($enc);
	}


	/**
	 * Decode
	 *
	 * Decrypts an encoded string
	 *
	 * @param	string
	 * @return	string
	 */
	public function decode($string) {
		$key = $this->get_key();
		
		if (preg_match('/[^a-zA-Z0-9\/\+=]/', $string)) {
			return FALSE;
		}

		$dec = base64_decode($string);

		if (function_exists('mcrypt_encrypt')) {
			if (($dec = $this->mcrypt_decode($dec, $key)) === FALSE) {
				return FALSE;
			}
		}

		return $this->_xor_decode($dec, $key);
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
		while (strlen($rand) < 32) {
			$rand .= mt_rand(0, mt_getrandmax());
		}

		$rand = $this->hash($rand);

		$enc = '';
		for ($i = 0; $i < strlen($string); $i++) {			
			$enc .= substr($rand, ($i % strlen($rand)), 1).(substr($rand, ($i % strlen($rand)), 1) ^ substr($string, $i, 1));
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
		for ($i = 0; $i < strlen($string); $i++) {
			$dec .= (substr($string, $i++, 1) ^ substr($string, $i, 1));
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
		for ($i = 0; $i < strlen($string); $i++) {
			$str .= substr($string, $i, 1) ^ substr($hash, ($i % strlen($hash)), 1);
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
	 * @param	string
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
		$keyhash = $this->hash($key);
		$keylen = strlen($keyhash);
		$str = '';

		for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j) {
			if ($j >= $keylen) {
				$j = 0;
			}

			$str .= chr((ord($data[$i]) + ord($keyhash[$j])) % 256);
		}

		return $str;
	}


	/**
	 * Removes permuted noise from the IV + encrypted data, reversing
	 * _add_cipher_noise()
	 *
	 * @param	type
	 * @return	type
	 */
	private function _remove_cipher_noise($data, $key) {
		$keyhash = $this->hash($key);
		$keylen = strlen($keyhash);
		$str = '';

		for ($i = 0, $j = 0, $len = strlen($data); $i < $len; ++$i, ++$j) {
			if ($j >= $keylen) {
				$j = 0;
			}

			$temp = ord($data[$i]) - ord($keyhash[$j]);

			if ($temp < 0) {
				$temp = $temp + 256;
			}
			
			$str .= chr($temp);
		}

		return $str;
	}


	/**
	 * Set the Mcrypt Cipher
	 *
	 * @param	constant
	 * @return	string
	 */
	public function set_cipher($cipher) {
		$this->_mcrypt_cipher = $cipher;
	}


	/**
	 * Set the Mcrypt Mode
	 *
	 * @param	constant
	 * @return	string
	 */
	public function set_mode($mode) {
		$this->_mcrypt_mode = $mode;
	}


	/**
	 * Get Mcrypt cipher Value
	 *
	 * @return	string
	 */
	private function _get_cipher() {
		if ($this->_mcrypt_cipher === '') {
			$this->_mcrypt_cipher = MCRYPT_RIJNDAEL_256;
		}

		return $this->_mcrypt_cipher;
	}

	
	/**
	 * Get Mcrypt Mode Value
	 *
	 * @return	string
	 */
	private function _get_mode() {
		if ($this->_mcrypt_mode === '') {
			$this->_mcrypt_mode = MCRYPT_MODE_ECB;
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
		$this->_hash_type = ($type !== 'sha1' && $type !== 'md5') ? 'sha1' : $type;
	}


	/**
	 * Hash encode a string
	 *
	 * @param	string
	 * @return	string
	 */	
	public function hash($str) {
		return ($this->_hash_type === 'sha1') ? sha1($str) : md5($str);
	}

}


/* End of file: ./system/libraries/encrypt/encrypt_library.php */