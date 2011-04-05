<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo BASE_URI; ?>home">Home</a> &gt; <a href="<?php echo BASE_URI; ?>documentation/">Documentation</a> &gt; UTF-8
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">UTF-8</h1>	

<a href="http://www.phpwact.org/php/i18n/utf-8">http://www.phpwact.org/php/i18n/utf-8</a>

<a href="http://www.phpwact.org/php/i18n/charsets#checking_utf-8_for_well_formedness">http://www.phpwact.org/php/i18n/charsets#checking_utf-8_for_well_formedness</a>

<a href="http://htmlpurifier.org/docs/enduser-utf8.html">http://htmlpurifier.org/docs/enduser-utf8.html</a>

<p>
After some experience with PHP, developers will often start to notice issues related to character encoding including "weird" characters and multiple characters where there should only be one. Handling character encoding on the web usually means support the UTF-8 character encoding to allow for more than the standard ASCII characters present on US keyboard layouts.
</p>

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

</div> 
<!-- end onecolumn -->
