<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Image Manipulation</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; Image Manipulation
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/image/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
InfoPotato's Image Manipulation library lets you perform the following actions:
</p> 

<ul>
<li>Image Resizing (Thumbnail Creation)</li>
<li>Image Cropping</li>
<li>Image Rotating</li>
<li>Image Watermarking</li>
</ul>

<p>
All three major image libraries are supported: GD/GD2, NetPBM, and ImageMagick
</p>
 
<div class="notebox">
Watermarking is only available using the GD/GD2 library. In addition, even though other libraries are supported, GD is required in order for the script to calculate the image properties. The image processing, however, will be performed with the library you specify.
</div>
 
<h2>Initializing the Class</h2> 
 
<p>Like most other classes in InfoPotato, the Image Manipulation class is initialized in your controller using the <dfn>$this->load_library</dfn> function:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
</pre>
</div> 

<p>Once loaded, the Image object will be available using: <dfn>$this->img</dfn></p> 
 
<h2>Image Resizing (Thumbnail Creation)</h2>

<p>
The image resizing function lets you resize the original image, create a copy (with or without resizing), or create a thumbnail image.
</p>

<p>
For practical purposes there is no difference between creating a copy and creating a thumbnail except a thumb will have the thumbnail marker as part of the name (ie, mypic_thumb.jpg).
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Img_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_resize</span><span class="p">()</span> <span class="p">{</span> 
 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;source_image&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;t.jpg&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;create_thumb&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="k">TRUE</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;maintain_ratio&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="k">TRUE</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;width&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">120</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;height&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">90</span><span class="p">;</span> 
		
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
         <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">resize</span><span class="p">())</span> <span class="p">{</span> 
            <span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">();</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/img_manager.php</span> 
</pre></div> 


<h2>Image Cropping</h2>

<p>
The cropping function works nearly identically to the resizing function except it requires that you set preferences for the X and Y axis (in pixels) specifying where to crop, like this:
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Img_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_crop</span><span class="p">()</span> <span class="p">{</span> 
 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;image_library_to_use&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;gd2&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;source_image&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;t.jpg&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;x_axis&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">500</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;y_axis&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">200</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;width&#39;</span><span class="p">]</span>  <span class="o">=</span> <span class="m">205</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;height&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="m">200</span><span class="p">;</span> 
		
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
         <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">crop</span><span class="p">())</span> <span class="p">{</span> 
            <span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">();</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/img_manager.php</span> 
</pre></div> 

<h2>Image Rotating</h2>

<p>
The image rotation function requires that the angle of rotation be set via its preference:
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Img_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_rotate</span><span class="p">()</span> <span class="p">{</span> 
 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;source_image&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;t.jpg&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;rotation_angle&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;hor&#39;</span><span class="p">;</span> 
		
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
         <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">rotate</span><span class="p">())</span> <span class="p">{</span> 
            <span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">();</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/img_manager.php</span> 
</pre></div> 

<h2>Image Watermarking</h2>

<p>
There are two types of watermarking that you can use:
</p>

<ul>
<li>
<strong>Text</strong>: The watermark message will be generating using text, either with a True Type font that you specify, or using the native text output that the GD library supports. If you use the True Type version your GD installation must be compiled with True Type support (most are, but not all).
</li>
<li>
<strong>Overlay</strong>: The watermark message will be generated by overlaying an image (usually a transparent PNG or GIF) containing your watermark over the source image.
</li>
</ul>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Img_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_watermark</span><span class="p">()</span> <span class="p">{</span> 
 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;source_image&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;t.jpg&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_text&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;Copyright 2006 - John Doe&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_type&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;text&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_font_path&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;times.ttf&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_font_size&#39;</span><span class="p">]</span>	<span class="o">=</span> <span class="s1">&#39;16&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_font_color&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;000000&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_vrt_alignment&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;middle&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_hor_alignment&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;center&#39;</span><span class="p">;</span> 
        <span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wm_padding&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;20&#39;</span><span class="p">;</span> 
		
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;image/image_library&#39;</span><span class="p">,</span> <span class="s1">&#39;img&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
         <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">watermark</span><span class="p">())</span> <span class="p">{</span> 
            <span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">img</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">();</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/img_manager.php</span> 
</pre></div> 

<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
