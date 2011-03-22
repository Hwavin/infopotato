<?php
/**
 * Filename Security
 *
 * @access	public
 * @param	string
 * @return	string
 */
function sanitize_filename($str, $relative_path = FALSE) {
	$bad = array(
					"../",
					"<!--",
					"-->",
					"<",
					">",
					"'",
					'"',
					'&',
					'$',
					'#',
					'{',
					'}',
					'[',
					']',
					'=',
					';',
					'?',
					"%20",
					"%22",
					"%3c",		// <
					"%253c",	// <
					"%3e",		// >
					"%0e",		// >
					"%28",		// (
					"%29",		// )
					"%2528",	// (
					"%26",		// &
					"%24",		// $
					"%3f",		// ?
					"%3b",		// ;
					"%3d"		// =
				);

	if ( ! $relative_path) {
		$bad[] = './';
		$bad[] = '/';
	}

	return stripslashes(str_replace($bad, '', $str));
}

// End of file: ./system/scripts/sanitize_filename/sanitize_filename_script.php
