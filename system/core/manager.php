<?php
/**
 * Manager class file.
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Manager {    
    /**
     * Key-value array of HTTP POST parameters
     * It's value is assigned in dispatch(), that's why it's marked as public
     *
     * @var array   
     */
    public $_POST_DATA = array();
    
    /**
     * Key-value array of uploaded files info
     * It's value is assigned in dispatch(), that's why it's marked as public
     *
     * @var array   
     */
    public $_FILES_DATA = array();
    
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
                // If there is a collision, overwrite the existing variable
                // Needs to think carefully wheather to use EXTR_OVERWRITE or EXTR_SKIP
                extract($template_vars, EXTR_OVERWRITE);
            }
            
            // Capture the rendered output
            // Turn on output buffering
            ob_start();
            // NOTE: don't use require_once here.
            // require_once will cause problem if the same sub template/element 
            // needs to be rendered more than once in the same scope, because require_once 
            // will not include the sub template/element file again if it has already been included
            // so only the first call can be rendered as a result of the use of require_once
            require $template_file_path;
            // Gets the contents of the output buffer
            $content = ob_get_contents();
            // Clean (erase) the output buffer and turn off output buffering
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
     * @return NULL
     */   
    protected function response(array $config = NULL) {
        if (isset($config['content']) && isset($config['type'])) {
            // Response headers
            // The origin server will add additional headers about the response if not specified here
            $headers = array();
            
            // http://tools.ietf.org/html/rfc6657
            // Common Textual Media Types that SHOULD/RECOMMENDED utf-8 charset encoding
            // CSS: the majority of characters will be CSS syntax and thus US-ASCII, no need to force UTF-8 here
            // XML: Although listed as an optional parameter, the use of the charset parameter is STRONGLY RECOMMENDED
            // JSON: text SHALL be encoded in Unicode.  The default encoding is UTF-8.
            // http://www.ietf.org/rfc/rfc4627.txt?number=4627
            // JAVASCRIPT: http://www.rfc-editor.org/rfc/rfc4329.txt

            // Any HTTP/1.1 message containing an entity-body SHOULD include a
            // Content-Type header field defining the media type of that body.
            $headers['Content-Type'] = $config['type'];

            // Send server response headers
            // Like other headers, cookies must be sent before any output from your script
            // In InfoPotato, you should use Cookie class before $this->response()
            if (isset($config['extra_headers']) && is_array($config['extra_headers'])) {
                $headers = array_merge($headers, $config['extra_headers']);
            }
            foreach ($headers as $name => $val) {
                header($name.': '.$val);
            }
            
            // Output the uncompressed content
            // You can use apache's mod_gzip module to compress the output if you want
            // 1xx, 204, and 304 responses and any response to a HEAD request "MUST NOT" include a message-body
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
            $source_file = APP_DATA_DIR.$path.$data.'.php';
            
            if ( ! file_exists($source_file)) {
                halt('A System Error Was Encountered', "Unknown data file name '{$orig_data}'", 'sys_error');
            }
            
            // Load stripped source when system runtime cache turned-on
            // Otherwise load the origional source
            if (RUNTIME_CACHE === TRUE) {
                // Replace all directory separators with underscore
                $temp_cache_name = str_replace(DS, '_', $path.$data);
                // The runtime folder must be writable
                $file = APP_RUNTIME_CACHE_DIR.'~'.$temp_cache_name.'.php';
                if ( ! file_exists($file)) {
                    // Return source with stripped comments and whitespace
                    file_put_contents($file, php_strip_whitespace($source_file));
                }
            } else {
                $file = $source_file;
            }
            
            require_once $file;
            
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
     * @param    string    $scope 'SYS' or 'APP'
     * @param    string    $library the name of the class
     * @param    string    $alias (optional) alias name
     * @param    array    $config the optional config parameters
     * @return    void
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
                $source_file = SYS_LIBRARY_DIR.$path.$library.'.php';
            } elseif ($scope === 'APP') {
                $source_file = APP_LIBRARY_DIR.$path.$library.'.php';
            } else {
                halt('A System Error Was Encountered', "The location of the library must be specified, either 'SYS' or 'APP'", 'sys_error');
            }
            
            if ( ! file_exists($source_file)) {
                halt('A System Error Was Encountered', "Unknown library file name '{$orig_library}'", 'sys_error');
            }
            
            // Load stripped source when system runtime cache turned-on
            // Otherwise load the origional source
            if (RUNTIME_CACHE === TRUE) {
                // Replace all directory separators with underscore
                $temp_cache_name = str_replace(DS, '_', $path.$library);
            
                // The runtime cache folder must be writable
                if ($scope === 'SYS') {
                    $file = SYS_RUNTIME_CACHE_DIR.'~'.$temp_cache_name.'.php';
                } elseif ($scope === 'APP') {
                    $file = APP_RUNTIME_CACHE_DIR.'~'.$temp_cache_name.'.php';
                }
                
                if ( ! file_exists($file)) {
                    // Return source with stripped comments and whitespace
                    file_put_contents($file, php_strip_whitespace($source_file));
                }
            } else {
                $file = $source_file;
            }
            
            require_once $file;
            
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
            $source_file = SYS_FUNCTION_DIR.$path.$func.'.php';
        } elseif ($scope === 'APP') {
            $source_file = APP_FUNCTION_DIR.$path.$func.'.php';
        } else {
            halt('A System Error Was Encountered', "The location of the functions folder must be specified, either 'SYS' or 'APP'", 'sys_error');
        }
        
        if ( ! file_exists($source_file)) {
            halt('An Error Was Encountered', "Unknown function script '{$orig_func}'", 'sys_error');        
        }
        
        // Load stripped source when system runtime cache turned-on
        // Otherwise load the origional source
        if (RUNTIME_CACHE === TRUE) {
            // Replace all directory separators with underscore
            $temp_cache_name = str_replace(DS, '_', $path.$func);
            
            // The runtime cache folder must be writable
            if ($scope === 'SYS') {
                $file = SYS_RUNTIME_CACHE_DIR.'~'.$temp_cache_name.'.php';
            } elseif ($scope === 'APP') {
                $file = APP_RUNTIME_CACHE_DIR.'~'.$temp_cache_name.'.php';
            }
            
            if ( ! file_exists($file)) {
                // Return source with stripped comments and whitespace
                file_put_contents($file, php_strip_whitespace($source_file));
            }
        } else {
            $file = $source_file;
        }
        
        // The require_once() statement will check if the file has already been included, 
        // and if so, not include (require) it again
        require_once $file;
    }
    
}

// End of file: ./system/core/manager.php 
