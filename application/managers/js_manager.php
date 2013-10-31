<?php
namespace app\managers;

use InfoPotato\core\manager;

class Js_Manager extends Manager {
    public function get_index(array $params = NULL) {
        // $js_files is an array created from $params[0]
        $js_files = count($params) > 0 ? explode(':', $params[0]) : NULL;
        
        if ($js_files !== NULL) {
            $file = '';
            $last_modified = '';
            $js_content = '';
            foreach ($js_files as $js_file) {
                // Physical file that contains the output content
                $file .= APP_TEMPLATE_DIR.'js'.DS.$js_file.'.php';
                // The Unix timestamp the file was last modified, or FALSE on failure.
                $last_modified .= filemtime(APP_TEMPLATE_DIR.'js'.DS.$js_file.'.php');

                // Combined content
                $js_content .= $this->render_template('js/'.$js_file);
            }
            
            // MD5 hash of the combined file names and their modified timestamps
            $etag = md5($file.$last_modified);
            // The client saved ETag value can be found in "If-None-Match" header field
            $etag_in_request = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : '';

            if ($etag === $etag_in_request) {
                // They already have the most up to date copy
                header('HTTP/1.1 304 Not Modified');
                
                // The ETag must be enclosed with double quotes
                // Etag was introduced in HTTP 1.1
                header('ETag: "'.$etag.'"');
                exit;
            } else {
                // ETag not match, output the updated content with new ETag value
                $response_data = array(
                    'content' => $js_content,
                    'type' => 'application/javascript',
                    'extra_headers' => array('ETag' => $etag)
                );
                $this->response($response_data);
            }
        }
    }
}

/* End of file: ./application/managers/js_manager.php */
