<?php
/**
 * FTP Library
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://snoopy.sourceforge.net/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class FTP_Library {
    /**
	 * FTP Server hostname
	 *
	 * @var	string
	 */
	protected $hostname = '';
	
	/**
	 * FTP Username
	 *
	 * @var	string
	 */
	protected $username = '';
	
	/**
	 * FTP Password
	 *
	 * @var	string
	 */
	protected $password = '';
	
	/**
	 * FTP Server port
	 *
	 * @var	int
	 */
	protected $port = 21;
	
	/**
	 * Passive mode flag
	 *
	 * @var	bool
	 */
	protected $passive = TRUE;
	
	/**
	 * Debug flag
	 *
	 * Specifies whether to display error messages.
	 *
	 * @var	bool
	 */
	protected $debug = FALSE; 
	
	/**
	 * Whether to use passive mode. Passive is set automatically by default
	 * 
	 * @var  bollean  
	 */
	protected $conn_id = FALSE;
	
	/**
	 * Error messages
	 *
	 * @var	array
	 */
	private $_error_messages = array(
		'ftp_no_connection' => 'Unable to locate a valid connection ID.  Please make sure you are connected before peforming any file routines.',
		'ftp_unable_to_connect' => 'Unable to connect to your FTP server using the supplied hostname.',
		'ftp_unable_to_login' => 'Unable to login to your FTP server.  Please check your username and password.',
		'ftp_unable_to_makdir' => 'Unable to create the directory you have specified.',
		'ftp_unable_to_changedir' => 'Unable to change directories.',
		'ftp_unable_to_chmod' => 'Unable to set file permissions.  Please check your path.  Note: This feature is only available in PHP 5 or higher.',
		'ftp_unable_to_upload' => 'Unable to upload the specified file.  Please check your path.',
		'ftp_unable_to_download' => 'Unable to download the specified file.  Please check your path.',
		'ftp_no_source_file' => 'Unable to locate the source file.  Please check your path.',
		'ftp_unable_to_rename' => 'Unable to rename the file.',
		'ftp_unable_to_delete' => 'Unable to delete the file.',
		'ftp_unable_to_move' => 'Unable to move the file.  Please make sure the destination directory exists.',
	);
	
	/**
	 * Constructor
	 *
	 * The constructor can be passed an array of config values
	 */
	public function __construct(array $config = NULL) {
		if (count($config) > 0) {
			foreach ($config as $key => $val) {
				// Using isset() requires $this->$key not to be NULL in property definition
				if (isset($this->$key)) {
					$method = 'set_'.$key;

					if (method_exists($this, $method)) {
						$this->$method($val);
					}
				} else {
				    exit("'".$key."' is not an acceptable config argument!");
				}
			}
		}
		
		if (($this->conn_id = @ftp_connect($this->hostname, $this->port)) === FALSE) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_unable_to_connect');
			}
		}
		
		if ( ! $this->_login()) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_unable_to_login');
			}
		}
		
		// Set passive mode if needed
		if ($this->passive === TRUE) {
			ftp_pasv($this->conn_id, TRUE);
		}
	}
	
	
	/**
	 * Set $hostname
	 *
	 * @param  $val string
	 * @return	void
	 */
	protected function set_hostname($val) {
		if ( ! is_string($val)) {
		    $this->_invalid_argument_value('hostname');
		}
		$this->hostname = preg_replace('|.+?://|', '', $val);
	}
	
	/**
	 * Set $username
	 *
	 * @param  $val string
	 * @return	void
	 */
	protected function set_username($val) {
		if ( ! is_string($val)) {
		    $this->_invalid_argument_value('username');
		}
		$this->username = $val;
	}
	
	/**
	 * Set $password
	 *
	 * @param  $val string
	 * @return	void
	 */
	protected function set_password($val) {
		if ( ! is_string($val)) {
		    $this->_invalid_argument_value('password');
		}
		$this->password = $val;
	}
	
	/**
	 * Set $port
	 *
	 * @param  $val int
	 * @return	void
	 */
	protected function set_port($val) {
		if ( ! is_int($val)) {
		    $this->_invalid_argument_value('port');
		}
		$this->port = $val;
	}
	
	/**
	 * Set $passive
	 *
	 * @param  $val bool
	 * @return	void
	 */
	protected function set_passive($val) {
		if ( ! is_bool($val)) {
		    $this->_invalid_argument_value('passive');
		}
		$this->passive = $val;
	}
	
	/**
	 * Set $debug
	 *
	 * @param  $val bool
	 * @return	void
	 */
	protected function set_debug($val) {
		if ( ! is_bool($val)) {
		    $this->_invalid_argument_value('debug');
		}
		$this->debug = $val;
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
	 * FTP Login
	 *
	 * @return	bool
	 */
	private function _login() {
		return @ftp_login($this->conn_id, $this->username, $this->password);
	}


	/**
	 * Validates the connection ID
	 *
	 * @return	bool
	 */
	private function _is_conn() {
		if ( ! is_resource($this->conn_id)) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_no_connection');
			}
			return FALSE;
		}
		return TRUE;
	}


	/**
	 * Change directory
	 *
	 * The second parameter lets us momentarily turn off debugging so that
	 * this function can be used to test for the existence of a folder
	 * without throwing an error.  There's no FTP equivalent to is_dir()
	 * so we do it by trying to change to a particular directory.
	 * Internally, this parameter is only used by the "mirror" function below.
	 *
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	public function changedir($path = '', $supress_debug = FALSE) {
		if ($path === '' || ! $this->_is_conn()) {
			return FALSE;
		}

		$result = @ftp_chdir($this->conn_id, $path);

		if ($result === FALSE) {
			if ($this->debug === TRUE && $supress_debug === FALSE) {
				$this->_error('ftp_unable_to_changedir');
			}
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Create a directory
	 *
	 * @param	string
	 * @return	bool
	 */
	public function mkdir($path = '', $permissions = NULL) {
		if ($path === '' || ! $this->_is_conn()) {
			return FALSE;
		}

		$result = @ftp_mkdir($this->conn_id, $path);

		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_unable_to_makdir');
			}
			return FALSE;
		}

		// Set file permissions if needed
		if ( ! is_null($permissions)) {
			$this->chmod($path, (int)$permissions);
		}

		return TRUE;
	}


	/**
	 * Upload a file to the server
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function upload($locpath, $rempath, $mode = 'auto', $permissions = NULL) {
		if ( ! $this->_is_conn()) {
			return FALSE;
		}

		if ( ! file_exists($locpath)) {
			$this->_error('ftp_no_source_file');
			return FALSE;
		}

		// Set the mode if not specified
		if ($mode == 'auto') {
			// Get the file extension so we can set the upload type
			$ext = $this->_getext($locpath);
			$mode = $this->_settype($ext);
		}

		$mode = ($mode === 'ascii') ? FTP_ASCII : FTP_BINARY;

		$result = @ftp_put($this->conn_id, $rempath, $locpath, $mode);

		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_unable_to_upload');
			}
			return FALSE;
		}

		// Set file permissions if needed
		if ( ! is_null($permissions)) {
			$this->chmod($rempath, (int)$permissions);
		}

		return TRUE;
	}


	/**
	 * Download a file from a remote server to the local server
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function download($rempath, $locpath, $mode = 'auto') {
		if ( ! $this->_is_conn()) {
			return FALSE;
		}

		// Set the mode if not specified
		if ($mode == 'auto') {
			// Get the file extension so we can set the upload type
			$ext = $this->_getext($rempath);
			$mode = $this->_settype($ext);
		}

		$mode = ($mode === 'ascii') ? FTP_ASCII : FTP_BINARY;

		$result = @ftp_get($this->conn_id, $locpath, $rempath, $mode);

		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_unable_to_download');
			}
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Rename (or move) a file
	 *
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	public function rename($old_file, $new_file, $move = FALSE) {
		if ( ! $this->_is_conn()) {
			return FALSE;
		}

		$result = @ftp_rename($this->conn_id, $old_file, $new_file);

		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$msg = ($move === FALSE) ? 'ftp_unable_to_rename' : 'ftp_unable_to_move';

				$this->_error($msg);
			}
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Move a file
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	public function move($old_file, $new_file) {
		return $this->rename($old_file, $new_file, TRUE);
	}


	/**
	 * Rename (or move) a file
	 *
	 * @param	string
	 * @return	bool
	 */
	public function delete_file($filepath) {
		if ( ! $this->_is_conn()) {
			return FALSE;
		}

		$result = @ftp_delete($this->conn_id, $filepath);

		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_unable_to_delete');
			}
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Delete a folder and recursively delete everything (including sub-folders)
	 * containted within it.
	 *
	 * @param	string
	 * @return	bool
	 */
	public function delete_dir($filepath) {
		if ( ! $this->_is_conn()) {
			return FALSE;
		}

		// Add a trailing slash to the file path if needed
		$filepath = preg_replace("/(.+?)\/*$/", "\\1/",  $filepath);

		$list = $this->list_files($filepath);

		if ($list !== FALSE && count($list) > 0) {
			foreach ($list as $item) {
				// If we can't delete the item it's probaly a folder so
				// we'll recursively call delete_dir()
				if ( ! @ftp_delete($this->conn_id, $item)) {
					$this->delete_dir($item);
				}
			}
		}

		$result = @ftp_rmdir($this->conn_id, $filepath);

		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_unable_to_delete');
			}
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * Set file permissions
	 *
	 * @param	string	the file path
	 * @param	string	the permissions
	 * @return	bool
	 */
	public function chmod($path, $perm) {
		if ( ! $this->_is_conn()) {
			return FALSE;
		}

		// Permissions can only be set when running PHP 5
		if ( ! function_exists('ftp_chmod')) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_unable_to_chmod');
			}
			return FALSE;
		}

		$result = @ftp_chmod($this->conn_id, $perm, $path);

		if ($result === FALSE) {
			if ($this->debug === TRUE) {
				$this->_error('ftp_unable_to_chmod');
			}
			return FALSE;
		}

		return TRUE;
	}


	/**
	 * FTP List files in the specified directory
	 *
	 * @return	array
	 */
	public function list_files($path = '.') {
		if ( ! $this->_is_conn()) {
			return FALSE;
		}

		return ftp_nlist($this->conn_id, $path);
	}


	/**
	 * Read a directory and recreate it remotely
	 *
	 * This function recursively reads a local folder and everything it contains (including
	 * sub-folders) and creates a mirror via FTP based on it.  Whatever the directory structure
	 * of the original file path will be recreated on the server.
	 *
	 * @param	string	path to source with trailing slash
	 * @param	string	path to destination - include the base folder with trailing slash
	 * @return	bool
	 */
	public function mirror($locpath, $rempath) {
		if ( ! $this->_is_conn()) {
			return FALSE;
		}

		// Open the local file path
		if ($fp = @opendir($locpath)) {
			// Attempt to open the remote file path.
			if ( ! $this->changedir($rempath, TRUE)) {
				// If it doesn't exist we'll attempt to create the direcotory
				if ( ! $this->mkdir($rempath) || ! $this->changedir($rempath)) {
					return FALSE;
				}
			}

			// Recursively read the local directory
			while (($file = readdir($fp)) !== FALSE) {
				if (@is_dir($locpath.$file) && substr($file, 0, 1) != '.') {
					$this->mirror($locpath.$file."/", $rempath.$file."/");
				} elseif (substr($file, 0, 1) != ".") {
					// Get the file extension so we can se the upload type
					$ext = $this->_getext($file);
					$mode = $this->_settype($ext);

					$this->upload($locpath.$file, $rempath.$file, $mode);
				}
			}
			return TRUE;
		}

		return FALSE;
	}


	/**
	 * Extract the file extension
	 *
	 * @param	string
	 * @return	string
	 */
	private function _getext($filename) {
		if (strpos($filename, '.') === FALSE) {
			return 'txt';
		}

		$x = explode('.', $filename);
		return end($x);
	}


	/**
	 * Set the upload type
	 *
	 * @param	string
	 * @return	string
	 */
	private function _settype($ext) {
		$text_types = array(
			'txt',
			'text',
			'php',
			'phps',
			'php4',
			'js',
			'css',
			'htm',
			'html',
			'phtml',
			'shtml',
			'log',
			'xml'
		);

		return (in_array($ext, $text_types)) ? 'ascii' : 'binary';
	}


	/**
	 * Close the connection
	 *
	 * @param	string	path to source
	 * @param	string	path to destination
	 * @return	bool
	 */
	public function close() {
		if ( ! $this->_is_conn()) {
			return FALSE;
		}

		@ftp_close($this->conn_id);
	}


	/**
	 * Display error message
	 *
	 * @param	string
	 * @return	bool
	 */
	private function _error($msg) {
		echo isset($this->_error_messages[$msg]) ? $this->_error_messages[$msg] : $msg;
	}

}

/* End of file: ./system/libraries/ftp/ftp_library.php */