<?php
/**
 * Read File
 *
 * Opens the file specfied in the path and returns it as a string.
 *
 * @param	string	path to file
 * @return	string
 */	
if ( ! function_exists('read_file')) {
	function read_file($file) {
		if ( ! file_exists($file)) {
			return FALSE;
		}
	
		if (function_exists('file_get_contents')) {
			return file_get_contents($file);		
		}

		if ( ! $fp = @fopen($file, FOPEN_READ)) {
			return FALSE;
		}
		
		flock($fp, LOCK_SH);
	
		$data = '';
		if (filesize($file) > 0) {
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Write File
 *
 * Writes data to the file specified in the path.
 * Creates a new file if non-existent.
 *
 * @param	string	path to file
 * @param	string	file data
 * @return	bool
 */	
if ( ! function_exists('write_file')) {
	function write_file($path, $data, $mode = FOPEN_WRITE_CREATE_DESTRUCTIVE) {
		if ( ! $fp = @fopen($path, $mode)) {
			return FALSE;
		}
		
		flock($fp, LOCK_EX);
		fwrite($fp, $data);
		flock($fp, LOCK_UN);
		fclose($fp);	

		return TRUE;
	}
}
	
// ------------------------------------------------------------------------

/**
 * Delete Files
 *
 * Deletes all files contained in the supplied directory path.
 * Files must be writable or owned by the system in order to be deleted.
 * If the second parameter is set to TRUE, any directories contained
 * within the supplied base directory will be nuked as well.
 *
 * @param	string	path to file
 * @param	bool	whether to delete any directories found in the path
 * @return	bool
 */	
if ( ! function_exists('delete_files')) {
	function delete_files($path, $del_dir = FALSE, $level = 0) {	
		// Trim the trailing slash
		$path = rtrim($path, DIRECTORY_SEPARATOR);
			
		if ( ! $current_dir = @opendir($path))
			return;
	
		while(FALSE !== ($filename = @readdir($current_dir))) {
			if ($filename != "." and $filename != "..") {
				if (is_dir($path.DIRECTORY_SEPARATOR.$filename)) {
					// Ignore empty folders
					if (substr($filename, 0, 1) != '.') {
						delete_files($path.DIRECTORY_SEPARATOR.$filename, $del_dir, $level + 1);
					}				
				} else {
					unlink($path.DIRECTORY_SEPARATOR.$filename);
				}
			}
		}
		@closedir($current_dir);
	
		if ($del_dir == TRUE AND $level > 0) {
			@rmdir($path);
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Get Filenames
 *
 * Reads the specified directory and builds an array containing the filenames.  
 * Any sub-folders contained within the specified path are read as well.
 *
 * @param	string	path to source
 * @param	bool	whether to include the path as part of the filename
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */	
if ( ! function_exists('get_filenames')) {
	function get_filenames($source_dir, $include_path = FALSE, $_recursion = FALSE) {
		static $_filedata = array();
				
		if ($fp = @opendir($source_dir)) {
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE) {
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}
			
			while (FALSE !== ($file = readdir($fp))) {
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0) {
					 get_filenames($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, TRUE);
				} elseif (strncmp($file, '.', 1) !== 0) {
					$_filedata[] = ($include_path == TRUE) ? $source_dir.$file : $file;
				}
			}
			return $_filedata;
		} else {
			return FALSE;
		}
	}
}

// --------------------------------------------------------------------

/**
 * Get Directory File Information
 *
 * Reads the specified directory and builds an array containing the filenames,  
 * filesize, dates, and permissions
 *
 * Any sub-folders contained within the specified path are read as well.
 *
 * @param	string	path to source
 * @param	bool	whether to include the path as part of the filename
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */	
if ( ! function_exists('get_dir_file_info')) {
	function get_dir_file_info($source_dir, $include_path = FALSE, $_recursion = FALSE) {
		static $_filedata = array();
		$relative_path = $source_dir;

		if ($fp = @opendir($source_dir)) {
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE) {
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}

			while (FALSE !== ($file = readdir($fp))) {
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0) {
					 get_dir_file_info($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, TRUE);
				} elseif (strncmp($file, '.', 1) !== 0) {
					$_filedata[$file] = get_file_info($source_dir.$file);
					$_filedata[$file]['relative_path'] = $relative_path;
				}
			}
			return $_filedata;
		} else {
			return FALSE;
		}
	}
}

// --------------------------------------------------------------------

/**
 * Get File Info
 *
 * Given a file and path, returns the name, path, size, date modified
 * Second parameter allows you to explicitly declare what information you want returned
 * Options are: name, server_path, size, date, readable, writable, executable, fileperms
 * Returns FALSE if the file cannot be found.
 *
 * @param	string	path to file
 * @param	mixed	array or comma separated string of information returned
 * @return	array
 */
if ( ! function_exists('get_file_info')) {
	function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date')) {

		if ( ! file_exists($file)) {
			return FALSE;
		}

		if (is_string($returned_values)) {
			$returned_values = explode(',', $returned_values);
		}

		foreach ($returned_values as $key) {
			switch ($key) {
				case 'name':
					$fileinfo['name'] = substr(strrchr($file, DIRECTORY_SEPARATOR), 1);
					break;
				case 'server_path':
					$fileinfo['server_path'] = $file;
					break;
				case 'size':
					$fileinfo['size'] = filesize($file);
					break;
				case 'date':
					$fileinfo['date'] = filectime($file);
					break;
				case 'readable':
					$fileinfo['readable'] = is_readable($file);
					break;
				case 'writable':
					// There are known problems using is_weritable on IIS.  It may not be reliable - consider fileperms()
					$fileinfo['writable'] = is_writable($file);
					break;
				case 'executable':
					$fileinfo['executable'] = is_executable($file);
					break;
				case 'fileperms':
					$fileinfo['fileperms'] = fileperms($file);
					break;
			}
		}

		return $fileinfo;
	}
}

// --------------------------------------------------------------------

/**
 * Get Mime by Extension
 *
 * Translates a file extension into a mime type 
 * Returns FALSE if it can't determine the type, or open the mime config file
 *
 * Note: this is NOT an accurate way of determining file mime types, and is here strictly as a convenience
 * It should NOT be trusted, and should certainly NOT be used for security
 *
 * @param	string	path to file
 * @return	mixed
 */	
if ( ! function_exists('get_mime_by_extension')) {
	function get_mime_by_extension($file) {
		$extension = substr(strrchr($file, '.'), 1);

		$mimes = array(	
			'hqx'	=>	'application/mac-binhex40',
			'cpt'	=>	'application/mac-compactpro',
			'csv'	=>	array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel'),
			'bin'	=>	'application/macbinary',
			'dms'	=>	'application/octet-stream',
			'lha'	=>	'application/octet-stream',
			'lzh'	=>	'application/octet-stream',
			'exe'	=>	'application/octet-stream',
			'class'	=>	'application/octet-stream',
			'psd'	=>	'application/x-photoshop',
			'so'	=>	'application/octet-stream',
			'sea'	=>	'application/octet-stream',
			'dll'	=>	'application/octet-stream',
			'oda'	=>	'application/oda',
			'pdf'	=>	array('application/pdf', 'application/x-download'),
			'ai'	=>	'application/postscript',
			'eps'	=>	'application/postscript',
			'ps'	=>	'application/postscript',
			'smi'	=>	'application/smil',
			'smil'	=>	'application/smil',
			'mif'	=>	'application/vnd.mif',
			'xls'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
			'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint'),
			'wbxml'	=>	'application/wbxml',
			'wmlc'	=>	'application/wmlc',
			'dcr'	=>	'application/x-director',
			'dir'	=>	'application/x-director',
			'dxr'	=>	'application/x-director',
			'dvi'	=>	'application/x-dvi',
			'gtar'	=>	'application/x-gtar',
			'gz'	=>	'application/x-gzip',
			'php'	=>	'application/x-httpd-php',
			'php4'	=>	'application/x-httpd-php',
			'php3'	=>	'application/x-httpd-php',
			'phtml'	=>	'application/x-httpd-php',
			'phps'	=>	'application/x-httpd-php-source',
			'js'	=>	'application/x-javascript',
			'swf'	=>	'application/x-shockwave-flash',
			'sit'	=>	'application/x-stuffit',
			'tar'	=>	'application/x-tar',
			'tgz'	=>	'application/x-tar',
			'xhtml'	=>	'application/xhtml+xml',
			'xht'	=>	'application/xhtml+xml',
			'zip'	=>  array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
			'mid'	=>	'audio/midi',
			'midi'	=>	'audio/midi',
			'mpga'	=>	'audio/mpeg',
			'mp2'	=>	'audio/mpeg',
			'mp3'	=>	array('audio/mpeg', 'audio/mpg'),
			'aif'	=>	'audio/x-aiff',
			'aiff'	=>	'audio/x-aiff',
			'aifc'	=>	'audio/x-aiff',
			'ram'	=>	'audio/x-pn-realaudio',
			'rm'	=>	'audio/x-pn-realaudio',
			'rpm'	=>	'audio/x-pn-realaudio-plugin',
			'ra'	=>	'audio/x-realaudio',
			'rv'	=>	'video/vnd.rn-realvideo',
			'wav'	=>	'audio/x-wav',
			'bmp'	=>	'image/bmp',
			'gif'	=>	'image/gif',
			'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
			'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
			'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
			'png'	=>	array('image/png',  'image/x-png'),
			'tiff'	=>	'image/tiff',
			'tif'	=>	'image/tiff',
			'css'	=>	'text/css',
			'html'	=>	'text/html',
			'htm'	=>	'text/html',
			'shtml'	=>	'text/html',
			'txt'	=>	'text/plain',
			'text'	=>	'text/plain',
			'log'	=>	array('text/plain', 'text/x-log'),
			'rtx'	=>	'text/richtext',
			'rtf'	=>	'text/rtf',
			'xml'	=>	'text/xml',
			'xsl'	=>	'text/xml',
			'mpeg'	=>	'video/mpeg',
			'mpg'	=>	'video/mpeg',
			'mpe'	=>	'video/mpeg',
			'qt'	=>	'video/quicktime',
			'mov'	=>	'video/quicktime',
			'avi'	=>	'video/x-msvideo',
			'movie'	=>	'video/x-sgi-movie',
			'doc'	=>	'application/msword',
			'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'word'	=>	array('application/msword', 'application/octet-stream'),
			'xl'	=>	'application/excel',
			'eml'	=>	'message/rfc822'
		);

		if ( ! is_array($mimes)) {
			if ( ! require_once(APPPATH.'config/mimes.php'))
			{
				return FALSE;
			}
		}

		if (array_key_exists($extension, $mimes)) {
			if (is_array($mimes[$extension]))
			{
				// Multiple mime types, just give the first one
				return current($mimes[$extension]);
			} else {
				return $mimes[$extension];
			}
		} else {
			return FALSE;
		}
	}
}

// --------------------------------------------------------------------

/**
 * Symbolic Permissions
 *
 * Takes a numeric value representing a file's permissions and returns
 * standard symbolic notation representing that value
 *
 * @param	int
 * @return	string
 */	
if ( ! function_exists('symbolic_permissions')) {
	function symbolic_permissions($perms) {	
		if (($perms & 0xC000) == 0xC000) {
			$symbolic = 's'; // Socket
		} elseif (($perms & 0xA000) == 0xA000) {
			$symbolic = 'l'; // Symbolic Link
		} elseif (($perms & 0x8000) == 0x8000) {
			$symbolic = '-'; // Regular
		} elseif (($perms & 0x6000) == 0x6000) {
			$symbolic = 'b'; // Block special
		} elseif (($perms & 0x4000) == 0x4000) {
			$symbolic = 'd'; // Directory
		} elseif (($perms & 0x2000) == 0x2000) {
			$symbolic = 'c'; // Character special
		} elseif (($perms & 0x1000) == 0x1000) {
			$symbolic = 'p'; // FIFO pipe
		} else {
			$symbolic = 'u'; // Unknown
		}

		// Owner
		$symbolic .= (($perms & 0x0100) ? 'r' : '-');
		$symbolic .= (($perms & 0x0080) ? 'w' : '-');
		$symbolic .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

		// Group
		$symbolic .= (($perms & 0x0020) ? 'r' : '-');
		$symbolic .= (($perms & 0x0010) ? 'w' : '-');
		$symbolic .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

		// World
		$symbolic .= (($perms & 0x0004) ? 'r' : '-');
		$symbolic .= (($perms & 0x0002) ? 'w' : '-');
		$symbolic .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

		return $symbolic;		
	}
}

// --------------------------------------------------------------------

/**
 * Octal Permissions
 *
 * Takes a numeric value representing a file's permissions and returns
 * a three character string representing the file's octal permissions
 *
 * @param	int
 * @return	string
 */	
if ( ! function_exists('octal_permissions')) {
	function octal_permissions($perms) {
		return substr(sprintf('%o', $perms), -3);
	}
}

/* End of file: ./system/scripts/file_script.php */