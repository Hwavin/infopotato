<?php
/**
 * A lightweight approach to ACL 
 *
 * http://debuggable.com/posts/a-lightweight-approach-to-acl-the-33-lines-of-magic:480f4dd6-639c-44f4-a62a-49a8cbdd56cb
 * 
 * @return boolean
 */    
function allow_request($controller, $action, $rules, $default = FALSE) {
	// The default value to return if no rule matching $controller/$action can be found
	$allowed = $default;
	
	// This Regex converts a string of rules like "controllerA:actionA, controllerB:actionB,..." into the array $matches.
	preg_match_all('/([^:,]+):([^,:]+)/is', $rules, $matches, PREG_SET_ORDER);
	foreach ($matches as $match) {
		list($raw_match, $allowed_controller, $allowed_action) = $match;
		
		$allowed_controller = str_replace('*', '.*', $allowed_controller);
		$allowed_action = str_replace('*', '.*', $allowed_action);
		
		if (substr($allowed_controller, 0, 1) == '!') {
			$allowed_controller = substr($allowed_controller, 1);
			$negative_condition = TRUE;
		} else {
			$negative_condition = FALSE;
		}
		
		if (preg_match('/^'.$allowed_controller.'$/i', $controller) && preg_match('/^'.$allowed_action.'$/i', $action)) {
			if ($negative_condition) {
				$allowed = FALSE;
			} else {
				$allowed = TRUE;
			}
		}
	}        
	return $allowed;
}

// End of file: ./system/core/scripts/allow_request_script.php