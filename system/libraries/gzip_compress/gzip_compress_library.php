<?php
/**
 * HTTP GZIP compress
 *
 * The encoding part is based on Minify's HTTP_Encoder class
 * @link https://github.com/mrclay/minify/blob/master/min/lib/HTTP/Encoder.php
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2012 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class GZIP_Compress_Library {  
	
	/**
	 * The compression level, 0--9
	 * 
	 * @var int
	 */
	private $_compression_level = 6;
	
	/**
	 * Constructor
	 *
	 * @param array $config['compression_level']
	 *
	 */	
	public function __construct(array $config = NULL) { 
		if (count($config) > 0) {
			if (isset($config['compression_level'])) {
			    $this->_compression_level = $config['compression_level'];
			}
		}
	}
	
	/**
     * Compress the input content
     * 
     * @param array $config options
     * 
     * $config['content']: (string required) content to be compressed
     * 
     * $config['type']: (string required)  specify the character encoding of the text document, like html, css, plain text
	 *
     * @return array
     */   
	public function response(array $config = NULL) {
		if (isset($config['content'])) {
			// Response headers
			$headers = array();

			// MIME types that need utf-8 charset encoding and compression
			// Do not utf-8 encode and HTTP compress images and PDF files
			$mime_types = array(
				'text/html', 
				'text/plain', 
				'text/xml', 
				'text/css', 
				'text/javascript', 
				'application/json',
				'text/csv',
			);

			// By default, compress all text based files
			// Note: Image and PDF files should not be gzipped because they are already compressed. 
			// Trying to gzip them not only wastes CPU but can potentially increase file sizes.
			$is_compressed = FALSE;
			if (in_array($config['type'], $mime_types)) {
				$compression_method = self::_get_accepted_compression_method();
				// Return the compressed content or FALSE if an error occurred or the content was uncompressed
				$compressed = self::_compress($config['content'], $compression_method);

				// If compressed, the header "Vary: Accept-Encoding" and "Content-Encoding" added, 
				// and the "Content-Length" header updated.
				if ($compressed !== FALSE) {
					$headers['Vary'] = 'Accept-Encoding';
					$headers['Content-Encoding'] = $compression_method[1];
					$is_compressed = TRUE;
				}
			}

			return array(
			    'headers' => $headers,
				'compressed_content' => ($is_compressed === TRUE) ? $compressed : $config['content'];
			);
        }
	}
	
	/**
     * Determine the client's best encoding method from the HTTP Accept-Encoding header.
     * 
	 * For IE, encoding is only offered to IE7+
	 *
     * A syntax-aware scan is done of the Accept-Encoding, so the method must be non 0.
     * The methods are favored in order of gzip (lossless compressed-data format), deflate, then compress.
     * Deflate is always smallest and generally faster, but is 
     * rarely sent by servers, so client support could be buggier.
     * 
     * @return array two values, 1st is the actual encoding method, 2nd is the
     * alias of that method to use in the Content-Encoding header (some browsers
     * call gzip "x-gzip" etc.)
     */
    private static function _get_accepted_compression_method() {
        // Contents of the Accept-Encoding: header from the current request, if there is one. Example: 'gzip'
		if ( ! isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            return array('', '');
        }

        $ae = $_SERVER['HTTP_ACCEPT_ENCODING'];
        // gzip checks (quick), most browsers and opera
        if (strpos($ae, 'gzip,') === 0 || strpos($ae, 'deflate, gzip,') === 0) { 
            return array('gzip', 'gzip');
        }

        // gzip checks (slow)
        if (preg_match('@(?:^|,)\\s*((?:x-)?gzip)\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae, $m)) {
            return array('gzip', $m[1]);
        }

        // deflate checks    
        $ae_rev = strrev($ae);
		$a = strpos($ae_rev, 'etalfed ,'); // ie, webkit
		$b = strpos($ae_rev, 'etalfed,'); // gecko
		$c = strpos($ae, 'deflate,'); // opera
		$d = preg_match('@(?:^|,)\\s*deflate\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae); // slow parsing
		if ($a === 0 || $b === 0 || $c === 0 || $d) {
			return array('deflate', 'deflate');
		}

        return preg_match('@(?:^|,)\\s*((?:x-)?compress)\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae, $m)
		       ? array('compress', $m[1]) 
			   : array('', '');
    }

	/**
     * Content compression for faster page loads
     * 
     * If the compression method is '' (none) or compression level is 0, or the 'zlib'
     * extension isn't loaded, we return FALSE.
     * 
     * Then the appropriate gz_* function is called to compress the content. If
     * this fails, FALSE is returned.
     * 
     * @return string the compressed content or FALSE if an error occurred.
     */
    private static function _compress($content, array $compression_method = array('', ''), $compression_level = 6) {
        if ($compression_method[0] === '' || ! extension_loaded('zlib')) {
            return FALSE;
        }

        if ($compression_method[0] === 'deflate') {
            $compressed = gzdeflate($content, $compression_level);
        } elseif ($compression_method[0] === 'gzip') {
            $compressed = gzencode($content, $compression_level);
        } else {
            $compressed = gzcompress($content, $compression_level);
        }

		// Returns the compressed string or FALSE if an error occurred.
        return $compressed;
    }

} 
 
/* End of file: ./system/libraries/gzip_compress/gzip_compress_library.php */