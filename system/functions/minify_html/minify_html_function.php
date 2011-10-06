<?php
/**
 * Minify html
 *
 * Based on PeecFW HTMLCompressor
 *
 * @param string $html HTML content to be minified
 * 
 * @return string minified HTML content
 */
function minify_html_function($html) {
	if ( ! strstr($html, '<html') || ! strstr($html, '</html>')) {
		return $html;
	}
	
	preg_match_all("!<style[^>]+>.*?</style>!is", $html, $match);
	$styles = $match[0];

	preg_match_all("!<script[^>][^<src>]+>.*?</script>!is", $html, $match); // Modified, do not skip script tags with src in it..
	$scripts = $match[0];

	preg_match_all("!<pre[^>]*>.*?</pre>!is", $html, $match);
	$pre = $match[0];

	preg_match_all("!<textarea[^>]+>.*?</textarea>!is", $html, $match);
	$textareas = $match[0];

	$search = array("!<style[^>]+>.*?</style>!is", "!<script[^>][^<src>]+>.*?</script>!is", "!<pre[^>]*>.*?</pre>!is", "!<textarea[^>]+>.*?</textarea>!is");
	$replace = array('@TRIM:STYLE@', '@TRIM:SCRIPT@', '@TRIM:PRE@', '@TRIM:TEXTAREA@');

	$html = str_replace('<br>', '<br />', $html); // XHTML valid br tags must be done
	$html = str_replace('<BR>', '<br />', $html); // XHTML valid br tags must be done
	$html = trim(preg_replace(array('/((?<!\?>)\n)[\s]+/m', '/\>\s+\</'), array('\1', '><'), $html));

	// Printer library relys on comment
	//$html = preg_replace('/<!--[^<[if>](.|\s)*?-->/', '', $html);   // Remove html comments...

	$html = preg_replace($search, $replace, $html);

	$searches = array('@TRIM:STYLE@' => $styles, '@TRIM:SCRIPT@' => $scripts, '@TRIM:PRE@' => $pre, '@TRIM:TEXTAREA@' => $textareas);

	foreach ($searches as $search => $replace ) {
		$len = strlen($search);
		$pos = 0;
		$count = count($replace);

		if ($count < 1){
			continue;
		}

		for ($i = 0; $i < $count; $i++) {
			if (($pos = strpos($html, $search, $pos)) !== FALSE) {
				$html = substr_replace($html, $replace[$i], $pos, $len);
			} else {
				continue;
			}
		}
	} 

	return $html;
}

/* End of file: ./system/functions/minify_html/minify_html_function.php */
