<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_ENTRY_URI; ?>home">Home</a> &gt; <a href="<?php echo APP_ENTRY_URI; ?>documentation/">Documentation</a> &gt; UTF-8
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">UTF-8</h1>	

<p>
The class UTF8 is a static class that provides UTF-8 compatible versions of almost every string function that is provided with PHP. Since UTF-8 uses multiple bytes of data for some characters and the built-in PHP string functions are built to work with single-byte encodings, many of the PHP string functions will perform incorrectly on UTF-8 strings.
</p>

<p>
There is a PHP extension called mbstring that is designed for dealing with multi-byte string encodings, however it is not installed by default, does not include many commonly used functions, and contains some bugs. The UTF8 class will use the mbstring extension for performance benefits in appropriate situations if it is installed.
</p>

<h2>Method to Function Mapping</h2>

<p>
The table below contains a list of the built-in PHP string functions with the equivalent UTF8 method beside it. Any additional features or differences will also be listed.
</p>

<script type="text/javascript"> 
function stripedList(list) {
	var items = document.getElementById(list).getElementsByTagName('tr');
	for (i = 0; i < items.length; i++) {
		if ((i%2) ? false : true) {
			items[i].className += " odd";
		} else {
			items[i].className += " even";
		}
	}
}
window.onload = function() {
	stripedList('list'); 
};
</script>

<table class="grid"> 
<thead>
<tr><th width="18%">PHP Function</th><th width="18%">UTF8 Method</th><th>Differences</th></tr>
</thead>

<tbody id="list">
<tr>
<td><a class="external_link" href="http://php.net/chr"><span><tt>chr()</tt></span></a></td><td>chr()</td><td> Accepts U+hex or decimal Unicode code point instead of ASCII decimal value</td>
</tr>

<tr>
<td><a class="external_link" href="http://php.net/explode"><span><tt>explode()</tt></span></a></td><td>explode()</td><td> Parameter order is switched to <tt>$string</tt>, <tt>$delimeter</tt> - also accepts <tt>NULL</tt> delimeter to explode into characters</td>
</tr>

<tr>
<td><a class="external_link" href="http://php.net/trim"><span><tt>ltrim()</tt></span></a></td><td>ltrim()</td><td></td>
</tr>

<tr>
<td><a class="external_link" href="http://php.net/ord"><span><tt>ord()</tt></span></a></td><td>ord()</td><td>Returns U+hex Unicode code point instead of ASCII decimal value</td>
</tr>

<tr><td> <a class="external_link" href="http://php.net/rtrim"><span><tt>rtrim()</tt></span></a></td><td>rtrim()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/str_ireplace"><span><tt>str_ireplace()</tt></span></a></td><td>ireplace()</td><td>  
</td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/str_pad"><span><tt>str_pad()</tt></span></a></td><td>pad()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/str_replace"><span><tt>str_replace()</tt></span></a></td><td>replace()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strcasecmp"><span><tt>strcasecmp()</tt></span></a></td><td>icmp()</td><td> Letters that are ASCII letters with diacritics are sorted right after the base ASCII letter</td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strcmp"><span><tt>strcmp()</tt></span></a></td><td>cmp()</td><td> Letters that are ASCII letters with diacritics are sorted right after the base ASCII letter</td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/stripos"><span><tt>stripos()</tt></span></a></td><td>ipos()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/stristr"><span><tt>stristr()</tt></span></a></td><td>istr()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strlen"><span><tt>strlen()</tt></span></a></td><td>len()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strnatcasecmp"><span><tt>strnatcasecmp()</tt></span></a></td><td>inatcmp()</td><td> Letters that are ASCII letters with diacritics are sorted right after the base ASCII letter</td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strnatcmp"><span><tt>strnatcmp()</tt></span></a></td><td>natcmp()</td><td> Letters that are ASCII letters with diacritics are sorted right after the base ASCII letter</td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strpos"><span><tt>strpos()</tt></span></a></td><td>pos()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strrev"><span><tt>strrev()</tt></span></a></td><td>rev()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strripos"><span><tt>strripos()</tt></span></a></td><td>irpos()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strrpos"><span><tt>strrpos()</tt></span></a></td><td>rpos()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strstr"><span><tt>strstr()</tt></span></a></td><td>str()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strtolower"><span><tt>strtolower()</tt></span></a></td><td>lower()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/strtoupper"><span><tt>strtoupper()</tt></span></a></td><td>upper()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/substr"><span><tt>substr()</tt></span></a></td><td>sub()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/trim"><span><tt>trim()</tt></span></a></td><td>trim()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/ucfirst"><span><tt>ucfirst()</tt></span></a></td><td>ucfirst()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/ucwords"><span><tt>ucwords()</tt></span></a></td><td>ucwords()</td><td></td>
</tr>

<tr>
<td> <a class="external_link" href="http://php.net/wordwrap"><span><tt>wordwrap()</tt></span></a></td><td>wordwrap()</td><td></td>
</tr>
</tbody>

</table>

<h2>Cleaning Strings (Security)</h2>
<p>
Due to the way that UTF-8 is implemented, certain character combinations are not allowed. Allowing such invalid data into a system could easily lead to all sorts of bugs with character parsing. To solve this issue, the clean() method will remove any malformed UTF-8 characters from a string.
</p>

<p>
This method should be used when importing data into a system from an external data source that may contain invalid data.
</p>

<div class="syntax">
<tt>$cleaned_string = UTF8::clean($imported_string);</tt>
</div>

<p class="tipbox">
UFT8::len() is used in worker.php to caculate the content length of the response body message.
</p>

</div> 
<!-- end onecolumn -->
