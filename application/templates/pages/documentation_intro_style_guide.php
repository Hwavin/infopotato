<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Conventions &amp; Style Guide</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Conventions &amp; <a href="<?php echo APP_URI_BASE; ?>documentation/intro/">Introduction</a> &gt; Style Guide
</div>
<!-- end breadcrumb -->
http://docs.moodle.org/dev/Coding_style
<a href="<?php echo APP_URI_BASE; ?>print" class="print">Print</a>

<!-- PRINT: start -->
<div class="box_right greybox">
<blockquote>
<span>Happy is the man who is living by his hobby.</span>
<div>&mdash; G.Bernard Shaw</div>
</blockquote>
</div>

<h2>Goals</h2>
<p>
Consistent coding style is important in any development project, and particularly when many developers are involved. A standard style helps to ensure that the code is easier to read and understand, which helps overall quality.
</p>

<p>
Abstract goals we strive for:
</p>

<ul>
<li>simplicity</li>
<li>readability</li>
<li>
tool friendliness, such as use of method signatures, constants, and patterns that support IDE tools and auto-completion of method, class, and constant names.
</li>

<p>
When considering the goals above, each situation requires an examination of the circumstances and balancing of various trade-offs.
</p>

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


<h2>Indenting and Line Length</h2>

<p>
Use an indent of 4 spaces, with no tabs. This helps to avoid problems with diffs, patches, SVN history and annotations.
</p>

<p>
The key issue is readability. It is recommended to keep lines at approximately 75-85 characters long for better code readability. <a href="http://paul-m-jones.com/archives/276" class="external_link">Paul M. Jones</a> has some thoughts about that limit.
</p>


<h2>Control Structures</h2>

<p>
These include if, for, while, switch, etc. Here is an example if statement, since it is the most complicated of them:
</p>

<div class="syntax"><pre>
<span class="k">if</span> <span class="p">((</span><span class="nx">condition1</span><span class="p">)</span> <span class="o">||</span> <span class="p">(</span><span class="nx">condition2</span><span class="p">))</span> <span class="p">{</span> 
    <span class="nx">action1</span><span class="p">;</span> 
<span class="p">}</span> <span class="k">elseif</span> <span class="p">((</span><span class="nx">condition3</span><span class="p">)</span> <span class="o">&amp;&amp;</span> <span class="p">(</span><span class="nx">condition4</span><span class="p">))</span> <span class="p">{</span> 
    <span class="nx">action2</span><span class="p">;</span> 
<span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
    <span class="nx">defaultaction</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div> 
 
<p>
Split long if statements onto several lines
</p>
 
<p>
Long if statements may be split onto several lines when the character/line limit would be exceeded. The conditions have to be positioned onto the following line, and indented 4 characters. The logical operators (&&, ||, etc.) should be at the beginning of the line to make it easier to comment (and exclude) the condition. The closing parenthesis and opening brace get their own line at the end of the conditions.
</p>
 
<p>
Keeping the operators at the beginning of the line has two advantages: It is trivial to comment out a particular line during development while keeping syntactically correct code (except of course the first line). Further is the logic kept at the front where it's not forgotten. Scanning such conditions is very easy since they are aligned below each other.
</p>

<div class="syntax"><pre>
<span class="k">if</span> <span class="p">((</span><span class="nv">$condition1</span> 
    <span class="o">||</span> <span class="nv">$condition2</span><span class="p">)</span> 
    <span class="o">&amp;&amp;</span> <span class="nv">$condition3</span> 
    <span class="o">&amp;&amp;</span> <span class="nv">$condition4</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">//code here</span> 
<span class="p">}</span> 
</pre></div> 

<p>
The best case is of course when the line does not need to be split. When the if clause is really long enough to be split, it might be better to simplify it. In such cases, you could express conditions as variables an compare them in the if() condition. This has the benefit of "naming" and splitting the condition sets into smaller, better understandable chunks:
</p>
 
<div class="syntax"><pre>
<span class="nv">$is_foo</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$condition1</span> <span class="o">||</span> <span class="nv">$condition2</span><span class="p">);</span> 
<span class="nv">$is_bar</span> <span class="o">=</span> <span class="p">(</span><span class="nv">$condition3</span> <span class="o">&amp;&amp;</span> <span class="nv">$condtion4</span><span class="p">);</span> 
<span class="k">if</span> <span class="p">(</span><span class="nv">$is_foo</span> <span class="o">&amp;&amp;</span> <span class="nv">$is_bar</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">// ....</span> 
<span class="p">}</span> 
</pre></div>
 
<p class="notebox">
Control statements should have one space between the control keyword and opening parenthesis, to distinguish them from function calls. You are strongly encouraged to always use curly braces even in situations where they are technically optional. Having them increases readability and decreases the likelihood of logic errors being introduced when new lines are added.
</p>
 
<p>For switch statements:</p>

<div class="syntax"><pre>
<span class="k">switch</span> <span class="p">(</span><span class="nx">condition</span><span class="p">)</span> <span class="p">{</span> 
    <span class="k">case</span> <span class="m">1</span><span class="o">:</span> 
        <span class="nx">action1</span><span class="p">;</span> 
        <span class="k">break</span><span class="p">;</span> 
 
    <span class="k">case</span> <span class="m">2</span><span class="o">:</span> 
        <span class="nx">action2</span><span class="p">;</span> 
        <span class="k">break</span><span class="p">;</span> 
 
    <span class="k">default</span><span class="o">:</span> 
        <span class="nx">defaultaction</span><span class="p">;</span> 
        <span class="k">break</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div>

<p>Ternary operators</p>

<p>
The same rule as for if clauses also applies for the ternary operator: It may be split onto several lines, keeping the question mark and the colon at the front.
</p>

<div class="syntax"><pre>
<span class="nv">$a</span> <span class="o">=</span> <span class="nv">$condition1</span> <span class="o">&amp;&amp;</span> <span class="nv">$condition2</span> 
    <span class="o">?</span> <span class="nv">$foo</span> <span class="o">:</span> <span class="nv">$bar</span><span class="p">;</span> 
 
<span class="nv">$b</span> <span class="o">=</span> <span class="nv">$condition3</span> <span class="o">&amp;&amp;</span> <span class="nv">$condition4</span> 
    <span class="o">?</span> <span class="nv">$foo_man_this_is_too_long_what_should_i_do</span> 
    <span class="o">:</span> <span class="nv">$bar</span><span class="p">;</span> 
</pre></div> 


<h2>Function Calls</h2>

<p>
Functions should be called with no spaces between the function name, the opening parenthesis, and the first parameter; spaces between commas and each parameter, and no space between the last parameter, the closing parenthesis, and the semicolon. Here's an example:
</p>

<div class="syntax"><pre>
<span class="nv">$var</span> <span class="o">=</span> <span class="nx">foo</span><span class="p">(</span><span class="nv">$bar</span><span class="p">,</span> <span class="nv">$baz</span><span class="p">,</span> <span class="nv">$quux</span><span class="p">);</span> 
</pre></div> 

<p>
As displayed above, there should be one space on either side of an equals sign used to assign the return value of a function to a variable.
</p>


<h2>Class Definitions</h2>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Foo_Bar</span> <span class="p">{</span> 
    <span class="c1">//... code goes here</span> 
<span class="p">}</span> 
</pre></div> 

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


<h2>Function Definitions</h2>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">function</span> <span class="nf">foo_function</span><span class="p">(</span><span class="nv">$arg1</span><span class="p">,</span> <span class="nv">$arg2</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span> 
    <span class="k">if</span> <span class="p">(</span><span class="nx">condition</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nx">statement</span><span class="p">;</span> 
    <span class="p">}</span> 
    <span class="k">return</span> <span class="nv">$val</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div> 

<p class="notebox">
Arguments with default values go at the end of the argument list. Always attempt to return a meaningful value from a function if one is appropriate. 
</p>

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

<p><strong>Header Comment Blocks</strong></p>

<p>
All source code files in the InfoPotato distribution should contain the following comment block as the header:
</p> 

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="sd">/**</span> 
<span class="sd"> * Description</span> 
<span class="sd"> *</span> 
<span class="sd"> * Copyright 2009-2011 The InfoPotato Project (http://www.infopotato.com/)</span> 
<span class="sd"> *</span> 
<span class="sd"> * @author   Original Author &lt;author@example.com&gt;</span> 
<span class="sd"> * @author   Your Name &lt;you@example.com&gt;</span> 
<span class="sd"> * @package  Package</span> 
<span class="sd"> * @license  http://www.opensource.org/licenses/mit-license.php MIT Licence</span> 
<span class="sd"> * @since    InfoPotato 1.0.0 [only if needed]</span> 
<span class="sd"> */</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
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

<h2>Including Code</h2>

<p>
Anywhere you are unconditionally including a class file, use <strong>require_once</strong>. Anywhere you are conditionally including a class file (for example, factory methods), use <strong>include_once</strong>. Either of these will ensure that class files are included only once. They share the same file list, so you don't need to worry about mixing them - a file included with <strong>require_once</strong> will not be included again by <strong>include_once</strong>.
</p>
 
<p class="notebox">
<strong>include_once</strong> and <strong>require_once</strong> are statements, not functions. Parentheses should not surround the subject filename.
</p>

<h2>Constants</h2> 
<p>
Constants should always be all-uppercase, with underscores to separate words.
</p> 

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
<p>
Since string performance is not an issue in current versions of PHP, the main criteria for strings is readability.
</p>


<h3>Single quotes</h3>
<p>
Always use single quotes when a string is literal, or contains a lot of double quotes (like HTML):
</p>

<div class="syntax"><pre>
<span class="nv">$a</span> <span class="o">=</span> <span class="s1">&#39;Example string&#39;</span><span class="p">;</span> 
<span class="k">echo</span> <span class="s1">&#39;&lt;span class=&quot;&#39;</span><span class="o">.</span><span class="nx">s</span><span class="p">(</span><span class="nv">$class</span><span class="p">)</span><span class="o">.</span><span class="s1">&#39;&quot;&gt;&lt;/span&gt;&#39;</span><span class="p">;</span> 
<span class="nv">$html</span> <span class="o">=</span> <span class="s1">&#39;&lt;a href=&quot;http://something&quot; title=&quot;something&quot;&gt;Link&lt;/a&gt;&#39;</span><span class="p">;</span> 
</pre></div> 

<h3>Double quotes</h3>
<p>
Use double quotes when you need to include plain variables or a lot of single quotes. 
</p>

<div class="syntax"><pre>
<span class="k">echo</span> <span class="s2">&quot;&lt;span&gt;</span><span class="si">$string</span><span class="s2">&lt;/span&gt;&quot;</span><span class="p">;</span> 
<span class="nv">$statement</span> <span class="o">=</span> <span class="s2">&quot;You aren&#39;t serious!&quot;</span><span class="p">;</span> 
</pre></div> 

<h3>Variable substitution</h3>
<p>
Variable substitution can use either of these forms:
</p>

<div class="syntax"><pre>
<span class="nv">$greeting</span> <span class="o">=</span> <span class="s2">&quot;Hello </span><span class="si">$name</span><span class="s2">, welcome back!&quot;</span><span class="p">;</span> 
 
<span class="nv">$greeting</span> <span class="o">=</span> <span class="s2">&quot;Hello </span><span class="si">{</span><span class="nv">$name</span><span class="si">}</span><span class="s2">, welcome back!&quot;</span><span class="p">;</span> 
</pre></div> 

<h3>String concatenation</h3>
<p>
Strings must be concatenated using the "." operator.
</p>

<div class="syntax"><pre>
<span class="nv">$longstring</span> <span class="o">=</span> <span class="nv">$several</span><span class="o">.</span><span class="nv">$short</span><span class="o">.</span><span class="s1">&#39;strings&#39;</span><span class="p">;</span> 
</pre></div> 

<p>
If the lines are long, break the statement into multiple lines to improve readability. In these cases, put the "dot" at the end of each line.
</p>

<div class="syntax"><pre>
<span class="nv">$string</span> <span class="o">=</span> <span class="s1">&#39;This is a very long and stupid string because &#39;</span><span class="o">.</span><span class="nv">$editorname</span><span class="o">.</span> 
          <span class="s2">&quot; couldn&#39;t think of a better example at the time.&quot;</span><span class="p">;</span> 
</pre></div> 

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


<h2>Array Definitions</h2>
 
<p>
When defining arrays, or nested arrays, use the following format, where indentation is noted via the closing parenthesis characters:
</p>

<div class="syntax"><pre>
<span class="nv">$arrayname</span><span class="p">[</span><span class="s1">&#39;index&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
    <span class="s1">&#39;name1&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;value1&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;name2&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;subname1&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;subvalue1&#39;</span><span class="p">,</span> 
        <span class="s1">&#39;subname2&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;subvalue2&#39;</span> 
    <span class="p">),</span> 
<span class="p">);</span> 
</pre></div> 
 
<p>
The only exception should be for empty or short arrays that fit on one line, which may be written as:
</p>

<div class="syntax"><pre>
<span class="nv">$arrayname</span><span class="p">[</span><span class="s1">&#39;index&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="k">array</span><span class="p">();</span> 
<span class="nv">$arrayvar</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;foo1&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;bar1&#39;</span><span class="p">);</span> 
</pre></div> 

<h2>Default Function Arguments</h2> 
<p>Whenever appropriate, provide function argument defaults, which helps prevent PHP errors with mistaken calls and provides common fallback values which can save a few lines of code. Example:</p> 
<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">function</span> <span class="nf">foo</span><span class="p">(</span><span class="nv">$bar</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$baz</span> <span class="o">=</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">// Some code here</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<h2>Best practices</h2>
 
<p>
There are other things not covered by PEAR Coding Standards which are mostly subject of personal preference and not directly related to readability of the code. Things like "single quotes vs double quotes" are features of PHP itself to make programming easier and there are no reasons not use one way in preference to another. Such best practices are left solely on developer to decide. The only recommendation could be made to keep consistency within package and respect personal style of other developers.
</p>

<h3>Readability of code blocks</h3>
 
<p>
Related lines of code should be grouped into blocks, seperated from each other to keep readability as high as possible. The definition of "related" depends on the code :)

For example:
</p>

<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$bar</span> <span class="o">=</span> <span class="m">1</span><span class="p">;</span> 
<span class="p">}</span> 
<span class="k">if</span> <span class="p">(</span><span class="nv">$spam</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$ham</span> <span class="o">=</span> <span class="m">1</span><span class="p">;</span> 
<span class="p">}</span> 
<span class="k">if</span> <span class="p">(</span><span class="nv">$pinky</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$brain</span> <span class="o">=</span> <span class="m">1</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div> 

<p>
is a lot easier to read when seperated:
</p>

<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$bar</span> <span class="o">=</span> <span class="m">1</span><span class="p">;</span> 
<span class="p">}</span> 
 
<span class="k">if</span> <span class="p">(</span><span class="nv">$spam</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$ham</span> <span class="o">=</span> <span class="m">1</span><span class="p">;</span> 
<span class="p">}</span> 
 
<span class="k">if</span> <span class="p">(</span><span class="nv">$pinky</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$brain</span> <span class="o">=</span> <span class="m">1</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div>

<h3>Return early</h3>

<p>
To keep readability in functions and methods, it is wise to return early if simple conditions apply that can be checked at the beginning of a method:
</p>

<div class="syntax"><pre>
<span class="k">function</span> <span class="nf">foo</span><span class="p">(</span><span class="nv">$bar</span><span class="p">,</span> <span class="nv">$baz</span><span class="p">)</span> <span class="p">{</span> 
    <span class="k">if</span> <span class="p">(</span><span class="nv">$foo</span><span class="p">)</span> <span class="p">{</span> 
        <span class="c1">//assume</span> 
        <span class="c1">//that</span> 
        <span class="c1">//here</span> 
        <span class="c1">//is</span> 
        <span class="c1">//the</span> 
        <span class="c1">//whole</span> 
        <span class="c1">//logic</span> 
        <span class="c1">//of</span> 
        <span class="c1">//this</span> 
        <span class="c1">//method</span> 
        <span class="k">return</span> <span class="nv">$calculated_value</span><span class="p">;</span> 
    <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
        <span class="k">return</span> <span class="k">NULL</span><span class="p">;</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div>

<p>
It's better to return early, keeping indentation and brain power needed to follow the code low.
</p>

<div class="syntax"><pre>
<span class="k">function</span> <span class="nf">foo</span><span class="p">(</span><span class="nv">$bar</span><span class="p">,</span> <span class="nv">$baz</span><span class="p">)</span> <span class="p">{</span> 
    <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$foo</span><span class="p">)</span> <span class="p">{</span> 
        <span class="k">return</span> <span class="k">NULL</span><span class="p">;</span> 
    <span class="p">}</span> 
 
    <span class="c1">//assume</span> 
    <span class="c1">//that</span> 
    <span class="c1">//here</span> 
    <span class="c1">//is</span> 
    <span class="c1">//the</span> 
    <span class="c1">//whole</span> 
    <span class="c1">//logic</span> 
    <span class="c1">//of</span> 
    <span class="c1">//this</span> 
    <span class="c1">//method</span> 
    <span class="k">return</span> <span class="nv">$calculated_value</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div> 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
