<?php
 class Dispatcher { private function __construct() { } public static function dispatch() { $request_method = (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') ? 'post' : 'get'; $request_uri = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : ''; $uri_segments = ! empty($request_uri) ? explode('/', $request_uri) : NULL; $manager_name = ! empty($uri_segments[0]) ? strtolower($uri_segments[0]) : strtolower(APP_DEFAULT_MANAGER); $method_name = ! empty($uri_segments[1]) ? strtolower($uri_segments[1]) : strtolower(APP_DEFAULT_MANAGER_METHOD); $real_method = $request_method.'_'.$method_name; $params_cnt = count($uri_segments); $params = array(); for ($i = 2; $i < $params_cnt; $i++) { $params[] = $uri_segments[$i]; } $manager_file = APP_MANAGER_DIR.$manager_name.'_manager.php'; if ( ! file_exists($manager_file)) { show_sys_error('An Error Was Encountered', 'Manager file does not exist', 'sys_error'); } require_once $manager_file; $manager_class = $manager_name.'_manager'; if ( ! class_exists($manager_class)) { show_sys_error('An Error Was Encountered', 'Manager class does not exist', 'sys_error'); } $manager_obj = new $manager_class; if ( ! method_exists($manager_obj, $real_method)) { show_sys_error('An Error Was Encountered', "The requested manager method '{$real_method}' does not exist in object '{$manager_class}'", 'sys_error'); } $manager_obj->target_method = $real_method; $manager_obj->target_method_params = $params; $manager_obj->{$real_method}($params); } } 