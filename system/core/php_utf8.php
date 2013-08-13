<?php
/**
 * PHP UTF-8 aware library of functions mirroring PHP's own string functions.
 * use Iñtërnâtiônàlizætiøn for testing (it contains 20 characters)
 * 
 * Does not require PHP mbstring extension though will use it, if found, for a (small) performance gain.
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 * @link    based on   https://github.com/FSX/php-utf8
 */

/**
 * PCRE Pattern to locate bad bytes in a UTF-8 string.
 */
define('BAD_UTF_PATTERN',
    '([\x00-\x7F]'.                            # ASCII (including control chars)
    '|[\xC2-\xDF][\x80-\xBF]'.                # Non-overlong 2-byte
    '|\xE0[\xA0-\xBF][\x80-\xBF]'.            # Excluding overlongs
    '|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}'.    # Straight 3-byte
    '|\xED[\x80-\x9F][\x80-\xBF]'.            # Excluding surrogates
    '|\xF0[\x90-\xBF][\x80-\xBF]{2}'.        # Planes 1-3
    '|[\xF1-\xF3][\x80-\xBF]{3}'.            # Planes 4-15
    '|\xF4[\x80-\x8F][\x80-\xBF]{2}'.        # Plane 16
    '|(.{1}))'                                # Invalid byte
);

class PHP_UTF8 {
    /**
     * If the [http://php.net/mbstring mbstring] extension is available
     * 
     * @var boolean
     */
    private static $mbstring_available = FALSE;
    
    /**
     * Prevent direct object creation
     * 
     * @return UTF8
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
            if (ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING) {
                halt('A System Error Was Encountered', 'String functions are overloaded by mbstring, must be set to 0, 1 or 4 in php.ini for PHP-UTF8 to work.', 'sys_error');
            }
            
            // Also need to check we have the correct internal mbstring encoding.
            // The Mbstring functions assume mbstring internal encoding is set to UTF-8.
            mb_language('uni');
            mb_internal_encoding('UTF-8');
        }
    }
    
    /**
     * Strips out any bad bytes from a UTF-8 string and returns the rest.
     * Can optionally replace bad bytes with an alternative character.
     *
     * PCRE Pattern to locate bad bytes in a UTF-8 string comes from W3 FAQ: Multilingual Forms.
     * Note: modified to include full ASCII range including control chars
     *
     * @see http://www.w3.org/International/questions/qa-forms-utf-8
     * @param string $str
     * @return string
     */
    private static function bad_clean($str, $replace = FALSE) {
        ob_start();
        
        while (preg_match('/'.BAD_UTF_PATTERN.'/S', $str, $matches)) {
            if ( ! isset($matches[2])) {
                echo $matches[0];
            } elseif ($replace !== FALSE && is_string($replace)) {
                echo $replace;
            }
            $str = substr($str, strlen($matches[0]));
        }
        
        return ob_get_clean();
    }
    
    /**
     * Takes an UTF-8 string and returns an array of ints representing the Unicode characters.
     * Astral planes are supported ie. the ints in the output can be > 0xFFFF.
     * Occurrances of the BOM are ignored. Surrogates are not allowed.
     * Returns false if the input string isn't a valid UTF-8 octet sequence and stops running with displaying error msg
     * Note: this function has been modified slightly in this library to trigger errors on encountering bad bytes
     *
     * Tools for conversion between UTF-8 and unicode
     * The Original Code is Mozilla Communicator client code.
     * The Initial Developer of the Original Code is Netscape Communications Corporation.
     * Portions created by the Initial Developer are Copyright (C) 1998
     * the Initial Developer. All Rights Reserved.
     * Ported to PHP by Henri Sivonen (http://hsivonen.iki.fi)
     *
     * @author <hsivonen@iki.fi>
     * @param string $str UTF-8 encoded string
     * @return mixed array of unicode code points or FALSE if UTF-8 invalid
     * @see utf8_from_unicode
     * @see http://lxr.mozilla.org/seamonkey/source/intl/uconv/src/nsUTF8ToUnicode.cpp
     * @see http://lxr.mozilla.org/seamonkey/source/intl/uconv/src/nsUnicodeToUTF8.cpp
     * @see http://hsivonen.iki.fi/php-utf8/
     */
    private static function to_unicode($str) {
        $mState = 0; // Cached expected number of octets after the current octet
                     // until the beginning of the next UTF8 character sequence
        $mUcs4 = 0; // Cached Unicode character
        $mBytes = 1; // Cached expected number of octets in the current sequence
        
        $out = array();
        $len = strlen($str);
        
        for ($i = 0; $i < $len; $i++) {
            $in = ord($str[$i]);
            
            if ($mState == 0) {
                // When mState is zero we expect either a US-ASCII character or a multi-octet sequence.
                if (0 == (0x80 & ($in))) {
                    // US-ASCII, pass straight through.
                    $out[] = $in;
                    $mBytes = 1;
                } elseif (0xC0 == (0xE0 & ($in))) {
                    // First octet of 2 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x1F) << 6;
                    $mState = 1;
                    $mBytes = 2;
                } elseif (0xE0 == (0xF0 & ($in))) {
                    // First octet of 3 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x0F) << 12;
                    $mState = 2;
                    $mBytes = 3;
                } elseif (0xF0 == (0xF8 & ($in))) {
                    // First octet of 4 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x07) << 18;
                    $mState = 3;
                    $mBytes = 4;
                } elseif (0xF8 == (0xFC & ($in))) {
                    /* First octet of 5 octet sequence.
                     *
                     * This is illegal because the encoded codepoint must be either
                     * (a) not the shortest form or
                     * (b) outside the Unicode range of 0-0x10FFFF.
                     * Rather than trying to resynchronize, we will carry on until the end
                     * of the sequence and let the later error handling code catch it.
                     */
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x03) << 24;
                    $mState = 4;
                    $mBytes = 5;
                } elseif (0xFC == (0xFE & ($in))) {
                    // First octet of 6 octet sequence, see comments for 5 octet sequence.
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 1) << 30;
                    $mState = 5;
                    $mBytes = 6;
                } else {
                    // Current octet is neither in the US-ASCII range nor a legal first octet of a multi-octet sequence
                    halt('A System Error Was Encountered', "PHP_UTF8::to_unicode(): Illegal sequence identifier in UTF-8 at byte {$i}", 'sys_error');
                }
            } else {
                // When mState is non-zero, we expect a continuation of the multi-octet sequence
                if (0x80 == (0xC0 & ($in))) {
                    // Legal continuation.
                    $shift = ($mState - 1) * 6;
                    $tmp = $in;
                    $tmp = ($tmp & 0x0000003F) << $shift;
                    $mUcs4 |= $tmp;
                    
                    /**
                     * End of the multi-octet sequence. mUcs4 now contains the final
                     * Unicode codepoint to be output
                     */
                    if (0 == --$mState) {
                        /*
                         * Check for illegal sequences and codepoints.
                         */
                        // From Unicode 3.1, non-shortest form is illegal
                        if (((2 == $mBytes) && ($mUcs4 < 0x0080)) || ((3 == $mBytes) && ($mUcs4 < 0x0800)) ||
                                ((4 == $mBytes) && ($mUcs4 < 0x10000)) || (4 < $mBytes) ||
                                // From Unicode 3.2, surrogate characters are illegal
                                (($mUcs4 & 0xFFFFF800) == 0xD800) ||
                                // Codepoints outside the Unicode range are illegal
                                ($mUcs4 > 0x10FFFF))
                        {
                            halt('A System Error Was Encountered', "PHP_UTF8::to_unicode(): Illegal sequence or codepoint in UTF-8 at byte {$i}", 'sys_error');
                        }
                        
                        // BOM is legal but we don't want to output it
                        if (0xFEFF != $mUcs4) {
                            $out[] = $mUcs4;
                        }
                        // Initialize UTF8 cache
                        $mState = 0;
                        $mUcs4 = 0;
                        $mBytes = 1;
                    }
                } else {
                    /* ((0xC0 & (*in) != 0x80) && (mState != 0))
                      Incomplete multi-octet sequence. */
                    halt('A System Error Was Encountered', "PHP_UTF8::to_unicode(): Incomplete multi-octet sequence in UTF-8 at byte {$i}", 'sys_error');
                }
            }
        }
        
        return $out;
    }
    
    /**
     * Takes an array of ints representing the Unicode characters and returns a UTF-8 string.
     * Astral planes are supported ie. the ints in the input can be > 0xFFFF.
     * Occurrances of the BOM are ignored. Surrogates are not allowed.
     * Returns false if the input array contains ints that represent surrogates or are outside the Unicode range and stops running with displaying error msg
     * Note: this function has been modified slightly in this library to use output buffering to concatenate the UTF-8 string (faster) as well as reference the array by it's keys
     *
     *
     * Tools for conversion between UTF-8 and unicode
     * The Original Code is Mozilla Communicator client code.
     * The Initial Developer of the Original Code is Netscape Communications Corporation.
     * Portions created by the Initial Developer are Copyright (C) 1998
     * the Initial Developer. All Rights Reserved.
     * Ported to PHP by Henri Sivonen (http://hsivonen.iki.fi)
     *
     * @see utf8_to_unicode
     * @see http://hsivonen.iki.fi/php-utf8/
     * @see http://lxr.mozilla.org/seamonkey/source/intl/uconv/src/nsUTF8ToUnicode.cpp
     * @see http://lxr.mozilla.org/seamonkey/source/intl/uconv/src/nsUnicodeToUTF8.cpp
     * @param array $arr  Array of unicode code points representing a string
     * @return mixed UTF-8 string or FALSE if array contains invalid code points
     * @author <hsivonen@iki.fi>
     */
    private static function from_unicode($arr) {
        ob_start();
        
        foreach(array_keys($arr) as $k) {
            if(($arr[$k] >= 0) && ($arr[$k] <= 0x007f)) { // ASCII range (including control chars)
                echo chr($arr[$k]);
            } elseif ($arr[$k] <= 0x07ff) { // 2 byte sequence 
                echo chr(0xc0 | ($arr[$k] >> 6));
                echo chr(0x80 | ($arr[$k] & 0x003f));
            } elseif ($arr[$k] == 0xFEFF) { // Byte order mark (skip)
                // Nop -- zap the BOM
            } elseif ($arr[$k] >= 0xD800 && $arr[$k] <= 0xDFFF) { // Test for illegal surrogates
                // Found a surrogate
                halt('A System Error Was Encountered', "PHP_UTF8::from_unicode(): Illegal surrogate at index: {$k}, value: {$arr[$k]}", 'sys_error');
            } elseif ($arr[$k] <= 0xffff) { // 3 byte sequence
                echo chr(0xe0 | ($arr[$k] >> 12));
                echo chr(0x80 | (($arr[$k] >> 6) & 0x003f));
                echo chr(0x80 | ($arr[$k] & 0x003f));
            } elseif ($arr[$k] <= 0x10ffff) { // 4 byte sequence
                echo chr(0xf0 | ($arr[$k] >> 18));
                echo chr(0x80 | (($arr[$k] >> 12) & 0x3f));
                echo chr(0x80 | (($arr[$k] >> 6) & 0x3f));
                echo chr(0x80 | ($arr[$k] & 0x3f));
            } else {
                // Out of range
                halt('A System Error Was Encountered', "PHP_UTF8::from_unicode(): Codepoint out of Unicode range at index: {$k}, value: {$arr[$k]}", 'sys_error');
            }
        }
        
        $result = ob_get_contents();
        ob_end_clean();
        
        return $result;
    }
    
    /**
     * Unicode aware replacement for *strlen*.
     *
     * Returns the number of characters in the string (not the number of bytes),
     * replacing multibyte characters with a single byte equivalent utf8_decode
     * converts characters that are not in ISO-8859-1 to '?', which, for the purpose
     * of counting, is alright. It's much faster than mb_strlen.
     *
     * @see http://www.php.net/strlen
     * @param string $str A UTF-8 string
     * @return mixed integer length of a string
     */
    public static function mirror_strlen($str) {
        // Checks to see if the [http://php.net/mbstring mbstring] extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available && function_exists('mb_strlen')) {
            return mb_strlen($str);
        } else {
            return strlen(utf8_decode(self::bad_clean($str)));
        }
    }
    
    /**
     * UTF-8 aware alternative to strpos.
     *
     * Find position of first occurrence of a string.
     * This will get alot slower if offset is used.
     *
     * @see http://www.php.net/strpos
     * @see utf8_strlen
     * @see utf8_substr
     * @param string $str haystack
     * @param string $str needle (you should validate this with utf8_is_valid)
     * @param integer $offset offset in characters (from left)
     * @return mixed integer position or FALSE on failure
     */
    public static function mirror_strpos($str, $needle, $offset = FALSE) {
        // Checks to see if the mbstring extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available && function_exists('mb_strpos')) {
            $str = self::bad_clean($str);
            
            if ($offset === FALSE) {
                return mb_strpos($str, $needle);
            }
            return mb_strpos($str, $needle, $offset);
        } else {
            if ($offset === FALSE) {
                $ar = explode($needle, $str, 2);
                
                //if (count($ar) > 1)
                if (isset($ar[1])) {
                    return self::mirror_strlen($ar[0]);
                }
                
                return FALSE;
            }
            
            if ( ! is_int($offset)) {
                halt('A System Error Was Encountered', 'PHP_UTF8::mirror_strpos(): Offset must be an integer', 'sys_error');
            }
            
            $str = self::mirror_substr($str, $offset);
            
            if (($pos = self::mirror_strpos($str, $needle)) !== FALSE) {
                return $pos + $offset;
            }
            
            return FALSE; 
        }
    }
    
    /**
     * UTF-8 aware alternative to strrpos.
     *
     * Find position of last occurrence of a char in a string.
     * This will get alot slower if offset is used
     *
     * @see http://www.php.net/strrpos
     * @see utf8_substr
     * @see utf8_strlen
     * @param string $str haystack
     * @param string $needle needle (you should validate this with utf8_is_valid)
     * @param integer $offset (optional) offset (from left)
     * @return mixed integer position or FALSE on failure
     */
    public static function mirror_strrpos($str, $needle, $offset = FALSE) {
        // Checks to see if the mbstring extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available && function_exists('mb_strrpos')) {
            $str = self::bad_clean($str);
            
            if ( ! $offset) {
                // Emulate behaviour of strrpos rather than raising warning
                if (empty($str)) {
                    return FALSE;
                }
                return mb_strrpos($str, $needle);
            }
            
            if ( ! is_int($offset)) {
                halt('A System Error Was Encountered', 'PHP_UTF8::mirror_strrpos(): expects parameter 3 to be long', 'sys_error');
            }
            
            $str = mb_substr($str, $offset);
            
            if (($pos = mb_strrpos($str, $needle)) !== FALSE) {
                return $pos + $offset;
            }
            
            return FALSE;
        } else {
            if ($offset === FALSE) {
                $ar = explode($needle, $str);
                
                //if (count($ar) > 1)
                if (isset($ar[1])) {
                    // Pop off the end of the string where the last match was made
                    array_pop($ar);
                    $str = implode($needle, $ar);
                    
                    return self::mirror_strlen($str);
                }
                
                return FALSE;
            }
            
            if ( ! is_int($offset)) {
                halt('A System Error Was Encountered', 'PHP_UTF8::mirror_strrpos(): expects parameter 3 to be long', 'sys_error');
            }
            
            $str = self::mirror_substr($str, $offset);
            
            if (($pos = self::mirror_strrpos($str, $needle)) !== FALSE) {
                return $pos + $offset;
            }
            
            return FALSE;
        }
    }
    
    /**
     * UTF-8 aware alternative to substr.
     *
     * Return part of a string given character offset (and optionally length)
     *
     * Compared to substr, if offset or length are not integers, this version will
     * not complain but rather massages them into an integer.
     *
     * Note on returned values: substr documentation states false can be returned
     * in some cases (e.g. offset > string length) mb_substr never returns false,
     * it will return an empty string instead. This adopts the mb_substr approach.
     *
     * Note on implementation: PCRE only supports repetitions of less than 65536,
     * in order to accept up to MAXINT values for offset and length, we'll repeat
     * a group of 65535 characters when needed.
     *
     * Note on implementation: calculating the number of characters in the string
     * is a relatively expensive operation, so we only carry it out when necessary.
     * It isn't necessary for +ve offsets and no specified length
     *
     * @author Chris Smith<chris@jalakai.co.uk>
     * @param string $str
     * @param integer $offset number of UTF-8 characters offset (from left)
     * @param integer $length (optional) length in UTF-8 characters from offset
     * @return mixed string or FALSE if failure
     */
    public static function mirror_substr($str, $offset, $length = FALSE) {
        // Checks to see if the mbstring extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available && function_exists('mb_substr')) {
            if ($length === FALSE) {
                return mb_substr($str, $offset);
            }
            return mb_substr($str, $offset, $length);
        } else {
            // Generates E_NOTICE for PHP4 objects, but not PHP5 objects
            $str = (string) $str;
            $offset = (int) $offset;
            
            if ($length) {
                $length = (int) $length;
            }
            
            // Handle trivial cases
            if ($length === 0) {
                return '';
            }
            if ($offset < 0 && $length < 0 && $length < $offset) {
                return '';
            }
            
            // Normalise negative offsets (we could use a tail
            // anchored pattern, but they are horribly slow!)
            if ($offset < 0) {
                // See notes
                $strlen = self::mirror_strlen($str);
                $offset = $strlen + $offset;
                
                if ($offset < 0) {
                    $offset = 0;
                }
            }
            
            $offset_pattern = '';
            $length_pattern = '';
            
            // Establish a pattern for offset, a
            // non-captured group equal in length to offset
            if ($offset > 0) {
                $ox = (int) ($offset / 65535);
                $oy = $offset % 65535;
                
                if ($ox) {
                    $offset_pattern = '(?:.{65535}){'.$ox.'}';
                }
                $offset_pattern = '^(?:'.$offset_pattern.'.{'.$oy.'})';
            } else {
                $offset_pattern = '^';
            }
            
            
            // Establish a pattern for length
            if ( ! $length) {
                $length_pattern = '(.*)$'; // The rest of the string
            } else {
                // See notes
                if ( ! isset($strlen)) {
                    $strlen = self::mirror_strlen($str);
                }
                
                // Another trivial case
                if ($offset > $strlen) {
                    return '';
                }
                
                if ($length > 0) {
                    // Reduce any length that would go passed the end of the string
                    $length = min($strlen - $offset, $length);
                    
                    $lx = (int) ($length / 65535);
                    $ly = $length % 65535;
                    
                    // Negative length requires a captured group of length characters
                    if ($lx) {
                        $length_pattern = '(?:.{65535}){'.$lx.'}';
                    }
                    
                    $length_pattern = '('.$length_pattern.'.{'.$ly.'})';
                } elseif ($length < 0) {
                    if ($length < ($offset - $strlen)) {
                        return '';
                    }
                    
                    $lx = (int) ((-$length) / 65535);
                    $ly = (-$length) % 65535;
                    
                    // Negative length requires ... capture everything except a group of
                    // -length characters anchored at the tail-end of the string
                    if ($lx) {
                        $length_pattern = '(?:.{65535}){'.$lx.'}';
                    }
                    
                    $length_pattern = '(.*)(?:'.$length_pattern.'.{'.$ly.'})$';
                }
            }
            
            if ( ! preg_match('#'.$offset_pattern.$length_pattern.'#us', $str, $match)) {
                return '';
            }
            
            return $match[1];
        }
    }
    
    /**
     * UTF-8 aware alternative to strtolower.
     *
     * Make a string lowercase.
     *
     * The concept of a characters "case" only exists is some alphabets such as
     * Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does not exist in
     * the Chinese alphabet, for example. See Unicode Standard Annex #21: Case Mappings.
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see http://www.php.net/strtolower
     * @see utf8_to_unicode
     * @see utf8_from_unicode
     * @see http://www.unicode.org/reports/tr21/tr21-5.html
     * @see http://dev.splitbrain.org/view/darcs/dokuwiki/inc/utf8.php
     * @param string $str
     * @return mixed either string in lowercase or FALSE is UTF-8 invalid
     */
    public static function mirror_strtolower($str) {
        // Checks to see if the mbstring extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available && function_exists('mb_strtolower')) {
            return mb_strtolower($str);
        } else {
            static $UTF8_UPPER_TO_LOWER;
            
            $uni = self::to_unicode($str);
            if ( ! $uni) {
                return FALSE;
            }
            
            if ( ! $UTF8_UPPER_TO_LOWER) {
                $UTF8_UPPER_TO_LOWER = array(
                    0x0041 => 0x0061, 0x03A6 => 0x03C6, 0x0162 => 0x0163, 0x00C5 => 0x00E5, 0x0042 => 0x0062,
                    0x0139 => 0x013A, 0x00C1 => 0x00E1, 0x0141 => 0x0142, 0x038E => 0x03CD, 0x0100 => 0x0101,
                    0x0490 => 0x0491, 0x0394 => 0x03B4, 0x015A => 0x015B, 0x0044 => 0x0064, 0x0393 => 0x03B3,
                    0x00D4 => 0x00F4, 0x042A => 0x044A, 0x0419 => 0x0439, 0x0112 => 0x0113, 0x041C => 0x043C,
                    0x015E => 0x015F, 0x0143 => 0x0144, 0x00CE => 0x00EE, 0x040E => 0x045E, 0x042F => 0x044F,
                    0x039A => 0x03BA, 0x0154 => 0x0155, 0x0049 => 0x0069, 0x0053 => 0x0073, 0x1E1E => 0x1E1F,
                    0x0134 => 0x0135, 0x0427 => 0x0447, 0x03A0 => 0x03C0, 0x0418 => 0x0438, 0x00D3 => 0x00F3,
                    0x0420 => 0x0440, 0x0404 => 0x0454, 0x0415 => 0x0435, 0x0429 => 0x0449, 0x014A => 0x014B,
                    0x0411 => 0x0431, 0x0409 => 0x0459, 0x1E02 => 0x1E03, 0x00D6 => 0x00F6, 0x00D9 => 0x00F9,
                    0x004E => 0x006E, 0x0401 => 0x0451, 0x03A4 => 0x03C4, 0x0423 => 0x0443, 0x015C => 0x015D,
                    0x0403 => 0x0453, 0x03A8 => 0x03C8, 0x0158 => 0x0159, 0x0047 => 0x0067, 0x00C4 => 0x00E4,
                    0x0386 => 0x03AC, 0x0389 => 0x03AE, 0x0166 => 0x0167, 0x039E => 0x03BE, 0x0164 => 0x0165,
                    0x0116 => 0x0117, 0x0108 => 0x0109, 0x0056 => 0x0076, 0x00DE => 0x00FE, 0x0156 => 0x0157,
                    0x00DA => 0x00FA, 0x1E60 => 0x1E61, 0x1E82 => 0x1E83, 0x00C2 => 0x00E2, 0x0118 => 0x0119,
                    0x0145 => 0x0146, 0x0050 => 0x0070, 0x0150 => 0x0151, 0x042E => 0x044E, 0x0128 => 0x0129,
                    0x03A7 => 0x03C7, 0x013D => 0x013E, 0x0422 => 0x0442, 0x005A => 0x007A, 0x0428 => 0x0448,
                    0x03A1 => 0x03C1, 0x1E80 => 0x1E81, 0x016C => 0x016D, 0x00D5 => 0x00F5, 0x0055 => 0x0075,
                    0x0176 => 0x0177, 0x00DC => 0x00FC, 0x1E56 => 0x1E57, 0x03A3 => 0x03C3, 0x041A => 0x043A,
                    0x004D => 0x006D, 0x016A => 0x016B, 0x0170 => 0x0171, 0x0424 => 0x0444, 0x00CC => 0x00EC,
                    0x0168 => 0x0169, 0x039F => 0x03BF, 0x004B => 0x006B, 0x00D2 => 0x00F2, 0x00C0 => 0x00E0,
                    0x0414 => 0x0434, 0x03A9 => 0x03C9, 0x1E6A => 0x1E6B, 0x00C3 => 0x00E3, 0x042D => 0x044D,
                    0x0416 => 0x0436, 0x01A0 => 0x01A1, 0x010C => 0x010D, 0x011C => 0x011D, 0x00D0 => 0x00F0,
                    0x013B => 0x013C, 0x040F => 0x045F, 0x040A => 0x045A, 0x00C8 => 0x00E8, 0x03A5 => 0x03C5,
                    0x0046 => 0x0066, 0x00DD => 0x00FD, 0x0043 => 0x0063, 0x021A => 0x021B, 0x00CA => 0x00EA,
                    0x0399 => 0x03B9, 0x0179 => 0x017A, 0x00CF => 0x00EF, 0x01AF => 0x01B0, 0x0045 => 0x0065,
                    0x039B => 0x03BB, 0x0398 => 0x03B8, 0x039C => 0x03BC, 0x040C => 0x045C, 0x041F => 0x043F,
                    0x042C => 0x044C, 0x00DE => 0x00FE, 0x00D0 => 0x00F0, 0x1EF2 => 0x1EF3, 0x0048 => 0x0068,
                    0x00CB => 0x00EB, 0x0110 => 0x0111, 0x0413 => 0x0433, 0x012E => 0x012F, 0x00C6 => 0x00E6,
                    0x0058 => 0x0078, 0x0160 => 0x0161, 0x016E => 0x016F, 0x0391 => 0x03B1, 0x0407 => 0x0457,
                    0x0172 => 0x0173, 0x0178 => 0x00FF, 0x004F => 0x006F, 0x041B => 0x043B, 0x0395 => 0x03B5,
                    0x0425 => 0x0445, 0x0120 => 0x0121, 0x017D => 0x017E, 0x017B => 0x017C, 0x0396 => 0x03B6,
                    0x0392 => 0x03B2, 0x0388 => 0x03AD, 0x1E84 => 0x1E85, 0x0174 => 0x0175, 0x0051 => 0x0071,
                    0x0417 => 0x0437, 0x1E0A => 0x1E0B, 0x0147 => 0x0148, 0x0104 => 0x0105, 0x0408 => 0x0458,
                    0x014C => 0x014D, 0x00CD => 0x00ED, 0x0059 => 0x0079, 0x010A => 0x010B, 0x038F => 0x03CE,
                    0x0052 => 0x0072, 0x0410 => 0x0430, 0x0405 => 0x0455, 0x0402 => 0x0452, 0x0126 => 0x0127,
                    0x0136 => 0x0137, 0x012A => 0x012B, 0x038A => 0x03AF, 0x042B => 0x044B, 0x004C => 0x006C,
                    0x0397 => 0x03B7, 0x0124 => 0x0125, 0x0218 => 0x0219, 0x00DB => 0x00FB, 0x011E => 0x011F,
                    0x041E => 0x043E, 0x1E40 => 0x1E41, 0x039D => 0x03BD, 0x0106 => 0x0107, 0x03AB => 0x03CB,
                    0x0426 => 0x0446, 0x00DE => 0x00FE, 0x00C7 => 0x00E7, 0x03AA => 0x03CA, 0x0421 => 0x0441,
                    0x0412 => 0x0432, 0x010E => 0x010F, 0x00D8 => 0x00F8, 0x0057 => 0x0077, 0x011A => 0x011B,
                    0x0054 => 0x0074, 0x004A => 0x006A, 0x040B => 0x045B, 0x0406 => 0x0456, 0x0102 => 0x0103,
                    0x039B => 0x03BB, 0x00D1 => 0x00F1, 0x041D => 0x043D, 0x038C => 0x03CC, 0x00C9 => 0x00E9,
                    0x00D0 => 0x00F0, 0x0407 => 0x0457, 0x0122 => 0x0123
                );
            }
            
            $cnt = count($uni);
            for ($i = 0; $i < $cnt; $i++) {
                if (isset($UTF8_UPPER_TO_LOWER[$uni[$i]])) {
                    $uni[$i] = $UTF8_UPPER_TO_LOWER[$uni[$i]];
                }
            }
            
            return self::from_unicode($uni);
        }
    }
    
    /**
     * UTF-8 aware alternative to strtoupper.
     *
     * Make a string uppercase.
     *
     * The concept of a characters "case" only exists is some alphabets such as
     * Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does not exist in
     * the Chinese alphabet, for example. See Unicode Standard Annex #21: Case Mappings.
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see http://www.php.net/strtoupper
     * @see utf8_to_unicode
     * @see utf8_from_unicode
     * @see http://www.unicode.org/reports/tr21/tr21-5.html
     * @see http://dev.splitbrain.org/view/darcs/dokuwiki/inc/utf8.php
     * @param string $str
     * @return mixed either string in lowercase or FALSE is UTF-8 invalid
     */
    public static function mirror_strtoupper($str) {
        // Checks to see if the mbstring extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available && function_exists('mb_strtoupper')) {
            return mb_strtoupper($str);
        } else {
            static $UTF8_LOWER_TO_UPPER;
            
            $uni = self::to_unicode($str);
            if ( ! $uni) {
                return FALSE;
            }
            
            if ( ! $UTF8_LOWER_TO_UPPER) {
                $UTF8_LOWER_TO_UPPER = array(
                    0x0061 => 0x0041, 0x03C6 => 0x03A6, 0x0163 => 0x0162, 0x00E5 => 0x00C5, 0x0062 => 0x0042,
                    0x013A => 0x0139, 0x00E1 => 0x00C1, 0x0142 => 0x0141, 0x03CD => 0x038E, 0x0101 => 0x0100,
                    0x0491 => 0x0490, 0x03B4 => 0x0394, 0x015B => 0x015A, 0x0064 => 0x0044, 0x03B3 => 0x0393,
                    0x00F4 => 0x00D4, 0x044A => 0x042A, 0x0439 => 0x0419, 0x0113 => 0x0112, 0x043C => 0x041C,
                    0x015F => 0x015E, 0x0144 => 0x0143, 0x00EE => 0x00CE, 0x045E => 0x040E, 0x044F => 0x042F,
                    0x03BA => 0x039A, 0x0155 => 0x0154, 0x0069 => 0x0049, 0x0073 => 0x0053, 0x1E1F => 0x1E1E,
                    0x0135 => 0x0134, 0x0447 => 0x0427, 0x03C0 => 0x03A0, 0x0438 => 0x0418, 0x00F3 => 0x00D3,
                    0x0440 => 0x0420, 0x0454 => 0x0404, 0x0435 => 0x0415, 0x0449 => 0x0429, 0x014B => 0x014A,
                    0x0431 => 0x0411, 0x0459 => 0x0409, 0x1E03 => 0x1E02, 0x00F6 => 0x00D6, 0x00F9 => 0x00D9,
                    0x006E => 0x004E, 0x0451 => 0x0401, 0x03C4 => 0x03A4, 0x0443 => 0x0423, 0x015D => 0x015C,
                    0x0453 => 0x0403, 0x03C8 => 0x03A8, 0x0159 => 0x0158, 0x0067 => 0x0047, 0x00E4 => 0x00C4,
                    0x03AC => 0x0386, 0x03AE => 0x0389, 0x0167 => 0x0166, 0x03BE => 0x039E, 0x0165 => 0x0164,
                    0x0117 => 0x0116, 0x0109 => 0x0108, 0x0076 => 0x0056, 0x00FE => 0x00DE, 0x0157 => 0x0156,
                    0x00FA => 0x00DA, 0x1E61 => 0x1E60, 0x1E83 => 0x1E82, 0x00E2 => 0x00C2, 0x0119 => 0x0118,
                    0x0146 => 0x0145, 0x0070 => 0x0050, 0x0151 => 0x0150, 0x044E => 0x042E, 0x0129 => 0x0128,
                    0x03C7 => 0x03A7, 0x013E => 0x013D, 0x0442 => 0x0422, 0x007A => 0x005A, 0x0448 => 0x0428,
                    0x03C1 => 0x03A1, 0x1E81 => 0x1E80, 0x016D => 0x016C, 0x00F5 => 0x00D5, 0x0075 => 0x0055,
                    0x0177 => 0x0176, 0x00FC => 0x00DC, 0x1E57 => 0x1E56, 0x03C3 => 0x03A3, 0x043A => 0x041A,
                    0x006D => 0x004D, 0x016B => 0x016A, 0x0171 => 0x0170, 0x0444 => 0x0424, 0x00EC => 0x00CC,
                    0x0169 => 0x0168, 0x03BF => 0x039F, 0x006B => 0x004B, 0x00F2 => 0x00D2, 0x00E0 => 0x00C0,
                    0x0434 => 0x0414, 0x03C9 => 0x03A9, 0x1E6B => 0x1E6A, 0x00E3 => 0x00C3, 0x044D => 0x042D,
                    0x0436 => 0x0416, 0x01A1 => 0x01A0, 0x010D => 0x010C, 0x011D => 0x011C, 0x00F0 => 0x00D0,
                    0x013C => 0x013B, 0x045F => 0x040F, 0x045A => 0x040A, 0x00E8 => 0x00C8, 0x03C5 => 0x03A5,
                    0x0066 => 0x0046, 0x00FD => 0x00DD, 0x0063 => 0x0043, 0x021B => 0x021A, 0x00EA => 0x00CA,
                    0x03B9 => 0x0399, 0x017A => 0x0179, 0x00EF => 0x00CF, 0x01B0 => 0x01AF, 0x0065 => 0x0045,
                    0x03BB => 0x039B, 0x03B8 => 0x0398, 0x03BC => 0x039C, 0x045C => 0x040C, 0x043F => 0x041F,
                    0x044C => 0x042C, 0x00FE => 0x00DE, 0x00F0 => 0x00D0, 0x1EF3 => 0x1EF2, 0x0068 => 0x0048,
                    0x00EB => 0x00CB, 0x0111 => 0x0110, 0x0433 => 0x0413, 0x012F => 0x012E, 0x00E6 => 0x00C6,
                    0x0078 => 0x0058, 0x0161 => 0x0160, 0x016F => 0x016E, 0x03B1 => 0x0391, 0x0457 => 0x0407,
                    0x0173 => 0x0172, 0x00FF => 0x0178, 0x006F => 0x004F, 0x043B => 0x041B, 0x03B5 => 0x0395,
                    0x0445 => 0x0425, 0x0121 => 0x0120, 0x017E => 0x017D, 0x017C => 0x017B, 0x03B6 => 0x0396,
                    0x03B2 => 0x0392, 0x03AD => 0x0388, 0x1E85 => 0x1E84, 0x0175 => 0x0174, 0x0071 => 0x0051,
                    0x0437 => 0x0417, 0x1E0B => 0x1E0A, 0x0148 => 0x0147, 0x0105 => 0x0104, 0x0458 => 0x0408,
                    0x014D => 0x014C, 0x00ED => 0x00CD, 0x0079 => 0x0059, 0x010B => 0x010A, 0x03CE => 0x038F,
                    0x0072 => 0x0052, 0x0430 => 0x0410, 0x0455 => 0x0405, 0x0452 => 0x0402, 0x0127 => 0x0126,
                    0x0137 => 0x0136, 0x012B => 0x012A, 0x03AF => 0x038A, 0x044B => 0x042B, 0x006C => 0x004C,
                    0x03B7 => 0x0397, 0x0125 => 0x0124, 0x0219 => 0x0218, 0x00FB => 0x00DB, 0x011F => 0x011E,
                    0x043E => 0x041E, 0x1E41 => 0x1E40, 0x03BD => 0x039D, 0x0107 => 0x0106, 0x03CB => 0x03AB,
                    0x0446 => 0x0426, 0x00FE => 0x00DE, 0x00E7 => 0x00C7, 0x03CA => 0x03AA, 0x0441 => 0x0421,
                    0x0432 => 0x0412, 0x010F => 0x010E, 0x00F8 => 0x00D8, 0x0077 => 0x0057, 0x011B => 0x011A,
                    0x0074 => 0x0054, 0x006A => 0x004A, 0x045B => 0x040B, 0x0456 => 0x0406, 0x0103 => 0x0102,
                    0x03BB => 0x039B, 0x00F1 => 0x00D1, 0x043D => 0x041D, 0x03CC => 0x038C, 0x00E9 => 0x00C9,
                    0x00F0 => 0x00D0, 0x0457 => 0x0407, 0x0123 => 0x0122
                );
            }
            
            $cnt = count($uni);
            for ($i = 0; $i < $cnt; $i++) {
                if (isset($UTF8_LOWER_TO_UPPER[$uni[$i]])) {
                    $uni[$i] = $UTF8_LOWER_TO_UPPER[$uni[$i]];
                }
            }
            
            return self::from_unicode($uni);
        }
    }
    
    /**
     * UTF-8 aware alternative to ucwords.
     *
     * Uppercase the first character of each word in a string
     *
     * @see http://php.net/manual/en/function.ucwords.php
     * @uses utf8_substr_replace
     * @uses utf8_strtoupper
     * @param string
     * @return string with first char of each word uppercase
     */
    public static function mirror_ucwords($str) {
        // Checks to see if the mbstring extension is available
        self::check_mbstring();
        
        if (self::$mbstring_available && function_exists('mb_convert_case')) {
            return mb_convert_case($str, MB_CASE_TITLE);
        } else {
            // Note: [\x0c\x09\x0b\x0a\x0d\x20] matches;
            // Form feeds, horizontal tabs, vertical tabs, linefeeds and carriage returns
            // This corresponds to the definition of a "word" defined at http://www.php.net/ucwords
            $pattern = '/(^|([\x0c\x09\x0b\x0a\x0d\x20]+))([^\x0c\x09\x0b\x0a\x0d\x20]{1})[^\x0c\x09\x0b\x0a\x0d\x20]*/u';
            
            return preg_replace_callback($pattern, function ($match) {
                $leadingws = $match[2];
                $ucfirst = self::mirror_strtoupper($match[3]);
                $ucword = self::mirror_substr_replace(ltrim($match[0]), $ucfirst, 0, 1);
                
                return $leadingws.$ucword;
            }, $str);
        }
    }
    
    
    /**
     * UTF-8 aware alternative to wordwrap.
     *
     * Wraps a string to a given number of characters
     * 
     * @see https://github.com/zendframework/zf2/blob/master/library/Zend/Text/MultiByte.php
     * @param  string  $string
     * @param  integer $width
     * @param  string  $break
     * @param  boolean $cut
     * @param  string  $charset
     * @return string
     */
    public static function mirror_wordwrap($str, $width = 75, $break = "\n", $cut = FALSE, $charset = 'utf-8') {
        $string_width = iconv_strlen($str, $charset);
        $break_width = iconv_strlen($break, $charset);
        
        if (strlen($str) === 0) {
            return '';
        }
        
        if ($break_width === NULL) {
            halt('A System Error Was Encountered', 'PHP_UTF8::mirror_wordwrap(): Break string cannot be empty', 'sys_error');
        }
        
        if ($width === 0 && $cut) {
            halt('A System Error Was Encountered', 'PHP_UTF8::mirror_wordwrap(): Cannot force cut when width is zero', 'sys_error');
        }
        
        $result = '';
        $last_start = $last_space = 0;
        
        for ($current = 0; $current < $string_width; $current++) {
            $char = iconv_substr($str, $current, 1, $charset);
            
            $possibleBreak = $char;
            if ($break_width !== 1) {
                $possibleBreak = iconv_substr($str, $current, $break_width, $charset);
            }
            
            if ($possibleBreak === $break) {
                $result .= iconv_substr($str, $last_start, $current - $last_start + $break_width, $charset);
                $current += $break_width - 1;
                $last_start = $last_space = $current + 1;
                continue;
            }
            
            if ($char === ' ') {
                if ($current - $last_start >= $width) {
                    $result .= iconv_substr($str, $last_start, $current - $last_start, $charset) . $break;
                    $last_start = $current + 1;
                }
                
                $last_space = $current;
                continue;
            }
            
            if ($current - $last_start >= $width && $cut && $last_start >= $last_space) {
                $result .= iconv_substr($str, $last_start, $current - $last_start, $charset) . $break;
                $last_start = $last_space = $current;
                continue;
            }
            
            if ($current - $last_start >= $width && $last_start < $last_space) {
                $result .= iconv_substr($str, $last_start, $last_space - $last_start, $charset) . $break;
                $last_start = $last_space = $last_space + 1;
                continue;
            }
        }
        
        if ($last_start !== $current) {
            $result .= iconv_substr($str, $last_start, $current - $last_start, $charset);
        }
        
        return $result;
    }

    
    /**
     * UTF-8 aware alternative to ucfirst.
     *
     * Make a string's first character uppercase
     *
     * @package php-utf8
     * @subpackage functions
     * @see http://www.php.net/ucfirst
     * @uses utf8_strtoupper
     * @param string $str
     * @return string A string with the first character in Uppercase (if applicable).
     */
    public static function mirror_ucfirst($str) {
        switch (self::mirror_strlen($str)) {
            case 0:
                return '';
                break;
            case 1:
                return self::mirror_strtoupper($str);
                break;
            default:
                preg_match('/^(.{1})(.*)$/us', $str, $matches);
                return self::mirror_strtoupper($matches[1]).$matches[2];
                break;
        }
    }
    
    /**
     * UTF-8 aware replacement for ltrim().
     *
     * Use these only if you are supplying the charlist optional arg and it contains
     * UTF-8 characters. Otherwise trim will work normally on a UTF-8 string.
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see http://www.php.net/ltrim
     * @param string $str
     * @param string $charlist
     * @return string
     */
    public static function mirror_ltrim($str, $charlist = '') {
        if (empty($charlist)) {
            return ltrim($str);
        }
        
        // Quote charlist for use in a characterclass
        $charlist = preg_replace('!([\\\\\\-\\]\\[/^])!', '\\\${1}', $charlist);
        
        return preg_replace('/^['.$charlist.']+/u', '', $str);
    }
    
    /**
     * UTF-8 aware replacement for rtrim().
     *
     * Use these only if you are supplying the charlist optional arg and it contains
     * UTF-8 characters. Otherwise trim will work normally on a UTF-8 string.
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see http://www.php.net/rtrim
     * @param string $str
     * @param string $charlist
     * @return string
     */
    public static function mirror_rtrim($str, $charlist= '') {
        if (empty($charlist)) {
            return rtrim($str);
        }
        
        // Quote charlist for use in a characterclass
        $charlist = preg_replace('!([\\\\\\-\\]\\[/^])!', '\\\${1}', $charlist);
        
        return preg_replace('/['.$charlist.']+$/u', '', $str);
    }
    
    /**
     * UTF-8 aware replacement for trim().
     *
     * Use these only if you are supplying the charlist optional arg and it contains
     * UTF-8 characters. Otherwise trim will work normally on a UTF-8 string.
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see http://www.php.net/trim
     * @param string $str
     * @param boolean $charlist
     * @return string
     */
    public static function mirror_trim($str, $charlist= '') {
        if (empty($charlist)) {
            return trim($str);
        }
        return self::mirror_ltrim(self::mirror_rtrim($str, $charlist), $charlist);
    }
    
    /**
     * UTF-8 aware substr_replace.
     *
     * @package php-utf8
     * @subpackage functions
     * @see http://www.php.net/substr_replace
     * @uses utf8_strlen
     * @uses utf8_substr
     * @param string $str
     * @param string $repl
     * @param int $start
     * @param int $length
     * @return string
     */
    public static function mirror_substr_replace($str, $repl, $start, $length = NULL) {
        preg_match_all('/./us', $str, $ar);
        preg_match_all('/./us', $repl, $rar);
        
        $length = is_int($length) ? $length : self::mirror_strlen($str);
        
        array_splice($ar[0], $start, $length, $rar[0]);
        
        return implode($ar[0]);
    }
    
    /**
     * UTF-8 aware alternative to strspn.
     *
     * Find length of initial segment matching mask.
     *
     * @package php-utf8
     * @subpackage functions
     * @see http://www.php.net/strspn
     * @uses utf8_strlen
     * @uses utf8_substr
     * @param string $str
     * @param string $mask
     * @param int $start
     * @param int $length
     * @return int
     */
    public static function mirror_strspn($str, $mask, $start = NULL, $length = NULL) {
        $mask = preg_replace('!([\\\\\\-\\]\\[/^])!', '\\\${1}', $mask);
        
        if ($start !== NULL || $length !== NULL) {
            $str = self::mirror_substr($str, $start, $length);
        }
        
        preg_match('/^['.$mask.']+/u', $str, $matches);
        
        if (isset($matches[0])) {
            return self::mirror_strlen($matches[0]);
        }
        
        return 0;
    }
    
    /**
     * UTF-8 aware alternative to strrev.
     *
     * Reverse a string.
     *
     * @package php-utf8
     * @subpackage functions
     * @see http://www.php.net/strrev
     * @param string $str UTF-8 encoded
     * @return string characters in string reverses
     */
    public static function mirror_strrev($str) {
        preg_match_all('/./us', $str, $ar);
        return implode(array_reverse($ar[0]));
    }
    
    /**
     * UTF-8 aware alternative to stristr.
     *
     * Find first occurrence of a string using case insensitive comparison.
     *
     * @package php-utf8
     * @subpackage functions
     * @see http://us1.php.net/manual/en/function.stristr.php
     * @uses utf8_strtolower
     * @param string $str
     * @param string $search
     * @return int
     */
    public static function mirror_stristr($str, $search) {
        if (strlen($search) == 0) {
            return $str;
        }
        
        $lstr = self::mirror_strtolower($str);
        $lsearch = self::mirror_strtolower($search);
        preg_match('/^(.*)'.preg_quote($lsearch).'/Us', $lstr, $matches);
        
        if (count($matches) == 2) {
            return self::mirror_substr($str, self::mirror_strlen($matches[1]));
        }
        
        return FALSE;
    }
    
    /**
     * UTF-8 aware alternative to strcspn.
     *
     * Find length of initial segment not matching mask.
     *
     * @package php-utf8
     * @subpackage functions
     * @see http://www.php.net/strcspn
     * @uses utf8_strlen
     * @uses utf8_substr
     * @param string
     * @return int
     */
    public static function mirror_strcspn($str, $mask, $start = NULL, $length = NULL) {
        if (empty($mask) || strlen($mask) == 0) {
            return NULL;
        }
        
        $mask = preg_replace('!([\\\\\\-\\]\\[/^])!', '\\\${1}', $mask);
        
        if ($start !== NULL || $length !== NULL) {
            $str = self::mirror_substr($str, $start, $length);
        }
        
        preg_match('/^[^'.$mask.']+/u', $str, $matches);
        
        if (isset($matches[0])) {
            return self::mirror_strlen($matches[0]);
        }
        
        return 0;
    }
    
    /**
     * UTF-8 aware alternative to strcasecmp.
     *
     * A case insensivite string comparison
     *
     * @package php-utf8
     * @subpackage functions
     * @see http://www.php.net/strcasecmp
     * @uses utf8_strtolower
     * @param string $strX
     * @param string $strY
     * @return int
     */
    public static function mirror_strcasecmp($str_x, $str_y) {
        $str_x = self::mirror_strtolower($str_x);
        $str_y = self::mirror_strtolower($str_y);
        
        return strcmp($str_x, $str_y);
    }
    
    /**
     * UTF-8 aware alternative to str_split.
     *
     * Convert a string to an array
     *
     * @package php-utf8
     * @subpackage functions
     * @see http://www.php.net/str_split
     * @uses utf8_strlen
     * @param string $str A UTF-8 encoded string
     * @param int $split_len A number of characters to split string by
     * @return string characters in string reverses
     */
    public static function mirror_str_split($str, $split_len = 1) {
        if ( ! preg_match('/^[0-9]+$/', $split_len) || $split_len < 1) {
            return FALSE;
        }
        
        $len = self::mirror_strlen($str);
        if ($len <= $split_len) {
            return array($str);
        }
        
        preg_match_all('/.{'.$split_len.'}|[^\x00]{1,'.$split_len.'}$/us', $str, $ar);
        
        return $ar[0];
    }
    
    /**
     * UTF-8 aware alternative to str_pad.
     *
     * $pad_str may contain multi-byte characters.
     *
     * @author Oliver Saunders <oliver@osinternetservices.com>
     * @package php-utf8
     * @subpackage functions
     * @see http://www.php.net/str_pad
     * @uses utf8_substr
     * @param string $input
     * @param int $length
     * @param string $pad_str
     * @param int $type ( same constants as str_pad )
     * @return string
     */
    public static function mirror_str_pad($input, $length, $pad_str=' ', $type = STR_PAD_RIGHT) {
        $input_len = self::mirror_strlen($input);
        if ($length <= $input_len) {
            return $input;
        }
        
        $pad_str_len = self::mirror_strlen($pad_str);
        $pad_len = $length - $input_len;
        
        if ($type == STR_PAD_RIGHT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            return self::mirror_substr($input.str_repeat($pad_str, $repeat_times), 0, $length);
        }
        
        if ($type == STR_PAD_LEFT) {
            $repeat_times = ceil($pad_len / $pad_str_len);
            return self::mirror_substr(str_repeat($pad_str, $repeat_times), 0, floor($pad_len)).$input;
        }
        
        if ($type == STR_PAD_BOTH) {
            $pad_len /= 2;
            $pad_amount_left = floor($pad_len);
            $pad_amount_right = ceil($pad_len);
            $repeat_times_left = ceil($pad_amount_left / $pad_str_len);
            $repeat_times_right = ceil($pad_amount_right / $pad_str_len);
            
            $padding_left = self::mirror_substr(str_repeat($pad_str, $repeat_times_left), 0, $pad_amount_left);
            $padding_right = self::mirror_substr(str_repeat($pad_str, $repeat_times_right), 0, $pad_amount_right);
            
            return $padding_left.$input.$padding_right;
        }
        
        halt('A System Error Was Encountered', "PHP_UTF8::mirror_str_pad(): Unknown padding type ({$type})", 'sys_error');
    }
    
    
    /**
     * UTF-8 aware alternative to str_ireplace.
     *
     * Case-insensitive version of str_replace
     * This function is not fast and gets slower if $search/$replace is array.
     * It assumes that the lower and uppercase versions of a UTF-8 character will
     * have the same length in bytes which is currently true given the hash table
     * to strtoLower.
     *
     * @package php-utf8
     * @subpackage functions
     * @uses utf8_strtoLower
     * @see http://www.php.net/str_ireplace
     * @param string $search
     * @param string $replace
     * @param string $str
     * @param int $count
     * @return string
     */
    public static function mirror_str_ireplace($search, $replace, $str, $count = NULL) {
        if ( ! is_array($search)) {
            $slen = self::mirror_strlen($search);
            
            if ($slen == 0)
                return $str;
                
            $lendif = self::mirror_strlen($replace) - self::mirror_strlen($search);
            $search = self::mirror_strtolower($search);
            
            $search = preg_quote($search);
            $lstr = self::mirror_strtolower($str);
            $i = 0;
            $matched = 0;
            
            while (preg_match('/(.*)'.$search.'/Us', $lstr, $matches)) {
                if ($i === $count) {
                    break;
                }
                
                $mlen = self::mirror_strlen($matches[0]);
                $lstr = self::mirror_substr($lstr, $mlen);
                $str = self::mirror_substr_replace($str, $replace, $matched + self::mirror_strlen($matches[1]), $slen);
                $matched += $mlen + $lendif;
                $i++;
            }
            
            return $str;
        } else {
            foreach (array_keys($search) as $k) {
                if (is_array($replace)) {
                    if(array_key_exists($k, $replace)) {
                        $str = self::mirror_str_ireplace($search[$k], $replace[$k], $str, $count);
                    } else {
                        $str = self::mirror_str_ireplace($search[$k], '', $str, $count);
                    }
                } else {
                    $str = self::mirror_str_ireplace($search[$k], $replace, $str, $count);
                }
            }
            
            return $str;
        }
    }
    
    /**
     * UTF-8 aware alternative to ord.
     *
     * Returns the unicode ordinal for a character
     *
     * @see http://www.php.net/manual/en/function.ord.php#46267
     * @param string $chr UTF-8 encoded character
     * @return int unicode ordinal for the character
     * @package php-utf8
     * @subpackage functions
     */
    public static function mirror_ord($chr) {
        $ord0 = ord($chr);
        
        if ($ord0 >= 0 && $ord0 <= 127) {
            return $ord0;
        }
        
        if ( ! isset($chr[1])) {
            halt('A System Error Was Encountered', 'PHP_UTF8::mirror_ord(): Short sequence - at least 2 bytes expected, only 1 seen', 'sys_error');
        }
        
        $ord1 = ord($chr[1]);
        if ($ord0 >= 192 && $ord0 <= 223) {
            return ($ord0 - 192) * 64 + ($ord1 - 128);
        }
        
        if ( ! isset($chr[2])) {
            halt('A System Error Was Encountered', 'PHP_UTF8::mirror_ord(): Short sequence - at least 3 bytes expected, only 2 seen', 'sys_error');
        }
        
        $ord2 = ord($chr[2]);
        if ($ord0 >= 224 && $ord0 <= 239) {
            return ($ord0 - 224) * 4096 + ($ord1 - 128) * 64 + ($ord2 - 128);
        }
        
        if ( ! isset($chr[3])) {
            halt('A System Error Was Encountered', 'PHP_UTF8::mirror_ord(): Short sequence - at least 4 bytes expected, only 3 seen', 'sys_error');
        }
        
        $ord3 = ord($chr[3]);
        if ($ord0 >= 240 && $ord0 <= 247) {
            return ($ord0 - 240) * 262144 + ($ord1 - 128) * 4096 + ($ord2 - 128) * 64 + ($ord3 - 128);
        }
        
        if ( ! isset($chr[4])) {
            halt('A System Error Was Encountered', 'PHP_UTF8::mirror_ord(): Short sequence - at least 5 bytes expected, only 4 seen', 'sys_error');
        }
        
        $ord4 = ord($chr[4]);
        if ($ord0 >= 248 && $ord0 <= 251) {
            return ($ord0 - 248) * 16777216 + ($ord1 - 128) * 262144 + ($ord2 - 128) * 4096 + ($ord3 - 128) * 64 + ($ord4 - 128);
        }
        
        if ( ! isset($chr[5])) {
            halt('A System Error Was Encountered', 'PHP_UTF8::mirror_ord(): Short sequence - at least 6 bytes expected, only 5 seen', 'sys_error');
        }
        
        if ($ord0 >= 252 && $ord0 <= 253) {
            return ($ord0 - 252) * 1073741824 + ($ord1 - 128) * 16777216 + ($ord2 - 128) * 262144 + ($ord3 - 128) * 4096 + ($ord4 - 128) * 64 + (ord($chr[5]) - 128);
        }
        
        if ($ord0 >= 254 && $ord0 <= 255) {
            halt('A System Error Was Encountered', "PHP_UTF8::mirror_ord(): Invalid UTF-8 with surrogate ordinal {$ord0}", 'sys_error');
        }
    }
    
    /**
     * Tests a string as to whether it's valid UTF-8 and supported by the Unicode standard
     *
     * Tools for validing a UTF-8 string is well formed.
     * The Original Code is Mozilla Communicator client code.
     * The Initial Developer of the Original Code is Netscape Communications Corporation.
     * Portions created by the Initial Developer are Copyright (C) 1998 the Initial Developer. All Rights Reserved.
     * Ported to PHP by Henri Sivonen (http://hsivonen.iki.fi)
     *
     * @author <hsivonen@iki.fi>
     * @see http://lxr.mozilla.org/seamonkey/source/intl/uconv/src/nsUTF8ToUnicode.cpp
     * @see http://lxr.mozilla.org/seamonkey/source/intl/uconv/src/nsUnicodeToUTF8.cpp
     * @see http://hsivonen.iki.fi/php-utf8/
     * @see utf8_compliant
     * @param string $str UTF-8 encoded string
     * @return boolean TRUE if valid
     */
    public static function is_valid_utf8($str) {
        $mState = 0;  // Cached expected number of octets after the current octet
                      // until the beginning of the next UTF8 character sequence
        $mUcs4 = 0;  // Cached Unicode character
        $mBytes = 1;  // Cached expected number of octets in the current sequence
        
        $len = strlen($str);
        
        for ($i = 0; $i < $len; $i++) {
            $in = ord($str{$i});
            
            if ($mState == 0) {
                // When mState is zero we expect either a US-ASCII character or a multi-octet sequence.
                if (0 == (0x80 & ($in))) {
                    $mBytes = 1; // US-ASCII, pass straight through
                } elseif (0xC0 == (0xE0 & ($in))) {
                    // First octet of 2 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x1F) << 6;
                    $mState = 1;
                    $mBytes = 2;
                } elseif (0xE0 == (0xF0 & ($in))) {
                    // First octet of 3 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x0F) << 12;
                    $mState = 2;
                    $mBytes = 3;
                } elseif (0xF0 == (0xF8 & ($in))) {
                    // First octet of 4 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x07) << 18;
                    $mState = 3;
                    $mBytes = 4;
                } elseif (0xF8 == (0xFC & ($in))) {
                    /* First octet of 5 octet sequence.
                     *
                     * This is illegal because the encoded codepoint must be either
                     * (a) not the shortest form or
                     * (b) outside the Unicode range of 0-0x10FFFF.
                     * Rather than trying to resynchronize, we will carry on until the end
                     * of the sequence and let the later error handling code catch it.
                     */
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x03) << 24;
                    $mState = 4;
                    $mBytes = 5;
                } elseif (0xFC == (0xFE & ($in))) {
                    // First octet of 6 octet sequence, see comments for 5 octet sequence.
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 1) << 30;
                    $mState = 5;
                    $mBytes = 6;
                } else {
                    // Current octet is neither in the US-ASCII range nor a legal first octet of a multi-octet sequence.
                    return FALSE;
                }
            } else {
                // When mState is non-zero, we expect a continuation of the multi-octet sequence
                if (0x80 == (0xC0 & ($in))) {
                    // Legal continuation.
                    $shift = ($mState - 1) * 6;
                    $tmp = $in;
                    $tmp = ($tmp & 0x0000003F) << $shift;
                    $mUcs4 |= $tmp;
                    
                    /**
                     * End of the multi-octet sequence. mUcs4 now contains the final
                     * Unicode codepoint to be output
                     */
                    if (0 == --$mState) {
                        /*
                         * Check for illegal sequences and codepoints.
                         */
                        // From Unicode 3.1, non-shortest form is illegal
                        if (((2 == $mBytes) && ($mUcs4 < 0x0080)) || ((3 == $mBytes) && ($mUcs4 < 0x0800)) ||
                                ((4 == $mBytes) && ($mUcs4 < 0x10000)) || (4 < $mBytes) ||
                                // From Unicode 3.2, surrogate characters are illegal
                                (($mUcs4 & 0xFFFFF800) == 0xD800) ||
                                // Codepoints outside the Unicode range are illegal
                                ($mUcs4 > 0x10FFFF))
                        {
                            return FALSE;
                        }
                        
                        // Initialize UTF8 cache
                        $mState = 0;
                        $mUcs4 = 0;
                        $mBytes = 1;
                    }
                } else {
                    /**
                     * ((0xC0 & (*in) != 0x80) && (mState != 0))
                     * Incomplete multi-octet sequence.
                     */
                    return FALSE;
                }
            }
        }
        
        return TRUE;
    }
    
}

// End of file: ./system/core/php_utf8.php 