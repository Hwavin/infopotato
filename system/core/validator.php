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
     * Value equals
     *
     * @param    value 1
     * @param    value 2
     * @return    bool
     */    
    public static function equals($val_1, $val_2) {
        return ($val_1 !== $val_2) ? FALSE : TRUE;
    }
    
    // String
    
    /**
     * Required
     *
     * @param    string
     * @return    bool
     */
    public static function not_empty($str) {
        return (trim($str) === '') ? FALSE : TRUE;
    }

    /**
     * Minimum Length
     *
     * @param    string
     * @param    value
     * @return    bool
     */    
    public static function min_length($str, $val) {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }
        
        if (function_exists('mb_strlen')) {
            return (mb_strlen($str) < $val) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($str)) < $val) ? FALSE : TRUE;
    }
    
    /**
     * Max Length
     *
     * @param    string
     * @param    value
     * @return    bool
     */    
    public static function max_length($str, $val) {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }
        
        if (function_exists('mb_strlen')) {
            return (mb_strlen($str) > $val) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($str)) > $val) ? FALSE : TRUE;
    }
    
    /**
     * Exact Length
     *
     * @param    string
     * @param    value
     * @return    bool
     */    
    public static function exact_length($str, $val) {
        if (preg_match("/[^0-9]/", $val)) {
            return FALSE;
        }
        
        if (function_exists('mb_strlen')) {
            return (mb_strlen($str) !== $val) ? FALSE : TRUE;
        }
    
        return (strlen(utf8_decode($str)) !== $val) ? FALSE : TRUE;
    }
    
    /**
     * Valid Email
     *
     * The local-part of the email address may use any of these ASCII characters RFC 5322 Section 3.2.3, 
     * RFC 6531 permits Unicode beyond the ASCII range, UTF8 charcters can be used but 
     * many of the current generation of email servers and clients won't work with that
     * @param    string
     * @return    bool
     */    
    public static function valid_email($str) {
        return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }
    
    /**
     * Determine if the provided value is a valid URL
     *
     * @param    string
     * @return    bool
     */
    public static function valid_url($str) {
        return ( ! filter_var($str, FILTER_VALIDATE_URL)) ? FALSE : TRUE;
    }
    
    /**
     * Validates a valid 7, 10, 11 digit phone number (North America, Europe and most Asian and Middle East countries)
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
    public static function valid_phone($str) {
        return ! empty($input) && preg_match('/^[+]?([\d]{0,3})?[\(\.\-\s]?([\d]{1,3})[\)\.\-\s]*(([\d]{3})[\.\-\s]?([\d]{4})|([\d]{2}[\.\-\s]?){4})$/', $str);
    }
    
        
    /**
     *
     * Validate a date
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
     * Alpha
     *
     * @param    string
     * @return    bool
     */        
    public static function alpha($str) {
        return ( ! preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
    }
    
    /**
     * Alpha-numeric
     *
     * @param    string
     * @return    bool
     */
    public static function alpha_numeric($str) {
        return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
    }
    
    /**
     * Alpha-numeric with underscores and dashes
     *
     * @param    string
     * @return    bool
     */    
    public static function alpha_dash($str) {
        return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
    }

    // Integer or Decimal

    /**
     * Is a Decimal number?
     *
     * @param    string
     * @return    bool
     */
    public static function is_decimal($str) {
        return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str);
    }
    
    /**
     * Greather than
     *
     * @param    string
     * @return    bool
     */
    public static function greater_than($str, $min) {
        if ( ! is_numeric($str)) {
            return FALSE;
        }
        return $str > $min;
    }
    
    /**
     * Less than
     *
     * @param    string
     * @return    bool
     */
    public static function less_than($str, $max) {
        if ( ! is_numeric($str)) {
            return FALSE;
        }
        return $str < $max;
    }
    
    /**
     * Is a Natural number  (0,1,2,3, etc.)
     *
     * @param    string
     * @return    bool
     */
    public static function is_natural($str) {
        return (bool)preg_match( '/^[0-9]+$/', $str);
    }
    
    /**
     * Is a Natural number, but not a zero  (1,2,3, etc.)
     *
     * @param    string
     * @return    bool
     */
    public static function is_natural_no_zero($str) {
        if ( ! preg_match( '/^[0-9]+$/', $str)) {
            return FALSE;
        }
        
        if ($str === 0) {
            return FALSE;
        }
        return TRUE;
    }


}

/* End of file: ./system/core/validator.php */