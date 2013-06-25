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
    public static function equals($check_1, $check_2) {
        return ($check_1 === $check_2);
    }
    
    // String
    
    /**
     * Checks that a string contains something other than whitespace
     *
     * @param    string
     * @return    bool
     */
    public static function not_empty($check) {
        return (trim($check) === '') ? FALSE : TRUE;
    }

    /**
     * Checks whether the length of a string is greater or equal to a minimal length
     *
     * @param    string $check The string to test
     * @param    integer $min The minimal string length
     * @return    bool
     */    
    public static function min_length($check, $min) {
        if (function_exists('mb_strlen')) {
            return (mb_strlen($check) < $min) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($check)) < $min) ? FALSE : TRUE;
    }
    
    /**
     * Checks whether the length of a string is smaller or equal to a maximal length
     *
     * @param    string $check The string to test
     * @param    integer $max The minimal string length
     * @return    bool
     */    
    public static function max_length($check, $max) {
        if (function_exists('mb_strlen')) {
            return (mb_strlen($check) > $max) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($check)) > $max) ? FALSE : TRUE;
    }
    
    /**
     * Checks whether the length of a string is the desired length
     *
     * @param    string
     * @param    value
     * @return    bool
     */    
    public static function exact_length($check, $val) {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }
        
        if (function_exists('mb_strlen')) {
            return (mb_strlen($check) !== $val) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($check)) !== $val) ? FALSE : TRUE;
    }
    
    /**
     * Check that a value is a valid email address
     *
     * The local-part of the email address may use any of these ASCII characters RFC 5322 Section 3.2.3, 
     * RFC 6531 permits Unicode beyond the ASCII range, UTF8 charcters can be used but 
     * many of the current generation of email servers and clients won't work with that
     * @param    string
     * @return    bool
     */    
    public static function valid_email($check) {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $check)) ? FALSE : TRUE;
    }
    
    /**
     * Checks that a value is a valid URL
     *
     * @param    string
     * @return    bool
     */
    public static function valid_url($check) {
        return ( ! filter_var($check, FILTER_VALIDATE_URL)) ? FALSE : TRUE;
    }
    
    /**
     * Check that a value is a valid 7, 10, 11 digit phone number (North America, Europe and most Asian and Middle East countries)
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
    public static function valid_phone($check) {
        return ! empty($input) && preg_match('/^[+]?([\d]{0,3})?[\(\.\-\s]?([\d]{1,3})[\)\.\-\s]*(([\d]{3})[\.\-\s]?([\d]{4})|([\d]{2}[\.\-\s]?){4})$/', $check);
    }
    
    /**
     *
     * Check that a value is a valid date
     *
     * @param    string    $date
     * @param    string    format
     * @return    bool
     *
     */
    function valid_date($date, $format = 'YYYY-MM-DD') {
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
     * Check that a value is a valid IP address
     *
     * @param string $check The string to test
     * @param string $type The IP Protocol version to validate against
     * @return boolean Success
     */
    public static function ip($check, $type = 'both') {
        $type = strtolower($type);
        $flags = 0;
        
        if ($type === 'ipv4') {
            $flags = FILTER_FLAG_IPV4;
        }
        if ($type === 'ipv6') {
            $flags = FILTER_FLAG_IPV6;
        }
        
        return (bool) filter_var($check, FILTER_VALIDATE_IP, array('flags' => $flags));
    }
    
    /**
     * Checks that a string contains only letters
     *
     * @param    string
     * @return    bool
     */        
    public static function alpha($check) {
        return ( ! preg_match("/^([a-z])+$/i", $check)) ? FALSE : TRUE;
    }
    
    /**
     * Checks that a string contains only integer or letters
     *
     * @param    string
     * @return    bool
     */
    public static function alpha_numeric($check) {
        return ( ! preg_match("/^([a-z0-9])+$/i", $check)) ? FALSE : TRUE;
    }

    // Integer or Decimal

    /**
     * Checks that a value is a valid decimal
     *
     * @param    float $check The value the test for decimal
     * @return    bool
     */
    public static function decimal($check) {
        return ( ! preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $check)) ? FALSE : TRUE;
    }
    
    /**
     * Checks that a value is greather than the $min
     *
     * @param    mix
     * @return    bool
     */
    public static function greater_than($check, $min) {
        if ( ! is_numeric($check)) {
            return FALSE;
        }
        
        return $check > $min;
    }
    
    /**
     * Checks that a value is less than the $max
     *
     * @param    numeric
     * @return    bool
     */
    public static function less_than($check, $max) {
        if ( ! is_numeric($check)) {
            return FALSE;
        }
        
        return $check < $max;
    }

    /**
     * Checks if a value is a natural number
     *
     * @param string $check Value to check
     * @param boolean $allow_zero Set true to allow zero, defaults to false
     * @return boolean Success
     * @see http://en.wikipedia.org/wiki/Natural_number
     */
    public static function natural_number($check, $allow_zero = FALSE) {
        $regex = $allow_zero ? '/^(?:0|[1-9][0-9]*)$/' : '/^[1-9][0-9]*$/';
        return ( ! preg_match($regex, $check)) ? FALSE : TRUE;
    }

}

/* End of file: ./system/core/validator.php */