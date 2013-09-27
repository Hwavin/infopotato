<?php
 class Dispatcher{ private function __construct() {} public static function run($manager = NULL, $method = NULL) { if (isset($manager) && is_string($manager) && ! empty($manager)) { $manager = strtolower($manager).'_manager'; if (isset($method) && is_string($method) && ! empty($method)) { $method = 'get_'.strtolower($method); } else { $method = 'get_'.strtolower(APP_DEFAULT_MANAGER_METHOD); } $manager_obj = new $manager; $manager_obj->{$method}(); } else { $request_method = (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') ? 'post' : 'get'; if (isset($_SERVER['PATH_INFO'])) { $request_uri = trim($_SERVER['PATH_INFO'], '/'); } elseif (isset($_SERVER['ORIG_PATH_INFO'])) { $request_uri = trim(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['ORIG_PATH_INFO']), '/'); } else { $request_uri = ''; } $uri_segments = ! empty($request_uri) ? explode('/', $request_uri) : array(); $manager_name = ! empty($uri_segments[0]) ? strtolower($uri_segments[0]) : strtolower(APP_DEFAULT_MANAGER); $method_name = ! empty($uri_segments[1]) ? strtolower($uri_segments[1]) : strtolower(APP_DEFAULT_MANAGER_METHOD); $real_method = $request_method.'_'.$method_name; $params_cnt = count($uri_segments); $params = array(); for ($i = 2; $i < $params_cnt; $i++) { $params[] = $uri_segments[$i]; } $manager_class = $manager_name.'_manager'; $manager_obj = new $manager_class; if (isset($_POST)) { $manager_obj->_POST_DATA = self::sanitize($_POST); unset($_POST); } if (isset($_FILES)) { $manager_obj->_FILES_DATA = self::sanitize($_FILES); unset($_FILES); } $_COOKIE = isset($_COOKIE) ? self::sanitize($_COOKIE) : array(); if ( ! method_exists($manager_obj, $real_method)) { halt('An Error Was Encountered', "The requested manager method '{$real_method}' does not exist in object '{$manager_class}'", 'sys_error'); } $manager_obj->{$real_method}($params); } } private static function sanitize($str) { if (is_array($str)) { foreach ($str as $key => $val) { $str[$key] = self::sanitize($val); } } if (is_string($str)) { if (version_compare(PHP_VERSION, '5.4.0', '<')) { if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) { $str = stripslashes($str); } } preg_replace('/(?:\r\n|[\r\n])/', PHP_EOL, $str); } return $str; } } 