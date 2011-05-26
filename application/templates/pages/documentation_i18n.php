<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Function</h1>	
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Internationalization
</div>
<!-- end breadcrumb -->

<p>
Internationalization (I18N) refers to the process of designing a software application so that it can be adapted to various languages and regions without engineering changes. For Web applications, this is of particular importance because the potential users may be from worldwide.
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

</div> 
<!-- end onecolumn -->
