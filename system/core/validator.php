<?php
/**
 * Data Validator
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\core;

class Validator {
    /**
     * If the [http://php.net/mbstring mbstring] extension is available
     * 
     * @var bool
     */
    private static $mbstring_available = FALSE;
    
    /**
     * Prevent direct object creation
     * 
     * @return Validator
     */
    private function __construct() {}
    
    /**
     * Checks to see if the [http://php.net/mbstring mbstring] extension is available
     * 
     * @return void
     */
    private static function check_mbstring() {
        self::$mbstring_available = extension_loaded('mbstring');
        if (self::$mbstring_available) {
            // If string overloading is active, it will break many of the native implementations
            // MB_OVERLOAD_STRING (integer) is a constant defined by the mbstring extension 
            // http://php.net/mbstring.func-overload
            // Possible values are 0,1,2,4 or combination of them.
            // For example, 7 for overload everything.
            // 0: No overload
            // 1: Overload mail() function
            // 2: Overload str*() functions
            // 4: Overload ereg*() functions
            // & is bitwise AND. && is logical AND.
            if (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING) {
                Common::halt('A System Error Was Encountered', 'String functions are overloaded by mbstring, must be set to 0, 1 or 4 in php.ini for UTF8 to work.', 'sys_error');
            }
            
            // Also need to check we have the correct internal mbstring encoding.
            // The Mbstring functions assume mbstring internal encoding is set to UTF-8.
            mb_internal_encoding('UTF-8');
        }
    }

    /**
     * Output the error message for invalid argument value
     *
     * @return void
     */
    private static function invalid_argument_value($func_name) {
        exit('The provided argument value of '."'".$func_name."()'".' is invalid.');
    }

    /**
     * Checks whether two values are identical in both value and data type
     *
     * @param value 1
     * @param value 2
     * @return bool
     */    
    public static function equals($input_1, $input_2) {
        // Whitespace stripped from the beginning and end for string
        if (is_string($input_1)) {
            $input_1 = trim($input_1);
        }
        
        if (is_string($input_2)) {
            $input_2 = trim($input_2);
        }
        
        return ($input_1 === $input_2);
    }
    
    // String
    
    /**
     * Checks that a string contains something other than whitespace
     *
     * @param string
     * @return bool
     */
    public static function not_empty($input) {
        if ( ! is_string($input)) {
            self::invalid_argument_value('not_empty');
        }
        
        // Whitespace stripped from the beginning and end 
        return trim($input) !== '';
    }

    /**
     * Checks whether the length of a string is greater or equal to a minimal length
     *
     * @param string $input The string to test
     * @param integer $min The minimal string length
     * @return bool
     */    
    public static function min_length($input, $min) {
        if ( ! is_string($input) || ! is_int($min)) {
            self::invalid_argument_value('min_length');
        }
        
        // Using trim() on UTF8 string will just work fine
        // Whitespace stripped from the beginning and end 
        $input = trim($input);
        
        // Checks to see if the mbstring extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available) {
            return (mb_strlen($input) < $min) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($input)) < $min) ? FALSE : TRUE;
    }
    
    /**
     * Checks whether the length of a string is smaller or equal to a maximal length
     *
     * @param string $input The string to test
     * @param integer $max The minimal string length
     * @return bool
     */    
    public static function max_length($input, $max) {
        if ( ! is_string($input) || ! is_int($max)) {
            self::invalid_argument_value('max_length');
        }
        
        // Using trim() on UTF8 string will just work fine
        // Whitespace stripped from the beginning and end 
        $input = trim($input);
        
        // Checks to see if the mbstring extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available) {
            return (mb_strlen($input) > $max) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($input)) > $max) ? FALSE : TRUE;
    }
    
    /**
     * Checks whether the length of a string is the desired length
     *
     * @param string
     * @param value
     * @return bool
     */    
    public static function exact_length($input, $val) {
        if ( ! is_string($input) || ! is_int($val)) {
            self::invalid_argument_value('exact_length');
        }
        
        // Using trim() on UTF8 string will just work fine
        // Whitespace stripped from the beginning and end 
        $input = trim($input);
        
        // Checks to see if the mbstring extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available) {
            return (mb_strlen($input) !== $val) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($input)) !== $val) ? FALSE : TRUE;
    }
    
    /**
     * Checks that a value is a valid email address
     *
     * The local-part of the email address may use any of these ASCII characters RFC 5322 Section 3.2.3, 
     * RFC 6531 permits Unicode beyond the ASCII range, UTF8 charcters can be used but 
     * many of the current generation of email servers and clients won't work with that
     *
     * @param string
     * @return bool
     */    
    public static function is_email($input) {
        if ( ! is_string($input)) {
            self::invalid_argument_value('is_email');
        }
        
        // Using trim() on UTF8 string will just work fine
        // Whitespace stripped from the beginning and end 
        $input = trim($input);
        
        // filter_var() returns the filtered data, or FALSE if the filter fails
        if (filter_var($input, FILTER_VALIDATE_EMAIL) !== FALSE) {
            $email_parts = explode('@', $input);
            $host = array_pop($email_parts);
            
            // Check host DNS Records for MX type
            // http://en.wikipedia.org/wiki/List_of_DNS_record_types
            return checkdnsrr($host, 'MX');
        }
        
        return FALSE;
    }
    
    /**
     * Checks that a value is a valid URL
     *
     * @param string
     * @return bool
     */
    public static function is_url($input) {
        if ( ! is_string($input)) {
            self::invalid_argument_value('is_url');
        }
        
        // Using trim() on UTF8 string will just work fine
        // Whitespace stripped from the beginning and end 
        // filter_var() returns the filtered data, or FALSE if the filter fails
        return (filter_var(trim($input), FILTER_VALIDATE_URL) === FALSE) ? FALSE : TRUE;
    }

    /**
     *
     * Checks that a value is a valid date
     *
     * @param string $date
     * @param string $format
     * @return bool
     */
    public static function is_date($date, $format = 'YYYY-MM-DD') {
        $allowed_formats = array(
            'YYYY/MM/DD',
            'YYYY-MM-DD',
            'YYYY/DD/MM',
            'YYYY-DD-MM',
            'DD-MM-YYYY',
            'DD/MM/YYYY',
            'MM-DD-YYYY',
            'MM/DD/YYYY',
            'YYYYMMDD',
            'YYYYDDMM'
        );
        
        if ( ! is_string($date) || ! in_array($format, $allowed_formats)) {
            self::invalid_argument_value('is_date');
        }

        switch($format) {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
                list($y, $m, $d) = preg_split('/[-\.\/ ]/', $date);
                break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
                list($y, $d, $m) = preg_split('/[-\.\/ ]/', $date);
                break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
                list($d, $m, $y) = preg_split('/[-\.\/ ]/', $date);
                break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
                list($m, $d, $y) = preg_split('/[-\.\/ ]/', $date);
                break;

            case 'YYYYMMDD':
                $y = substr($date, 0, 4);
                $m = substr($date, 4, 2);
                $d = substr($date, 6, 2);
                break;

            case 'YYYYDDMM':
                $y = substr($date, 0, 4);
                $d = substr($date, 4, 2);
                $m = substr($date, 6, 2);
                break;
        }
        
        return checkdate($m, $d, $y);
    }
    
    /**
     * Checks that a value is a valid IP address
     *
     * @param string $input The string to test
     * @param string $constraint The IP Protocol version (ipv4 or ipv6) to validate against
     * @return bool 
     */
    public static function is_ip($input, $constraint = 'v4') {
        $constraints = array(
            'v4',
            'v6',
            'v4_no_priv',
            'v6_no_priv',
            'all_no_priv',
            'v4_no_res',
            'v6_no_res',
            'all_no_res',
            'v4_only_public',
            'v6_only_public',
            'all_only_public'
        );
        
        $constraint = trim(strtolower($constraint));
        
        if ( ! is_string($input) || ! is_string($constraint) || ! in_array($constraint, $constraints)) {
            self::invalid_argument_value('is_ip');
        }

        $flag = NULL;
        
        switch ($constraint) {
            case 'v4':
               $flag = FILTER_FLAG_IPV4;
               break;

            case 'v6':
               $flag = FILTER_FLAG_IPV6;
               break;

            case 'v4_no_priv':
               $flag = FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE;
               break;

            case 'v6_no_priv':
               $flag = FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE;
               break;

            case 'all_no_priv':
               $flag = FILTER_FLAG_NO_PRIV_RANGE;
               break;

            case 'v4_no_res':
               $flag = FILTER_FLAG_IPV4 | FILTER_FLAG_NO_RES_RANGE;
               break;

            case 'v6_no_res':
               $flag = FILTER_FLAG_IPV6 | FILTER_FLAG_NO_RES_RANGE;
               break;

            case 'all_no_res':
               $flag = FILTER_FLAG_NO_RES_RANGE;
               break;

            case 'v4_only_public':
               $flag = FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
               break;

            case 'v6_only_public':
               $flag = FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
               break;

            case 'all_only_public':
               $flag = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
               break;
        }

        // Using trim() on UTF8 string will just work fine
        // Whitespace stripped from the beginning and end 
        // filter_var() returns the filtered data, or FALSE if the filter fails
        return (filter_var(trim($input), FILTER_VALIDATE_IP, $flag) === FALSE) ? FALSE : TRUE;
    }
    
    /**
     * Checks if every character in the provided string is a letter
     *
     * @param string
     * @return bool
     */
    public static function is_alpha($input) {
        if ( ! is_string($input)) {
            self::invalid_argument_value('is_alpha');
        }
        
        return ctype_alpha(trim($input));
    }
    
    /**
     * Checks if every character in the provided string is either a letter or a digit
     *
     * @param string
     * @return bool
     */
    public static function is_alnum($input) {
        if ( ! is_string($input)) {
            self::invalid_argument_value('is_alnum');
        }
        
        return ctype_alnum(trim($input));
    }

    /**
     * Checks if all of the characters in the provided string are decimal digits
     *
     * @param string
     * @return bool
     */
    public static function is_digit($input) {
        if ( ! is_string($input)) {
            self::invalid_argument_value('is_digit');
        }
        
        return ctype_digit(trim($input));
    }
    
    /**
     * Checks that a value is greather than the $min
     *
     * @param numeric
     * @param numeric
     * @return bool
     */
    public static function is_greater_than($input, $min) {
        if ( ! is_numeric($input) || ! is_numeric($min)) {
            self::invalid_argument_value('is_greater_than');
        }
        
        return $input > $min;
    }
    
    /**
     * Checks that a value is less than the $max
     *
     * @param numeric
     * @param numeric
     * @return bool
     */
    public static function is_less_than($input, $max) {
        if ( ! is_numeric($input) || ! is_numeric($max)) {
            self::invalid_argument_value('is_less_than');
        }
        
        return $input < $max;
    }

}

/* End of file: ./system/core/validator.php */