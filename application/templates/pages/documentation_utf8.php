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

<p>
The class UTF8 is a static class that provides UTF-8 compatible versions of almost every string function that is provided with PHP. Since UTF-8 uses multiple bytes of data for some characters and the built-in PHP string functions are built to work with single-byte encodings, many of the PHP string functions will perform incorrectly on UTF-8 strings.
</p>

<p>
There is a PHP extension called mbstring that is designed for dealing with multi-byte string encodings, however it is not installed by default, does not include many commonly used functions, and contains some bugs. The fUTF8 class will use the mbstring extension for performance benefits in appropriate situations if it is installed.
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

<table> 
<thead>
<tr><th>PHP Function</th><th>UTF8 Method</th><th>Differences</th></tr>
</thead>

<tbody id="list">
<tr><td> <a class="external_link" href="http://php.net/chr"><span><tt>chr()</tt></span></a>                     </td><td> <a class="auto_api api" href="/api/fUTF8#chr" title="fUTF8#chr">chr()</a>             </td><td> Accepts U+hex or decimal Unicode code point instead of ASCII decimal value  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/explode"><span><tt>explode()</tt></span></a>             </td><td> <a class="auto_api api" href="/api/fUTF8#explode" title="fUTF8#explode">explode()</a>         </td><td> Parameter order is switched to <tt>$string</tt>, <tt>$delimeter</tt> - also accepts <tt>NULL</tt> delimeter to explode into characters 
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/trim"><span><tt>ltrim()</tt></span></a>                  </td><td> <a class="auto_api api" href="/api/fUTF8#ltrim" title="fUTF8#ltrim">ltrim()</a>            </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/ord"><span><tt>ord()</tt></span></a>                     </td><td> <a class="auto_api api" href="/api/fUTF8#ord" title="fUTF8#ord">ord()</a>             </td><td> Returns U+hex Unicode code point instead of ASCII decimal value 
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/rtrim"><span><tt>rtrim()</tt></span></a>                 </td><td> <a class="auto_api api" href="/api/fUTF8#rtrim" title="fUTF8#rtrim">rtrim()</a>           </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/str_ireplace"><span><tt>str_ireplace()</tt></span></a>   </td><td> <a class="auto_api api" href="/api/fUTF8#ireplace" title="fUTF8#ireplace">ireplace()</a>        </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/str_pad"><span><tt>str_pad()</tt></span></a>             </td><td> <a class="auto_api api" href="/api/fUTF8#pad" title="fUTF8#pad">pad()</a>             </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/str_replace"><span><tt>str_replace()</tt></span></a>     </td><td> <a class="auto_api api" href="/api/fUTF8#replace" title="fUTF8#replace">replace()</a>         </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strcasecmp"><span><tt>strcasecmp()</tt></span></a>       </td><td> <a class="auto_api api" href="/api/fUTF8#icmp" title="fUTF8#icmp">icmp()</a>            </td><td> Letters that are ASCII letters with diacritics are sorted right after the base ASCII letter 
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strcmp"><span><tt>strcmp()</tt></span></a>               </td><td> <a class="auto_api api" href="/api/fUTF8#cmp" title="fUTF8#cmp">cmp()</a>             </td><td> Letters that are ASCII letters with diacritics are sorted right after the base ASCII letter 
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/stripos"><span><tt>stripos()</tt></span></a>             </td><td> <a class="auto_api api" href="/api/fUTF8#ipos" title="fUTF8#ipos">ipos()</a>            </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/stristr"><span><tt>stristr()</tt></span></a>             </td><td> <a class="auto_api api" href="/api/fUTF8#istr" title="fUTF8#istr">istr()</a>            </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strlen"><span><tt>strlen()</tt></span></a>               </td><td> <a class="auto_api api" href="/api/fUTF8#len" title="fUTF8#len">len()</a>             </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strnatcasecmp"><span><tt>strnatcasecmp()</tt></span></a> </td><td> <a class="auto_api api" href="/api/fUTF8#inatcmp" title="fUTF8#inatcmp">inatcmp()</a>         </td><td> Letters that are ASCII letters with diacritics are sorted right after the base ASCII letter 
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strnatcmp"><span><tt>strnatcmp()</tt></span></a>         </td><td> <a class="auto_api api" href="/api/fUTF8#natcmp" title="fUTF8#natcmp">natcmp()</a>          </td><td> Letters that are ASCII letters with diacritics are sorted right after the base ASCII letter 
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strpos"><span><tt>strpos()</tt></span></a>               </td><td> <a class="auto_api api" href="/api/fUTF8#pos" title="fUTF8#pos">pos()</a>             </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strrev"><span><tt>strrev()</tt></span></a>               </td><td> <a class="auto_api api" href="/api/fUTF8#rev" title="fUTF8#rev">rev()</a>             </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strripos"><span><tt>strripos()</tt></span></a>           </td><td> <a class="auto_api api" href="/api/fUTF8#irpos" title="fUTF8#irpos">irpos()</a>           </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strrpos"><span><tt>strrpos()</tt></span></a>             </td><td> <a class="auto_api api" href="/api/fUTF8#rpos" title="fUTF8#rpos">rpos()</a>            </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strstr"><span><tt>strstr()</tt></span></a>               </td><td> <a class="auto_api api" href="/api/fUTF8#str" title="fUTF8#str">str()</a>             </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strtolower"><span><tt>strtolower()</tt></span></a>       </td><td> <a class="auto_api api" href="/api/fUTF8#lower" title="fUTF8#lower">lower()</a>           </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/strtoupper"><span><tt>strtoupper()</tt></span></a>       </td><td> <a class="auto_api api" href="/api/fUTF8#upper" title="fUTF8#upper">upper()</a>           </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/substr"><span><tt>substr()</tt></span></a>               </td><td> <a class="auto_api api" href="/api/fUTF8#sub" title="fUTF8#sub">sub()</a>             </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/trim"><span><tt>trim()</tt></span></a>                   </td><td> <a class="auto_api api" href="/api/fUTF8#trim" title="fUTF8#trim">trim()</a>            </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/ucfirst"><span><tt>ucfirst()</tt></span></a>             </td><td> <a class="auto_api api" href="/api/fUTF8#ucfirst" title="fUTF8#ucfirst">ucfirst()</a>         </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/ucwords"><span><tt>ucwords()</tt></span></a>             </td><td> <a class="auto_api api" href="/api/fUTF8#ucwords" title="fUTF8#ucwords">ucwords()</a>         </td><td>  
</td></tr>

<tr><td> <a class="external_link" href="http://php.net/wordwrap"><span><tt>wordwrap()</tt></span></a>           </td><td> <a class="auto_api api" href="/api/fUTF8#wordwrap" title="fUTF8#wordwrap">wordwrap()</a>        </td><td>  
</td></tr>
</tbody>

</table>

<h2>Cleaning Strings (Security)</h2>
<p>
Due to the way that UTF-8 is implemented, certain character combinations are not allowed. Allowing such invalid data into a system could easily lead to all sorts of bugs with character parsing. To solve this issue, the clean() method will remove any malformed UTF-8 characters from a string.
</p>

<p>
This method should be used when importing data into a system from an external data source that may contain invalid data. Please note that Request::get() and fCookie::get() automatically call this method, so it is not necessary to clean it again.
</p>

<div class="syntax">
$cleaned_string = UTF8::clean($imported_string);
</div>

</div> 
<!-- end onecolumn -->
