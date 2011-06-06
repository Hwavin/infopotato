<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Password Hashing Library</h1>	
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; Password Hashing Library
</div>
<!-- end breadcrumb -->

<p>
This is a portable public domain password hashing library based on the <a href="http://www.openwall.com/phpass/" class="external_link">Portable PHP password hashing framework</a>
</p>

<p>
The preferred (most secure) hashing method supported by phpass is the OpenBSD-style Blowfish-based bcrypt, also supported with our public domain crypt_blowfish package (for C applications), and known in PHP as CRYPT_BLOWFISH, with a fallback to BSDI-style extended DES-based hashes, known in PHP as CRYPT_EXT_DES, and a last resort fallback to MD5-based salted and variable iteration count password hashes implemented in phpass itself (also referred to as portable hashes).
</p>

<a href="http://www.openwall.com/articles/PHP-Users-Passwords">http://www.openwall.com/articles/PHP-Users-Passwords</a>

<h2>Initializing Password Hashing Library in Manager</h2>

<div class="syntax"><pre>
<span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
    <span class="s1">&#39;iteration_count_log2&#39;</span> <span class="o">=&gt;</span> <span class="m">8</span><span class="p">,</span> 
    <span class="s1">&#39;portable_hashes&#39;</span> <span class="o">=&gt;</span> <span class="k">FALSE</span> 
<span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;password_hash/password_hash_library&#39;</span><span class="p">,</span> <span class="s1">&#39;pass&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
</pre></div>

<h2>How to create Password hashing</h2>

<div class="syntax"><pre>
<span class="nv">$hashed_password</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">pass</span><span class="o">-&gt;</span><span class="na">hash_password</span><span class="p">(</span><span class="nv">$password</span><span class="p">);</span> 
</pre></div> 

<p>
Decent systems/applications do not actually store users' passwords. Instead, they transform new passwords being set/changed into password hashes with cryptographic (one-way) hash functions, and they store those hashes.
</p>

<p>
Let's try to create the same password hashing, just for kicks. The script succeeds again, and we get a new hash encoding string whihc is indeed almost entirely different.
</p>

<h2>How to authenticate created password</h2>

<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">pass</span><span class="o">-&gt;</span><span class="na">check_password</span><span class="p">(</span><span class="nv">$password</span><span class="p">,</span> <span class="nv">$hashed_password</span><span class="p">)</span> 
</pre></div> 

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
