<?php
/**
 * Outputs or returns a syntax highlighted version of the given PHP code using the colors defined in the built-in syntax highlighter for PHP
 *
 * @param string $str the file or string to highlight
 * @param boolean $show whether or not to output the result
 * @return string output the highlighted PHP code if $show is TRUE
 */
function highlight_php($str, $show = FALSE) {
    if (file_exists($str)) {
        $str = file_get_contents($str);
    }
    $str = stripslashes(trim($str));
    // The highlight string function encodes and highlights
    // brackets so we need them to start raw
    $str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);

    // Replace any existing PHP tags to temporary markers so they don't accidentally
    // break the string out of PHP, and thus, thwart the highlighting.

    $str = str_replace(array('&lt;?php', '?&gt;',  '\\'), array('phptagopen', 'phptagclose', 'backslashtmp'), $str);

    // The highlight_string function requires that the text be surrounded
    // by PHP tags.  Since we don't know if A) the submitted text has PHP tags,
    // or B) whether the PHP tags enclose the entire string, we will add our
    // own PHP tags around the string along with some markers to make replacement easier later

    $str = '<?php //tempstart'."\n".$str.'//tempend ?>'; // <?

    // All the magic happens here, baby!
    $str = highlight_string($str, TRUE);

    // Prior to PHP 5, the highlight function used icky font tags
    // so we'll replace them with span tags.
    if (abs(phpversion()) < 5) {
        $str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
        $str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
    }

    // Remove our artificially added PHP
    $str = preg_replace("#\<code\>.+?//tempstart\<br />\</span\>#is", "<code>\n", $str);
    $str = preg_replace("#\<code\>.+?//tempstart\<br />#is", "<code>\n", $str);
    $str = preg_replace("#//tempend.+#is", "</span>\n</code>", $str);

    // Replace our markers back to PHP tags.
    $str = str_replace(array('phptagopen', 'phptagclose', 'backslashtmp'), array('&lt;?php', '?&gt;', '\\'), $str); //<?
    $line = explode("<br />", rtrim(ltrim($str,'<code>'), '</code>'));
    $result = '<div class="code"><ol>'; // You can style "code" in CSS
    foreach ($line as $key => $val) {
        $result .= '<li>'.$val.'</li>';
    }
    $result .= '</ol></div>';
    $result = str_replace("\n", "", $result);
    if ($show !== FALSE) {
        echo $result;
    } else {
        return $result;
    }
}

// End of file: ./system/scripts/highlight_php/highlight_php_script.php
