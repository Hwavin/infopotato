<?php
 class Manager { public $target_method = ''; public $target_method_params = array(); protected $POST_DATA = array(); public function __construct() { $this->POST_DATA = $_POST; unset($_POST); } protected function render_template($template, array $template_vars = NULL) { $orig_template = strtolower($template); if (strpos($template, '/')) { $template = str_replace('/', DS, pathinfo($orig_template, PATHINFO_DIRNAME)).DS.substr(strrchr($orig_template, '/'), 1); } $template_file_path = APP_TEMPLATE_DIR.$template.'.php'; ob_start(); if ( ! file_exists($template_file_path)) { halt('A System Error Was Encountered', "Unknown template file name '{$orig_template}'", 'sys_error'); } else { if (is_array($template_vars) && (count($template_vars) > 0)) { extract($template_vars); } require_once $template_file_path; } $content = ob_get_contents(); ob_end_clean(); return $content; } protected function response($config = array()) { if (isset($config['content']) && isset($config['type'])) { $headers = array(); $mime_types = array( 'text/html', 'text/plain', 'text/xml', 'text/css', 'text/javascript', 'application/json', 'text/csv', ); $headers['Content-Type'] = in_array($config['type'], $mime_types) ? $config['type'].'; charset=utf-8' : $config['type']; $is_compressed = FALSE; if (in_array($config['type'], $mime_types)) { $compression_method = self::_get_accepted_compression_method(); $compressed = isset($config['compression_level']) ? self::_compress($config['content'], $compression_method, $config['compression_level']) : self::_compress($config['content'], $compression_method); if ($compressed !== FALSE) { $headers['Vary'] = 'Accept-Encoding'; $headers['Content-Encoding'] = $compression_method[1]; $is_compressed = TRUE; } } if (isset($config['disable_cache']) && $config['disable_cache'] === TRUE) { $headers['Expires'] = 'Mon, 26 Jul 1997 05:00:00 GMT'; $headers['Last-Modified'] = gmdate("D, d M Y H:i:s") . " GMT"; $headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'; $headers['Pragma'] = 'no-cache'; } foreach ($headers as $name => $val) { header($name.': '.$val); } echo ($is_compressed === TRUE) ? $compressed : $config['content']; } } private static function _get_accepted_compression_method($allow_compress = TRUE, $allow_deflate = TRUE) { if ( ! isset($_SERVER['HTTP_ACCEPT_ENCODING'])) { return array('', ''); } $ae = $_SERVER['HTTP_ACCEPT_ENCODING']; if (strpos($ae, 'gzip,') === 0 || strpos($ae, 'deflate, gzip,') === 0) { return array('gzip', 'gzip'); } if (preg_match('@(?:^|,)\\s*((?:x-)?gzip)\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae, $m)) { return array('gzip', $m[1]); } if ($allow_deflate) { $ae_rev = strrev($ae); if (strpos($ae_rev, 'etalfed ,') === 0 || strpos($ae_rev, 'etalfed,') === 0 || strpos($ae, 'deflate,') === 0 || preg_match('@(?:^|,)\\s*deflate\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae)) { return array('deflate', 'deflate'); } } if ($allow_compress && preg_match('@(?:^|,)\\s*((?:x-)?compress)\\s*(?:$|,|;\\s*q=(?:0\\.|1))@', $ae, $m)) { return array('compress', $m[1]); } return array('', ''); } private static function _compress($content, $compression_method = array('', ''), $compression_level = 6) { if ($compression_method[0] === '' || ($compression_level == 0) || ! extension_loaded('zlib')) { return FALSE; } if ($compression_method[0] === 'deflate') { $compressed = gzdeflate($content, $compression_level); } elseif ($compression_method[0] === 'gzip') { $compressed = gzencode($content, $compression_level); } else { $compressed = gzcompress($content, $compression_level); } if ($compressed === FALSE) { return FALSE; } return $compressed; } protected function load_data($data, $alias = '') { $data = strtolower($data); $orig_data = $data; if (strpos($data, '/') === FALSE) { $path = ''; } else { $path = str_replace('/', DS, pathinfo($data, PATHINFO_DIRNAME)).DS; $data = substr(strrchr($data, '/'), 1); } if ($alias === '') { $alias = $data; } if (method_exists($this, $alias)) { halt('A System Error Was Encountered', "Data name '{$alias}' is an invalid (reserved) name", 'sys_error'); } if (isset($this->$alias)) { return TRUE; } $file_path = APP_DATA_DIR.$path.$data.'.php'; if ( ! file_exists($file_path)) { halt('A System Error Was Encountered', "Unknown data file name '{$orig_data}'", 'sys_error'); } require_once $file_path; if ( ! class_exists($data)) { halt('A System Error Was Encountered', "Unknown class name '{$data}'", 'sys_error'); } $this->{$alias} = new $data; return TRUE; } protected function load_library($scope, $library, $alias = '', $config = array()) { $library = strtolower($library); $orig_library = $library; if (strpos($library, '/') === FALSE) { $path = ''; } else { $path = str_replace('/', DS, pathinfo($library, PATHINFO_DIRNAME)).DS; $library = substr(strrchr($library, '/'), 1); } if ($alias === '') { $alias = $library; } if (method_exists($this, $alias)) { halt('A System Error Was Encountered', "Library name '{$alias}' is an invalid (reserved) name", 'sys_error'); } if (isset($this->$alias)) { return TRUE; } if ($scope === 'SYS') { $file_path = SYS_LIBRARY_DIR.$path.$library.'.php'; } elseif ($scope === 'APP') { $file_path = APP_LIBRARY_DIR.$path.$library.'.php'; } else { halt('A System Error Was Encountered', "The location of the library must be specified, either 'SYS' or 'APP'", 'sys_error'); } $file_path = SYS_LIBRARY_DIR.$path.$library.'.php'; if ( ! file_exists($file_path)) { halt('A System Error Was Encountered', "Unknown library file name '{$orig_library}'", 'sys_error'); } require_once $file_path; if ( ! class_exists($library)) { halt('A System Error Was Encountered', "Unknown class name '{$library}'", 'sys_error'); } $this->{$alias} = new $library($config); return TRUE; } protected function load_function($scope, $func) { $orig_func = strtolower($func); if (strpos($func, '/') === FALSE) { $path = ''; } else { $path = str_replace('/', DS, pathinfo($func, PATHINFO_DIRNAME)).DS; $func = substr(strrchr($func, '/'), 1); } if ($scope === 'SYS') { $file_path = SYS_FUNCTION_DIR.$path.$func.'.php'; } elseif ($scope === 'APP') { $file_path = APP_FUNCTION_DIR.$path.$func.'.php'; } else { halt('A System Error Was Encountered', "The location of the functions folder must be specified, either 'SYS' or 'APP'", 'sys_error'); } if ( ! file_exists($file_path)) { halt('An Error Was Encountered', "Unknown function script '{$orig_func}'", 'sys_error'); } require_once $file_path; } public function __call($method, $args) { halt('A System Error Was Encountered', "Unknown class method '{$method}'", 'sys_error'); } } 