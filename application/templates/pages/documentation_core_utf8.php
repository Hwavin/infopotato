<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">UTF-8 Support</h1>
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; UTF-8 Support
</div>
<!-- end breadcrumb -->

<p>
After some experience with PHP, developers will often start to notice issues related to character encoding including "weird" characters and multiple characters where there should only be one. Handling character encoding on the web usually means support the UTF-8 character encoding to allow for more than the standard ASCII characters present on US keyboard layouts.
</p>

<ul>
<li>
<a href="http://www.phpwact.org/php/i18n/utf-8">http://www.phpwact.org/php/i18n/utf-8</a>
</li>
<li>
<a href="http://www.phpwact.org/php/i18n/charsets#checking_utf-8_for_well_formedness">http://www.phpwact.org/php/i18n/charsets#checking_utf-8_for_well_formedness</a>
</li>
<li>
<a href="http://htmlpurifier.org/docs/enduser-utf8.html">http://htmlpurifier.org/docs/enduser-utf8.html</a>
</li>
</ul>


<h2>What is UTF-8? Iñtërnâtiônàlizætiøn</h2>
<p>
UTF-8 is a character encoding, or a way to represent characters in a digital manner. It is an encoding of the of the Unicode standard which is closely related to the Universal Character Set (UCS). There are many different character encodings in the Unicode standard, however UTF-8 has a few properties that make it one of the best and most popular choices for work on the web.
</p>

<p>
Unicode contains around 100,000 characters, allowing it to represent a majority of the written languages in the world. Other common characters sets in languages with Latin characters include ISO-8859-1 and Windows-1252. Each of these character encoding suffers from issues that they only support 256 different characters, with some common characters not being present (such as curly quotes and the Euro symbol in ISO-8859-1).
</p>

<p>
Since UTF-8 is an encoding that represents the Unicode standard, character availability is not an issue. However, to be able to represent so many different characters, UTF-8 uses multiple bytes of space for all non-ASCII characters. One of the nice properties of UTF-8 is that it is backwards compatible with ASCII and the first 128 characters in ISO-8859-1 and Windows-1252. In addition, UTF-8 is constructed in such a way that it is possible to tell where character boundaries are even if a parser is started in the middle of a character. Related to that, it is simple for a UTF-8 string to be verified as correctly encoded.
</p>

<h2>Why UTF-8?</h2>

<p>
What makes UTF-8 special is, first, that it’s an encoding of Unicode and, second, that it’s backwards compatible with ASCII.
</p>

<h2>PHP and UTF-8</h2>
<p>
Unfortunately, PHP does not include native support for UTF-8. All of the built-in string functions are designed to work with single-byte encodings only. The mbstring extension exists to provide string functions that are compatible with UTF-8, however it only covers a fraction of the standard string functions, and is not installed by default.
</p>

<p>
In addition to manipulating strings, the character encoding also affect the HTML that is returned to browsers and text in databases. The HTML standard defines ISO-8859-1 as the default character set to use for HTML when not specified, which will cause "weird" characters when UTF-8 content is returned. In a similar fashion, both MySQL and PostgreSQL default to using ISO-8859-1 as the character encoding unless specified.
</p>

<p>
MSSQL is much more complicated to work with since the whole server uses a single character encoding. In order to store unicode information, the column data types must be specified as one of the national character types, NVARCHAR, NCHAR or NTEXT. These national columns store data in USC-2 encoding, which contains null bytes for the characters in the ASCII range. Unfortunately none of the PHP extensions support binary data to be returned in string columns, so the national columns require extra work to cast them as binary data and then translated the data once in PHP.
</p>

<h2>PHP's Problem with Character Encoding</h2>

<p>
The basic problem PHP has with character encoding is it has a very simple idea of what the notion of a character is: that one character equals one byte. Being more precise, the problem is most of PHP‘s string related functionality (see common_problem_areas_with_utf-8 for further details) make this assumption but to be able to support a wide range of characters (or all characters, ever, as Unicode does), you need more than one byte to represent a character.
</p>

<h2>But what about mbstring, iconv etc.?</h2>

<p>
Yep there's PHP extensions to help with character encoding issues but (if you use a shared host, you’ve probably already got that sinking feeling) they’re not enabled by default in PHP4. Two of particular note;
</p>

<ul>
<li>
iconv: The iconv extension became a default part of PHP5 but it doesn’t offer you a magic wand that will make all problems go away. It probably has most value when either migrating old content to UTF-8 or when interfacing with systems can’t deliver you US-ASCII, ISO-8859-1 or UTF-85), such as an RSS feed, your PHP script reads, which is encoded with BIG5.
</li>
<li>
mbstring: The mbstring extension is potentially a magic wand, as it provides a mechanism to override a large number of PHP‘s string functions. Bad news is it’s not avaible by default in PHP. Third-hand reports say it used to be pretty unstable but in the last year or so has stabilized (more detail appreciated).
</li>
</ul>

<p>
It may be you can take advantage of these extensions in your own environment but if you’re writing software for other people to install for themselves, that makes them bad news.
</p>

<h2>InfoPotato's UTF-8 Support</h2>
<p>
Even though PHP has poor UTF-8 support by default, there are ways to work around it. Since using UTF-8 is the most universal way to handle characters from other languages, Flourish includes code that automatically works the shortcomings of PHP and provides UTF-8 support in all situations. While Flourish can solve many of the PHP issues with UTF-8, it is also important to understand how UTF-8 affects the other aspects of building a web site.
</p>

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

<table class="grid"> 
<thead>
<tr><th width="18%">PHP Function</th><th width="18%">UTF8 Method</th><th>Differences</th></tr>
</thead>

<tbody>
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

<p class="notebox">
UFT8::len() is used in worker.php to caculate the content length of the response body message.
</p>

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
