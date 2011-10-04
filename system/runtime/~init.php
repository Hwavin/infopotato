<?php
 switch (ENVIRONMENT) { case 'development': error_reporting(E_ALL | E_STRICT); break; case 'production': error_reporting(0); break; default: exit('The application environment is not set correctly.'); } function auto_load($class_name) { $class_name = strtolower($class_name); $runtime_list = array( 'dispatcher', 'manager', 'dumper', 'utf8', 'i18n', 'cookie', 'session', 'data', 'base_dao', 'mysql_dao', 'mysqli_dao', 'postgresql_dao', 'sqlite_dao' ); if (in_array($class_name, $runtime_list)) { if (SYS_RUNTIME_CACHE === TRUE) { $file = SYS_RUNTIME_DIR.'~'.$class_name.'.php'; if ( ! file_exists($file)) { file_put_contents($file, php_strip_whitespace(SYS_CORE_DIR.$class_name.'.php')); } } else { $file = SYS_CORE_DIR.$class_name.'.php'; } } else { $file = APP_MANAGER_DIR.$class_name.'.php'; } require $file; } spl_autoload_register('auto_load'); function halt($heading, $message, $template = 'sys_error') { if (ENVIRONMENT === 'development') { ob_start(); require_once SYS_CORE_DIR.'sys_templates'.DS.$template.'.php'; $buffer = ob_get_contents(); ob_end_clean(); echo $buffer; exit; } } function dump($var, $force_type = '', $collapsed = FALSE) { Dumper::dump($var, $force_type, $collapsed); } function __($string, array $values = array()) { $string = I18n::get($string); return empty($values) ? $string : strtr($string, $values); } function sanitize($value) { if (is_array($value)) { foreach ($value as $key => $val) { $value[$key] = sanitize($val); } } if (is_string($value)) { if (get_magic_quotes_gpc()) { $value = stripslashes($value); } if (strpos($value, "\r") !== FALSE) { $value = str_replace(array("\r\n", "\r"), "\n", $value); } } return $value; } 