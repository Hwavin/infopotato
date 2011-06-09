<?php
/**
 * Remove image tags from a string.
 *
 *     $str = Security::strip_image_tags($str);
 *
 * @param   string  string to sanitize
 * @return  string
 */
function strip_image_tags($str) {
	return preg_replace('#<img\s.*?(?:src\s*=\s*["\']?([^"\'<>\s]*)["\']?[^>]*)?>#is', '$1', $str);
}

/* End of file: ./system/functions/strip_image_tags/strip_image_tags_function.php */