<?php
 final class Dispatcher { private function __construct() { } public static function dispatch() { $request_method = (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') ? 'post' : 'get'; $request_uri = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : ''; $uri_segments = ! empty($request_uri) ? explode('/', $request_uri) : NULL; $manager_name = ! empty($uri_segments[0]) ? strtolower($uri_segments[0]) : strtolower(APP_DEFAULT_MANAGER); $method_name = ! empty($uri_segments[1]) ? strtolower($uri_segments[1]) : strtolower(APP_DEFAULT_MANAGER_METHOD); $real_method = $request_method.'_'.$method_name; $params_cnt = count($uri_segments); $params = array(); for ($i = 2; $i < $params_cnt; $i++) { $params[] = $uri_segments[$i]; } $manager_class = $manager_name.'_manager'; $manager_obj = new $manager_class; if ( ! method_exists($manager_obj, $real_method)) { halt('An Error Was Encountered', "The requested manager method '{$real_method}' does not exist in object '{$manager_class}'", 'sys_error'); } $_POST = isset($_POST) ? sanitize($_POST) : array(); $_COOKIE = isset($_COOKIE) ? sanitize($_COOKIE) : array(); $manager_obj->{$real_method}($params); } } 