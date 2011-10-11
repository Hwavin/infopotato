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
     * Key-value array of HTTP POST parameters
	 * 
	 * @var array   
     */
    protected $_POST_DATA = array();

	/**
     * Array of global template variables
	 * 
	 * @var array   
     */
	protected static $template_global_vars = array();

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
	 * Assign global template variable
	 * Variables assigned with $this->assign_template_global() are always available to every template thereafter.
	 *
	 * @param   mixed $key key of assignment, or value to assign
	 * @param   mixed $value value of assignment
	 * @return  void
	 */    
	protected function assign_template_global($key, $value = NULL) {
		if ($value == NULL) {
			halt('A System Error Was Encountered', "Please specify the value of your global variable '${$key}'", 'sys_error');
		}	
		self::$template_global_vars[$key] = $value;
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
			// str_replace is faster than preg_replace, but strtr is faster than str_replace by a factor of 4
			$template = strtr(pathinfo($orig_template, PATHINFO_DIRNAME), '/', DS).DS.substr(strrchr($orig_template, '/'), 1);
		}
		
		$template_file_path = APP_TEMPLATE_DIR.$template.'.php';

		if ( ! file_exists($template_file_path)) {
			halt('A System Error Was Encountered', "Unknown template file '{$orig_template}'", 'sys_error');
		} else {
			if (count($template_vars) > 0) {
				// Import the template variables to local namespace
				// If there is a collision, don't overwrite the existing variable.
				extract($template_vars, EXTR_SKIP);
			}
			
			// Import the global template variables to local namespace
			if (count(self::$template_global_vars) > 0) {
				extract(self::$template_global_vars, EXTR_SKIP);
			}
			
			// Capture the rendered output
			ob_start();
			require_once $template_file_path;
			$content = ob_get_contents();
		    ob_end_clean();
			
			return $content;
		}	
	}
	
	/**
     * Output the rendered template to browser or some requesting web services
     * 
     * @param array $config options
     * 
     * $config['content']: (string required) content to output
     * 
     * $config['type']: (string required)  specify the character encoding of the text document, like html, css, plain text
	 *
	 * $config['extra_headers']: (array optional)  any extra headers to response
	 *
	 * $config['disable_cache']: (boolean optional) Sets the correct headers to instruct the client to not cache the response
	 * 
     * @return NULL
     */   
	protected function response(array $config = NULL) {
		if (isset($config['content']) && isset($config['type'])) {
			// Response headers
			$headers = array();

			// MIME types that need utf-8 charset encoding
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
			$headers['Content-Type'] = in_array($config['type'], $mime_types) 
			                           ? $config['type'].'; charset=utf-8' 
									   : $config['type'];

			// Sets the correct headers to instruct the client to not cache the response
			if (isset($config['disable_cache']) && $config['disable_cache'] === TRUE) {
				$headers['Expires'] = 'Mon, 26 Jul 1997 05:00:00 GMT';
				$headers['Last-Modified'] = gmdate("D, d M Y H:i:s") . " GMT";
				$headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0';
				$headers['Pragma'] = 'no-cache';
			}
			
			// Send server response headers
			if (isset($config['extra_headers']) && is_array($config['extra_headers'])) {
			    $headers = array_merge($headers, $config['extra_headers']);
			}
			foreach ($headers as $name => $val) {
				header($name.': '.$val);
			}

			// Output the uncompressed content
			// You can use apache's mod_gzip module to compress the output if you want
			echo $config['content'];	
        }
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
			$path = strtr(pathinfo($data, PATHINFO_DIRNAME), '/', DS).DS;
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
		if ( ! isset($this->$alias)) {
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
		}
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
	protected function load_library($scope, $library, $alias = '', array $config = NULL) {
		$library = strtolower($library);

		$orig_library = $library;
		
		// Is the library in a sub-folder? If so, parse out the filename and path.
		if (strpos($library, '/') === FALSE) {
			$path = '';
		} else {
			$path = strtr(pathinfo($library, PATHINFO_DIRNAME), '/', DS).DS;
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
		if ( ! isset($this->$alias)) {
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
		}
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
			$path = strtr(pathinfo($func, PATHINFO_DIRNAME), '/', DS).DS;
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

}

// End of file: ./system/core/manager.php 
