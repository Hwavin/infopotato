<?php
/**
 * Get Directory File Information
 *
 * Reads the specified directory and builds an array containing the filenames,
 * filesize, dates, and permissions
 *
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	bool	Look only at the top level directory specified?
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */
if ( ! function_exists('dir_file_info')) {
	function dir_file_info($source_dir, $top_level_only = TRUE, $_recursion = FALSE) {
		static $_filedata = array();
		$relative_path = $source_dir;

		if ($fp = @opendir($source_dir)) {
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE) {
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}

			// foreach (scandir($source_dir, 1) as $file) // In addition to being PHP5+, scandir() is simply not as fast
			while (FALSE !== ($file = readdir($fp))) {
				if (@is_dir($source_dir.$file) AND strncmp($file, '.', 1) !== 0 AND $top_level_only === FALSE) {
					dir_file_info($source_dir.$file.DIRECTORY_SEPARATOR, $top_level_only, TRUE);
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

/**
* Get File Info
*
* Given a file and path, returns the name, path, size, date modified
* Second parameter allows you to explicitly declare what information you want returned
* Options are: name, server_path, size, date, readable, writable, executable, fileperms
* Returns FALSE if the file cannot be found.
*
* @access	public
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
					$fileinfo['date'] = filemtime($file);
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

/* End of file: ./system/scripts/dir_file_info/dir_file_info_script.php */
