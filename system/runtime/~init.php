<?php
 switch (ENVIRONMENT) { case 'development': error_reporting(E_ALL | E_STRICT); break; case 'production': error_reporting(0); break; default: exit('The application environment is not set correctly.'); } function auto_load($class_name) { $class_name = strtolower($class_name); $runtime_list = array( 'dispatcher', 'manager', 'dumper', 'utf8', 'i18n', 'cookie', 'session', 'data', 'base_dao', 'mysql_dao', 'mysqli_dao', 'postgresql_dao', 'sqlite_dao' ); if (in_array($class_name, $runtime_list)) { if (SYS_RUNTIME_CACHE === TRUE) { $file = SYS_RUNTIME_DIR.'~'.$class_name.'.php'; if ( ! file_exists($file)) { file_put_contents($file, php_strip_whitespace(SYS_CORE_DIR.$class_name.'.php')); } } else { $file = SYS_CORE_DIR.$class_name.'.php'; } } else { $file = APP_MANAGER_DIR.$class_name.'.php'; if ( ! file_exists($file)) { halt('An Error Was Encountered', 'Manager file does not exist', 'sys_error', 404); } } require $file; } spl_autoload_register('auto_load'); function halt($heading, $message, $template = 'sys_error', $status_code = 404) { if (isset($status_code)) { $stati = array( 400 => 'Bad Request', 401 => 'Authorization Required', 403 => 'Forbidden', 404 => 'Not Found', 500 => 'Internal Server Error', ); if (isset($stati[$status_code])) { $status_text = $stati[$status_code]; } $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE; if (substr(php_sapi_name(), 0, 3) == 'cgi') { header("Status: {$status_code} {$status_text}", TRUE); } elseif ($server_protocol == 'HTTP/1.1' || $server_protocol == 'HTTP/1.0') { header($server_protocol." {$status_code} {$status_text}", TRUE, $status_code); } else { header("HTTP/1.1 {$status_code} {$status_text}", TRUE, $status_code); } } if (ENVIRONMENT === 'development') { ob_start(); require SYS_CORE_DIR.'sys_templates'.DS.$template.'.php'; $buffer = ob_get_contents(); ob_end_clean(); echo $buffer; exit; } } function dump($var, $force_type = '', $collapsed = FALSE) { Dumper::dump($var, $force_type, $collapsed); } function __($string, array $values = array()) { $string = I18n::get($string); return empty($values) ? $string : strtr($string, $values); } function sanitize($value) { if (is_array($value)) { foreach ($value as $key => $val) { $value[$key] = sanitize($val); } } if (is_string($value)) { if (get_magic_quotes_gpc()) { $value = stripslashes($value); } if (strpos($value, "\r") !== FALSE) { $value = str_replace(array("\r\n", "\r"), "\n", $value); } } return $value; } unset($_GET); unset($_ENV); unset($_REQUEST); $_POST = isset($_POST) ? sanitize($_POST) : array(); $_COOKIE = isset($_COOKIE) ? sanitize($_COOKIE) : array(); 