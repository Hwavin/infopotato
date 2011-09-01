<?php
 class Session { private static $_normal_timespan = NULL; private static $_open = FALSE; private static $_persistent_timespan = NULL; private static $_regenerated = FALSE; private function __construct() { } public static function add($key, $value, $beginning = FALSE) { self::open(); $tip =& $_SESSION; if ($bracket_pos = strpos($key, '[')) { $original_key = $key; $array_dereference = substr($key, $bracket_pos); $key = substr($key, 0, $bracket_pos); preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER); $array_keys = array_map('current', $array_keys); array_unshift($array_keys, $key); foreach (array_slice($array_keys, 0, -1) as $array_key) { if ( ! isset($tip[$array_key])) { $tip[$array_key] = array(); } elseif ( ! is_array($tip[$array_key])) { halt('A System Error Was Encountered', "Session::add() was called for the key, {$original_key}, which is not an array", 'sys_error'); } $tip =& $tip[$array_key]; } $key = end($array_keys); } if ( ! isset($tip[$key])) { $tip[$key] = array(); } elseif ( ! is_array($tip[$key])) { halt('A System Error Was Encountered', "Session::add() was called for the key, {$key}, which is not an array", 'sys_error'); } if ($beginning) { array_unshift($tip[$key], $value); } else { $tip[$key][] = $value; } } public static function clear($prefix = NULL) { self::open(); $session_type = $_SESSION['SESSION::type']; $session_expires = $_SESSION['SESSION::expires']; if ($prefix) { foreach ($_SESSION as $key => $value) { if (strpos($key, $prefix) === 0) { unset($_SESSION[$key]); } } } else { $_SESSION = array(); } $_SESSION['SESSION::type'] = $session_type; $_SESSION['SESSION::expires'] = $session_expires; } public static function close() { if ( ! self::$_open) { return; } session_write_close(); unset($_SESSION); self::$_open = FALSE; } public static function delete($key, $default_value = NULL) { self::open(); $value = $default_value; if ($bracket_pos = strpos($key, '[')) { $original_key = $key; $array_dereference = substr($key, $bracket_pos); $key = substr($key, 0, $bracket_pos); if ( ! isset($_SESSION[$key])) { return $value; } preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER); $array_keys = array_map('current', $array_keys); $tip =& $_SESSION[$key]; foreach (array_slice($array_keys, 0, -1) as $array_key) { if ( ! isset($tip[$array_key])) { return $value; } elseif ( ! is_array($tip[$array_key])) { halt('A System Error Was Encountered', "Session::delete() was called for an element, {$original_key}, which is not an array", 'sys_error'); } $tip =& $tip[$array_key]; } $key = end($array_keys); } else { $tip =& $_SESSION; } if (isset($tip[$key])) { $value = $tip[$key]; unset($tip[$key]); } return $value; } public static function destroy() { self::open(); $_SESSION = array(); if (isset($_COOKIE[session_name()])) { $params = session_get_cookie_params(); setcookie(session_name(), '', time() - 43200, $params['path'], $params['domain'], $params['secure']); } session_destroy(); self::regenerate_id(); } public static function enable_persistence() { if (self::$_persistent_timespan === NULL) { halt('A System Error Was Encountered', "The method Session::set_length() must be called with the '$_persistent_timespan' parameter before calling Session::enable_persistence()", 'sys_error'); } $current_params = session_get_cookie_params(); $params = array( self::$_persistent_timespan, $current_params['path'], $current_params['domain'], $current_params['secure'] ); call_user_func_array('session_set_cookie_params', $params); self::open(); $_SESSION['SESSION::type'] = 'persistent'; session_regenerate_id(); self::$_regenerated = TRUE; } public static function get($key, $default_value = NULL) { self::open(); $array_dereference = NULL; if ($bracket_pos = strpos($key, '[')) { $array_dereference = substr($key, $bracket_pos); $key = substr($key, 0, $bracket_pos); } if ( ! isset($_SESSION[$key])) { return $default_value; } $value = $_SESSION[$key]; if ($array_dereference) { preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER); $array_keys = array_map('current', $array_keys); foreach ($array_keys as $array_key) { if ( ! is_array($value) || ! isset($value[$array_key])) { $value = $default_value; break; } $value = $value[$array_key]; } } return $value; } public static function ignore_subdomain() { if (self::$_open || isset($_SESSION)) { halt('A System Error Was Encountered', "Session::ignore_subdomain() must be called before any of Session::add(), Session::clear(), Session::enable_persistence(), Session::get(), Session::open(), Session::set(), session_start()", 'sys_error'); } $current_params = session_get_cookie_params(); if (isset($_SERVER['SERVER_NAME'])) { $domain = $_SERVER['SERVER_NAME']; } elseif (isset($_SERVER['HTTP_HOST'])) { $domain = $_SERVER['HTTP_HOST']; } else { halt('A System Error Was Encountered', "The domain name could not be found in ['SERVER_NAME'] or ['HTTP_HOST']. Please set one of these keys to use Session::ignore_subdomain().", 'sys_error'); } $params = array( $current_params['lifetime'], $current_params['path'], preg_replace('#.*?([a-z0-9\\-]+\.[a-z]+)$#iD', '.\1', $domain), $current_params['secure'] ); call_user_func_array('session_set_cookie_params', $params); } public static function open($cookie_only_session_id = TRUE) { if (self::$_open) { return; } self::$_open = TRUE; if (self::$_normal_timespan === NULL) { self::$_normal_timespan = ini_get('session.gc_maxlifetime'); } if ( ! isset($_SESSION)) { if ($cookie_only_session_id) { ini_set('session.use_cookies', 1); ini_set('session.use_only_cookies', 1); } session_start(); } if (isset($_SESSION['SESSION::expires']) && $_SESSION['SESSION::expires'] < $_SERVER['REQUEST_TIME']) { $_SESSION = array(); self::regenerate_id(); } if ( ! isset($_SESSION['SESSION::type'])) { $_SESSION['SESSION::type'] = 'normal'; } if ($_SESSION['SESSION::type'] == 'persistent' && self::$_persistent_timespan) { $_SESSION['SESSION::expires'] = $_SERVER['REQUEST_TIME'] + self::$_persistent_timespan; } else { $_SESSION['SESSION::expires'] = $_SERVER['REQUEST_TIME'] + self::$_normal_timespan; } } public static function regenerate_id() { if ( ! self::$_regenerated){ session_regenerate_id(); self::$_regenerated = TRUE; } } public static function remove($key, $beginning = FALSE) { self::open(); $tip =& $_SESSION; if ($bracket_pos = strpos($key, '[')) { $original_key = $key; $array_dereference = substr($key, $bracket_pos); $key = substr($key, 0, $bracket_pos); preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER); $array_keys = array_map('current', $array_keys); array_unshift($array_keys, $key); foreach (array_slice($array_keys, 0, -1) as $array_key) { if ( ! isset($tip[$array_key])) { return NULL; } elseif ( ! is_array($tip[$array_key])) { halt('A System Error Was Encountered', "Session::remove() was called for the key, {$original_key}, which is not an array", 'sys_error'); } $tip =& $tip[$array_key]; } $key = end($array_keys); } if ( ! isset($tip[$key])) { return NULL; } elseif ( ! is_array($tip[$key])) { halt('A System Error Was Encountered', "Session::remove() was called for the key, {$key}, which is not an array", 'sys_error'); } if ($beginning) { return array_shift($tip[$key]); } return array_pop($tip[$key]); } public static function reset() { self::$_normal_timespan = NULL; self::$_persistent_timespan = NULL; self::$_regenerated = FALSE; self::$destroy(); self::$close(); } public static function set($key, $value) { self::open(); $tip =& $_SESSION; if ($bracket_pos = strpos($key, '[')) { $array_dereference = substr($key, $bracket_pos); $key = substr($key, 0, $bracket_pos); preg_match_all('#(?<=\[)[^\[\]]+(?=\])#', $array_dereference, $array_keys, PREG_SET_ORDER); $array_keys = array_map('current', $array_keys); array_unshift($array_keys, $key); foreach (array_slice($array_keys, 0, -1) as $array_key) { if ( ! isset($tip[$array_key]) || ! is_array($tip[$array_key])) { $tip[$array_key] = array(); } $tip =& $tip[$array_key]; } $tip[end($array_keys)] = $value; } else { $tip[$key] = $value; } } public static function set_length($normal_timespan, $persistent_timespan = NULL) { if (self::$_open || isset($_SESSION)) { halt('A System Error Was Encountered', "Session::set_length() must be called before any of Session::set_path(), Session::add(), Session::clear(), Session::enable_persistence(), Session::get(), Session::open(), Session::set(), session_start()", 'sys_error'); } $seconds = ( ! is_numeric($normal_timespan)) ? strtotime($normal_timespan) - time() : $normal_timespan; self::$_normal_timespan = $seconds; if ($persistent_timespan) { $seconds = ( ! is_numeric($persistent_timespan)) ? strtotime($persistent_timespan) - time() : $persistent_timespan; self::$_persistent_timespan = $seconds; } ini_set('session.gc_maxlifetime', $seconds); } public static function set_path($directory) { if (self::$_open || isset($_SESSION)) { halt('A System Error Was Encountered', "Session::set_path() must be called before any of Session::set_path(), Session::add(), Session::clear(), Session::enable_persistence(), Session::get(), Session::open(), Session::set(), session_start()", 'sys_error'); } if ( ! is_writable($directory)) { } session_save_path($directory); } } 