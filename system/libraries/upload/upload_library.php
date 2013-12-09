<?php
/**
 * Form-based File Uploading Library
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\libraries\upload;

class Upload_Library {
    /**
     * Key-value array of HTTP FILES parameters to be validated
     * 
     * @var array
     */
    private $files = array();    
    
    /**
     * The maximum size (in kilobytes) that the file can be. Set to zero for no limit.
     * Note: Most PHP installations have their own limit, as specified in the php.ini file. 
     * Usually 2 MB (or 2048 KB) by default.
     * 
     * @var integer
     */
    private $max_size = 0;
    
    /**
     * The maximum width (in pixels) that the file can be. Set to zero for no limit.
     * 
     * @var integer
     */
    private $max_width = 0;
    
    /**
     * The maximum height (in pixels) that the file can be. Set to zero for no limit.
     * 
     * @var integer
     */
    private $max_height = 0;
    
    /**
     * Maximum filename length
     *
     * @var    int
     */
    private $max_filename = 0;
    
    /**
     * The mime types corresponding to the types of files you allow to be uploaded. 
     * Usually the file extension can be used as the mime type. 
     * E.g. array('pdf', 'doc', 'docx')
     * 
     * @var array
     */
    private $allowed_types = array();
    
    /**
     * The maximum length that a file name can be. Set to zero for no limit.
     * 
     * @var string
     */
    private $file_temp = '';
    
    /**
     * If set InfoPotato will rename the uploaded file to this name. 
     * The extension provided in the file name must also be an allowed file type.
     * 
     * @var string
     */
    private $file_name = '';
    
    /**
     * Original filename
     *
     * @var    string
     */
    private $orig_name = '';
    
    /**
     * File type
     *
     * @var    string
     */
    private $file_type = '';
    
    /**
     * File size
     *
     * @var    int
     */
    private $file_size = 0;
    
    /**
     * Filename extension
     *
     * @var    string
     */
    private $file_ext = '';
    
    /**
     * The path to the folder where the upload should be placed. 
     * The folder must be writable and the path can be absolute or relative.
     * Make sure it has a trailing slash
     * 
     * @var string
     */
    private $upload_path = '';
    
    /**
     * If set to true, if a file with the same name as the one you are uploading exists, 
     * it will be overwritten. If set to false, a number will be appended to the filename 
     * if another with the same name exists.
     * 
     * @var boolean
     */
    private $overwrite = FALSE;
    
    /**
     * If set to TRUE the file name will be converted to a random encrypted string. 
     * This can be useful if you would like the file saved with a name that 
     * can not be discerned by the person uploading it.
     * 
     * @var boolean
     */
    private $encrypt_name = FALSE;
    
    /**
     * Image width
     *
     * @var    int
     */
    private $image_width = '';
    
    /**
     * Image height
     *
     * @var    int
     */
    private $image_height = '';
    
    /**
     * Image type
     *
     * @var    string
     */
    private $image_type = '';
    
    /**
     * Image size string
     *
     * @var    string
     */
    private $image_size_str = '';
    
    /**
     * If set to TRUE, any spaces in the file name will be converted to underscores. This is recommended.
     * 
     * @var boolean
     */
    private $remove_spaces = TRUE;
    
    /**
     * If a file_name was provided in the config, use it instead of the user input
     * supplied file name for all uploads until initialized again
     *
     * @var string
     */
    private $file_name_override = '';
    
    /**
     * All the pre defined error messages
     * 
     * @var array
     */
    private $error_messages = array(
        'upload_userfile_not_set' => 'Unable to find a post variable called userfile.',
        'upload_file_exceeds_limit' => 'The uploaded file exceeds the maximum allowed size in your PHP configuration file.',
        'upload_file_exceeds_form_limit' => 'The uploaded file exceeds the maximum size allowed by the submission form.',
        'upload_file_partial' => 'The file was only partially uploaded.',
        'upload_no_temp_directory' => 'The temporary folder is missing.',
        'upload_unable_to_write_file' => 'The file could not be written to disk.',
        'upload_stopped_by_extension' => 'The file upload was stopped by extension.',
        'upload_no_file_selected' => 'You did not select a file to upload.',
        'upload_invalid_filetype' => 'The filetype you are attempting to upload is not allowed.',
        'upload_invalid_filesize' => 'The file you are attempting to upload is larger than the permitted size.',
        'upload_invalid_dimensions' => 'The image you are attempting to upload exceedes the maximum height or width.',
        'upload_destination_error' => 'A problem was encountered while attempting to move the uploaded file to the final destination.',
        'upload_no_filepath' => 'The upload path does not appear to be valid.',
        'upload_no_file_types' => 'You have not specified any allowed file types.',
        'upload_bad_filename' => 'The file name you submitted already exists on the server.',
        'upload_not_writable' => 'The upload destination folder does not appear to be writable.',
    );
    
    /**
     * The errors caputered to display
     * 
     * @var array
     */
    private $error_msg_to_display= array();
    
    
    /**
     * Constructor
     *
     * The constructor can be passed an array of config values
     */
    public function __construct(array $config = NULL) {
        if (count($config) > 0) {
            foreach ($config as $key => $val) {
                // Using isset() requires $this->$key not to be NULL in property definition
                // property_exists() allows empty property
                if (property_exists($this, $key)) {
                    $method = 'initialize_'.$key;
                    
                    if (method_exists($this, $method)) {
                        $this->$method($val);
                    }
                } else {
                    exit("'".$key."' is not an acceptable config argument!");
                }
            }
        }
        
        // If a file_name was provided in the config, use it instead of the user input
        // supplied file name for all uploads until initialized again
        $this->file_name_override = $this->file_name;
    }
    
    /**
     * Validate and set $files
     *
     * @param  $val array
     * @return    void
     */
    private function initialize_files($val) {
        if ( ! is_array($val)) {
            $this->invalid_argument_value('files');
        }
        $this->files = $val;
    }
    
    /**
     * Validate and set $allowed_types
     *
     * @param  $val array
     * @return    void
     */
    private function initialize_allowed_types($val) {
        if ( ! is_array($val)) {
            $this->invalid_argument_value('allowed_types');
        }
        $this->allowed_types = $val;
    }
    
    /**
     * Validate and set $upload_path
     *
     * @param  $val string
     * @return    void
     */
    private function initialize_upload_path($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('upload_path');
        }
        $this->upload_path = $val;
    }
    
    /**
     * Validate and set $max_size
     *
     * @param  $val int
     * @return    void
     */
    private function initialize_max_size($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('max_size');
        }
        $this->max_size = $val;
    }
    
    /**
     * Validate and set $max_width
     *
     * @param  $val int
     * @return    void
     */
    private function initialize_max_width($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('max_width');
        }
        $this->max_width = $val;
    }
    
    /**
     * Validate and set $max_height
     *
     * @param  $val int
     * @return    void
     */
    private function initialize_max_height($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('max_height');
        }
        $this->max_height = $val;
    }
    
    /**
     * Validate and set $file_name
     *
     * @param  $val string
     * @return    void
     */
    private function initialize_file_name($val) {
        if ( ! is_string($val)) {
            $this->invalid_argument_value('file_name');
        }
        $this->file_name = $val;
    }
    
    /**
     * Validate and set $overwrite
     *
     * @param  $val bool
     * @return    void
     */
    private function initialize_overwrite($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('overwrite');
        }
        $this->overwrite = $val;
    }
    
    /**
     * Validate and set $max_filename
     *
     * @param  $val int
     * @return    void
     */
    private function initialize_max_filename($val) {
        if ( ! is_int($val)) {
            $this->invalid_argument_value('max_filename');
        }
        $this->max_filename = $val;
    }
    
    /**
     * Validate and set $encrypt_name
     *
     * @param  $val bool
     * @return    void
     */
    private function initialize_encrypt_name($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('encrypt_name');
        }
        $this->encrypt_name = $val;
    }
    
    /**
     * Validate and set $remove_spaces
     *
     * @param  $val bool
     * @return    void
     */
    private function initialize_remove_spaces($val) {
        if ( ! is_bool($val)) {
            $this->invalid_argument_value('remove_spaces');
        }
        $this->remove_spaces = $val;
    }
    
    /**
     * Output the error message for invalid argument value
     *
     * @return void
     */
    private function invalid_argument_value($arg) {
        exit("In your config array, the provided argument value of "."'".$arg."'"." is invalid.");
    }
    
    /**
     * Perform the file upload
     *
     * By default the upload routine expects the file to come from a form field called 'userfile', 
     * and the form must be a multipart type
     * 
     * @return    bool
     */    
    public function run($field = 'userfile') {
        // Is $this->files[$field] set? If not, no reason to continue.
        if ( ! isset($this->files[$field])) {
            $this->set_error('upload_no_file_selected');
            return FALSE;
        }
        
        // Is the upload path valid?
        if ( ! $this->validate_upload_path()) {
            // errors will already be set by validate_upload_path() so just return FALSE
            return FALSE;
        }
        
        // Was the file able to be uploaded? If not, determine the reason why.
        if ( ! is_uploaded_file($this->files[$field]['tmp_name'])) {
            $error = ( ! isset($this->files[$field]['error'])) ? 4 : $this->files[$field]['error'];
            
            switch($error) {
                case 1:    // UPLOAD_ERR_INI_SIZE
                    $this->set_error('upload_file_exceeds_limit');
                    break;
                
                case 2: // UPLOAD_ERR_FORM_SIZE
                    $this->set_error('upload_file_exceeds_form_limit');
                    break;
                
                case 3: // UPLOAD_ERR_PARTIAL
                   $this->set_error('upload_file_partial');
                    break;
                
                case 4: // UPLOAD_ERR_NO_FILE
                   $this->set_error('upload_no_file_selected');
                    break;
                
                case 6: // UPLOAD_ERR_NO_TMP_DIR
                    $this->set_error('upload_no_temp_directory');
                    break;
                
                case 7: // UPLOAD_ERR_CANT_WRITE
                    $this->set_error('upload_unable_to_write_file');
                    break;
                
                case 8: // UPLOAD_ERR_EXTENSION
                    $this->set_error('upload_stopped_by_extension');
                    break;
                
                default : $this->set_error('upload_no_file_selected');
                    break;
            }
            
            return FALSE;
        }
        
        // Set the uploaded data as class variables
        $this->file_temp = $this->files[$field]['tmp_name'];
        $this->file_size = $this->files[$field]['size'];            
        $this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $this->files[$field]['type']);
        $this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
        $this->file_name = $this->prep_filename($this->files[$field]['name']);
        $this->file_ext     = $this->get_extension($this->file_name);
        
        // Is the file type allowed to be uploaded?
        if ( ! $this->is_allowed_filetype()) {
            $this->set_error('upload_invalid_filetype');
            return FALSE;
        }
        
        // if we're overriding, let's now make sure the new name and type is allowed
        if ($this->file_name_override !== '') {
            $this->file_name = $this->prep_filename($this->file_name_override);
            
            // If no extension was provided in the file_name config item, use the uploaded one
            if(strpos($this->file_name_override, '.') === FALSE) {
                $this->file_name .= $this->file_ext;
            } else {
                // An extension was provided, lets have it!
                $this->file_ext = $this->get_extension($this->file_name_override);
            }
            
            if ( ! $this->is_allowed_filetype(TRUE)) {
                $this->set_error('upload_invalid_filetype');
                return FALSE;
            }
        }
        
        // Convert the file size to kilobytes
        if ($this->file_size > 0) {
            $this->file_size = round($this->file_size/1024, 2);
        }
        
        // Is the file size within the allowed maximum?
        if ( ! $this->is_allowed_filesize()) {
            $this->set_error('upload_invalid_filesize');
            return FALSE;
        }
        
        // Are the image dimensions within the allowed size?
        // Note: This can fail if the server has an open_basdir restriction.
        if ( ! $this->is_allowed_dimensions()) {
            $this->set_error('upload_invalid_dimensions');
            return FALSE;
        }
        
        // Sanitize the file name for security
        $this->file_name = $this->clean_file_name($this->file_name);
        
        // Truncate the file name if it's too long
        if ($this->max_filename > 0) {
            $this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
        }
        
        // Remove white spaces in the name
        if ($this->remove_spaces === TRUE) {
            $this->file_name = preg_replace("/\s+/", "_", $this->file_name);
        }
        
        /*
         * Validate the file name
         * This function appends an number onto the end of
         * the file if one with the same name already exists.
         * If it returns false there was a problem.
         */
        $this->orig_name = $this->file_name;
        
        if ($this->overwrite === FALSE) {
            $this->file_name = $this->set_filename($this->upload_path, $this->file_name);
            
            if ($this->file_name === FALSE) {
                return FALSE;
            }
        }
        
        /*
         * Move the file to the final destination
         * To deal with different server configurations
         * we'll attempt to use copy() first.  If that fails
         * we'll use move_uploaded_file().  One of the two should
         * reliably work in most environments
         */
        if ( ! @copy($this->file_temp, $this->upload_path.$this->file_name)) {
            if ( ! @move_uploaded_file($this->file_temp, $this->upload_path.$this->file_name)) {
                 $this->set_error('upload_destination_error');
                 return FALSE;
            }
        }
        
        /*
         * Set the finalized image dimensions
         * This sets the image width/height (assuming the
         * file was an image).  We use this information
         * in the "data" function.
         */
        $this->set_image_properties($this->upload_path.$this->file_name);
        
        return TRUE;
    }
    
    /**
     * Finalized Data Array
     *    
     * Returns an associative array containing all of the information
     * related to the upload, allowing the developer easy access in one array.
     *
     * @return    array
     */    
    public function data() {
        return array (
            'file_name' => $this->file_name,
            'file_type' => $this->file_type,
            'file_path' => $this->upload_path,
            'full_path' => $this->upload_path.$this->file_name,
            'raw_name' => str_replace($this->file_ext, '', $this->file_name),
            'orig_name' => $this->orig_name,
            'file_ext' => $this->file_ext,
            'file_size' => $this->file_size,
            'is_image' => $this->is_image(),
            'image_width' => $this->image_width,
            'image_height' => $this->image_height,
            'image_type' => $this->image_type,
            'image_size_str' => $this->image_size_str,
        );
    }
    
    /**
     * Set the file name
     *
     * This function takes a filename/path as input and looks for the
     * existence of a file with the same name. If found, it will append a
     * number to the end of the filename to avoid overwriting a pre-existing file.
     *
     * @param    string
     * @param    string
     * @return    string
     */    
    private function set_filename($path, $filename) {
        if ($this->encrypt_name === TRUE) {        
            mt_srand();
            $filename = md5(uniqid(mt_rand())).$this->file_ext;    
        }
    
        if ( ! file_exists($path.$filename)) {
            return $filename;
        }
    
        $filename = str_replace($this->file_ext, '', $filename);
        
        $new_filename = '';
        for ($i = 1; $i < 100; $i++) {            
            if ( ! file_exists($path.$filename.$i.$this->file_ext)) {
                $new_filename = $filename.$i.$this->file_ext;
                break;
            }
        }
        
        if ($new_filename === '') {
            $this->set_error('upload_bad_filename');
            return FALSE;
        } else {
            return $new_filename;
        }
    }
    
    /**
     * Set Image Properties
     *
     * Uses GD to determine the width/height/type of image
     *
     * @param    string
     * @return    void
     */    
    private function set_image_properties($path = '') {
        if ( ! $this->is_image()) {
            return;
        }
        
        if (function_exists('getimagesize')) {
            if (FALSE !== ($dimension = @getimagesize($path))) {    
                $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
                
                $this->image_width = $dimension['0'];
                $this->image_height = $dimension['1'];
                $this->image_type = ( ! isset($types[$dimension['2']])) ? 'unknown' : $types[$dimension['2']];
                $this->image_size_str = $dimension['3'];  // string containing height and width
            }
        }
    }
    
    /**
     * Validate the image
     *
     * @return    bool
     */    
    private function is_image() {
        // IE will sometimes return odd mime-types during upload, so here we just standardize all
        // jpegs or pngs to the same file type.
        
        $png_mimes = array('image/x-png');
        $jpeg_mimes = array('image/jpg', 'image/jpe', 'image/jpeg', 'image/pjpeg');
        
        if (in_array($this->file_type, $png_mimes)) {
            $this->file_type = 'image/png';
        }
        
        if (in_array($this->file_type, $jpeg_mimes)) {
            $this->file_type = 'image/jpeg';
        }
        
        $img_mimes = array(
            'image/gif',
            'image/jpeg',
            'image/png',
        );
        
        return (in_array($this->file_type, $img_mimes, TRUE)) ? TRUE : FALSE;
    }
    
    /**
     * Verify that the filetype is allowed
     * Different extensions could have the same filetype, like .rtf and .doc
     *
     * @return    bool
     */    
    private function is_allowed_filetype($ignore_mime = FALSE) {
        if ($this->allowed_types === '*') {
            return TRUE;
        }
        
        if (count($this->allowed_types) === 0 || ! is_array($this->allowed_types)) {
            $this->set_error('upload_no_file_types');
            return FALSE;
        }
        
        $ext = strtolower(ltrim($this->file_ext, '.'));
        
        if ( ! in_array($ext, $this->allowed_types)) {
            return FALSE;
        }
        
        // Images get some additional checks
        $image_types = array('gif', 'jpg', 'jpeg', 'png', 'jpe');
        
        if (in_array($ext, $image_types)) {
            if (getimagesize($this->file_temp) === FALSE) {
                return FALSE;
            }
        }
        
        if ($ignore_mime === TRUE) {
            return TRUE;
        }
        
        $mime = $this->mimes_types($ext);
        
        if (is_array($mime)) {
            if (in_array($this->file_type, $mime, TRUE)) {
                return TRUE;
            }
        } elseif ($mime === $this->file_type) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Verify that the file is within the allowed size
     *
     * @return    bool
     */    
    private function is_allowed_filesize() {
        if ($this->max_size !== 0  &&  $this->file_size > $this->max_size) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    /**
     * Verify that the image is within the allowed width/height
     *
     * @return    bool
     */    
    private function is_allowed_dimensions() {
        if ( ! $this->is_image()) {
            return TRUE;
        }
        
        if (function_exists('getimagesize')) {
            $dimensions = @getimagesize($this->file_temp);
            
            if ($this->max_width > 0 && $dimensions['0'] > $this->max_width) {
                return FALSE;
            }
            
            if ($this->max_height > 0 && $dimensions['1'] > $this->max_height) {
                return FALSE;
            }
            
            return TRUE;
        }
        
        return TRUE;
    }
    
    /**
     * Validate Upload Path
     *
     * Verifies that it is a valid upload path with proper permissions.
     *
     * @return    bool
     */    
    private function validate_upload_path() {
        if ($this->upload_path === '') {
            $this->set_error('upload_no_filepath');
            return FALSE;
        }
        
        if (function_exists('realpath') && @realpath($this->upload_path) !== FALSE) {
            $this->upload_path = str_replace("\\", "/", realpath($this->upload_path));
        }
        
        if ( ! @is_dir($this->upload_path)) {
            $this->set_error('upload_no_filepath');
            return FALSE;
        }
        
        if ( ! is_writable($this->upload_path)) {
            $this->set_error('upload_not_writable');
            return FALSE;
        }
        
        $this->upload_path = preg_replace("/(.+?)\/*$/", "\\1/",  $this->upload_path);
        return TRUE;
    }
    
    /**
     * Extract the file extension
     *
     * @param    string
     * @return    string
     */    
    private function get_extension($filename) {
        $x = explode('.', $filename);
        return '.'.end($x);
    }    
    
    /**
     * Clean the file name for security
     *
     * @param    string
     * @return    string
     */        
    private function clean_file_name($filename) {
        $bad = array(
            "<!--",
            "-->",
            "'",
            "<",
            ">",
            '"',
            '&',
            '$',
            '=',
            ';',
            '?',
            '/',
            "%20",
            "%22",
            "%3c",        // <
            "%253c",     // <
            "%3e",         // >
            "%0e",         // >
            "%28",         // (
            "%29",         // )
            "%2528",     // (
            "%26",         // &
            "%24",         // $
            "%3f",         // ?
            "%3b",         // ;
            "%3d"        // =
        );
                    
        $filename = str_replace($bad, '', $filename);
        
        return stripslashes($filename);
    }
    
    /**
     * Limit the File Name Length
     *
     * @param    string
     * @return    string
     */        
    private function limit_filename_length($filename, $length) {
        if (strlen($filename) < $length) {
            return $filename;
        }
    
        $ext = '';
        if (strpos($filename, '.') !== FALSE) {
            $parts = explode('.', $filename);
            $ext = '.'.array_pop($parts);
            $filename = implode('.', $parts);
        }
    
        return substr($filename, 0, ($length - strlen($ext))).$ext;
    }
    
    /**
     * Set an error message
     *
     * @param    string
     * @return    void
     */    
    private function set_error($msg) {
        $this->error_msg_to_display[] = isset($this->error_messages[$msg]) ? $this->error_messages[$msg] : $msg;
    }
    
    /**
     * Display the error message
     *
     * @param    string
     * @param    string
     * @return    string
     */    
    public function display_errors($open = '<p>', $close = '</p>') {
        $str = '';
        foreach ($this->error_msg_to_display as $val) {
            $str .= $open.$val.$close;
        }
    
        return $str;
    }
    
    /**
     * List of Mime Types
     *
     * @param    string
     * @return    string
     */    
    private function mimes_types($mime) {
        $mimes = array(
            'hqx' => array('application/mac-binhex40', 'application/mac-binhex', 'application/x-binhex40', 'application/x-mac-binhex40'),
            'cpt' => 'application/mac-compactpro',
            'csv' => array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain'),
            'bin' => array('application/macbinary', 'application/mac-binary', 'application/octet-stream', 'application/x-binary', 'application/x-macbinary'),
            'dms' => 'application/octet-stream',
            'lha' => 'application/octet-stream',
            'lzh' => 'application/octet-stream',
            'class' => 'application/octet-stream',
            'psd' => array('application/x-photoshop', 'image/vnd.adobe.photoshop'),
            'so' => 'application/octet-stream',
            'sea' => 'application/octet-stream',
            'dll' => 'application/octet-stream',
            'oda' => 'application/oda',
            'pdf' => array('application/pdf', 'application/force-download', 'application/x-download', 'binary/octet-stream'),
            'ai' => array('application/pdf', 'application/postscript'),
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            'smi' => 'application/smil',
            'smil' => 'application/smil',
            'mif' => 'application/vnd.mif',
            'xls' => array('application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel', 'application/x-ms-excel', 'application/x-excel', 'application/x-dos_ms_excel', 'application/xls', 'application/x-xls', 'application/excel', 'application/download', 'application/vnd.ms-office', 'application/msword'),
            'ppt' => array('application/powerpoint', 'application/vnd.ms-powerpoint'),
            'pptx' => array('application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/x-zip', 'application/zip'),
            'wbxml' => 'application/wbxml',
            'wmlc' => 'application/wmlc',
            'dcr' => 'application/x-director',
            'dir' => 'application/x-director',
            'dxr' => 'application/x-director',
            'dvi' => 'application/x-dvi',
            'gtar' => 'application/x-gtar',
            'gz' => 'application/x-gzip',
            'gzip' => 'application/x-gzip',
            'php' => array('application/x-httpd-php', 'application/php', 'application/x-php', 'text/php', 'text/x-php', 'application/x-httpd-php-source'),
            'phtml' => 'application/x-httpd-php',
            'phps' => 'application/x-httpd-php-source',
            'js' => array('application/x-javascript', 'text/plain'),
            'swf' => 'application/x-shockwave-flash',
            'sit' => 'application/x-stuffit',
            'tar' => 'application/x-tar',
            'tgz' => array('application/x-tar', 'application/x-gzip-compressed'),
            'xhtml' => 'application/xhtml+xml',
            'xht' => 'application/xhtml+xml',
            'zip' => array('application/x-zip', 'application/zip', 'application/x-zip-compressed', 'application/s-compressed', 'multipart/x-zip'),
            'rar' => array('application/x-rar', 'application/rar', 'application/x-rar-compressed'),
            'mid' => 'audio/midi',
            'midi' => 'audio/midi',
            'mpga' => 'audio/mpeg',
            'mp2' => 'audio/mpeg',
            'mp3' => array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
            'aif'  => array('audio/x-aiff', 'audio/aiff'),
            'aiff' => array('audio/x-aiff', 'audio/aiff'),
            'aifc' => 'audio/x-aiff',
            'ram' => 'audio/x-pn-realaudio',
            'rm' => 'audio/x-pn-realaudio',
            'rpm' => 'audio/x-pn-realaudio-plugin',
            'ra' => 'audio/x-realaudio',
            'rv' => 'video/vnd.rn-realvideo',
            'wav' => array('audio/x-wav', 'audio/wave', 'audio/wav'),
            'bmp' => array('image/bmp', 'image/x-windows-bmp'),
            'gif' => 'image/gif',
            'jpeg' => array('image/jpeg', 'image/pjpeg'),
            'jpg' => array('image/jpeg', 'image/pjpeg'),
            'jpe' => array('image/jpeg', 'image/pjpeg'),
            'png' => array('image/png',  'image/x-png'),
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'css' => array('text/css', 'text/plain'),
            'html' => array('text/html', 'text/plain'),
            'htm' => array('text/html', 'text/plain'),
            'shtml' => array('text/html', 'text/plain'),
            'txt' => 'text/plain',
            'text' => 'text/plain',
            'log' => array('text/plain', 'text/x-log'),
            'rtx' => 'text/richtext',
            'rtf' => 'text/rtf',
            'xml' => array('application/xml', 'text/xml', 'text/plain'),
            'xsl' => array('application/xml', 'text/xsl', 'text/xml'),
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mpe' => 'video/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'avi' => array('video/x-msvideo', 'video/msvideo', 'video/avi', 'application/x-troff-msvideo'),
            'movie' => 'video/x-sgi-movie',
            'doc' => array('application/msword', 'application/vnd.ms-office'),
            'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword', 'application/x-zip'),
            'dot' => array('application/msword', 'application/vnd.ms-office'),
            'dotx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/msword'),
            'xlsx' => array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'application/vnd.ms-excel', 'application/msword', 'application/x-zip'),
            'word' => array('application/msword', 'application/octet-stream'),
            'xl' => 'application/excel',
            'eml' => 'message/rfc822',
            'json' => array('application/json', 'text/json'),
            'pem' => array('application/x-x509-user-cert', 'application/x-pem-file', 'application/octet-stream'),
            'p10' => array('application/x-pkcs10', 'application/pkcs10'),
            'p12' => 'application/x-pkcs12',
            'p7a' => 'application/x-pkcs7-signature',
            'p7c' => array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
            'p7m' => array('application/pkcs7-mime', 'application/x-pkcs7-mime'),
            'p7r' => 'application/x-pkcs7-certreqresp',
            'p7s' => 'application/pkcs7-signature',
            'crt' => array('application/x-x509-ca-cert', 'application/x-x509-user-cert', 'application/pkix-cert'),
            'crl' => array('application/pkix-crl', 'application/pkcs-crl'),
            'der' => 'application/x-x509-ca-cert',
            'kdb' => 'application/octet-stream',
            'pgp' => 'application/pgp',
            'gpg' => 'application/gpg-keys',
            'sst' => 'application/octet-stream',
            'csr' => 'application/octet-stream',
            'rsa' => 'application/x-pkcs7',
            'cer' => array('application/pkix-cert', 'application/x-x509-ca-cert'),
            '3g2' => 'video/3gpp2',
            '3gp' => 'video/3gp',
            'mp4' => 'video/mp4',
            'm4a' => 'audio/x-m4a',
            'f4v' => 'video/mp4',
            'webm' => 'video/webm',
            'aac' => 'audio/x-acc',
            'm4u' => 'application/vnd.mpegurl',
            'm3u' => 'text/plain',
            'xspf' => 'application/xspf+xml',
            'vlc' => 'application/videolan',
            'wmv' => array('video/x-ms-wmv', 'video/x-ms-asf'),
            'au' => 'audio/x-au',
            'ac3' => 'audio/ac3',
            'flac' => 'audio/x-flac',
            'ogg' => 'audio/ogg',
            'kmz' => array('application/vnd.google-earth.kmz', 'application/zip', 'application/x-zip'),
            'kml' => array('application/vnd.google-earth.kml+xml', 'application/xml', 'text/xml'),
            'ics' => 'text/calendar',
            'zsh' => 'text/x-scriptzsh',
            '7zip' => array('application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip'),
            'cdr' => array('application/cdr', 'application/coreldraw', 'application/x-cdr', 'application/x-coreldraw', 'image/cdr', 'image/x-cdr', 'zz-application/zz-winassoc-cdr'),
            'wma' => array('audio/x-ms-wma', 'video/x-ms-asf'),
            'jar' => array('application/java-archive', 'application/x-java-application', 'application/x-jar', 'application/x-compressed')
        );
        
        return ( ! isset($mimes[$mime])) ? FALSE : $mimes[$mime];
    }
    
    /**
     * Prep Filename
     *
     * Prevents possible script execution from Apache's handling of files multiple extensions
     * http://httpd.apache.org/docs/1.3/mod/mod_mime.html#multipleext
     *
     * @param    string
     * @return    string
     */
    private function prep_filename($filename) {
        if (strpos($filename, '.') === FALSE) {
            return $filename;
        }
        
        $parts = explode('.', $filename);
        $ext = array_pop($parts);
        $filename = array_shift($parts);
        
        foreach ($parts as $part) {
            if ($this->mimes_types(strtolower($part)) === FALSE) {
                $filename .= '.'.$part.'_';
            } else {
                $filename .= '.'.$part;
            }
        }
        
        // file name override, since the exact name is provided, no need to
        // run it through a $this->mimes check.
        if ($this->file_name !== '') {
            $filename = $this->file_name;
        }
        
        $filename .= '.'.$ext;
        
        return $filename;
    }
    
}

/* End of file: ./system/libraries/upload/upload_library.php */