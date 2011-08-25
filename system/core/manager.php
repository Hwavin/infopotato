<?php
/**
 * Manager class file.
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Manager {
    /**
	 * The name of the current requested manager method
	 *
	 * @var  string 
	 */
	public $target_method = '';
	
	/**
	 * Parameters of the current requested manager method
	 *
	 * @var  array  
	 */
	public $target_method_params = array();
	
	/**
     * Key-value array of HTTP POST parameters
	 * 
	 * @var array   
     */
    protected $_POST_DATA = array();

	/**
	 * Constructor
	 *
	 * Get the post, put, cookie values and automagically escapes them
	 * 
	 * $_GET data is simply disallowed by InfoPotato since it utilizes URI segments rather than traditional URL query strings
	 * 
	 * Parent constructors are not implicitly called in derived classes
	 * you must explicitly call parent::__construct() in derived classes
	 *
	 * @return	void
	 */
	public function __construct() {
		// The POST data can only be accessed in manager using $this->_POST_DATA
		// The $_POST data is already sanitized by the dispatcher
		$this->_POST_DATA = $_POST;
		// Disable access to $_POST
		unset($_POST);
	} 
	
	/**
	 * Render template and return output as string
	 *
	 * If your template is located in a sub-folder, include the relative path from your templates folder.
	 *
	 * @param   string $template template file path and file name
	 * @param   array $template_vars (optional) template variables
	 * @return  string rendered contents of template
	 */    
	protected function render_template($template, array $template_vars = NULL) {
		$orig_template = strtolower($template);
		
		// Is the template in a sub-folder? If so, parse out the filename and path.
		if (strpos($template, '/')) {
			$template = str_replace('/', DS, pathinfo($orig_template, PATHINFO_DIRNAME)).DS.substr(strrchr($orig_template, '/'), 1);
		}
		
		$template_file_path = APP_TEMPLATE_DIR.$template.'.php';

		ob_start();
		if ( ! file_exists($template_file_path)) {
			halt('A System Error Was Encountered', "Unknown template file name '{$orig_template}'", 'sys_error');
		} else {
			if (is_array($template_vars) && (count($template_vars) > 0)) {
				// Bring template vars into template scope
			    // Import variables from an array into the current symbol table.
				extract($template_vars);
			}
			require_once $template_file_path;
		}	
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	/**
     * Output the rendered template to browser or some requesting web services
     * 
     * @param array $config options
     * 
     * $config['content']: (string required) content to be compressed
     * 
     * $config['type']: (string required)  specify the character encoding of the text document, like html, css, plain text
	 *
	 * $config['compression_level']: (int optional) Can be given as 0 for no compression up to 9 for maximum compression.
     * 
	 * $config['disable_cache']: (boolean optional) Sets the correct headers to instruct the client to not cache the response
	 * 
     * @return NULL
     */   
	protected function response($config = array()) {
		if (isset($config['content']) && isset($config['type'])) {
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
			
			// Explicitly specify the charset parameter (utf-8) of the text document
			// The value of charset should be case insensitive - browsers shouldn't care.
			$headers['Content-Type'] = in_array($config['type'], $mime_types) ? $config['type'].'; charset=utf-8' : $config['type'];

			// By default, compress all text based files
			// Note: Image and PDF files should not be gzipped because they are already compressed. 
			// Trying to gzip them not only wastes CPU but can potentially increase file sizes.
			$is_compressed = FALSE;
			if (in_array($config['type'], $mime_types)) {
				$compression_method = self::_get_accepted_compression_method();
				// Return the compressed content or FALSE if an error occurred or the content was uncompressed
				$compressed = isset($config['compression_level']) 
							  ? self::_compress($config['content'], $compression_method, $config['compression_level']) 
							  : self::_compress($config['content'], $compression_method);
			
				// If compressed, the header "Vary: Accept-Encoding" and "Content-Encoding" added, 
				// and the "Content-Length" header updated.
				if ($compressed !== FALSE) {
					$headers['Vary'] = 'Accept-Encoding';
					$headers['Content-Encoding'] = $compression_method[1];
					$is_compressed = TRUE;
				}
			}
			
			// Sets the correct headers to instruct the client to not cache the response
			if (isset($config['disable_cache']) && $config['disable_cache'] === TRUE) {
				$headers['Expires'] = 'Mon, 26 Jul 1997 05:00:00 GMT';
				$headers['Last-Modified'] = gmdate("D, d M Y H:i:s") . " GMT";
				$headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0';
				$headers['Pragma'] = 'no-cache';
			}
			
			// Send server response headers
			foreach ($headers as $name => $val) {
				header($name.': '.$val);
			}

			// Content was actually compressed? If not, output the uncompressed content
			echo ($is_compressed === TRUE) ? $compressed : $config['content'];	
        }
	}

	/**
     * Determine the client's best encoding method from the HTTP Accept-Encoding header.
     * 
	 * By default, encoding is only offered to IE7+
	 *
     * A syntax-aware scan is done of the Accept-Encoding, so the method must
     * be non 0. The methods are favored in order of gzip (a lossless compressed-data format), deflate, then 
     * compress. Deflate is always smallest and generally faster, but is 
     * rarely sent by servers, so client support could be buggier.
     * 
     * @param boolean $allow_compress allow the older compress encoding
     * @param boolean $allow_deflate allow the more recent deflate encoding
     * 
     * @return array two values, 1st is the actual encoding method, 2nd is the
     * alias of that method to use in the Content-Encoding header (some browsers
     * call gzip "x-gzip" etc.)
     */
    private static function _get_accepted_compression_method($allow_compress = TRUE, $allow_deflate = TRUE) {
        if ( ! isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            return array('', '');
        }
        $ae = $_SERVER['HTTP_ACCEPT_ENCODING'];
        // gzip checks (quick)
        if (strpos($ae, 'gzip,') === 0 // most browsers
			// opera 
			|| strpos($ae, 'deflate, gzip,') === 0) { 
            return array('gzip', 'gzip');
        }
		
        // gzip checks (slow)
        if (preg_match('@(?:^|,)\\s*((?:x-)?gzip)\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae, $m)) {
            return array('gzip', $m[1]);
        }
		
        if ($allow_deflate) {
            // deflate checks    
            $ae_rev = strrev($ae);
            if (strpos($ae_rev, 'etalfed ,') === 0 // ie, webkit
                || strpos($ae_rev, 'etalfed,') === 0 // gecko
                || strpos($ae, 'deflate,') === 0 // opera
                // slow parsing
                || preg_match('@(?:^|,)\\s*deflate\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae)) {
                return array('deflate', 'deflate');
            }
        }
		
        if ($allow_compress && preg_match('@(?:^|,)\\s*((?:x-)?compress)\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae, $m)) {
            return array('compress', $m[1]);
        }
        return array('', '');
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
    private static function _compress($content, $compression_method = array('', ''), $compression_level = 6) {
        if ($compression_method[0] === '' || ($compression_level == 0) || ! extension_loaded('zlib')) {
            return FALSE;
        }
		
        if ($compression_method[0] === 'deflate') {
            $compressed = gzdeflate($content, $compression_level);
        } elseif ($compression_method[0] === 'gzip') {
            $compressed = gzencode($content, $compression_level);
        } else {
            $compressed = gzcompress($content, $compression_level);
        }
		
        if ($compressed === FALSE) {
            return FALSE;
        }

        return $compressed;
    }

	/**
	 * Data Object Loader
	 *
	 * If your data is located in a sub-folder, include the relative path from your data folder.
	 *
	 * @param   string $data the name of the data class
	 * @param   string $alias the optional property name alias
	 * @return  boolean
	 */    
	protected function load_data($data, $alias = '') {
		$data = strtolower($data);
		
		$orig_data = $data;

		// Is the data in a sub-folder? If so, parse out the filename and path.
		if (strpos($data, '/') === FALSE) {
			$path = '';
		} else {
			$path = str_replace('/', DS, pathinfo($data, PATHINFO_DIRNAME)).DS;
			$data = substr(strrchr($data, '/'), 1);		
		}

		// If no alias, use the data name
		if ($alias === '') {
			$alias = $data;
		}

		if (method_exists($this, $alias)) {
			halt('A System Error Was Encountered', "Data name '{$alias}' is an invalid (reserved) name", 'sys_error');
		}
		
		// Data already loaded? silently skip
		if (isset($this->$alias)) {
			return TRUE;
		}

		$file_path = APP_DATA_DIR.$path.$data.'.php';

		if ( ! file_exists($file_path)) {
			halt('A System Error Was Encountered', "Unknown data file name '{$orig_data}'", 'sys_error');
		}
		require_once $file_path;

		// Class name must be the same as the data name
		if ( ! class_exists($data)) {
			halt('A System Error Was Encountered', "Unknown class name '{$data}'", 'sys_error');
		}

		// Instantiate the data object as a worker's property 
		// The names of user-defined classes are case-insensitive
		$this->{$alias} = new $data;

		return TRUE;	
	}

	/**
	 * Library Class Loader
	 *
	 * This function lets users load and instantiate classes.
	 * It is designed to be called from a user's app controllers.
	 *
	 * If library is located in a sub-folder, include the relative path from libraries folder.
	 *
	 * @param	string	$scope 'SYS' or 'APP'
	 * @param	string	$library the name of the class
	 * @param	string	$alias (optional) alias name
	 * @param	array	$config the optional config parameters
	 * @return	void
	 */	   
	protected function load_library($scope, $library, $alias = '', $config = array()) {
		$library = strtolower($library);

		$orig_library = $library;
		
		// Is the library in a sub-folder? If so, parse out the filename and path.
		if (strpos($library, '/') === FALSE) {
			$path = '';
		} else {
			$path = str_replace('/', DS, pathinfo($library, PATHINFO_DIRNAME)).DS;
			$library = substr(strrchr($library, '/'), 1);	
		}
		
		// If no alias, use the library name
		if ($alias === '') {
			$alias = $library;
		}
		
		if (method_exists($this, $alias)) {	
			halt('A System Error Was Encountered', "Library name '{$alias}' is an invalid (reserved) name", 'sys_error');
		}
		
		// Library already loaded? silently skip
		if (isset($this->$alias)) {
			return TRUE;
		}
		
		if ($scope === 'SYS') {
			$file_path = SYS_LIBRARY_DIR.$path.$library.'.php';
		} elseif ($scope === 'APP') {
			$file_path = APP_LIBRARY_DIR.$path.$library.'.php';
		} else {
			halt('A System Error Was Encountered', "The location of the library must be specified, either 'SYS' or 'APP'", 'sys_error');
		}
		
		$file_path = SYS_LIBRARY_DIR.$path.$library.'.php';

		if ( ! file_exists($file_path)) {
			halt('A System Error Was Encountered', "Unknown library file name '{$orig_library}'", 'sys_error');
		}
		require_once $file_path;
		
		// Class name must be the same as the library name
		if ( ! class_exists($library)) {
			halt('A System Error Was Encountered', "Unknown class name '{$library}'", 'sys_error');
		}

		// Instantiate the library object as a manager's property 
		// An empty array is considered as a NULL variable
		// The names of user-defined classes are case-insensitive
		// Don't create static properties or methods for library
		$this->{$alias} = new $library($config);
		
		return TRUE;
	}
	
	/**
	 * Load user-defined function
	 *
	 * If function script is located in a sub-folder, include the relative path from functions folder
	 *
	 * @param   string $scope 'SYS' or 'APP'
	 * @param   string $func the function script name
	 * @return  void
	 */    
	protected function load_function($scope, $func) {
		$orig_func = strtolower($func);
		
		// Is the script in a sub-folder? If so, parse out the filename and path.
		if (strpos($func, '/') === FALSE) {
			$path = '';
		} else {
			$path = str_replace('/', DS, pathinfo($func, PATHINFO_DIRNAME)).DS;
			$func = substr(strrchr($func, '/'), 1);	
		}

		if ($scope === 'SYS') {
			$file_path = SYS_FUNCTION_DIR.$path.$func.'.php';
		} elseif ($scope === 'APP') {
			$file_path = APP_FUNCTION_DIR.$path.$func.'.php';
		} else {
			halt('A System Error Was Encountered', "The location of the functions folder must be specified, either 'SYS' or 'APP'", 'sys_error');
		}

		if ( ! file_exists($file_path)) {
			halt('An Error Was Encountered', "Unknown function script '{$orig_func}'", 'sys_error');		
		}
		// The require_once() statement will check if the file has already been included, 
		// and if so, not include (require) it again
		require_once $file_path;
	}

	/**
	 * __call
	 *
	 * Gets triggered when an inaccessible methods requested
	 */    
	public function __call($method, $args) {
		halt('A System Error Was Encountered', "Unknown class method '{$method}'", 'sys_error');
	}

}

// End of file: ./system/core/manager.php 
