<?php
/**
 * Force File Download
 *
 * If you want to do something on download abort/finish,
 * register_shutdown_function('function_name');
 * 
 * @param string $file (required) the path of the file to be downloaded
 * @param string $name (optional) file name shown in the save window
 * @param string $mime_type (optional) MIME type of the target file
 * @return none
 * @link based on http://w-shadow.com/blog/2007/08/12/how-to-force-file-download-with-php/
 */
function download($file, $mime_type = '') { 
	// Tells whether a file exists and is readable
	if ( ! is_readable($file)) {
		die('File not found or inaccessible!');
	}

	// Grab the file extension if provided
	//$dot_ext = strrchr($file, '.');
	//$file_extension = $dot_ext ? strtolower(substr($dot_ext, 1)) : '';

	// The fastest method to get file extension?
	// http://cowburn.info/2008/01/13/get-file-extension-comparison/
	$file_extension = pathinfo($file, PATHINFO_EXTENSION);

	// File name shown in the save window
	$save_name = strrchr($file, DIRECTORY_SEPARATOR);

	// Figure out the MIME type (if not specified) 
	$known_mime_types = array(	
		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'text/html',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',
		'vcf' => 'text/x-vcard',
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',
		'zip' => 'application/zip',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',
		'doc' => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'xls' => 'application/vnd.ms-excel',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	);

	if ($mime_type === '') {
		if (array_key_exists($file_extension, $known_mime_types)) {
			$mime_type = $known_mime_types[$file_extension];
		} else {
			$mime_type = 'application/force-download';
		}
	} else {
		if ( ! isset($known_mime_types[$file_extension]) || $mime_type !== $known_mime_types[$file_extension]) {
			die('Please specify the valid MIME type or leave it blank!');
		}
	}
	
	$size = filesize($file);

	// Turn off output buffering to decrease cpu usage
	@ob_end_clean(); 

	header('Content-Type: '.$mime_type);
	header('Content-Disposition: attachment; filename="'.$save_name.'"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');

	// The three lines below basically make the download non-cacheable 
	header('Cache-control: private');
	header('Pragma: private');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

	// Multipart-download and download resuming support
	if (isset($_SERVER['HTTP_RANGE'])) {
		list($a, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
		list($range) = explode(',', $range, 2);
		list($range, $range_end) = explode('-', $range);
		$range = intval($range);
		if ( ! $range_end) {
			$range_end = $size - 1;
		} else {
			$range_end = intval($range_end);
		}

		$new_length = $range_end-$range + 1;
		header("HTTP/1.1 206 Partial Content");
		header("Content-Length: $new_length");
		header("Content-Range: bytes $range-$range_end/$size");
	} else {
		$new_length = $size;
		header("Content-Length: ".$size);
	}

	// Output the file itself 
	// You may want to change this
	$chunksize = 1*(1024*1024); 
	$bytes_send = 0;
	if ($file = fopen($file, 'r')) {
		if (isset($_SERVER['HTTP_RANGE'])) {
			fseek($file, $range);
		}
		while ( ! feof($file) && ( ! connection_aborted()) && ($bytes_send < $new_length)) {
			$buffer = fread($file, $chunksize);
			echo $buffer; 
			flush();
			$bytes_send += strlen($buffer);
		}
		fclose($file);
	} else {
		die('Error - can not open file.');
	}
	die();
}	

// End of file: ./system/scripts/download/download_script.php
