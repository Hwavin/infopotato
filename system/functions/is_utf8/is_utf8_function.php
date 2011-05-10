<?php
/**
 * 识别UTF-8编码 判断一个字符串是否为utf-8编码 2011-04-18 sz
 * http://www.phppan.com/2011/04/utf8/
 */

/**
 * 方法一
 * 依据UTF-8编码的字节布局，以逆向思维，通过判断其不是UTF-8编码来判断正确性。
 * 此算法来源于<<Building Scalable Web Sites>>
 * @param <type> $string
 */
function is_utf8($string) {
    $pattern = '[\xC0-\xDF]([^\x80-\xBF]|$)'; //  匹配110xxxxx，其后应该有1个字节，如果此字节无法匹配10xxxxxx，或为结束符，则不是utf-8
    $pattern .= '|[\xE0-\xEF].{0,1}([^\x80-\xBF]|$)'; //匹配1110xxxx，其后应该有2个字节，
    $pattern .= '|[\xF0-\xF7].{0,2}([^\x80-\xBF]|$)';//匹配11110xxx，其后应该有3个字节，
    $pattern .= '|[\xF8-\xFB].{0,3}([^\x80-\xBF]|$)';//匹配111110xx，其后应该有4个字节，
    $pattern .= '|[\xFC-\xFD].{0,4}([^\x80-\xBF]|$)';//匹配1111110x，其后应该有5个字节，
    $pattern .= '|[\xFE-\xFE].{0,5}([^\x80-\xBF]|$)';//匹配1111110，其后应该有6个字节，
    $pattern .= '|[\x00-\x7F][\x80-\xBF]';
    $pattern .= '|[\xC0-\xDF].[\x80-\xBF]';
    $pattern .= '|[\xE0-\xEF]..[\x80-\xBF]';
    $pattern .= '|[\xF0-\xF7]...[\x80-\xBF]';
    $pattern .= '|[\xF8-\xFB]....[\x80-\xBF]';
    $pattern .= '|[\xFC-\xFD].....[\x80-\xBF]';
    $pattern .= '|[\xFE-\xFE]......[\x80-\xBF]';
    $pattern .= '|^[\x80-\xBF]';

    return preg_match("!$pattern!", $string) ? FALSE : TRUE;
}

/**
 * 方法二
 * @link http://www.w3.org/International/questions/qa-forms-utf-8.en.php
 * @param <type> $string
 * @return <type>
 */
function is_utf8($string) {
    $pattern = '/^(?:';
    $pattern .= '[\x09\x0A\x0D\x20-\x7E]';             # ASCII
    $pattern .= '|[\xC2-\xDF][\x80-\xBF]';             # non-overlong 2-byte
    $pattern .= '|\xE0[\xA0-\xBF][\x80-\xBF]';         # excluding overlongs
    $pattern .= '|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}';  # straight 3-byte
    $pattern .= '|\xED[\x80-\x9F][\x80-\xBF]';         # excluding surrogates
    $pattern .= '|\xF0[\x90-\xBF][\x80-\xBF]{2}';      # planes 1-3
    $pattern .= '|[\xF1-\xF3][\x80-\xBF]{3}';          # planes 4-15
    $pattern .= '|  \xF4[\x80-\x8F][\x80-\xBF]{2}';
    $pattern .= ')*$/xs';

    return preg_match($pattern, $string);
}

/**
 * 方法三
 * @param <type> $string
 * @return <type>
 */
function is_utf8($string) {
    if (preg_match("/^([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){1}/", $string) == TRUE
            || preg_match("/([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){1}$/", $string) == TRUE
            || preg_match("/([" . chr(228) . "-" . chr(233) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}[" . chr(128) . "-" . chr(191) . "]{1}){2,}/", $string) == TRUE
    ) {
        return TRUE;
    }

    return FALSE;
}


/*
第一种和第二种基本类似，结果也基本类似，但是对于“营业”等字符串无法准确的识别。 第三种方法对于上面提到的字符串可以正确识别，但是对于“欧舒丹”等字符串却无法识别。
*/

/* End of file: ./system/functions/is_utf8/is_utf8_function.php */