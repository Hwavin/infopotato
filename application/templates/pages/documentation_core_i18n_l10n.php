<div class="container"> 

<div class="row"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading"><?php echo __('Internationalization &amp; Localization'); ?></h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; <?php echo __('Internationalization &amp; Localization'); ?>
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/i18n_l10n/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
The term "internationalization" refers to the process of abstracting strings and other locale-specific pieces out of your application and into a layer where they can be translated and converted based on the user's locale (i.e. language and country). For text (text will "always" print out in English), this means wrapping each with a function capable of translating the text (or "message") into the language of the user.
</p> 

<p>
If you ever developed an international application, you know that dealing with every aspect of text translation, local standards, and localized content can be a nightmare.
</p>

<ul>
<li>Text translation (interface, assets, and content)</li>
<li>Standards and formats (dates, amounts, numbers, and so on)</li>
<li>Localized content (many versions of a given object according to a country)</li>
</ul>

<p>
All in all, dealing with i18n and l10n means that the application can take care of the following:
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s1">&#39;Text to be translated&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<p>
Let's say that you want to create a website in English and French, with English being the default language. Before even thinking about having the site translated, you probably wrote the templates something like the example shown below:
</p>

<div class="syntax"><pre>Welcome to our website. Today&#39;s date is <span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">format_date</span><span class="p">(</span><span class="nb">date</span><span class="p">());</span> <span class="cp">?&gt;</span> 
</pre></div>

<p>
For InfoPotato to translate the phrases of a template, they must be identified as text to be translated. To save on typing time, and to reduce code clutter, this is the purpose of the __() function (two underscores). So all your templates need to enclose the phrases to translate in such function calls. The above example can be modified to look like:
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s1">&#39;Welcome to our website.&#39;</span><span class="p">)</span><span class="nx">l</span> <span class="cp">?&gt;</span><span class="x"></span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s2">&quot;Today&#39;s date is &quot;</span><span class="p">);</span> <span class="cp">?&gt;</span><span class="x"></span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">format_date</span><span class="p">(</span><span class="nb">date</span><span class="p">());</span> <span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<p>
Each time the __() function is called, InfoPotato looks for a translation of its argument in the dictionary of the current language setting. If it finds a corresponding phrase, the translation is sent back and displayed in the response. So the user interface translation relies on a dictionary file.
</p>

<h2>Handling Complex Translation Needs</h2>

<p>
Translation only makes sense if the __() argument is a word, phase or a full sentence. However, as you sometimes have formatting or variables mixed with words, you could be tempted to cut sentences into several chunks, thus calling the function on senseless phrases. Fortunately, the __() function offers a replacement feature based on tokens, which will help you to have a meaningful dictionary that is easier to handle by translators. As with HTML formatting, you can leave it in the helper call as well.
</p>

<div class="syntax"><pre>Welcome to all the <span class="nt">&lt;b</span> <span class="na">class=</span><span class="s">&quot;new&quot;</span><span class="nt">&gt;</span>new<span class="nt">&lt;/b&gt;</span> users.<span class="nt">&lt;br</span> <span class="nt">/&gt;</span> 
There are <span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$num</span><span class="p">;</span> <span class="cp">?&gt;</span> persons logged.
</pre></div>

<p>
Bad way to enable text translation
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s1">&#39;Welcome to all the&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span> 
<span class="nt">&lt;b</span> <span class="na">class=</span><span class="s">&quot;new&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s1">&#39;new&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span><span class="nt">&lt;/b&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s1">&#39;users&#39;</span><span class="p">)</span> <span class="cp">?&gt;</span>.<span class="nt">&lt;br</span> <span class="nt">/&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s1">&#39;There are&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$num</span><span class="p">;</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s1">&#39;persons logged&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span> 
</pre></div>

<p>
Good way to enable text translation
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s1">&#39;Welcome to all the &lt;b class=&quot;new&quot;&gt;new&lt;/b&gt; users&#39;</span><span class="p">);</span> <span class="cp">?&gt;</span> <span class="nt">&lt;br</span> <span class="nt">/&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">__</span><span class="p">(</span><span class="s1">&#39;There are :num persons logged&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;:num&#39;</span> <span class="o">=&gt;</span> <span class="nv">$num</span><span class="p">));</span> <span class="cp">?&gt;</span> 
</pre></div>

<p>
In this example, the token is <span class="red">:num</span>, but it can be anything, since the replacement function used by the translation helper is <a href="http://www.php.net/manual/en/function.strtr.php" class="external_link"><code>strtr()</code></a>.
</p>

<div class="notebox">
There's one other aspect of localizing your application which is not covered by the use of the translate functions, and that is date/money formats. Don't forget to set the formats for these things you need to use <a href="http://us3.php.net/setlocale" class="external_link"><code>setlocale</code></a>.
</div>

<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

</div>
