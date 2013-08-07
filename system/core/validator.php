<?php
/**
 * Data Validator
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Validator {
    /**
     * Prevent direct object creation
     * 
     * @return Validator
     */
    private function __construct() {
        // Set the character encoding in MB.
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding('UTF-8');
        }
    }

    // Comparing Two Strings or Two Integers
    
    /**
     * Checks whether two values are identical in both value and data type
     *
     * @param    value 1
     * @param    value 2
     * @return    bool
     */    
    public static function equals($input_1, $input_2) {
        return ($input_1 === $input_2);
    }
    
    // String
    
    /**
     * Checks that a string contains something other than whitespace
     *
     * @param    string
     * @return    bool
     */
    public static function not_empty($input) {
        return (trim($input) === '') ? FALSE : TRUE;
    }

    /**
     * Checks whether the length of a string is greater or equal to a minimal length
     *
     * @param    string $input The string to test
     * @param    integer $min The minimal string length
     * @return    bool
     */    
    public static function min_length($input, $min) {
        if (function_exists('mb_strlen')) {
            return (mb_strlen($input) < $min) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($input)) < $min) ? FALSE : TRUE;
    }
    
    /**
     * Checks whether the length of a string is smaller or equal to a maximal length
     *
     * @param    string $input The string to test
     * @param    integer $max The minimal string length
     * @return    bool
     */    
    public static function max_length($input, $max) {
        if (function_exists('mb_strlen')) {
            return (mb_strlen($input) > $max) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($input)) > $max) ? FALSE : TRUE;
    }
    
    /**
     * Checks whether the length of a string is the desired length
     *
     * @param    string
     * @param    value
     * @return    bool
     */    
    public static function exact_length($input, $val) {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }
        
        if (function_exists('mb_strlen')) {
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
     * @param    string
     * @return    bool
     */    
    public static function is_email($input) {
        //return ( ! preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix', $input)) ? FALSE : TRUE;
        return is_string($input) && filter_var($input, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Checks that a value is a valid URL
     *
     * @param    string
     * @return    bool
     */
    public static function is_url($input) {
        return is_string($input) && filter_var($input, FILTER_VALIDATE_URL);
    }
    
    /**
     * Checks that a value is a valid 7, 10, 11 digit phone number (North America, Europe and most Asian and Middle East countries)
     *
     * supporting country and area codes (in dot, space or dashed notations) such as:
     * (555)555-5555
     * 555 555 5555
     * +5(555)555.5555
     * 33(1)22 22 22 22
     * +33(1)22 22 22 22
     * +33(020)7777 7777
     * 03-6106666
     *
     * @param    string
     * @return    bool
     */
    public static function is_phone($input) {
        return ! empty($input) && preg_match('/^[+]?([\d]{0,3})?[\(\.\-\s]?([\d]{1,3})[\)\.\-\s]*(([\d]{3})[\.\-\s]?([\d]{4})|([\d]{2}[\.\-\s]?){4})$/', $input);
    }
    
    /**
     *
     * Checks that a value is a valid date
     *
     * @param    string    $date
     * @param    string    format
     * @return    bool
     *
     */
    function is_date($date, $format = 'YYYY-MM-DD') {
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

            default:
                throw new Exception("Invalid Date Format");
        }
        
        return checkdate($m, $d, $y);
    }
    
    /**
     * Checks that a value is a valid IP address
     *
     * @param string $input The string to test
     * @param string $type The IP Protocol version (ipv4 or ipv6) to validate against
     * @return boolean Success
     */
    public static function is_ip($input, $type = 'both') {
        $type = strtolower($type);
        $flags = 0;
        
        if ($type === 'ipv4') {
            $flags = FILTER_FLAG_IPV4;
        }
        if ($type === 'ipv6') {
            $flags = FILTER_FLAG_IPV6;
        }
        
        return (bool) filter_var($input, FILTER_VALIDATE_IP, array('flags' => $flags));
    }
    
    /**
     * Checks that a string contains only letters
     *
     * @param    string
     * @return    bool
     */        
    public static function is_alpha($input) {
        return is_string($input) && preg_match('/^([a-z])+$/i', $input);
    }
    
    /**
     * Checks that a string contains only integer or letters
     *
     * @param    string
     * @return    bool
     */
    public static function is_alpha_numeric($input) {
        return is_string($input) && preg_match('/^([a-z0-9])+$/i', $input);
    }

    // Integer or Decimal

    /**
     * Checks that a value is a valid decimal
     *
     * @param    float $input The value the test for decimal
     * @return    bool
     */
    public static function is_decimal($input) {
        return ( ! preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $input)) ? FALSE : TRUE;
    }
    
    /**
     * Checks that a value is greather than the $min
     *
     * @param    mix
     * @return    bool
     */
    public static function is_greater_than($input, $min) {
        if ( ! is_numeric($input)) {
            return FALSE;
        }
        
        return $input > $min;
    }
    
    /**
     * Checks that a value is less than the $max
     *
     * @param    numeric
     * @return    bool
     */
    public static function is_less_than($input, $max) {
        if ( ! is_numeric($input)) {
            return FALSE;
        }
        
        return $input < $max;
    }

    /**
     * Checks if a value is a natural number
     *
     * @param string $input Value to check
     * @param boolean $allow_zero Set true to allow zero, defaults to false
     * @return boolean Success
     * @see http://en.wikipedia.org/wiki/Natural_number
     */
    public static function is_natural_number($input, $allow_zero = FALSE) {
        $regex = $allow_zero ? '/^(?:0|[1-9][0-9]*)$/' : '/^[1-9][0-9]*$/';
        return ( ! preg_match($regex, $input)) ? FALSE : TRUE;
    }

}

/* End of file: ./system/core/validator.php */