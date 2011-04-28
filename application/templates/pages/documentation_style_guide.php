<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Conventions &amp; Style Guide
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Conventions &amp; Style Guide</h1>	

<div class="box_right greybox">
<blockquote>
<span>Happy is the man who is living by his hobby.</span>
<div>&mdash; G.Bernard Shaw</div>
</blockquote>
</div>

<p>The following page describes the use of coding rules adhered to when developing InfoPotato.</p> 
 
<h2>Text Encoding</h2> 
<p>
InfoPotato uses UTF-8 for everything, everywhere, all-the time. You should always use UTF-8. The reason why is that UTF-8 supports full internationalization while beeng back-compatible with both ASCII and ISO-8859-1 which are commonly used.
</p>

<p>
The easiest way to work with InfoPotato is to store all external data as UTF-8. If you don't, InfoPotato will often be able to convert your native data into UTF-8, but this doesn't always work reliably, so you're better off ensuring that all external data is UTF-8.
</p>

<p>Files should be saved with Unicode (UTF-8) encoding.  The <abbr title="Byte Order Mark">BOM</abbr> 
should <em>not</em> be used.  Unlike UTF-16 and UTF-32, there's no byte order to indicate in
a UTF-8 encoded file, and the <abbr title="Byte Order Mark">BOM</abbr> can have a negative side effect in PHP of sending output,
preventing the application from being able to set its own headers.  Unix line endings should
be used (LF).</p> 


<h2>PHP Closing Tag</h2> 

<p>The PHP closing tag on a PHP document <strong>?&gt;</strong> is optional to the PHP parser.  However, if used, any whitespace following the closing tag, whether introduced
by the developer, user, or an FTP application, can cause unwanted output, PHP errors, or if the latter are suppressed, blank pages.  For this reason, all PHP files should
<strong>OMIT</strong> the closing PHP tag, and instead use a comment block to mark the end of file and it's location relative to the application root.
This allows you to still identify a file as being complete and not truncated.</p> 

<strong>INCORRECT</strong>
<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">echo</span> <span class="s2">&quot;Here&#39;s my code!&quot;</span><span class="p">;</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<strong>CORRECT</strong>
<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">echo</span> <span class="s2">&quot;Here&#39;s my code!&quot;</span><span class="p">;</span> 
<span class="cm">/* End of file: ./system/libraries/example/example_library.php */</span> 
</pre></div> 


<h2>Class and Method Naming</h2> 

<p>
Class names should always have their first letter uppercase. Multiple words should be separated with an underscore, and not CamelCased. All other class methods should be entirely lowercased and named to clearly indicate their function, preferably including a verb. Try to avoid overly long and verbose names.
</p> 

<strong>INCORRECT</strong>:
<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">HomeController</span> <span class="k">extends</span> <span class="nx">Controller</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">index</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Some code here</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/controllers/home_controller.php</span> 
</pre></div> 

<strong>CORRECT</strong>
<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Home_Controller</span> <span class="k">extends</span> <span class="nx">Controller</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">index</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Some code here</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/controllers/home_controller.php</span> 
</pre></div> 


<h2>Variable Names</h2> 
<p>The guidelines for variable naming is very similar to that used for class methods.  Namely, variables should contain only lowercase letters, use underscore separators, and be reasonably named to indicate their purpose and contents. Very short, non-word variables should only be used as iterators in for() loops.</p> 
<strong>INCORRECT</strong>:
<div class="syntax"><pre>
<span class="nv">$j</span> <span class="o">=</span> <span class="s1">&#39;foo&#39;</span><span class="p">;</span>		<span class="c1">// single letter variables should only be used in for() loops</span> 
<span class="nv">$Str</span>			<span class="c1">// contains uppercase letters</span> 
<span class="nv">$bufferedText</span>		<span class="c1">// uses CamelCasing, and could be shortened without losing semantic meaning</span> 
<span class="nv">$groupid</span>		<span class="c1">// multiple words, needs underscore separator</span> 
<span class="nv">$name_of_last_city_used</span>	<span class="c1">// too long</span> 
</pre></div> 

<strong>CORRECT</strong>:
<div class="syntax"><pre>
<span class="k">for</span> <span class="p">(</span><span class="nv">$j</span> <span class="o">=</span> <span class="m">0</span><span class="p">;</span> <span class="nv">$j</span> <span class="o">&lt;</span> <span class="m">10</span><span class="p">;</span> <span class="nv">$j</span><span class="o">++</span><span class="p">)</span> 
<span class="nv">$str</span> 
<span class="nv">$buffer</span> 
<span class="nv">$group_id</span> 
<span class="nv">$last_city</span> 
</pre></div> 

 
<h2>Commenting</h2> 
<p>In general, code should be commented prolifically.  It not only helps describe the flow and intent of the code for less experienced programmers, but can prove invaluable when returning to your own code months down the line.  There is not a required format for comments, but the following are recommended.</p> 

<p><a href="http://manual.phpdoc.org/HTMLSmartyConverter/HandS/phpDocumentor/tutorial_phpDocumentor.howto.pkg.html#basics.docblock">DocBlock</a> style comments preceding class and method declarations so they can be picked up by IDEs:</p> 

<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Super Class</span> 
<span class="sd"> *</span> 
<span class="sd"> * @package	Package Name</span> 
<span class="sd"> * @subpackage	Subpackage</span> 
<span class="sd"> * @category	Category</span> 
<span class="sd"> * @author	Author Name</span> 
<span class="sd"> * @link	http://example.com</span> 
<span class="sd"> */</span> 
<span class="k">class</span> <span class="nc">Super_class</span> <span class="p">{</span> 
</pre></div>
 
<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Encodes string for use in XML</span> 
<span class="sd"> *</span> 
<span class="sd"> * @access	public</span> 
<span class="sd"> * @param	string</span> 
<span class="sd"> * @return	string</span> 
<span class="sd"> */</span> 
<span class="k">function</span> <span class="nf">xml_encode</span><span class="p">(</span><span class="nv">$str</span><span class="p">)</span> <span class="p">{</span> 
</pre></div> 
 
<p>Use single line comments within code, leaving a blank line between large comment blocks and code.</p> 
 
<div class="syntax"><pre>
<span class="c1">// break up the string by newlines</span> 
<span class="nv">$parts</span> <span class="o">=</span> <span class="nb">explode</span><span class="p">(</span><span class="s2">&quot;</span><span class="se">\n</span><span class="s2">&quot;</span><span class="p">,</span> <span class="nv">$str</span><span class="p">);</span> 
 
<span class="c1">// A longer comment that needs to give greater detail on what is</span> 
<span class="c1">// occurring and why can use multiple single-line comments.  Try to</span> 
<span class="c1">// keep the width reasonable, around 70 characters is the easiest to</span> 
<span class="c1">// read.  Don&#39;t hesitate to link to permanent external resources</span> 
<span class="c1">// that may provide greater detail:</span> 
<span class="c1">//</span> 
<span class="c1">// http://example.com/information_about_something/in_particular/</span> 
 
<span class="nv">$parts</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">foo</span><span class="p">(</span><span class="nv">$parts</span><span class="p">);</span> 
</pre></div> 

 
 
<h2>Constants</h2> 
<p>Constants follow the same guidelines as do variables, except constants should always be fully uppercase.  <em>Always use InfoPotato constants when appropriate, i.e. SLASH, LD, RD, PATH_CACHE, etc.</em></p> 
<strong>INCORRECT</strong>:
<div class="syntax"><pre>
<span class="nx">myConstant</span>	<span class="c1">// missing underscore separator and not fully uppercase</span> 
<span class="nx">N</span>		<span class="c1">// no single-letter constants</span> 
<span class="nx">S_C_VER</span>		<span class="c1">// not descriptive</span> 
<span class="nv">$str</span> <span class="o">=</span> <span class="nb">str_replace</span><span class="p">(</span><span class="s1">&#39;{foo}&#39;</span><span class="p">,</span> <span class="s1">&#39;bar&#39;</span><span class="p">,</span> <span class="nv">$str</span><span class="p">);</span>	<span class="c1">// should use LD and RD constants</span> 
</pre></div>
 
<strong>CORRECT</strong>:
<div class="syntax"><pre>
<span class="nx">MY_CONSTANT</span> 
<span class="nx">NEWLINE</span> 
<span class="nx">SUPER_CLASS_VERSION</span> 
<span class="nv">$str</span> <span class="o">=</span> <span class="nb">str_replace</span><span class="p">(</span><span class="nx">LD</span><span class="o">.</span><span class="s1">&#39;foo&#39;</span><span class="o">.</span><span class="nx">RD</span><span class="p">,</span> <span class="s1">&#39;bar&#39;</span><span class="p">,</span> <span class="nv">$str</span><span class="p">);</span> 
</pre></div> 

 
 
<h2>TRUE, FALSE, and NULL</h2> 

<p>
<strong>TRUE</strong>, <strong>FALSE</strong>, and <strong>NULL</strong> keywords should always be fully uppercase.
</p> 

<strong>INCORRECT</strong>:
<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span> <span class="o">==</span> <span class="k">true</span><span class="p">)</span> 
<span class="nv">$bar</span> <span class="o">=</span> <span class="k">false</span><span class="p">;</span> 
<span class="k">function</span> <span class="nf">foo</span><span class="p">(</span><span class="nv">$bar</span> <span class="o">=</span> <span class="k">null</span><span class="p">)</span> 
</pre></div> 
 
<strong>CORRECT</strong>:
<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span> <span class="o">==</span> <span class="k">TRUE</span><span class="p">)</span> 
<span class="nv">$bar</span> <span class="o">=</span> <span class="k">FALSE</span><span class="p">;</span> 
<span class="k">function</span> <span class="nf">foo</span><span class="p">(</span><span class="nv">$bar</span> <span class="o">=</span> <span class="k">NULL</span><span class="p">)</span> 
</pre></div> 

 
 
 
<h2>Logical Operators</h2> 

<p>Use of <strong>||</strong> is discouraged as its clarity on some output devices is low (looking like the number 11 for instance).
<strong>&amp;&amp;</strong> is preferred over <strong>AND</strong> but either are acceptable, and a space should always precede and follow <strong>!</strong>.</p> 
<strong>INCORRECT</strong>:
<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span> <span class="o">||</span> <span class="nv">$bar</span><span class="p">)</span> 
<span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span> <span class="k">AND</span> <span class="nv">$bar</span><span class="p">)</span>  <span class="c1">// okay but not recommended for common syntax highlighting applications</span> 
<span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="nv">$foo</span><span class="p">)</span> 
<span class="k">if</span> <span class="p">(</span><span class="o">!</span> <span class="nb">is_array</span><span class="p">(</span><span class="nv">$foo</span><span class="p">))</span> 
</pre></div> 
 
<strong>CORRECT</strong>:
<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span> <span class="k">OR</span> <span class="nv">$bar</span><span class="p">)</span> 
<span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span> <span class="o">&amp;&amp;</span> <span class="nv">$bar</span><span class="p">)</span> <span class="c1">// recommended</span> 
<span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$foo</span><span class="p">)</span> 
<span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nb">is_array</span><span class="p">(</span><span class="nv">$foo</span><span class="p">))</span> 
</pre></div> 
 

<h2>Comparing Return Values and Typecasting</h2> 
<p>Some PHP functions return FALSE on failure, but may also have a valid return value of "" or 0, which would evaluate to FALSE in loose comparisons.  Be explicit by comparing the variable type when using these return values in conditionals to ensure the return value is indeed what you expect, and not a value that has an equivalent loose-type evaluation.</p> 
<p>Use the same stringency in returning and checking your own variables.  Use <strong>===</strong> and <strong>!==</strong> as necessary.

<strong>INCORRECT</strong>:
<div class="syntax"><pre>
<span class="c1">// If &#39;foo&#39; is at the beginning of the string, strpos will return a 0,</span> 
<span class="c1">// resulting in this conditional evaluating as TRUE</span> 
<span class="k">if</span> <span class="p">(</span><span class="nb">strpos</span><span class="p">(</span><span class="nv">$str</span><span class="p">,</span> <span class="s1">&#39;foo&#39;</span><span class="p">)</span> <span class="o">==</span> <span class="k">FALSE</span><span class="p">)</span> 
</pre></div> 
 
<strong>CORRECT</strong>:
<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nb">strpos</span><span class="p">(</span><span class="nv">$str</span><span class="p">,</span> <span class="s1">&#39;foo&#39;</span><span class="p">)</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> 
</pre></div> 

 
<strong>INCORRECT</strong>:
<div class="syntax"><pre>
<span class="k">function</span> <span class="nf">build_string</span><span class="p">(</span><span class="nv">$str</span> <span class="o">=</span> <span class="s2">&quot;&quot;</span><span class="p">)</span> <span class="p">{</span> 
    <span class="k">if</span> <span class="p">(</span><span class="nv">$str</span> <span class="o">==</span> <span class="s2">&quot;&quot;</span><span class="p">)</span> <span class="p">{</span> 
        <span class="c1">// uh-oh!  What if FALSE or the integer 0 is passed as an argument?</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 
 
<strong>CORRECT</strong>:
<div class="syntax"><pre>
<span class="k">function</span> <span class="nf">build_string</span><span class="p">(</span><span class="nv">$str</span> <span class="o">=</span> <span class="s2">&quot;&quot;</span><span class="p">)</span> <span class="p">{</span> 
    <span class="k">if</span> <span class="p">(</span><span class="nv">$str</span> <span class="o">===</span> <span class="s2">&quot;&quot;</span><span class="p">)</span> <span class="p">{</span> 
        <span class="c1">// Here we go</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div>
 
<p>See also information regarding <a href="http://us3.php.net/manual/en/language.types.type-juggling.php#language.types.typecasting">typecasting</a>, which can be quite useful.  Typecasting has a slightly different effect which may be desirable.  When casting a variable as a string, for instance, NULL and boolean FALSE variables become empty strings, 0 (and other numbers) become strings of digits, and boolean TRUE becomes "1":</p> 
 
<div class="syntax"><pre>
<span class="nv">$str</span> <span class="o">=</span> <span class="p">(</span><span class="nx">string</span><span class="p">)</span> <span class="nv">$str</span><span class="p">;</span>	<span class="c1">// cast $str as a string</span> 
</pre></div> 
 
 
<h2>Debugging Code</h2> 
<p>No debugging code can be left in place for submitted add-ons unless it is commented out, i.e. no var_dump(), print_r(), die(), and exit() calls that were used while creating the add-on, unless they are commented out.</p> 

<div class="syntax"><pre>
<span class="c1">//print_r($foo);</span> 
</pre></div> 
  
<h2>One File per Class</h2> 
<p>Use separate files for each class your add-on uses, unless the classes are <em>closely related</em>.  An example of InfoPotato files that contains multiple classes is the Database class file, which contains both the DB class and the DB_Cache class, and the Magpie plugin, which contains both the Magpie and Snoopy classes.</p> 

 
<h2>Whitespace</h2> 
<p>Use tabs for whitespace in your code, not spaces.  This may seem like a small thing, but using tabs instead of whitespace allows the developer looking at your code to have indentation at levels that they prefer and customize in whatever application they use.  And as a side benefit, it results in (slightly) more compact files, storing one tab character versus, say, four space characters.</p> 

 
 
<h2>Line Breaks</h2> 
<p>Files must be saved with Unix line breaks.  This is more of an issue for developers who work in Windows, but in any case ensure that your text editor is setup to save files with Unix line breaks.</p> 


<h2>Code Indenting</h2> 
<p>Use Allman style indenting.  With the exception of Class declarations, braces are always placed on a line by themselves, and indented at the same level as the control statement that "owns" them.</p> 

<strong>CORRECT</strong>:
<div class="syntax"><pre>
<span class="k">function</span> <span class="nf">foo</span><span class="p">(</span><span class="nv">$bar</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">// ...</span> 
<span class="p">}</span> 
 
<span class="k">foreach</span> <span class="p">(</span><span class="nv">$arr</span> <span class="k">as</span> <span class="nv">$key</span> <span class="o">=&gt;</span> <span class="nv">$val</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">// ...</span> 
<span class="p">}</span> 
 
<span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span> <span class="o">==</span> <span class="nv">$bar</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">// ...</span> 
<span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
    <span class="c1">// ...</span> 
<span class="p">}</span> 
 
<span class="k">for</span> <span class="p">(</span><span class="nv">$i</span> <span class="o">=</span> <span class="m">0</span><span class="p">;</span> <span class="nv">$i</span> <span class="o">&lt;</span> <span class="m">10</span><span class="p">;</span> <span class="nv">$i</span><span class="o">++</span><span class="p">)</span> <span class="p">{</span> 
    <span class="k">for</span> <span class="p">(</span><span class="nv">$j</span> <span class="o">=</span> <span class="m">0</span><span class="p">;</span> <span class="nv">$j</span> <span class="o">&lt;</span> <span class="m">10</span><span class="p">;</span> <span class="nv">$j</span><span class="o">++</span><span class="p">)</span> <span class="p">{</span> 
	<span class="c1">// ...</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div>
 
 
<h2>Bracket and Parenthetic Spacing</h2> 
<p>In general, parenthesis and brackets should not use any additional spaces.  The exception is that a space should always follow PHP control structures that accept arguments with parenthesis (declare, do-while, elseif, for, foreach, if, switch, while), to help distinguish them from functions and increase readability.</p> 


<h2>Private Methods and Variables</h2> 

<p>Methods and variables that are only accessed internally by your class, such as utility and helper functions that your public methods use for code abstraction, should be prefixed with an underscore.</p> 

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Home_Controller</span> <span class="k">extends</span> <span class="nx">Controller</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="nv">$index_data</span><span class="p">;</span> 
    <span class="k">private</span> <span class="nv">$_internal_data</span><span class="p">;</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">index</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Some code here</span> 
    <span class="p">}</span> 
 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_get_home_content</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Some code here</span> 
    <span class="p">}</span> 
 
    <span class="k">protected</span> <span class="k">function</span> <span class="nf">_get_data</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Some code here</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/controllers/home_controller.php</span> 
</pre></div> 

 
 
<h2>Short Open Tags</h2> 
<p>Always use full PHP opening tags, in case a server does not have short_open_tag enabled.</p> 

<strong>INCORRECT</strong>:
<div class="syntax"><pre><span class="cp">&lt;?</span> <span class="k">echo</span> <span class="nv">$foo</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="x"> </span> 
 
<span class="cp">&lt;?</span><span class="o">=</span><span class="nv">$foo</span><span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 
 
<strong>CORRECT</strong>:
<div class="syntax"><pre><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$foo</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

 
<h2>One Statement Per Line</h2> 
<p>Never combine statements on one line.</p> 

<strong>INCORRECT</strong>:
<div class="syntax"><pre>
<span class="nv">$foo</span> <span class="o">=</span> <span class="s1">&#39;this&#39;</span><span class="p">;</span> <span class="nv">$bar</span> <span class="o">=</span> <span class="s1">&#39;that&#39;</span><span class="p">;</span> <span class="nv">$bat</span> <span class="o">=</span> <span class="nb">str_replace</span><span class="p">(</span><span class="nv">$foo</span><span class="p">,</span> <span class="nv">$bar</span><span class="p">,</span> <span class="nv">$bag</span><span class="p">);</span> 
</pre></div>
 
<strong>CORRECT</strong>:
<div class="syntax"><pre>
<span class="nv">$foo</span> <span class="o">=</span> <span class="s1">&#39;this&#39;</span><span class="p">;</span> 
<span class="nv">$bar</span> <span class="o">=</span> <span class="s1">&#39;that&#39;</span><span class="p">;</span> 
<span class="nv">$bat</span> <span class="o">=</span> <span class="nb">str_replace</span><span class="p">(</span><span class="nv">$foo</span><span class="p">,</span> <span class="nv">$bar</span><span class="p">,</span> <span class="nv">$bag</span><span class="p">);</span> 
</pre></div> 

 
<h2>Strings</h2> 
<p>Always use single quoted strings unless you need variables parsed, and in cases where you do need variables parsed, use braces to prevent greedy token parsing.  You may also use double-quoted strings if the string contains single quotes, so you do not have to use escape characters.</p> 

<strong>INCORRECT</strong>:
<div class="syntax"><pre>
<span class="nv">$str</span> <span class="o">=</span> <span class="s2">&quot;My String&quot;</span><span class="p">;</span> <span class="c1">// no variable parsing, so no use for double quotes</span> 
<span class="nv">$str</span> <span class="o">=</span> <span class="s2">&quot;My string </span><span class="si">$foo</span><span class="s2">&quot;</span><span class="p">;</span>	<span class="c1">// needs braces</span> 
<span class="nv">$sql</span> <span class="o">=</span> <span class="s1">&#39;SELECT foo FROM bar WHERE baz = \&#39;bag\&#39;&#39;</span><span class="p">;</span> <span class="c1">// ugly</span> 
</pre></div>
 
<strong>CORRECT</strong>:
<div class="syntax"><pre>
<span class="nv">$str</span> <span class="o">=</span> <span class="s1">&#39;My String&#39;</span><span class="p">;</span> <span class="c1">// no variable parsing, so no use for double quotes</span> 
<span class="nv">$str</span> <span class="o">=</span> <span class="s2">&quot;My string </span><span class="si">{</span><span class="nv">$foo</span><span class="si">}</span><span class="s2">&quot;</span><span class="p">;</span>	<span class="c1">// needs braces</span> 
<span class="nv">$sql</span> <span class="o">=</span> <span class="s2">&quot;SELECT foo FROM bar WHERE baz = &#39;bag&#39;&quot;</span><span class="p">;</span> 
</pre></div> 

 
<h2>Default Function Arguments</h2> 
<p>Whenever appropriate, provide function argument defaults, which helps prevent PHP errors with mistaken calls and provides common fallback values which can save a few lines of code. Example:</p> 
<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">function</span> <span class="nf">foo</span><span class="p">(</span><span class="nv">$bar</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$baz</span> <span class="o">=</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">// Some code here</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 
 
</div> 
 
 
</div> 
<!-- end onecolumn -->
