<?php
/**
 * Worker class file.
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Worker {
    /**
     * @var array   Key-value array of HTTP POST parameters
     */
    protected $post = array();

    /**
     * @var array   Key-value array of HTTP PUT parameters
     */
    protected $put = array();

	/**
     * @var array   Key-value array of cookie sent with the current HTTP request           
     */
    protected $cookie = array();
	
	/**
	 * @var  array  vars for template file assignment
	 */
	protected $global_template_vars = array();

	/**
	 * Constructor
	 *
	 * Get the post, put, cookie values and automagically escapes them
	 * 
	 * $_GET data is simply disallowed by InfoPotato since it utilizes URI segments rather than traditional URL query strings
	 * 
	 * @return	void
	 */
	public function __construct() {
		// $_GET data is simply disallowed by InfoPotato since it utilizes URI segments rather than traditional URL query strings
		$this->post = $_POST;
		$this->cookie = $_COOKIE;
		
		// Here's how to access the content of a PUT request in PHP
		if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
			$putdata = file_get_contents('php://input');
			$exploded = explode('&', $putdata); 
			foreach ($exploded as $pair) {
				$item = explode('=', $pair);
				if (count($item) == 2) {
					$this->put[urldecode($item[0])] = urldecode($item[1]);
				}
			}
		}
		
		// Check magic quotes, this feature has been DEPRECATED as of PHP 5.3.0.
		// Magic Quotes is a process that automagically escapes incoming data to the PHP script. 
	    // It's preferred to code with magic quotes off and to instead escape the data at runtime, as needed.
		// Strip slashes if magic quotes is enabled so all input data is free of slashes
		if (version_compare(PHP_VERSION, '5.3.0', '<') && get_magic_quotes_gpc()) {
			$in = array($this->post, $this->cookie, $this->put);
			while (list($k, $v) = each($in)) {
				foreach ($v as $key => $val) {
					if ( ! is_array($val)) {
						// Un-quotes a quoted string
						$in[$k][$key] = stripslashes($val); 
						continue;
					}
					$in[] = $in[$k][$key];
				}
			}
			unset($in);
		}
	}
	
	/**
	 * Assign global template variable
	 * the variables assigned with $this->assign_template_global are always available to every template thereafter.
	 *
	 * @param   mixed $key key of assignment, or value to assign
	 * @param   mixed $value value of assignment
	 * @return  void
	 */    
	protected function assign_template_global($key, $value = NULL) {
		if ($value !== NULL) {
			$this->global_template_vars[$key] = $value;
		} else {
			foreach ($key as $k => $v) {
				if (is_int($k)) {
					$this->global_template_vars[] = $v;
				} else {
					$this->global_template_vars[$k] = $v;
				}
			}
		}
	}  
	
	/**
	 * Render template and return output as string
	 *
	 * If your template is located in a sub-folder, include the relative path from your templates folder.
	 *
	 * @param   string $template template filename and path
	 * @param   array $template_vars template variables
	 * @return  string rendered contents of template
	 */    
	protected function load_template($template, $template_vars = array()) {
		// Is the template in a sub-folder? If so, parse out the filename and path.
		if (strpos($template, '/') === FALSE) {
			$path = '';
		} else {
			$x = explode('/', $template);
			$template = end($x);			
			unset($x[count($x) - 1]);
			$path = implode(DS, $x).DS;
		}
	
		$file = $template.'.php';
	
		$template_file_path = APP_TEMPLATE_DIR.strtolower($path.$file);
		
		ob_start();
		if ( ! file_exists($template_file_path)) {
			if (ENVIRONMENT === 'development') {
				show_sys_error('A System Error Was Encountered', "Unknown template file name '{$template}'", 'sys_error');
			}
		} else {
			// Bring template vars into template scope
			// Import variables from an array into the current symbol table.
			extract($this->global_template_vars);
			if (is_array($template_vars) && (count($template_vars) > 0)) {
				extract($template_vars);
			}
			include($template_file_path);
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
	 *
     * @return NULL
     */   
	protected function response($config = array()) {
		if (isset($config['content']) && isset($config['type'])) {
			$headers = array();
            
			// The number of bytes of the response body in octets (8-bit bytes), not the number of characters
			$use_mb_strlen = (function_exists('mb_strlen') && (ini_get('mbstring.func_overload') !== '') && ((int) ini_get('mbstring.func_overload') & 2));
			
			// RFC 2616: Applications SHOULD use this field to indicate the transfer-length of the message-body, unless this is prohibited by the rules in section 4.4.
			// http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.4
			//$headers['Content-Length'] = $use_mb_strlen ? (string) mb_strlen($config['content'], '8bit') : (string) strlen(utf8_decode($config['content']));
			
			// MIME types need utf-8 charset encoding
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
				// The number of bytes of the response body in octets (8-bit bytes), not the number of characters
				//$headers['Content-Length'] = (string) strlen($compressed);
			}
		
			// Send server headers
			foreach ($headers as $name => $val) {
				header($name.': '.$val);
			}

			// Content was actually compressed? If not, output the uncompressed content
			echo ($compressed !== FALSE) ? $compressed : $config['content'];	
        }
	}

	/**
     * Determine the client's best encoding method from the HTTP Accept-Encoding header.
     * 
	 * By default, encoding is only offered to IE7+
	 *
     * A syntax-aware scan is done of the Accept-Encoding, so the method must
     * be non 0. The methods are favored in order of gzip, deflate, then 
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

		// Is the data in a sub-folder? If so, parse out the filename and path.
		if (strpos($data, '/') === FALSE) {
			$path = '';
		} else {
			$x = explode('/', $data);
			$data = end($x);			
			unset($x[count($x)-1]);
			$path = implode(DS, $x).DS;
		}
		
		// If no alias, use the data name
		if ($alias === '') {
			$alias = $data;
		}
		
		if (method_exists($this, $alias)) {
			if (ENVIRONMENT === 'development') {
				show_sys_error('A System Error Was Encountered', "Data name '{$alias}' is an invalid (reserved) name", 'sys_error');
			}
		}
		// Data already loaded? silently skip
		if (isset($this->$alias)) {
			return TRUE;
		}
		
		$file_path = APP_DATA_DIR.$path.$data.'.php';

		if ( ! file_exists($file_path)) {
			if (ENVIRONMENT === 'development') {
				show_sys_error('A System Error Was Encountered', "Unknown data file name '{$data}'", 'sys_error');
			}
		}
		require_once($file_path);
		
		// Class name must be the same as the data name
		if ( ! class_exists($data)) {
			if (ENVIRONMENT === 'development') {
				show_sys_error('A System Error Was Encountered', "Unknown class name '{$data}'", 'sys_error');
			}
		}

		// Instantiate the data object as a worker's property 
		// The names of user-defined classes are case-insensitive
		$this->$alias = new $data;
		
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
	 * @param	string	the name of the class
	 * @param	string	an optional alias name
	 * @param	array	the optional config parameters
	 * @return	void
	 */	   
	protected function load_library($library, $alias = '', $config = array()) {
		$library = strtolower($library);

		// Is the library in a sub-folder? If so, parse out the filename and path.
		if (strpos($library, '/') === FALSE) {
			$path = '';
		} else {
			$x = explode('/', $library);
			$library = end($x);			
			unset($x[count($x)-1]);
			$path = implode(DS, $x).DS;
		}
		
		// If no alias, use the library name
		if ($alias === '') {
			$alias = $library;
		}
		
		if (method_exists($this, $alias)) {	
			if (ENVIRONMENT === 'development') {
				show_sys_error('A System Error Was Encountered', "Library name '{$alias}' is an invalid (reserved) name", 'sys_error');
			}
		}
		
		// Library already loaded? silently skip
		if (isset($this->$alias)) {
			return TRUE;
		}
		
		$file_path = SYS_DIR.'libraries'.DS.$path.$library.'.php';

		if ( ! file_exists($file_path)) {
			if (ENVIRONMENT === 'development') {
				show_sys_error('A System Error Was Encountered', "Unknown library file name '{$library}'", 'sys_error');
			}
		}
		require_once($file_path);
		
		// Class name must be the same as the library name
		if ( ! class_exists($library)) {
			if (ENVIRONMENT === 'development') {
				show_sys_error('A System Error Was Encountered', "Unknown class name '{$library}'", 'sys_error');
			}
		}

		// Instantiate the library object as a presenter's property 
		// An empty array is considered as a NULL variable
		// The names of user-defined classes are case-insensitive
		// Don't create static properties or methods for library
		$this->$alias = new $library($config);
		
		return TRUE;
	}
	
	/**
	 * __call
	 *
	 * Gets triggered when an inaccessible methods requested
	 */    
	public function __call($method, $args) {
		if (ENVIRONMENT === 'development') {
			show_sys_error('A System Error Was Encountered', "Unknown class method '{$method}'", 'sys_error');
		}
	}

}

// End of file: ./system/core/worker.php 
