<?php
/**
 * Manager class file.
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
namespace InfoPotato\core;

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
     * @param string $template template file path and file name
     * @param array $template_vars (optional) template variables
     * @return string rendered contents of template
     */    
    protected function render_template($template, array $template_vars = NULL) {
        // Is the template in a sub-folder? If so, parse out the filename and path.
        if (strpos($template, '/')) {
            // str_replace is faster than preg_replace, but strtr is faster than str_replace by a factor of 4
            $template = strtr(pathinfo($template, PATHINFO_DIRNAME), '/', DS).DS.substr(strrchr($template, '/'), 1);
        }
        
		// Template name is case-sensitive, since you can have two files with same names but different cases of same letters on Linux
		// You need to make sure the case of the provided name is consistent with the case of the file name
        $template_file = APP_TEMPLATE_DIR.$template.'.php';
        
        if ( ! file_exists($template_file)) {
            Common::halt('A System Error Was Encountered', "Unknown template file '{$template}'", 'sys_error');
        } else {
            if (count($template_vars) > 0) {
                // Import the template variables to local namespace
                // If there is a collision, overwrite the existing variable
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
            require $template_file;
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
     * $config['content']: (string, optional for 4xx, no content for 204, 304) content to output
     * $config['type']: (string, optional, suggested to specify if there's output content)  specify the media type for the output
     * $config['status']: (int, optional, use if only you want to change the default status code)  specify the Status-Line
     * $config['extra_headers']: (associative array, optional)  any extra headers to response
     * 
     * @return void
     */   
    protected function response(array $config = NULL) {
        // Send the Status-Line (case is not significant) if specified
        // In most cases, no need to specify this, the server will use "HTTP/1.1 200 OK"
        if (isset($config['status'])) {
            // Mapping of status codes to reason phrases
            $status_map = array(
                // 1xx: Informational - Request received, continuing process
                100 => 'Continue',
                101 => 'Switching Protocols',
                102 => 'Processing',
                // 2xx: Success - The action was successfully received, understood, and accepted
                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',
                207 => 'Multi-Status',
                208 => 'Already Reported',
                226 => 'IM Used',
                // 3xx: Redirection - Further action must be taken in order to complete the request
                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                307 => 'Temporary Redirect',
                308 => 'Permanent Redirect',
                // 4xx: Client Error - The request contains bad syntax or cannot be fulfilled
                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                422 => 'Unprocessable Entity',
                423 => 'Locked',
                424 => 'Failed Dependency',
                425 => 'Reserved for WebDAV advanced collections expired proposal',
                426 => 'Upgrade required',
                428 => 'Precondition Required',
                429 => 'Too Many Requests',
                431 => 'Request Header Fields Too Large',
                // 5xx: Server Error - The server failed to fulfill an apparently valid request
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
                506 => 'Variant Also Negotiates (Experimental)',
                507 => 'Insufficient Storage',
                508 => 'Loop Detected',
                510 => 'Not Extended',
                511 => 'Network Authentication Required',
            );
            
            // In case the status code is quoted
            $config['status'] = (int) $config['status'];

            if (isset($status_map[$config['status']])) {
                // Status-Line = HTTP-Version SP Status-Code SP Reason-Phrase CRLF
                $status_line = 'HTTP/1.1 '.$config['status'].' '.$status_map[$config['status']];
                // Replace the default Status-Line
                header($status_line);
                
                // Determine if continue to output the message body
                if (in_array($config['status'], array(204, 304))) {
                    // Send other response headers if provided
                    // There are still headers added by the origin server
                    if (isset($config['extra_headers']) && is_array($config['extra_headers'])) {
                        // Send out all added extra headers before exit
                        foreach ($config['extra_headers'] as $name => $val) {
                            header($name.': '.$val);
                        }
                    }

                    // These status codes must not be followed by a message body
                    exit();
                }
            } else {
                Logger::log_debug(APP_LOG_DIR, 'The provided HTTP status code is invalid.');
            }
        }
        
        // If no 'status' specified, we just send out the headers and output the 'content'
        
        // Add other response headers
        // The origin server will add additional headers about the response if not specified here
        // Like other headers, cookies must be sent before any output from your script
        // In InfoPotato, you should use Cookie class before $this->response()
        if (isset($config['extra_headers']) && is_array($config['extra_headers'])) {
            // Send out all the specified headers
            foreach ($config['extra_headers'] as $name => $val) {
                header($name.': '.$val);
            }
        }
        
        if (isset($config['content'])) {
            // Any HTTP/1.1 message containing an entity-body SHOULD include a
            // Content-Type header field defining the media type of that body.
            if (isset($config['type'])) {
                header('Content-Type: '.$config['type']);
            }

            // Lastly, output the uncompressed content
            // You can use apache's mod_gzip module to compress the output if you want
            echo $config['content'];
        } else {
            Logger::log_debug(APP_LOG_DIR, "Missing 'content' for response.");
        }
    }
    
}

// End of file: ./system/core/manager.php 
