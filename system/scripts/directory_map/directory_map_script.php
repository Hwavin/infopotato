<?php
/**
 * Create a Directory Map
 *
 * Reads the specified directory and builds an array
 * representation of it.  Sub-folders contained with the
 * directory will be mapped as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	int		depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
 * @return	array
 */
if ( ! function_exists('directory_map')) {
	function directory_map($target_dir, $directory_depth = 0, $hidden = FALSE) {
		if ($fp = @opendir($target_dir)) {
			$filedata = array();
			$new_depth = $directory_depth - 1;
			$target_dir	= rtrim($target_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

			while (FALSE !== ($file = readdir($fp))) {
				// Remove '.', '..', and hidden files [optional]
				if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.')) {
					continue;
				}

				if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($target_dir.$file)) {
					$filedata[$file] = directory_map($target_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
				} else {
					$filedata[] = $file;
				}
			}

			closedir($fp);
			return $filedata;
		}

		return FALSE;
	}
}

/* End of file: ./system/scripts/directory_map/directory_map_script.php */
