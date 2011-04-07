<?php
/**
 * Force File Download With PHP
 *
 * If you want to do something on download abort/finish,
 * register_shutdown_function('function_name');
 * 
 * @param string $file the path of the file to be downloaded
 * @param string $name file name shown in the save window
 * @param string $mime_type MIME type of the target file
 * @return none
 * @link http://w-shadow.com/blog/2007/08/12/how-to-force-file-download-with-php/
 */
function download($file, $name, $mime_type = '') { 
	if ( ! is_readable($file)) {
		die('File not found or inaccessible!');
	}
	
	$size = filesize($file);
	$name = rawurldecode($name);

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

	if ($mime_type == '') {
		$file_extension = strtolower(substr(strrchr($file, '.'), 1));
		if (array_key_exists($file_extension, $known_mime_types)) {
			$mime_type = $known_mime_types[$file_extension];
		} else {
			$mime_type = 'application/force-download';
		};
	};

	//@ob_end_clean(); //turn off output buffering to decrease cpu usage

	// Required for IE, otherwise Content-Disposition may be ignored
	if (ini_get('zlib.output_compression')) {
		ini_set('zlib.output_compression', 'Off');
	}
	header('Content-Type: '.$mime_type);
	header('Content-Disposition: attachment; filename="'.$name.'"');
	header('Content-Transfer-Encoding: binary');
	header('Accept-Ranges: bytes');

	// The three lines below basically make the download non-cacheable 
	header('Cache-control: private');
	header('Pragma: private');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

	// multipart-download and download resuming support
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

	// output the file itself 
	$chunksize = 1*(1024*1024); //you may want to change this
	$bytes_send = 0;
	if ($file = fopen($file, 'r')) {
		if (isset($_SERVER['HTTP_RANGE'])) {
			fseek($file, $range);
		}
		while ( ! feof($file) && ( ! connection_aborted()) && ($bytes_send < $new_length)) {
			$buffer = fread($file, $chunksize);
			echo $buffer; //echo($buffer); // is also possible
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
