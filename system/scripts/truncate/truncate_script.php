<?php
/**
 * Truncate a string to a certain length if necessary, optionally splitting in the middle of a word,
 * and appending the $etc string or inserting $etc into the middle.          
 * 
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php truncate (Smarty online manual)
 * @param string $string input string
 * @param integer $length lenght of truncated text
 * @param string $etc end string
 * @param boolean $break_words truncate at word boundary
 * @param boolean $middle truncate in the middle of text
 * @return string truncated string
 */
function truncate($string, $length = 80, $etc = '...', $break_words = FALSE, $middle = FALSE) {
    if ($length == 0) {
        return '';
	}
	
    if (is_callable('mb_strlen')) {
        if (mb_strlen($string) > $length) {
            $length -= min($length, mb_strlen($etc));
            if ( ! $break_words && ! $middle) {
                $string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length + 1));
            } 
            if ( ! $middle) {
                return mb_substr($string, 0, $length) . $etc;
            } else {
                return mb_substr($string, 0, $length / 2) . $etc . mb_substr($string, - $length / 2);
            } 
        } else {
            return $string;
        } 
    } else {
        if (strlen($string) > $length) {
            $length -= min($length, strlen($etc));
            if ( ! $break_words && !$middle) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
            } 
            if ( ! $middle) {
                return substr($string, 0, $length) . $etc;
            } else {
                return substr($string, 0, $length / 2) . $etc . substr($string, - $length / 2);
            } 
        } else {
            return $string;
        } 
    } 
} 

/* End of file: ./system/scripts/truncate/truncate_script.php */
